import http from './http';
import { IPaymentInfo, PayInvoiceRequest, PaymentInformationResponse } from '../Interfaces/InvoiceInterfaces';

class InvoiceService {
    public static payInvoice(body: PayInvoiceRequest): Promise<PaymentInformationResponse | any> {
        return http.post('/appointment-invoices/', body);
    }

    public static payTips(body: PayInvoiceRequest): Promise<PaymentInformationResponse | any> {
        return http.post('/tips/', body);
    }

    public static getPaymentInfo(id: number): Promise<IPaymentInfo | any> {
        return http.get('/appointment-invoices/', { params: { appointment: id } });
    }
}

export default InvoiceService;
