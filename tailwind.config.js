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

    theme: {
        fontSize: {
          'xs': '0.75rem',
          'sm': '0.875rem',
          'base': '1rem',
          'lg': '1.125rem',
          'xl': '1.25rem',
          '2xl': '1.5rem',
          '3xl': '1.875rem',
          '4xl': '2.25rem',
          '5xl': '3rem',
          '6xl': '4rem',
          '7xl': '5rem',
          '8xl': '6rem',
          '9xl': '7rem',
        },
    },
};
