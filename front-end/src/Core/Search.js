import { hasIn, isNil, is, type } from 'ramda';
import { $searchMap } from './Mappers/search';

export default class Search {
    constructor() {
        const wSearch = hasIn('$search', window) ? window.$search : {};
        const options = { ...$searchMap, ...wSearch };

        this._init(options);
    }

    _init(options = {}) {
        this._baseUrl = hasIn('src', options) ? options.src : '';
        this._type = hasIn('type', options) ? options.type : null;
        this._filters = hasIn('filters', options) ? options.filters : null;
        this._section = hasIn('section', options) ? options.section : null;
        this._staticSearch = !isNil(options.static) && options.static ? options.static : false;
    }

    get(attr, $default = null) {
        if (hasIn(`_${attr}`, this)) {
            return this[`_${attr}`];
        }
        return $default;
    }

    set(attr, value) {
        if (hasIn(`_${attr}`, this)) {
            this[`_${attr}`] = value;
        } else {
            console.error(`Invalid property "this._${attr}"`);
        }
        return this;
    }

    route(route) {
        if (is(String, route)) {
            route = route.startsWith('/') ? route : `/${route}`;
            return `${this._baseUrl}${route}`;
        }
        console.error(`route must be a String send ${type(route)}`);
        return '';
    }

    isStatic($overrideFalse = false) {
        if ($overrideFalse !== false && !this._staticSearch) {
            return $overrideFalse;
        }
        return this._staticSearch;
    }
}