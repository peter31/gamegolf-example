import React, { useEffect, useRef, useState } from 'react';
import { useHistory, useParams } from 'react-router';
import clsx from 'clsx';
import { observer } from 'mobx-react';
import { Form } from 'react-bootstrap';

import MyBookingsStore from '../../Stores/MyBookingsStore';
import ProfileStore from '../../Stores/ProfileStore';
import DateFormatting from '../../Utils/DateFormatting';
import PaymentStore from '../../Stores/PaymentStore';
import PaymentSettingsItem from '../ProfilePages/Payment/PaymentSettingsItem';
import { ICreditCardAfterSave } from '../../Interfaces/CreditCardsInterfaces';
import OutlineButton from '../../Shared/Buttons/OutlinedButton';
import BlackButton from '../../Shared/Buttons/BlackButton';
import CardForm from '../../Components/CardForm';
import { ReactComponent as InvoiceIcon } from '../../assets/icons/invoice-icon.svg';
import Footer from '../../Shared/Footer';
import SpinnerComponent from '../../Shared/Spinner';
import InvoiceStore from '../../Stores/InvoiceStore';
import { ReactComponent as Arrow } from '../../assets/icons/arrow_left.svg';
import './styles.scss';

const Invoices = observer(
    (): React.ReactElement => {
        const { id } = useParams();
        const history = useHistory();
        const [splitAmount, setSplitAmount] = useState('0.00');
        const [showForm, setShowForm] = useState(false);
        const [error, setError] = useState('');
        const [inputComplete, setInputComplete] = useState(false);

        const cardForm: React.MutableRefObject<HTMLFormElement | null> = useRef<HTMLFormElement | null>(null);

        const { detailAppointment, error: appointmentError, isLoading: bookingLoading } = MyBookingsStore;
        const { invoice, isLoading, error: invoiceError } = InvoiceStore;
        const invoiceAmount =
            detailAppointment.all_payments.find((payment) => payment.type === 'Credit card')?.amount || 0;
        const restAmountToPay = invoice?.to_pay || invoiceAmount;

        useEffect(() => {
            return () => {
                MyBookingsStore.error = '';
            };
        }, []);

        useEffect(() => {
            if (id) {
                const { getDetailAppointment } = MyBookingsStore;
                const { retrievePaymentInfo } = InvoiceStore;
                getDetailAppointment(id);
                retrievePaymentInfo(id);
            }
        }, [id]);

        useEffect(() => {
            if (invoice?.to_pay) {
                setSplitAmount(invoice?.to_pay.toFixed(2));
            }
        }, [invoice, setSplitAmount]);

        const profile = ProfileStore.profile;

        const renderCards = (): React.ReactNode => {
            return PaymentStore.paymentSettings.map(
                (creditCard: ICreditCardAfterSave, i: number): React.ReactElement => (
                    <PaymentSettingsItem
                        value={creditCard}
                        img={'fas fa-credit-card'}
                        deleteItem={() => null}
                        editItem={() => null}
                        key={`${i}-${creditCard.card_number}`}
                        hideControl
                    />
                ),
            );
        };

        const handleChange = (event: React.ChangeEvent<HTMLInputElement>) => {
            InvoiceStore.error = '';
            const { value } = event.target;
            let validatedValue = +value;
            if (+value < 0) {
                validatedValue = 0;
            }
            if (+value > restAmountToPay) {
                validatedValue = restAmountToPay;
            }
            setSplitAmount(validatedValue + '');
        };

        const submitForm = async () => {
            if (!inputComplete) return;
            PaymentStore.isLoading = true;
            // this.cardForm.current && (await this.cardForm.current.wrappedInstance.saveCard());
            try {
                await PaymentStore.savePaymentInfo();
            } catch (e) {
                setError(e.message);
            }
            setShowForm(false);
        };

        const payBill = () => {
            InvoiceStore.payBill(+splitAmount, detailAppointment.id, PaymentStore.defaultPaymentMethodId);
        };

        const formatAmount = () => setSplitAmount(Number(splitAmount).toFixed(2));

        const paidAmount = invoice?.data.reduce((sum, data) => (data.is_payed ? sum + data.amount : sum), 0);

        const totalAppointmentSum = detailAppointment.services.reduce((acc, service) => acc + service.price, 0);
        return (
            <>
                <div className={'invoices-container'}>
                    <div className={'back_button'} onClick={history.goBack}>
                        <Arrow /> Back
                    </div>
                    <div className={'invoices'}>
                        <InvoiceIcon />
                        {bookingLoading ? (
                            <SpinnerComponent variant={'dark'} size={'lg'} />
                        ) : appointmentError ? (
                            <div className={'not-found-error'}>Invoice not found.</div>
                        ) : (
                            <>
                                {' '}
                                <div className={'invoice-header'}>
                                    <h2>
                                        Invoice from{' '}
                                        {`${detailAppointment?.nurse?.first_name} ${detailAppointment?.nurse?.last_name}`}
                                    </h2>
                                    <h5 className={'text-secondary'}>
                                        <div>Billed to {`${profile.first_name} ${profile.last_name}`}</div>
                                        <div>
                                            {detailAppointment?.date &&
                                                DateFormatting.usFormatWithDate(detailAppointment?.date)}
                                        </div>
                                    </h5>
                                </div>
                                <div className={'invoice-checkout-container'}>
                                    <h1 className={'total-amount font-weight-bold'}>
                                        TOTAL AMOUNT: $ {invoice?.total_card_payments_amount}
                                    </h1>
                                    <h2 className={'payed-amount text-center'}>PAID AMOUNT: $ {paidAmount}</h2>
                                    {!!invoice?.to_pay && (
                                        <>
                                            <div
                                                className={
                                                    'd-flex align-items-center justify-content-center invoice-split-form'
                                                }
                                            >
                                                <h3>Amount to pay:</h3>
                                                <Form>
                                                    <Form.Row>
                                                        <Form.Group>
                                                            <Form.Control
                                                                type={'number'}
                                                                value={splitAmount}
                                                                onChange={handleChange}
                                                                onBlur={formatAmount}
                                                            />
                                                        </Form.Group>
                                                    </Form.Row>
                                                </Form>
                                            </div>

                                            <div className={'invoice-card-container'}>
                                                <div className={'items_container'}>{renderCards()}</div>
                                                {!showForm && (
                                                    <OutlineButton
                                                        value={'+ add new credit card'}
                                                        onClick={(): void => setShowForm(true)}
                                                    />
                                                )}
                                                {showForm && (
                                                    <div className={'payment_add_form'}>
                                                        <h5 className={'text-uppercase mb-3'}>Add Credit card</h5>
                                                        <CardForm
                                                            inputComplete={(e: boolean): void => setInputComplete(e)}
                                                            showError={(error?: string): void => setError(error || '')}
                                                            ref={cardForm}
                                                            error={error}
                                                            submitContinue={submitForm}
                                                            isLoading={PaymentStore.isLoading}
                                                            buttonType={'outline'}
                                                        />
                                                    </div>
                                                )}
                                                <div
                                                    className={clsx(
                                                        'inline_error',
                                                        error || invoiceError ? 'visible' : 'invisible',
                                                    )}
                                                >
                                                    {error || invoiceError}
                                                </div>
                                                <BlackButton
                                                    onClick={payBill}
                                                    value={'pay invoice'}
                                                    spinner={isLoading}
                                                    additionalClass={'w-100'}
                                                />
                                            </div>
                                        </>
                                    )}
                                </div>
                                <div className={'invoice-services-list'}>
                                    <h4 className={'text-uppercase'}>Treatment made</h4>
                                    {detailAppointment.services.map((service) => (
                                        <div className={'invoice-service-item'} key={service.service.id}>
                                            <span>{service.service.title}</span>
                                            <span>${service?.price}</span>
                                        </div>
                                    ))}
                                    <div className={'invoice-service-item total '}>
                                        <span className={'title'}>total:</span>
                                        <span className={'amount'}>${totalAppointmentSum}</span>
                                    </div>
                                </div>
                            </>
                        )}
                    </div>
                </div>
                <Footer />
            </>
        );
    },
);

export default Invoices;
