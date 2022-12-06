const mix = require('laravel-mix');
require('laravel-mix-svelte');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.setPublicPath('public')
    .js('resources/js/app.js', 'js')
    .postCss('resources/css/app.css', 'css', [
        require('postcss-import'),
        require('tailwindcss'),
        require('autoprefixer'),
    ])
    .browserSync({
        proxy: "servas.localhost",
        // files: [
        //     './path/**/*.yaml',
        //     './path/**/*.blade.php',
        // ],
        open: false,
        notify: false,
    })
    .disableSuccessNotifications()
    .options({
        // Don't perform any css url rewriting by default
        processCssUrls: false,
    })
    .webpackConfig(require('./webpack.config'))
    .copy('resources/fonts', 'public/assets/fonts')
    .version()
    .svelte();

if (process.env.NODE_ENV === 'development') {
    mix.js('resources/js/swagger/swagger.js', 'js');
}
