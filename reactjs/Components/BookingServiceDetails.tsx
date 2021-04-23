import React, { ChangeEvent, useEffect } from 'react';
import { observer } from 'mobx-react';
import { useHistory } from 'react-router';

import StepsComponent from '../../../Shared/StepsComponent';
import { getQryParams } from '../../../Utils/url';
import BookingStore from '../../../Stores/BookingStore';
import Footer from '../../../Shared/Footer';
import { IBookingLocationType } from '../../../Interfaces/BookingInterfaces';
import AuthStore from '../../../Stores/AuthStore';
import ServiceDetails from '../../../Components/ServiceDetails';

const BookingServiceDetails = (): React.ReactElement => {
    const history = useHistory();

    useEffect(() => {
        const params = getQryParams(history.location?.search);
        if (params.mode) {
            const { mode } = params;
            BookingStore.changeBookingData('booking_type', mode as IBookingLocationType);
            history.replace(history.location.pathname);
        }
    }, [history]);

    useEffect(() => {
        const { servicesList, getServices } = BookingStore;
        if (!servicesList?.length) {
            getServices();
        }
    }, []);

    const {
        bookingData: { quantity, services, service_notes, booking_type },
        changeBookingData,
    } = BookingStore;

    const changeNotesHandler = (event: ChangeEvent<HTMLTextAreaElement>) =>
        BookingStore.changeBookingData('service_notes', event.target.value);

    const continueHandler = () => {
        BookingStore.setBookingToStorage();
        const parsed = getQryParams(history.location.search);
        if (parsed.nurseID) {
            history.push(`booking_address_${booking_type.toLowerCase()}/?nurseID=${parsed.nurseID}`);
            return;
        }
        AuthStore.isLogged
            ? history.push(`booking_address_${booking_type.toLowerCase()}`)
            : history.push(booking_type === 'Office' ? `booking_address_office` : '/add_location');
    };

    const selectChangeHandler = (values: number[]) => BookingStore.changeBookingData('services', values);
    return (
        <>
            <div className="gray_background service_details">
                <StepsComponent
                    step={AuthStore.isLogged ? 'bookingSteps' : 'bookingStepsNotReg'}
                    done={AuthStore.isLogged ? [] : [0, 1]}
                    active={AuthStore.isLogged ? 0 : 2}
                    onBack
                    backPath={'/'}
                />
                <ServiceDetails
                    bookingType={booking_type}
                    changeBookingDataOffice={() => changeBookingData('booking_type', 'Office' as IBookingLocationType)}
                    changeBookingDataHome={() => changeBookingData('booking_type', 'Home' as IBookingLocationType)}
                    quantity={quantity}
                    services={services}
                    selectChangeHandler={selectChangeHandler}
                    serviceNotes={service_notes}
                    changeNotesHandler={changeNotesHandler}
                    continueHandler={continueHandler}
                />
            </div>
            <Footer />
        </>
    );
};

export default observer(BookingServiceDetails);
