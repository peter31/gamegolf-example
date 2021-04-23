import React, { RefObject } from 'react';
import Button from 'react-bootstrap/Button';
import './styles.scss';
import clsx from 'clsx';
import SpinnerComponent from '../../Spinner';

interface IProps {
    value: string;
    onClick?: (event: any) => void;
    disabled?: boolean;
    additionalClass?: string;
    spinner?: boolean;
    className?: string;
    refLink?: RefObject<any>;
    type?: 'button' | 'submit';
}

const OutlineButton = (props: IProps) => {
    const {
        value,
        onClick = () => null,
        disabled = false,
        additionalClass = '',
        spinner = false,
        className,
        type = 'button',
    } = props;
    const currentClass = clsx('button_container', className);
    return (
        <div className={currentClass}>
            <Button
                variant={'outline-dark'}
                type={type}
                onClick={onClick}
                className={clsx('outline_button', additionalClass)}
                disabled={disabled || spinner}
                ref={props.refLink}
            >
                {spinner ? <SpinnerComponent variant={'dark'} /> : value}
            </Button>
        </div>
    );
};

export default OutlineButton;
