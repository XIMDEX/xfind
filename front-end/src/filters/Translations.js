import Lang from 'lang.js';
import translations from '../settings/Translations';

const lang = new Lang();
lang.setFallback('es');
lang.setLocale('es');
lang.setMessages(translations);

export function translate(...args) {
    return lang.get(...args);
};