const mix = require("laravel-mix");

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js("resources/js/app.js", "public/js")
    .sass("resources/sass/app.scss", "public/css")
    .js("resources/js/vendor/voyager/app.js", "public/js/vendor/main.js")
    .js('resources/js/pages/appointment.js', 'public/js/pages/appointments.js')
    .js('resources/js/pages/reports.js', 'public/js/pages/reports.js')
    .sass("resources/sass/vendor/app.sass", "public/css/vendor/main.css");
