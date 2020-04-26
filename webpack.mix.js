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

//配置忽略node_modules文件夹
// if(Mix.isWatching()){
//     mix.webpackConfig({
//         watchOptions:{
//             ignored : /node_modules/,
//             //poll : 1000
//         },
//     })
// }
mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css').version();
