class StorageService {
    public async getItem(KEY: string): Promise<any> {
        try {
            if (!KEY) return null;
            const item = await localStorage.getItem(KEY);
            if (!item) return null;
            return JSON.parse(item);
        } catch (e) {
            console.log('parse error:', e);
            throw e;
        }
    }

    public async setItem(KEY: string, VALUE: any): Promise<any> {
        try {
            if (!KEY) return null;
            await localStorage.setItem(KEY, JSON.stringify(VALUE));
            return { KEY, VALUE };
        } catch (e) {
            throw e;
        }
    }

    public async removeItem(KEY: string): Promise<any> {
        try {
            if (!KEY) return null;
            await localStorage.removeItem(KEY);
        } catch (e) {
            throw e;
        }
    }

    public clear() {
        try {
            localStorage.clear();
        } catch (e) {
            throw e;
        }
    }
}

export default new StorageService();
