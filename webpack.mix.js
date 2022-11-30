const mix = require('laravel-mix');
require('laravel-mix-jigsaw');

mix.disableSuccessNotifications();
mix.setPublicPath('source/assets/build');

mix.sass('source/__source/assets/sass/main.scss', 'css/main.css')
    .jigsaw({
        watch: ['config.php', 'source/**/*.md', 'source/**/*.php', 'source/**/*.scss'],
    })
    .options({
        processCssUrls: false,
        postCss: [
            require('tailwindcss')('./tailwind.config.js')
        ],
    })
    .webpackConfig({
        module: {
            rules: [
                {
                  test: /\.ya?ml$/,
                  use: 'yaml-loader'
                }
              ]
        }
    })
    .sass('source/__source/assets/sass/manual_mode.scss', 'css/manual_mode.css', {processCssUrls: false}, [
        require('tailwindcss')('./tailwindForManualMode.config.js')
    ])
    .js('source/__source/assets/js/main.js', 'js/main.js')
    .js('source/__source/assets/js/quiz-bundle.js', 'js/quiz-bundle.js')
    .js('source/__source/assets/js/section-highlight.js', 'js/section-highlight.js')
    .js('source/__source/assets/js/i18n/index.js', 'js/i18n/index.js')
    .js('source/__source/assets/js/search/index.js', 'js/search/index.js')
    .js('source/__source/assets/js/search/mark.js', 'js/search/mark.js')
    .sourceMaps()
    .version();