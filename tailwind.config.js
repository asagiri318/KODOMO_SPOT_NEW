const defaultTheme = require('tailwindcss/defaultTheme');
const forms = require('@tailwindcss/forms');

module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            width: {
                '72': '30rem', // カスタム幅を追加
                '84': '21rem',
                '96': '24rem',
            },
            height: {
                '72': '13rem', // カスタム高さを追加
                '84': '21rem',
                '96': '24rem',
            },
        },
    },

    plugins: [forms],
};
