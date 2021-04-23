export class AutoFocusAndEventHandler {
    submit: (() => void) | undefined;

    constructor(submit?: () => void) {
        this.submit = submit;
        if (submit) {
            document.addEventListener('keyup', this.eventHandler);
        }
        this.setAutoFocus();
    }

    eventHandler = (event: KeyboardEvent) => {
        if (event.code === 'Enter') {
            this.submit?.();
        }
    };

    setAutoFocus = () => {
        const firstInput = document.querySelector('input:not(.fileInput):not(.disabled), textarea');
        if (firstInput) {
            (firstInput as HTMLInputElement).focus();
        }
    };

    public clearListener = () => {
        document.removeEventListener('keyup', this.eventHandler);
    };
}
