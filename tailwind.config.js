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
                'blue-500': '#3b82f6',
                'green-500': '#10b981',
                'yellow-500': '#f59e0b',
                'red-500': '#ef4444',
                'purple-500': '#8b5cf6',
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans], // Changed to Inter for a formal look
            },
        },
    },
    plugins: [forms],
};
