import axios from 'axios';
import StorageService from '../StorageService';
import config from '../../config';
import AuthStore from '../../Stores/AuthStore';

const http = axios.create({
    baseURL: config.apiUrl,
    timeout: 30000,
});

http.interceptors.request.use(
    async (req) => {
        const originalRequest = req;
        const jwt = await StorageService.getItem('jwt');

        if (jwt && jwt.token) {
            originalRequest.headers.Authorization = `Token ${jwt.token}`;
        }

        return originalRequest;
    },

    (err) => Promise.reject(err),
);

http.interceptors.response.use(
    (response) => {
        return response.data;
    },
    async (error) => {
        let data = {
            message: 'unknown http error',
            code: 0,
        };

        if (error.response && error.response.hasOwnProperty('status')) {
            switch (error.response.status) {
                case 401:
                    await AuthStore.clean();
                    break;
            }

            data = {
                ...data,
                message: error.response.data,
                code: error.response.status,
            };
        }
        return Promise.reject(data);
    },
);

export default http;
