const mix = require('laravel-mix');

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

let assets = {
    js: [
        "resources/js/app.js"
    ],
    css: [
        "resources/sass/app.scss"
    ]
}

assets.js.forEach(source => {
    mix.js(source, "public/assets/js");
});

assets.css.forEach(source => {
    mix.sass(source, "public/assets/css");
});