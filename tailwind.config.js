/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    theme: {
        extend: {
            colors: {
                blood: {
                    50:  '#fff1f1',
                    100: '#ffe0e0',
                    200: '#ffc5c5',
                    300: '#ff9d9d',
                    400: '#ff6464',
                    500: '#ff2d2d',
                    600: '#e81010',
                    700: '#c30a0a',
                    800: '#a10d0d',
                    900: '#850f0f',
                },
            },
            fontFamily: {
                display: ['Playfair Display', 'serif'],
                body:    ['DM Sans', 'sans-serif'],
                mono:    ['DM Mono', 'monospace'],
            },
        },
    },
    plugins: [],
};
