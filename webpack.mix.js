require("dotenv").config();
let webpack = require("webpack");
const mix = require("laravel-mix");

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
let dotenvplugin = new webpack.DefinePlugin({
    "process.env": {
        API_URL: JSON.stringify(process.env.API_URL || "API_URL"),
    },
});
mix.webpackConfig({
    plugins: [dotenvplugin],
});
mix.js("resources/js/app.js", "public/js").vue();
