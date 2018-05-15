import 'es6-promise/auto';
import axios from 'axios';
import { isNil } from 'ramda';
import queryString from 'query-string';

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

axios.interceptors.response.use(
    response => response.data,
    error => Promise.reject(error.response.data)
);

export default function(Vue) {
    Vue.xfind = {
        get(uri, query = null, filters = {}) {
            const configs = {
                headers: {
                    'Content-type': 'application/json'
                },
                filters
            };

            if (!isNil(query) && query instanceof Object) {
                query = queryString.stringify(query, { arrayFormat: 'index' });
            }

            if (!isNil(query) && typeof query === 'string') {
                uri = `${uri}?${query}`;
            }

            return axios.get(uri, configs);
        }
    };

    Object.defineProperties(Vue.prototype, {
        $xfind: {
            get: () => Vue.xfind
        }
    });
}
