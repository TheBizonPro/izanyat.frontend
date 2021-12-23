import axios from "axios";

const HTTPV2 = axios.create({
    baseURL: `${process.env.API_URL}/api/v2/`,
});

const requestHandler = (request) => {
    request.headers.Authorization = `Bearer ${window.localStorage.getItem(
        "token"
    )}`;

    return request;
};

const responseHandler = (response) => {
    if (response.status === 401) {
        window.location = "/login";
    }

    return response;
};

const errorHandler = (error) => {
    if (error.response.status === 422) {
        const errors = error.response.data.errors;
        let errorsMessages = [];
        const keys = Object.keys(errors);
        for (let key of keys) {
            errorsMessages = [...errors[key], ...errorsMessages];
        }
        for (let errorMessage of errorsMessages) {
            if (boottoast) {
                boottoast.danger({
                    message: errorMessage,
                    title: "Ошибка",
                    imageSrc: "/images/logo-sm.svg",
                });
            }
        }
    }
    return Promise.reject(error);
};

HTTPV2.interceptors.request.use(
    (request) => requestHandler(request),
    (error) => errorHandler(error)
);

HTTPV2.interceptors.response.use(
    (response) => responseHandler(response),
    (error) => errorHandler(error)
);

export default HTTPV2;
