/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './src/resources/views/**/*.blade.php',
        // Laravel's default (Tailwind) pagination views, so the paginator
        // classes are baked into the shipped stylesheet.
        '../../../vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    ],
    theme: {
        extend: {},
    },
    plugins: [require('@tailwindcss/forms')],
};
