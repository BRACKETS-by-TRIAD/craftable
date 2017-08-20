mix.js(['resources/assets/admin/js/admin.js'], 'public/admin/js')
	.webpackConfig({
		resolve: {
			modules: [
				path.resolve(__dirname, 'vendor/brackets/admin/resources/assets/js'),
				'node_modules'
			],
		}
	})
	.sass('resources/assets/admin/scss/app.scss', 'public/admin/css')
	.extract([
		'vue',
		'jquery',
		'vee-validate',
		'axios',
		'vue-notification',
		'vue-quill-editor',
		'vue-flatpickr-component',
		'moment',
		'lodash'
	])
	.version();