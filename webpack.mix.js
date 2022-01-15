const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js');

mix.scripts([
    'public/js/jquery.min.js',
    'public/js/jquery-ui.js',
    'public/js/jquery.modal.min.js',
    'public/js/wnoty.js',
    'public/js/clipboard.min.js',
    'public/js/chart.min.js',
    'public/js/chartjs-plugin-labels.js',
    'public/js/circle-progress.js',
    'public/js/app.js'
], 'public/js/all.js');

mix.js('resources/admin/admin.js', 'public/js');

mix.styles([
    'public/css/all.css',
    'public/css/wnoty.css',
    'public/css/app.css'
], 'public/css/project.css');