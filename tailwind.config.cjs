const colors = require('tailwindcss/colors')
const defaultTheme = require('tailwindcss/defaultTheme')

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.svelte',
        './resources/js/**/*.js',
    ],

    theme: {
        colors: {
            transparent: 'transparent',
            current: 'currentColor',
            black: colors.black,
            white: colors.white,
            primary: {
                900: 'hsl(var(--color-primary-900) / <alpha-value>)',
                800: 'hsl(var(--color-primary-800) / <alpha-value>)',
                700: 'hsl(var(--color-primary-700) / <alpha-value>)',
                600: 'hsl(var(--color-primary-600) / <alpha-value>)',
                500: 'hsl(var(--color-primary-500) / <alpha-value>)',
                400: 'hsl(var(--color-primary-400) / <alpha-value>)',
                300: 'hsl(var(--color-primary-300) / <alpha-value>)',
                200: 'hsl(var(--color-primary-200) / <alpha-value>)',
                100: 'hsl(var(--color-primary-100) / <alpha-value>)',
                50: 'hsl(var(--color-primary-50) / <alpha-value>)',
            },
            gray: colors.gray,
            blue: {
                900: 'hsl(var(--color-blue-900) / <alpha-value>)',
                800: 'hsl(var(--color-blue-800) / <alpha-value>)',
                700: 'hsl(var(--color-blue-700) / <alpha-value>)',
                600: 'hsl(var(--color-blue-600) / <alpha-value>)',
                500: 'hsl(var(--color-blue-500) / <alpha-value>)',
                400: 'hsl(var(--color-blue-400) / <alpha-value>)',
                300: 'hsl(var(--color-blue-300) / <alpha-value>)',
                200: 'hsl(var(--color-blue-200) / <alpha-value>)',
                100: 'hsl(var(--color-blue-100) / <alpha-value>)',
                50: 'hsl(var(--color-blue-50) / <alpha-value>)',
            },
            red: {
                900: 'hsl(var(--color-red-900) / <alpha-value>)',
                800: 'hsl(var(--color-red-800) / <alpha-value>)',
                700: 'hsl(var(--color-red-700) / <alpha-value>)',
                600: 'hsl(var(--color-red-600) / <alpha-value>)',
                500: 'hsl(var(--color-red-500) / <alpha-value>)',
                400: 'hsl(var(--color-red-400) / <alpha-value>)',
                300: 'hsl(var(--color-red-300) / <alpha-value>)',
                200: 'hsl(var(--color-red-200) / <alpha-value>)',
                100: 'hsl(var(--color-red-100) / <alpha-value>)',
                50: 'hsl(var(--color-red-50) / <alpha-value>)',
            },
            yellow: {
                900: 'hsl(var(--color-yellow-900) / <alpha-value>)',
                800: 'hsl(var(--color-yellow-800) / <alpha-value>)',
                700: 'hsl(var(--color-yellow-700) / <alpha-value>)',
                600: 'hsl(var(--color-yellow-600) / <alpha-value>)',
                500: 'hsl(var(--color-yellow-500) / <alpha-value>)',
                400: 'hsl(var(--color-yellow-400) / <alpha-value>)',
                300: 'hsl(var(--color-yellow-300) / <alpha-value>)',
                200: 'hsl(var(--color-yellow-200) / <alpha-value>)',
                100: 'hsl(var(--color-yellow-100) / <alpha-value>)',
                50: 'hsl(var(--color-yellow-50) / <alpha-value>)',
            },
            green: {
                900: 'hsl(var(--color-green-900) / <alpha-value>)',
                800: 'hsl(var(--color-green-800) / <alpha-value>)',
                700: 'hsl(var(--color-green-700) / <alpha-value>)',
                600: 'hsl(var(--color-green-600) / <alpha-value>)',
                500: 'hsl(var(--color-green-500) / <alpha-value>)',
                400: 'hsl(var(--color-green-400) / <alpha-value>)',
                300: 'hsl(var(--color-green-300) / <alpha-value>)',
                200: 'hsl(var(--color-green-200) / <alpha-value>)',
                100: 'hsl(var(--color-green-100) / <alpha-value>)',
                50: 'hsl(var(--color-green-50) / <alpha-value>)',
            },
        },
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                mono: ['JetBrains Mono', ...defaultTheme.fontFamily.mono],
            },
        },
    },

    darkMode: 'selector',

    future: {
        hoverOnlyWhenSupported: true,
    },

    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
    ],
};
