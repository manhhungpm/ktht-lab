const path = require("path");
const mix = require("laravel-mix");

// const { BundleAnalyzerPlugin } = require('webpack-bundle-analyzer')

mix.config.vue.esModule = true;

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 */

mix.js("resources/js/app.js", "public/js")
    .sass("resources/sass/app.scss", "public/css")
    .sass("resources/sass/vendors/vendors.scss", "public/css")

    .sourceMaps(false, "source-map")
    .disableNotifications();

if (mix.inProduction()) {
    mix.version();

    mix.extract([
        "vue",
        "axios",
        "vuex",
        "jquery",
        "popper.js",
        "vue-i18n",
        "vue-meta",
        "js-cookie",
        "jquery-ui-dist/jquery-ui",
        "bootstrap",
        "vue-router",
        "vuex-router-sync",
        "perfect-scrollbar",
        "datatables.net",
        "datatables.net-bs4",
        "select2",
        "numeral",
        "moment",
        "highcharts"
    ]);
} else {
    mix.browserSync("http://localhost:8000");
}

mix.webpackConfig({
    plugins: [
        // new BundleAnalyzerPlugin()
    ],
    resolve: {
        extensions: [".js", ".json", ".vue"],
        alias: {
            "~": path.join(__dirname, "./resources/js"),
            "~assets": path.join(__dirname, "./resources/assets")
        }
    },
    output: {
        chunkFilename: "js/[name].[chunkhash].js",
        publicPath: mix.config.hmr ? "//localhost:8080" : "/"
    }
});
