import { action, observable } from 'mobx';

import InvoiceService from '../Services/InvoiceService';
import { IPaymentInfo, PayInvoiceRequest } from '../Interfaces/InvoiceInterfaces';
import history from '../history';

class InvoiceStore {
    @observable invoice: IPaymentInfo | null = null;

    @observable error = '';

    @observable isLoading = false;

    @action
    payBill = async (amount: number, appointment: number, payment: number) => {
        this.error = '';
        this.isLoading = true;
        const request: PayInvoiceRequest = {
            appointment,
            payment,
            amount,
            type: 'Credit card',
        };
        try {
            const response = await InvoiceService.payInvoice(request);
            await this.retrievePaymentInfo(appointment);
            if (response && response.to_pay === 0) {
                history.push(`/tips/${appointment}`);
            }
        } catch (e) {
            this.error = e.message.detail;
            console.log(e);
        } finally {
            this.isLoading = false;
        }
    };

    @action payTips = async (amount: number, appointment: number, payment: number) => {
        this.error = '';
        this.isLoading = true;
        const request: PayInvoiceRequest = {
            appointment,
            payment,
            amount,
            type: 'Credit card',
        };
        try {
            await InvoiceService.payTips(request);
            history.push(`/appointment_details/${appointment}`);
        } catch (e) {
            this.error = e.message.detail;
            console.log(e);
        } finally {
            this.isLoading = false;
        }
    };

    @action
    retrievePaymentInfo = async (id: number) => {
        this.isLoading = true;
        try {
            this.invoice = await InvoiceService.getPaymentInfo(id);
        } catch (e) {
            this.error = e.message.detail;
        } finally {
            this.isLoading = false;
        }
    };
}

export default new InvoiceStore();
