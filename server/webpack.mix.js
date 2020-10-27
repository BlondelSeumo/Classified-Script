const mix = require('laravel-mix');

// Client && Server entries point
mix.js('resources/js/app/client.js', 'public/js')
   .js('resources/js/app/server.js', 'public/js');

mix.sass('resources/sass/app.scss', 'public/css');

mix.webpackConfig({
    node: {
      fs: "empty"
    },
    resolve: {
        alias: {
            "handlebars" : "handlebars/dist/handlebars.js"
        }
    },
});