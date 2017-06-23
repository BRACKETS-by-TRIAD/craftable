mix.js(['resources/assets/js/admin/admin.js', 'resources/assets/js/admin/coreui/app.js'], 'public/js/admin')
    .webpackConfig({
        resolve: {
            modules: [
                path.resolve(__dirname, 'vendor/brackets/admin/resources/assets/js'),
                'node_modules'
            ],
        }
    })
    .sass('resources/assets/sass/admin/app.scss', 'public/css/admin')
    .version();