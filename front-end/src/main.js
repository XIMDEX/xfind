// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue';
import VueAxios from 'vue-axios';
import axios from 'axios';

import api from './settings/api';
import App from './App';
import { truncate, implode } from './filters/StringFilters';
import { translate } from './filters/Translations';
import Search from './Core/Search';
import { formatDate } from './Core/Date';
import Options from './Core/Options';

Vue.config.productionTip = false;

Vue.use(VueAxios, axios);
Vue.use(api);

Vue.filter('trans', translate);
Vue.filter('implode', implode);
Vue.filter('truncate', truncate);

Vue.filter('date', formatDate);

Vue.prototype.$search = new Search();
Vue.prototype.$s_options = new Options();

/* eslint-disable no-new */
new Vue({
    el: '#app',
    components: { App },
    template: '<App/>'
});
