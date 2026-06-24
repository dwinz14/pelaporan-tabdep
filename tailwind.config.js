import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/**/*.js',
    ],

    theme: {
        extend: {
            colors: {
                brand: {
                    50:  '#EEF2FF',
                    100: '#E0E7FF',
                    500: '#6366F1',
                    600: '#4F46E5',
                    700: '#4338CA',
                    900: '#1E1B4B',
                }
            },
            fontFamily: {
                sans: ['Inter', 'Figtree', ...defaultTheme.fontFamily.sans],
                mono: ['JetBrains Mono', 'Fira Code', ...defaultTheme.fontFamily.mono],
            },
            boxShadow: {
                'card': '0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04)',
                'card-hover': '0 4px 6px -1px rgba(0,0,0,0.08), 0 2px 4px -2px rgba(0,0,0,0.05)',
                'modal': '0 20px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -6px rgba(0,0,0,0.05)',
            },
            animation: {
                'page-in': 'pageIn 300ms cubic-bezier(0.16, 1, 0.3, 1)',
                'pulse-dot': 'pulseDot 2s ease-in-out infinite',
            }
        },
    },

    plugins: [forms],
};
