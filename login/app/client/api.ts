/* tslint:disable */
/* eslint-disable */
/**
 * FastAPI
 * No description provided (generated by Openapi Generator https://github.com/openapitools/openapi-generator)
 *
 * The version of the OpenAPI document: 0.1.0
 * 
 *
 * NOTE: This class is auto generated by OpenAPI Generator (https://openapi-generator.tech).
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */


import type { Configuration } from './configuration';
import type { AxiosPromise, AxiosInstance, RawAxiosRequestConfig } from 'axios';
import globalAxios from 'axios';
// Some imports not used depending on template conditions
// @ts-ignore
import { DUMMY_BASE_URL, assertParamExists, setApiKeyToObject, setBasicAuthToObject, setBearerAuthToObject, setOAuthToObject, setSearchParams, serializeDataIfNeeded, toPathString, createRequestFunction } from './common';
import type { RequestArgs } from './base';
// @ts-ignore
import { BASE_PATH, COLLECTION_FORMATS, BaseAPI, RequiredError, operationServerMap } from './base';

/**
 * 
 * @export
 * @interface HTTPValidationError
 */
export interface HTTPValidationError {
    /**
     * 
     * @type {Array<ValidationError>}
     * @memberof HTTPValidationError
     */
    'detail'?: Array<ValidationError>;
}
/**
 * 
 * @export
 * @interface ValidationError
 */
export interface ValidationError {
    /**
     * 
     * @type {Array<ValidationErrorLocInner>}
     * @memberof ValidationError
     */
    'loc': Array<ValidationErrorLocInner>;
    /**
     * 
     * @type {string}
     * @memberof ValidationError
     */
    'msg': string;
    /**
     * 
     * @type {string}
     * @memberof ValidationError
     */
    'type': string;
}
/**
 * 
 * @export
 * @interface ValidationErrorLocInner
 */
export interface ValidationErrorLocInner {
}

/**
 * DefaultApi - axios parameter creator
 * @export
 */
export const DefaultApiAxiosParamCreator = function (configuration?: Configuration) {
    return {
        /**
         * 
         * @summary Getuser
         * @param {number} idUser 
         * @param {*} [options] Override http request option.
         * @throws {RequiredError}
         */
        getUserUserPost: async (idUser: number, options: RawAxiosRequestConfig = {}): Promise<RequestArgs> => {
            // verify required parameter 'idUser' is not null or undefined
            assertParamExists('getUserUserPost', 'idUser', idUser)
            const localVarPath = `/user`;
            // use dummy base URL string because the URL constructor only accepts absolute URLs.
            const localVarUrlObj = new URL(localVarPath, DUMMY_BASE_URL);
            let baseOptions;
            if (configuration) {
                baseOptions = configuration.baseOptions;
            }

            const localVarRequestOptions = { method: 'POST', ...baseOptions, ...options};
            const localVarHeaderParameter = {} as any;
            const localVarQueryParameter = {} as any;

            if (idUser !== undefined) {
                localVarQueryParameter['idUser'] = idUser;
            }


    
            setSearchParams(localVarUrlObj, localVarQueryParameter);
            let headersFromBaseOptions = baseOptions && baseOptions.headers ? baseOptions.headers : {};
            localVarRequestOptions.headers = {...localVarHeaderParameter, ...headersFromBaseOptions, ...options.headers};

            return {
                url: toPathString(localVarUrlObj),
                options: localVarRequestOptions,
            };
        },
        /**
         * 
         * @summary Loginbytoken
         * @param {string} userToken 
         * @param {*} [options] Override http request option.
         * @throws {RequiredError}
         */
        logInByTokenLoginByTokenPost: async (userToken: string, options: RawAxiosRequestConfig = {}): Promise<RequestArgs> => {
            // verify required parameter 'userToken' is not null or undefined
            assertParamExists('logInByTokenLoginByTokenPost', 'userToken', userToken)
            const localVarPath = `/loginByToken`;
            // use dummy base URL string because the URL constructor only accepts absolute URLs.
            const localVarUrlObj = new URL(localVarPath, DUMMY_BASE_URL);
            let baseOptions;
            if (configuration) {
                baseOptions = configuration.baseOptions;
            }

            const localVarRequestOptions = { method: 'POST', ...baseOptions, ...options};
            const localVarHeaderParameter = {} as any;
            const localVarQueryParameter = {} as any;

            if (userToken !== undefined) {
                localVarQueryParameter['userToken'] = userToken;
            }


    
            setSearchParams(localVarUrlObj, localVarQueryParameter);
            let headersFromBaseOptions = baseOptions && baseOptions.headers ? baseOptions.headers : {};
            localVarRequestOptions.headers = {...localVarHeaderParameter, ...headersFromBaseOptions, ...options.headers};

            return {
                url: toPathString(localVarUrlObj),
                options: localVarRequestOptions,
            };
        },
        /**
         * 
         * @summary Login
         * @param {string} loginUser 
         * @param {string} passwordUser 
         * @param {*} [options] Override http request option.
         * @throws {RequiredError}
         */
        logInLoginPost: async (loginUser: string, passwordUser: string, options: RawAxiosRequestConfig = {}): Promise<RequestArgs> => {
            // verify required parameter 'loginUser' is not null or undefined
            assertParamExists('logInLoginPost', 'loginUser', loginUser)
            // verify required parameter 'passwordUser' is not null or undefined
            assertParamExists('logInLoginPost', 'passwordUser', passwordUser)
            const localVarPath = `/login`;
            // use dummy base URL string because the URL constructor only accepts absolute URLs.
            const localVarUrlObj = new URL(localVarPath, DUMMY_BASE_URL);
            let baseOptions;
            if (configuration) {
                baseOptions = configuration.baseOptions;
            }

            const localVarRequestOptions = { method: 'POST', ...baseOptions, ...options};
            const localVarHeaderParameter = {} as any;
            const localVarQueryParameter = {} as any;

            if (loginUser !== undefined) {
                localVarQueryParameter['loginUser'] = loginUser;
            }

            if (passwordUser !== undefined) {
                localVarQueryParameter['passwordUser'] = passwordUser;
            }


    
            setSearchParams(localVarUrlObj, localVarQueryParameter);
            let headersFromBaseOptions = baseOptions && baseOptions.headers ? baseOptions.headers : {};
            localVarRequestOptions.headers = {...localVarHeaderParameter, ...headersFromBaseOptions, ...options.headers};

            return {
                url: toPathString(localVarUrlObj),
                options: localVarRequestOptions,
            };
        },
        /**
         * 
         * @summary Readmain
         * @param {*} [options] Override http request option.
         * @throws {RequiredError}
         */
        readMainGet: async (options: RawAxiosRequestConfig = {}): Promise<RequestArgs> => {
            const localVarPath = `/`;
            // use dummy base URL string because the URL constructor only accepts absolute URLs.
            const localVarUrlObj = new URL(localVarPath, DUMMY_BASE_URL);
            let baseOptions;
            if (configuration) {
                baseOptions = configuration.baseOptions;
            }

            const localVarRequestOptions = { method: 'GET', ...baseOptions, ...options};
            const localVarHeaderParameter = {} as any;
            const localVarQueryParameter = {} as any;


    
            setSearchParams(localVarUrlObj, localVarQueryParameter);
            let headersFromBaseOptions = baseOptions && baseOptions.headers ? baseOptions.headers : {};
            localVarRequestOptions.headers = {...localVarHeaderParameter, ...headersFromBaseOptions, ...options.headers};

            return {
                url: toPathString(localVarUrlObj),
                options: localVarRequestOptions,
            };
        },
        /**
         * 
         * @summary Singup
         * @param {string} loginUser 
         * @param {string} passwordUser 
         * @param {string} firstName 
         * @param {string} lastName 
         * @param {string} email 
         * @param {string} phoneNumber 
         * @param {*} [options] Override http request option.
         * @throws {RequiredError}
         */
        singUpSingupPost: async (loginUser: string, passwordUser: string, firstName: string, lastName: string, email: string, phoneNumber: string, options: RawAxiosRequestConfig = {}): Promise<RequestArgs> => {
            // verify required parameter 'loginUser' is not null or undefined
            assertParamExists('singUpSingupPost', 'loginUser', loginUser)
            // verify required parameter 'passwordUser' is not null or undefined
            assertParamExists('singUpSingupPost', 'passwordUser', passwordUser)
            // verify required parameter 'firstName' is not null or undefined
            assertParamExists('singUpSingupPost', 'firstName', firstName)
            // verify required parameter 'lastName' is not null or undefined
            assertParamExists('singUpSingupPost', 'lastName', lastName)
            // verify required parameter 'email' is not null or undefined
            assertParamExists('singUpSingupPost', 'email', email)
            // verify required parameter 'phoneNumber' is not null or undefined
            assertParamExists('singUpSingupPost', 'phoneNumber', phoneNumber)
            const localVarPath = `/singup`;
            // use dummy base URL string because the URL constructor only accepts absolute URLs.
            const localVarUrlObj = new URL(localVarPath, DUMMY_BASE_URL);
            let baseOptions;
            if (configuration) {
                baseOptions = configuration.baseOptions;
            }

            const localVarRequestOptions = { method: 'POST', ...baseOptions, ...options};
            const localVarHeaderParameter = {} as any;
            const localVarQueryParameter = {} as any;

            if (loginUser !== undefined) {
                localVarQueryParameter['loginUser'] = loginUser;
            }

            if (passwordUser !== undefined) {
                localVarQueryParameter['passwordUser'] = passwordUser;
            }

            if (firstName !== undefined) {
                localVarQueryParameter['firstName'] = firstName;
            }

            if (lastName !== undefined) {
                localVarQueryParameter['lastName'] = lastName;
            }

            if (email !== undefined) {
                localVarQueryParameter['email'] = email;
            }

            if (phoneNumber !== undefined) {
                localVarQueryParameter['phoneNumber'] = phoneNumber;
            }


    
            setSearchParams(localVarUrlObj, localVarQueryParameter);
            let headersFromBaseOptions = baseOptions && baseOptions.headers ? baseOptions.headers : {};
            localVarRequestOptions.headers = {...localVarHeaderParameter, ...headersFromBaseOptions, ...options.headers};

            return {
                url: toPathString(localVarUrlObj),
                options: localVarRequestOptions,
            };
        },
    }
};

/**
 * DefaultApi - functional programming interface
 * @export
 */
export const DefaultApiFp = function(configuration?: Configuration) {
    const localVarAxiosParamCreator = DefaultApiAxiosParamCreator(configuration)
    return {
        /**
         * 
         * @summary Getuser
         * @param {number} idUser 
         * @param {*} [options] Override http request option.
         * @throws {RequiredError}
         */
        async getUserUserPost(idUser: number, options?: RawAxiosRequestConfig): Promise<(axios?: AxiosInstance, basePath?: string) => AxiosPromise<any>> {
            const localVarAxiosArgs = await localVarAxiosParamCreator.getUserUserPost(idUser, options);
            const localVarOperationServerIndex = configuration?.serverIndex ?? 0;
            const localVarOperationServerBasePath = operationServerMap['DefaultApi.getUserUserPost']?.[localVarOperationServerIndex]?.url;
            return (axios, basePath) => createRequestFunction(localVarAxiosArgs, globalAxios, BASE_PATH, configuration)(axios, localVarOperationServerBasePath || basePath);
        },
        /**
         * 
         * @summary Loginbytoken
         * @param {string} userToken 
         * @param {*} [options] Override http request option.
         * @throws {RequiredError}
         */
        async logInByTokenLoginByTokenPost(userToken: string, options?: RawAxiosRequestConfig): Promise<(axios?: AxiosInstance, basePath?: string) => AxiosPromise<any>> {
            const localVarAxiosArgs = await localVarAxiosParamCreator.logInByTokenLoginByTokenPost(userToken, options);
            const localVarOperationServerIndex = configuration?.serverIndex ?? 0;
            const localVarOperationServerBasePath = operationServerMap['DefaultApi.logInByTokenLoginByTokenPost']?.[localVarOperationServerIndex]?.url;
            return (axios, basePath) => createRequestFunction(localVarAxiosArgs, globalAxios, BASE_PATH, configuration)(axios, localVarOperationServerBasePath || basePath);
        },
        /**
         * 
         * @summary Login
         * @param {string} loginUser 
         * @param {string} passwordUser 
         * @param {*} [options] Override http request option.
         * @throws {RequiredError}
         */
        async logInLoginPost(loginUser: string, passwordUser: string, options?: RawAxiosRequestConfig): Promise<(axios?: AxiosInstance, basePath?: string) => AxiosPromise<any>> {
            const localVarAxiosArgs = await localVarAxiosParamCreator.logInLoginPost(loginUser, passwordUser, options);
            const localVarOperationServerIndex = configuration?.serverIndex ?? 0;
            const localVarOperationServerBasePath = operationServerMap['DefaultApi.logInLoginPost']?.[localVarOperationServerIndex]?.url;
            return (axios, basePath) => createRequestFunction(localVarAxiosArgs, globalAxios, BASE_PATH, configuration)(axios, localVarOperationServerBasePath || basePath);
        },
        /**
         * 
         * @summary Readmain
         * @param {*} [options] Override http request option.
         * @throws {RequiredError}
         */
        async readMainGet(options?: RawAxiosRequestConfig): Promise<(axios?: AxiosInstance, basePath?: string) => AxiosPromise<any>> {
            const localVarAxiosArgs = await localVarAxiosParamCreator.readMainGet(options);
            const localVarOperationServerIndex = configuration?.serverIndex ?? 0;
            const localVarOperationServerBasePath = operationServerMap['DefaultApi.readMainGet']?.[localVarOperationServerIndex]?.url;
            return (axios, basePath) => createRequestFunction(localVarAxiosArgs, globalAxios, BASE_PATH, configuration)(axios, localVarOperationServerBasePath || basePath);
        },
        /**
         * 
         * @summary Singup
         * @param {string} loginUser 
         * @param {string} passwordUser 
         * @param {string} firstName 
         * @param {string} lastName 
         * @param {string} email 
         * @param {string} phoneNumber 
         * @param {*} [options] Override http request option.
         * @throws {RequiredError}
         */
        async singUpSingupPost(loginUser: string, passwordUser: string, firstName: string, lastName: string, email: string, phoneNumber: string, options?: RawAxiosRequestConfig): Promise<(axios?: AxiosInstance, basePath?: string) => AxiosPromise<any>> {
            const localVarAxiosArgs = await localVarAxiosParamCreator.singUpSingupPost(loginUser, passwordUser, firstName, lastName, email, phoneNumber, options);
            const localVarOperationServerIndex = configuration?.serverIndex ?? 0;
            const localVarOperationServerBasePath = operationServerMap['DefaultApi.singUpSingupPost']?.[localVarOperationServerIndex]?.url;
            return (axios, basePath) => createRequestFunction(localVarAxiosArgs, globalAxios, BASE_PATH, configuration)(axios, localVarOperationServerBasePath || basePath);
        },
    }
};

/**
 * DefaultApi - factory interface
 * @export
 */
export const DefaultApiFactory = function (configuration?: Configuration, basePath?: string, axios?: AxiosInstance) {
    const localVarFp = DefaultApiFp(configuration)
    return {
        /**
         * 
         * @summary Getuser
         * @param {number} idUser 
         * @param {*} [options] Override http request option.
         * @throws {RequiredError}
         */
        getUserUserPost(idUser: number, options?: RawAxiosRequestConfig): AxiosPromise<any> {
            return localVarFp.getUserUserPost(idUser, options).then((request) => request(axios, basePath));
        },
        /**
         * 
         * @summary Loginbytoken
         * @param {string} userToken 
         * @param {*} [options] Override http request option.
         * @throws {RequiredError}
         */
        logInByTokenLoginByTokenPost(userToken: string, options?: RawAxiosRequestConfig): AxiosPromise<any> {
            return localVarFp.logInByTokenLoginByTokenPost(userToken, options).then((request) => request(axios, basePath));
        },
        /**
         * 
         * @summary Login
         * @param {string} loginUser 
         * @param {string} passwordUser 
         * @param {*} [options] Override http request option.
         * @throws {RequiredError}
         */
        logInLoginPost(loginUser: string, passwordUser: string, options?: RawAxiosRequestConfig): AxiosPromise<any> {
            return localVarFp.logInLoginPost(loginUser, passwordUser, options).then((request) => request(axios, basePath));
        },
        /**
         * 
         * @summary Readmain
         * @param {*} [options] Override http request option.
         * @throws {RequiredError}
         */
        readMainGet(options?: RawAxiosRequestConfig): AxiosPromise<any> {
            return localVarFp.readMainGet(options).then((request) => request(axios, basePath));
        },
        /**
         * 
         * @summary Singup
         * @param {string} loginUser 
         * @param {string} passwordUser 
         * @param {string} firstName 
         * @param {string} lastName 
         * @param {string} email 
         * @param {string} phoneNumber 
         * @param {*} [options] Override http request option.
         * @throws {RequiredError}
         */
        singUpSingupPost(loginUser: string, passwordUser: string, firstName: string, lastName: string, email: string, phoneNumber: string, options?: RawAxiosRequestConfig): AxiosPromise<any> {
            return localVarFp.singUpSingupPost(loginUser, passwordUser, firstName, lastName, email, phoneNumber, options).then((request) => request(axios, basePath));
        },
    };
};

/**
 * DefaultApi - object-oriented interface
 * @export
 * @class DefaultApi
 * @extends {BaseAPI}
 */
export class DefaultApi extends BaseAPI {
    /**
     * 
     * @summary Getuser
     * @param {number} idUser 
     * @param {*} [options] Override http request option.
     * @throws {RequiredError}
     * @memberof DefaultApi
     */
    public getUserUserPost(idUser: number, options?: RawAxiosRequestConfig) {
        return DefaultApiFp(this.configuration).getUserUserPost(idUser, options).then((request) => request(this.axios, this.basePath));
    }

    /**
     * 
     * @summary Loginbytoken
     * @param {string} userToken 
     * @param {*} [options] Override http request option.
     * @throws {RequiredError}
     * @memberof DefaultApi
     */
    public logInByTokenLoginByTokenPost(userToken: string, options?: RawAxiosRequestConfig) {
        return DefaultApiFp(this.configuration).logInByTokenLoginByTokenPost(userToken, options).then((request) => request(this.axios, this.basePath));
    }

    /**
     * 
     * @summary Login
     * @param {string} loginUser 
     * @param {string} passwordUser 
     * @param {*} [options] Override http request option.
     * @throws {RequiredError}
     * @memberof DefaultApi
     */
    public logInLoginPost(loginUser: string, passwordUser: string, options?: RawAxiosRequestConfig) {
        return DefaultApiFp(this.configuration).logInLoginPost(loginUser, passwordUser, options).then((request) => request(this.axios, this.basePath));
    }

    /**
     * 
     * @summary Readmain
     * @param {*} [options] Override http request option.
     * @throws {RequiredError}
     * @memberof DefaultApi
     */
    public readMainGet(options?: RawAxiosRequestConfig) {
        return DefaultApiFp(this.configuration).readMainGet(options).then((request) => request(this.axios, this.basePath));
    }

    /**
     * 
     * @summary Singup
     * @param {string} loginUser 
     * @param {string} passwordUser 
     * @param {string} firstName 
     * @param {string} lastName 
     * @param {string} email 
     * @param {string} phoneNumber 
     * @param {*} [options] Override http request option.
     * @throws {RequiredError}
     * @memberof DefaultApi
     */
    public singUpSingupPost(loginUser: string, passwordUser: string, firstName: string, lastName: string, email: string, phoneNumber: string, options?: RawAxiosRequestConfig) {
        return DefaultApiFp(this.configuration).singUpSingupPost(loginUser, passwordUser, firstName, lastName, email, phoneNumber, options).then((request) => request(this.axios, this.basePath));
    }
}



