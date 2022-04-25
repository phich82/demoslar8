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
mix.js([
        // mix js files into a single js file
        'resources/js/app.js',
        // 'resources/js/broadcast.js',
    ], 'public/js')
    .js('resources/js/broadcast.js', 'public/js/broadcast.js')
    .vue()
    .sass('resources/sass/app.scss', 'public/css')
    .copyDirectory('resources/images', 'public/images')
    .sourceMaps();

if (mix.inProduction()) {
    mix.version();
}

// Disable notifications after built
// mix.disableNotifications();
