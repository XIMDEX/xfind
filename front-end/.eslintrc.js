
module.exports = {
    root: true,
    parserOptions: {
        parser: 'babel-eslint',
        sourceType: 'module'
    },
    env: {
        browser: true,
    },
    // https://github.com/feross/standard/blob/master/RULES.md#javascript-standard-style
    extends: [
        'standard',
        'plugin:vue/recommended',
    ],
    // required to lint *.vue files
    plugins: [
        'html'
    ],
    'settings': {
        'html/html-extensions': ['.html'], // don't include .vue
    },
    // add your custom rules here
    'rules': {
        // allow paren-less arrow functions
        'arrow-parens': 0,
        // allow async-await
        'generator-star-spacing': 0,
        // allow debugger during development
        'no-debugger': process.env.NODE_ENV === 'production' ? 2 : 0,

        "indent": ["error", 4],
        "eol-last": ["off", "never"],
        "comma-dangle": ["off", false],
        "semi": ["off"],
        "space-before-function-paren": ["error", "never"],
        "quotes": ["off"],
        "spaced-comment": ["off"],
        "vue/order-in-components": ["off"],
        "vue/name-property-casing": ["error", "kebab-case"],
        "vue/html-indent": ["error", 4, {
            "attribute": 1,
            "closeBracket": 0,
            "ignores": []
        }],
    }
}