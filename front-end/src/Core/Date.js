import { clone, isNil } from 'ramda';
import dateFormat from 'dateformat';
import moment from 'moment';
import Options from './Options';

export default class Date extends Options {
    isYear(string) {
        let result;
        if (Array.isArray(string)) {
            result = [];
            for (let key in string) {
                result.push(moment(string[key], ['YYYY', 'Y', 'YY']).isValid());
            }
        } else {
            result = moment(string, ['YYYY', 'Y', 'YY']).isValid();
        }
        return result;
    }
    parse(date, format = null) {
        if (isNil(format)) {
            format = this._formatDate.toUpperCase();
        }
        let result = date;
        try {
            if (Array.isArray(result)) {
                result = clone(date);
                for (const key in result) {
                    result[key] = moment(result[key], format).toDate();
                }
            } else {
                result = moment(result, format).toDate();
            }
        } catch (e) {
            console.error(`failed to parse date ${date}`)
        }
        return result;
    }

    format(date, format = null) {
        if (isNil(format)) {
            format = this._formatDate;
        }
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
        } catch (e) {
            console.error(`failed to parse date ${date}`)
        }
        return result;
    }
}

export function formatDate(date, format = null) {
    let $date = new Date();
    let result = date;
    const isYear = $date.isYear(date);

    if (isYear === false) {
        result = $date.format(date, format);
    } else if (Array.isArray(isYear)) {
        result = [];
        for (let key in isYear) {
            if (isYear[key]) {
                result.push(date[key]);
                continue;
            }
            result.push(formatDate(date[key], format));
        }
    }
    return result;
}

export function parseDate(date, format = null) {
    let $date = new Date();
    let result = date;
    const isYear = $date.isYear(date);

    if (isYear === false) {
        result = $date.parse(date, format);
    } else if (Array.isArray(isYear)) {
        result = [];
        for (let key in isYear) {
            if (isYear[key]) {
                result.push(date[key]);
                continue;
            }
            result.push(parseDate(date[key], format));
        }
    }
    return result;
}