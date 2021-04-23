import { CancelTokenSource } from 'axios';
import http from './http';

interface IParams {
    [key: string]: string | number | boolean;
}

class NurseService {
    public static getListOfUserNurses = (id: number, status: string): Promise<any> => {
        let params: IParams = {};
        if (status) {
            params = { [status.toLocaleLowerCase()]: true };
        }
        return http.get('/favorite-nurses/', { params });
    };

    public static getUserNurse(id: number, source: CancelTokenSource): Promise<any> {
        return http.get(`/favorite-nurses/${id}/`, { cancelToken: source.token });
    }

    public static getNurse(id: number, source: CancelTokenSource): Promise<any> {
        return http.get(`/nurses/${id}/`, { cancelToken: source.token });
    }

    public static changeNurseStatus = (body: IParams): Promise<any> => {
        return http.put(`/favorite-nurses/${body.nurse}/`, body);
    };

    public static deleteNurse = (id: number, nurseId: number): Promise<any> => {
        return http.delete(`/favorite-nurses/${nurseId}/`);
    };

    public static addFeedback = (feedback: any): Promise<any> => {
        return http.post('/feedback/', feedback);
    };
}

export default NurseService;
