import { hasIn } from 'ramda';
import Search from './Search';

export default class Options extends Search {
    _init(options = {}) {
        const _options = options.options;
        this._formatDate = hasIn('format_date', _options) ? _options.format_date : 'dd/mm/yyyy';
        this._responseDate = hasIn('response_date', _options) ? _options.response_date : 'yyyy-mm-ddTHH:MM:SSZ';
    }
}