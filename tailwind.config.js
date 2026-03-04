import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

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
                gold: {
                    light: '#F4E0A1',   // Champagne
                    DEFAULT: '#D4AF37', // Classic Gold
                    dark: '#996515',    // Golden Brown
                    muted: '#8A7139',
                },
                luxury: {
                    black: '#0A0A0A',
                    charcoal: '#111111',
                    cream: '#FAF9F6',
                    bronze: '#CD7F32'
                }
            },
            fontFamily: {
                sans: ['Outfit', ...defaultTheme.fontFamily.sans],
                serif: ['"Playfair Display"', 'serif'],
            },
        },
    },

    plugins: [forms],
};
