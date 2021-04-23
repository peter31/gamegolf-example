interface IParams {
    email: (value: string) => boolean;
    phone: (value: string) => boolean;
    password: (value: string) => boolean;
    [key: string]: (value: string) => boolean;
}

// interface IExpDate {
//     exp_month: string;
//     exp_year: string;
// }

const Validation: IParams = {
    email: (value: string): boolean => {
        const reg = new RegExp('^[a-z0-9.!#$%&’*+=?^_`{|}~-]+@[a-z0-9-]+(?:\\.[a-z0-9-]+)', 'i');
        return reg.test(String(value).toLowerCase());
    },
    phone: (value: string | number): boolean => {
        return !isNaN(+value);
    },
    password: (value: string): boolean => !(value.length < 6),
    code: (value: string): boolean => value.length === 6,
};

// export const expDateValidation = ({ exp_month, exp_year }: IExpDate) => {
//     const date = new Date();
//     const month = date.getMonth() + 1;
//     const year = date.getFullYear() - 2000;
//     return +exp_month > 12 || year > +exp_year || (year === +exp_year && month >= +exp_month);
// };

export default Validation;
