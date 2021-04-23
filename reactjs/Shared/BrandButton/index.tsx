import React, { Component, RefObject } from 'react';
import Button from 'react-bootstrap/Button';
import SpinnerComponent from '../../Spinner';
import './styles.scss';
import clsx from 'clsx';

export interface IButtonProps {
    onClick: () => void;
    value: string;
    disabled?: boolean;
    additionalClass?: string;
    refLink?: RefObject<any>;
    spinner?: boolean;
    containerClasses?: string;
}

export default class BrandButton extends Component<IButtonProps> {
    render(): React.ReactNode {
        const {
            onClick,
            value,
            disabled,
            additionalClass = '',
            refLink,
            spinner = false,
            containerClasses = '',
        } = this.props;
        return (
            <div className={clsx('button_container', containerClasses)}>
                <Button
                    onClick={onClick}
                    className={`brand_button ${additionalClass}`}
                    disabled={disabled || spinner}
                    ref={refLink}
                >
                    {spinner ? <SpinnerComponent /> : value}
                </Button>
            </div>
        );
    }
}
