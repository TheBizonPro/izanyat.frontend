import axios from "axios";

export class ContractorApiClient {
    constructor() {
        this.baseUrl = process.env.API_URL;

        this.axios = axios;
        this.JWT = window.localStorage.getItem("token");

        this.axios.interceptors.request.use((request) => {
            request.headers.Authorization = `Bearer ${this.JWT}`;

            return request;
        });
    }

    sendPost(url, data) {
        return new Promise((resolve, reject) => {
            axios
                .post(`${this.baseUrl}/api/v2/contractor/${url}`, data)
                .then((r) => {
                    resolve(r.data);
                })
                .catch((e) => reject(e));
        });
    }

    sendGet(url, queryParams) {
        return new Promise((resolve, reject) => {
            axios
                .get(`${this.baseUrl}/api/v2/contractor/${url}`, {
                    params: queryParams,
                })
                .then((r) => {
                    resolve(r.data);
                })
                .catch((e) => reject(e));
        });
    }

    register(data) {
        return this.sendPost("register", data);
    }
}

export const contractorApiClient = new ContractorApiClient();
