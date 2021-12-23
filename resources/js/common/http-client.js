import axios from "axios";

const HTTP = axios.create({
    baseURL: `${process.env.API_URL}/api/`,
});

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
    return Promise.reject(error);
};

HTTP.interceptors.request.use(
    (request) => requestHandler(request),
    (error) => errorHandler(error)
);

HTTP.interceptors.response.use(
    (response) => responseHandler(response),
    (error) => errorHandler(error)
);

HTTPV2.interceptors.request.use(
    (request) => requestHandler(request),
    (error) => errorHandler(error)
);

HTTPV2.interceptors.response.use(
    (response) => responseHandler(response),
    (error) => errorHandler(error)
);

export default HTTP;
