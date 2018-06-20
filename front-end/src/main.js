// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue';
import VueAxios from 'vue-axios';
import axios from 'axios';
import { isNil } from 'ramda';

import api from './settings/api';
import App from './App';

Vue.config.productionTip = false;

Vue.use(VueAxios, axios);
Vue.use(api);

Vue.filter('implode', (array, separator = ' ') => {
    return array.join(separator);
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

/* eslint-disable no-new */
new Vue({
    el: '#app',
    components: { App },
    template: '<App/>'
});
