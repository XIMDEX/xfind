// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue';
import VueAxios from 'vue-axios';
import axios from 'axios';
import Lang from 'lang.js';
import { isNil, clone } from 'ramda';
import dateFormat from 'dateformat';

import api from './settings/api';
import App from './App';
import translations from './settings/Translations';

const lang = new Lang();
lang.setFallback('es');
lang.setLocale('es');
lang.setMessages(translations);

Vue.config.productionTip = false;

Vue.use(VueAxios, axios);
Vue.use(api);

Vue.filter('trans', (...args) => lang.get(...args));

Vue.filter('implode', (array, separator = ' ') => {
    if (Array.isArray(array)) {
        return array.join(separator);
    }
    return array;
});

Vue.filter('truncate', (string, max = 60, start = 0, ellipsis = true) => {
    let result = isNil(string) ? '' : string;
    if (result.length > max) {
        result = result.substring(0, max);
        if (ellipsis) {
            result += '...';
        }
    }
    return result;
});

Vue.filter('date', (date, format = 'dd/mm/yyyy') => {
    let result = date;
    try {
        if (Array.isArray(result)) {
            result = clone(date);
            for (const key in result) {
                result[key] = dateFormat(result[key], format);
            }
        } else {
            result = dateFormat(result, format);
        }
    } catch (e) {}

    return result;
});

/* eslint-disable no-new */
new Vue({
    el: '#app',
    components: { App },
    template: '<App/>'
});
