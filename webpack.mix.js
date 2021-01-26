const mix = require('laravel-mix');
require('laravel-mix-jigsaw');

mix.disableSuccessNotifications();
mix.setPublicPath('source/dist/build');

mix.sass('source/assets/sass/main.scss', 'css/main.css')
    .jigsaw({
        watch: ['config.php', 'source/**/*.md', 'source/**/*.php', 'source/**/*.scss'],
    })
    .options({
        processCssUrls: false,
        postCss: [
            require('tailwindcss'),
        ],
    })
    .sourceMaps()
    .version();
