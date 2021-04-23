import React, { ChangeEvent } from 'react';

import CategoryButton from '../Shared/Buttons/CategoryButton';
import OutlineButton from '../Shared/Buttons/OutlinedButton';
import MultiSelect from '../Shared/MultiSelect';
import CustomInputNumber from '../Shared/QuantityInput';

interface IProps {
    bookingType?: string;
    changeBookingDataOffice?: () => void;
    changeBookingDataHome?: () => void;
    quantity: number;
    services: number[];
    selectChangeHandler: (values: number[]) => void;
    serviceNotes: string;
    changeNotesHandler: (event: ChangeEvent<HTMLTextAreaElement>) => void;
    continueHandler?: () => void;
    disabledServiceCategoryButtons?: boolean;
    onChangeNumberOfPeople?: (value: number) => void;
    withoutButton?: boolean;
    description?: string;
}

export default ({
    bookingType,
    changeBookingDataOffice,
    changeBookingDataHome,
    quantity,
    services,
    selectChangeHandler,
    serviceNotes,
    changeNotesHandler,
    continueHandler,
    disabledServiceCategoryButtons,
    onChangeNumberOfPeople,
    withoutButton,
    description,
}: IProps): React.ReactElement => {
    return (
        <div className="container d-flex flex-column align-items-center inner_container service-details-main">
            <h1>Service Details</h1>
            <div className={'service_categories d-flex align-items-center flex-column'}>
                <h5>select service category:</h5>
                <div className={'d-flex categories-container'}>
                    <CategoryButton
                        isActive={bookingType === 'Office'}
                        text={'office visit'}
                        action={changeBookingDataOffice}
                        disabled={disabledServiceCategoryButtons}
                        withBorder
                    />
                    <CategoryButton
                        isActive={bookingType === 'Home'}
                        text={'in home / party'}
                        action={changeBookingDataHome}
                        disabled={disabledServiceCategoryButtons}
                        withBorder
                    />
                </div>
            </div>
            <div className={'d-flex flex-column align-items-center service-detail-content'}>
                {bookingType === 'Home' && (
                    <CustomInputNumber
                        disabled={false}
                        value={quantity}
                        onChange={onChangeNumberOfPeople}
                        description={description}
                    />
                )}
                <h5>Select Treatment</h5>
                <div className={'mb-5 w-100'}>
                    <label htmlFor={'treatments'}>
                        Treatment Services <span className={'text-secondary'}>(optional)</span>
                    </label>
                    <MultiSelect selectedServices={services} onChange={selectChangeHandler} />
                </div>
                <div className={'mb-5'}>
                    <label htmlFor={'notes'}>
                        Do you have anything specific you want to discuss or ask the nurse during the visit?{' '}
                        <span className={'text-secondary'}>(optional)</span>
                    </label>
                    <textarea
                        className="form-control"
                        name={'service_notes'}
                        placeholder={'Notes...'}
                        value={serviceNotes}
                        onChange={changeNotesHandler}
                    />
                </div>
                {!withoutButton && <OutlineButton onClick={continueHandler} value={'continue'} />}
            </div>
        </div>
    );
};
