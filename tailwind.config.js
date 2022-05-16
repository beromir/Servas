const colors = require('tailwindcss/colors')
const defaultTheme = require('tailwindcss/defaultTheme')

function withOpacityValue(variable) {
    return ({opacityValue}) => {
        if (opacityValue === undefined) {
            return `hsl(var(${variable}))`
        }
        return `hsl(var(${variable}) / ${opacityValue})`
    }
}

module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.svelte',
        './resources/js/**/*.js',
    ],

    darkMode: 'class',

    theme: {
        colors: {
            transparent: 'transparent',
            current: 'currentColor',
            black: colors.black,
            white: colors.white,
            primary: {
                900: withOpacityValue('--color-primary-900'),
                800: withOpacityValue('--color-primary-800'),
                700: withOpacityValue('--color-primary-700'),
                600: withOpacityValue('--color-primary-600'),
                500: withOpacityValue('--color-primary-500'),
                400: withOpacityValue('--color-primary-400'),
                300: withOpacityValue('--color-primary-300'),
                200: withOpacityValue('--color-primary-200'),
                100: withOpacityValue('--color-primary-100'),
                50: withOpacityValue('--color-primary-50'),
            },
            gray: {
                900: withOpacityValue('--color-gray-900'),
                800: withOpacityValue('--color-gray-800'),
                700: withOpacityValue('--color-gray-700'),
                600: withOpacityValue('--color-gray-600'),
                500: withOpacityValue('--color-gray-500'),
                400: withOpacityValue('--color-gray-400'),
                300: withOpacityValue('--color-gray-300'),
                200: withOpacityValue('--color-gray-200'),
                100: withOpacityValue('--color-gray-100'),
                50: withOpacityValue('--color-gray-50'),
            },
            blue: {
                900: withOpacityValue('--color-blue-900'),
                800: withOpacityValue('--color-blue-800'),
                700: withOpacityValue('--color-blue-700'),
                600: withOpacityValue('--color-blue-600'),
                500: withOpacityValue('--color-blue-500'),
                400: withOpacityValue('--color-blue-400'),
                300: withOpacityValue('--color-blue-300'),
                200: withOpacityValue('--color-blue-200'),
                100: withOpacityValue('--color-blue-100'),
                50: withOpacityValue('--color-blue-50'),
            },
            red: {
                900: withOpacityValue('--color-red-900'),
                800: withOpacityValue('--color-red-800'),
                700: withOpacityValue('--color-red-700'),
                600: withOpacityValue('--color-red-600'),
                500: withOpacityValue('--color-red-500'),
                400: withOpacityValue('--color-red-400'),
                300: withOpacityValue('--color-red-300'),
                200: withOpacityValue('--color-red-200'),
                100: withOpacityValue('--color-red-100'),
                50: withOpacityValue('--color-red-50'),
            },
            yellow: {
                900: withOpacityValue('--color-yellow-900'),
                800: withOpacityValue('--color-yellow-800'),
                700: withOpacityValue('--color-yellow-700'),
                600: withOpacityValue('--color-yellow-600'),
                500: withOpacityValue('--color-yellow-500'),
                400: withOpacityValue('--color-yellow-400'),
                300: withOpacityValue('--color-yellow-300'),
                200: withOpacityValue('--color-yellow-200'),
                100: withOpacityValue('--color-yellow-100'),
                50: withOpacityValue('--color-yellow-50'),
            },
            green: {
                900: withOpacityValue('--color-green-900'),
                800: withOpacityValue('--color-green-800'),
                700: withOpacityValue('--color-green-700'),
                600: withOpacityValue('--color-green-600'),
                500: withOpacityValue('--color-green-500'),
                400: withOpacityValue('--color-green-400'),
                300: withOpacityValue('--color-green-300'),
                200: withOpacityValue('--color-green-200'),
                100: withOpacityValue('--color-green-100'),
                50: withOpacityValue('--color-green-50'),
            },
        },
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                mono: ['JetBrains Mono', ...defaultTheme.fontFamily.mono],
            },
        },
    },

    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
    ],
};
