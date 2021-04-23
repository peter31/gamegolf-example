import { action, observable } from 'mobx';
import React from 'react';
import mixpanel from 'mixpanel-browser';
import moment from 'moment';

import StorageService from '../Services/StorageService';
import { IClientData, ISignData } from '../Interfaces/RegistrationInterface';
import Validation from '../Utils/Validation';
import { defaultJwt, IErrorsObject, IJwt } from '../Interfaces/OtherInterfaces';
import { IAvatar, defaultProfile } from '../Interfaces/ProfileInterfaces';
import AuthService from '../Services/AuthService';
import ProfileStore from './ProfileStore';
import AddressesStore from './AddressesStore';
import PaymentStore from './PaymentStore';

const defaultSignIn: ISignData = {
    email: '',
    phone: '',
    password: '',
};

class AuthStore {
    @observable isLogged = false;

    @observable errorsAfterRequest = '';

    @observable ready = false;

    @observable jwt: IJwt = defaultJwt;

    @observable isLoading = false;

    @observable signInData: ISignData = {
        ...defaultSignIn,
    };

    @observable inlineErrors: IErrorsObject = {};

    @observable isSignInSuccess = false;

    constructor() {
        StorageService.getItem('jwt')
            .then((jwt) => {
                this.isLogged = jwt && jwt.token && jwt.isAuth;
                if (jwt && jwt.isAuth) {
                    this.setJWT(jwt.token, jwt.isAuth);
                    ProfileStore.initApp();
                }
                this.ready = true;
            })
            .catch(async () => {
                this.isLogged = false;
                await this.setJWT(defaultJwt.token);
                this.ready = true;
            });
    }

    @action changeSignInData = (key: string, value: string | IAvatar) => {
        if (key === 'phone') {
            value = value.toString().replace(/[^0-9.]/g, '');
        }
        return (this.signInData[key] = value);
    };

    @action
    login = async () => {
        const { email, password } = this.signInData;
        try {
            this.isLoading = true;
            this.setError('');
            this.isLogged = false;
            const resp: any = await AuthService.postLogin({ email, password });
            await this.setJWT(resp.token);
            this.isLogged = true;
            await ProfileStore.initApp();
        } catch (e) {
            this.errorsAfterRequest = Array.isArray(e?.message)
                ? e?.message?.join()
                : Array.isArray(e?.message?.non_field_errors)
                ? e?.message?.non_field_errors.join()
                : e?.message?.detail || 'Internal Server Error';
            this.isLogged = false;
        }
        if (this.errorsAfterRequest === 'need TwoFactor Auth') {
            try {
                const resp: any = await AuthService.twoFaAuth({ email });
                await this.setJWT(resp.key, false);
            } catch (e) {
                console.log('TWO FACTOR ERROR', e);
                this.setError(
                    Array.isArray(e?.message)
                        ? e?.message?.join()
                        : Array.isArray(e?.message?.non_field_errors)
                        ? e?.message?.non_field_errors.join()
                        : e?.message?.detail || 'Internal Server Error',
                );
            }
        }
        this.isLoading = false;
    };

    @action loginStep2 = async ({ code, email }: { code?: string; email?: string }, isLogged = true) => {
        try {
            this.isLoading = true;
            this.setError('');
            const newEmail = email || this.signInData.email;
            const { first_name, last_name } = ProfileStore.newProfile;
            const resp: any = await AuthService.twoFaAuth({ email: newEmail, code });
            resp.Token && (await this.setJWT(resp.Token, isLogged));
            this.isLogged = isLogged;
            if (!isLogged) {
                await ProfileStore.getProfileData();
                await ProfileStore.saveProfileData({
                    first_name,
                    last_name,
                });
            }
            await ProfileStore.initApp();
        } catch (e) {
            this.setError(e?.message?.detail || 'Wrong code.');
            this.isLogged = false;
        } finally {
            this.isLoading = false;
        }
    };

    @action signIn = async () => {
        try {
            this.isLoading = true;
            this.isSignInSuccess = false;
            this.setError('');
            const signInData = {
                email: this.signInData.email,
                phone: this.signInData.phone,
                password1: this.signInData.password,
                user_type: 2,
            };
            const response: any = await AuthService.signIn(signInData);
            mixpanel.track('Register', {
                email: this.signInData.email,
                phone: this.signInData.phone,
            });
            mixpanel.people.set({
                email: this.signInData.email,
                'Sign up date': moment().format('YYYY-MM-DD'),
                USER_ID: this.signInData.email,
            });
            if (!response) return null;
            await this.setJWT(response.key);
            await ProfileStore.getProfileData();
            this.isLogged = true;
        } catch (e) {
            console.log(e);
            this.setError(
                Array.isArray(e?.message)
                    ? e?.message?.join()
                    : Array.isArray(e?.message?.non_field_errors)
                    ? e?.message?.non_field_errors.join()
                    : e?.message?.detail || 'Internal Server Error',
            );
            this.isLogged = false;
        } finally {
            this.isLoading = false;
        }
    };

    @action addClientInDataBase = async (data: IClientData) => {
        try {
            this.isLoading = true;
            this.setError('');
            const authData = {
                email: data.email,
                phone: data.phone,
                user_type: 2,
            };
            StorageService.clear();
            AddressesStore.clear();
            PaymentStore.clear();
            await AuthService.guestSignIn(authData);
        } catch (e) {
            if (e.message.detail === 'need TwoFactor Auth') {
                try {
                    const response: any = await AuthService.twoFaAuth({ email: data.email });
                    await this.setJWT(response.key, false);
                } catch (e) {
                    throw e;
                }
            } else {
                this.setError(
                    Array.isArray(e?.message)
                        ? e?.message?.join()
                        : Array.isArray(e?.message?.non_field_errors)
                        ? e?.message?.non_field_errors.join()
                        : e?.message?.detail || 'Internal Server Error',
                );
                throw e;
            }
        } finally {
            this.isLoading = false;
        }
    };

    @action
    setJWT = async (data: string, isAuth = true, setToStorage = true) => {
        this.jwt = { token: data, isAuth };
        if (setToStorage) {
            await StorageService.setItem('jwt', this.jwt);
        }
    };

    @action
    async googleLogin(code: any): Promise<void> {
        try {
            const resp: any = await AuthService.googleLogin(code);
            await this.setJWT(resp.token);
            const profile: any = await ProfileStore.getProfileData();
            await StorageService.setItem('id', profile.id);
            // await ProfileStore.saveProfileData({first_name: data.first_name, last_name: data.last_name});
            if (resp.is_new) {
                this.signInData = {
                    email: profile.email,
                    phone: this.signInData.phone,
                    password: '1111111111111111',
                    user_type: 's2',
                };
                window.location.href = `/signIn?afterSocial=${profile.email}`;
            } else {
                window.location.href = '/';

                //    window.location.href= "/signIn?afterSocial=true"
            }
        } catch (error) {
            console.log(error);
            window.location.href = '/login';
            this.inlineErrors.formError = error.message.detail ? error.message.join() : 'Internal Server Error';
        }
    }

    async faceBookLogin(code: any) {
        const resp: any = await AuthService.facebookLogin({
            provider: 'facebook',
            access_token: code,
            user_type: 2,
        });
        await this.setJWT(resp.token);
        const profile: any = await ProfileStore.getProfileData();
        await StorageService.setItem('id', profile.id);
        // await ProfileStore.saveProfileData({first_name: data.first_name, last_name: data.last_name});
        if (resp.is_new) {
            this.signInData = {
                email: profile.email,
                phone: this.signInData.phone,
                password: '1111111111111111',
                user_type: 's2',
            };
            window.location.href = `/signIn?afterSocial=${profile.email}`;
        } else {
            window.location.href = '/';

            //    window.location.href= "/signIn?afterSocial=true"
        }
    }

    @action setError = (error: string) => (this.errorsAfterRequest = error);

    @action inputFieldsValidation = (
        event: React.FocusEvent<HTMLInputElement> | React.ChangeEvent<HTMLInputElement>,
    ) => {
        const { name, value } = event.target;
        if (name && value) {
            return (this.inlineErrors[name] = !Validation[name](value));
        }
    };

    @action phoneValidation = (name: string, value: string) => (this.inlineErrors[name] = !Validation[name](value));

    @action
    clean = async () => {
        this.signInData = defaultSignIn;
        await this.setJWT(defaultJwt.token);
        ProfileStore.profile = defaultProfile;
        await AuthService.logOut();
        this.isLogged = false;
        localStorage.clear();
        StorageService.clear();
        AddressesStore.clear();
        PaymentStore.clear();
        window.location.href = '/logIn';
    };
}

export default new AuthStore();
