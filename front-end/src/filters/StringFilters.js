import { isNil } from 'ramda';

export function truncate(string, max = 60, start = 0, ellipsis = true) {
    let result = isNil(string) ? '' : string;
    if (result.length > max) {
        result = result.substring(0, max);
        if (ellipsis) {
            result += '...';
        }
    }
    return result;
}

export function implode(array, separator = ' ') {
    if (Array.isArray(array)) {
        return array.join(separator);
    }
    return array;
}