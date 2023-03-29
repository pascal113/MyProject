require('dotenv').config()
const webpack = require('webpack')
const mix = require('laravel-mix')

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

// Make specific env vars available to JS
const dotenvplugin = new webpack.DefinePlugin({
    'process.env': {
        APP_ENV: JSON.stringify(process.env.APP_ENV || 'production'),
        BROWSER_SYNC_PORT: JSON.stringify(process.env.BROWSER_SYNC_PORT || undefined),
    },
})
mix.webpackConfig({ plugins: [ dotenvplugin ] })

/* Public */
mix.copy('node_modules/slick-carousel/slick/ajax-loader.gif', 'public/css')
mix.copyDirectory('node_modules/slick-carousel/slick/fonts', 'public/css/fonts')
mix.js('resources/js/app.js', 'public/js').vue()
mix.sass('resources/sass/app.scss', 'public/css').options({ processCssUrls: false })
mix.sass('resources/sass/print.scss', 'public/css').options({ processCssUrls: false })

/* Payeezy */
mix.sass('resources/sass/payeezy-forms.scss', 'public/css').options({ processCssUrls: false })

/* TinyMCE */
mix.sass('resources/sass/tinyMCE.scss', 'public/css').options({ processCssUrls: false })

/* Admin */
mix.js('resources/js/admin.js', 'public/js').vue()
mix.sass('resources/sass/admin-overrides.scss', 'public/css').options({ processCssUrls: false })

mix.sourceMaps()

if (process.env.BROWSER_SYNC_PORT) {
    mix.browserSync({
        proxy: process.env.APP_URL,
        port: process.env.BROWSER_SYNC_PORT,
        ui: false,
    })
}

/*
    .then(() => {
        del('public/js/app.js');
        del('public/js/global.js');
        del('public/css/app.css');
    })
*/
