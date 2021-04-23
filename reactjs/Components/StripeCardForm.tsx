import React, { FormEvent, useEffect, useState } from 'react';
import { Form } from 'react-bootstrap';
import { CardElement, useStripe, useElements, Elements } from '@stripe/react-stripe-js';
import PaymentStore from '../../Stores/PaymentStore';
import stripeJs, { loadStripe } from '@stripe/stripe-js';
import BlackButton from '../../Shared/Buttons/BlackButton';
import './styles.scss';
import config from '../../config';
import { AutoFocusAndEventHandler } from '../../Utils/autofocusForForms';
import OutlineButton from '../../Shared/Buttons/OutlinedButton';

interface IProps {
    showError: (error?: string) => void;
    inputComplete?: (value: boolean) => void;
    error?: string;
    submitContinue: () => Promise<void>;
    buttonType?: 'outline' | 'contained';
    isLoading: boolean;
    hideButton?: boolean;
}

export type Ref = HTMLFormElement;

const CheckoutForm = React.forwardRef<Ref, IProps>((props: IProps, ref) => {
    const stripe = useStripe();
    const elements = useElements();
    const [name, setName] = useState('');
    const handleSubmit = async (event: FormEvent<HTMLFormElement>) => {
        if (!props.error) {
            event.preventDefault();

            // Use elements.getElement to get a reference to the mounted Element.
            const cardElement = elements!.getElement(CardElement);
            const token = stripe && cardElement && (await stripe.createToken(cardElement, { name }));
            console.log(token);
            if (token && token.token) {
                await PaymentStore.changeToken(token);
                await props.submitContinue();
            }
            if (token && token.error) {
                props.showError(token.error.decline_code);
            }
        }
    };

    useEffect(() => {
        const submitForm = () => {
            if (!props.error) {
                const form = document.querySelector('form');
                form?.requestSubmit();
            }
        };
        const listener = new AutoFocusAndEventHandler(submitForm);
        return () => listener.clearListener();
    }, [props.error]);

    const handleNameChange = (event: React.ChangeEvent<HTMLInputElement>) => setName(event.target.value);
    const { buttonType = 'contained', hideButton = false } = props;
    return (
        <Form onSubmit={handleSubmit} ref={ref}>
            <Form.Row>
                <Form.Group>
                    <Form.Label>Card Holder</Form.Label>
                    <Form.Control
                        type={'text'}
                        value={name}
                        onChange={handleNameChange}
                        placeholder={'Enter a name as on the card'}
                    />
                </Form.Group>
            </Form.Row>
            <fieldset className="FormGroup">
                <div className="FormRow card_container">
                    <CardElement
                        onChange={(e: stripeJs.StripeCardElementChangeEvent) =>
                            props.inputComplete && props.inputComplete(e.complete)
                        }
                        // onReady={(el) => el.focus()}
                        className="form"
                        options={{
                            iconStyle: 'solid',
                            classes: { base: 'login_input', empty: 'error' },
                            style: {
                                base: {
                                    iconColor: '#000',
                                    color: '#000',
                                    fontSize: '16px',
                                    fontFamily: 'Montserrat',
                                    fontWeight: '500',
                                    padding: '16px',
                                },
                                invalid: {
                                    iconColor: '#FFC7EE',
                                    color: '#FFC7EE',
                                },
                            },
                        }}
                        //
                    />
                </div>
            </fieldset>
            {!hideButton &&
                (buttonType === 'contained' ? (
                    <BlackButton
                        type={'submit'}
                        value={'save'}
                        additionalClass={'w-100'}
                        disabled={!!props.error}
                        spinner={props.isLoading}
                    />
                ) : (
                    <OutlineButton
                        type={'submit'}
                        value={'save'}
                        additionalClass={'w-100'}
                        disabled={!!props.error}
                        spinner={props.isLoading}
                    />
                ))}
        </Form>
    );
});

const stripePromise = loadStripe(config.STRIPE_TOKEN);

const CardForm = React.forwardRef<Ref, IProps>(
    (props: IProps, ref): React.ReactElement => {
        return (
            <Elements stripe={stripePromise}>
                <CheckoutForm {...props} ref={ref} />
            </Elements>
        );
    },
);

export default CardForm;
