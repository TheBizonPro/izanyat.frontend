var gulp       = require('gulp'); // Сообственно Gulp JS
var cleanCSS   = require('gulp-clean-css'); // Минификация CSS
var uglify     = require('gulp-uglify-es').default; // Минификация JS
var concat     = require('gulp-concat'); // Склейка файлов
var rename     = require('gulp-rename'); // Переименовывалка
var gutil      = require('gulp-util');
var sass       = require('gulp-sass');
var scss       = require('gulp-scss');
var sourcemaps = require('gulp-sourcemaps');
var sassGlob   = require('gulp-sass-glob');
var tildeImporter = require('node-sass-tilde-importer');


var path = {
	build: {
		img:    'public/images/',
		js:     'public/js/',
		css:    'public/css/',
		fonts:  'public/webfonts/',
		sounds: 'public/sounds/'
	},
    watch: {
        img:    './resources/assets/images/**/*.*',
        js:     './resources/assets/js/**/*.*',
        css:    './resources/assets/css/**/*.*',
        fonts:  './resources/assets/fonts/**/*.*',
        sounds: './resources/assets/sounds/**/*.*'
    }
};




/**
 * Картинки
 */
gulp.task('images', function(done) {
    gulp.src(path.watch.img)
        .pipe(gulp.dest(path.build.img));
	return done();
});


/**
 * Шрифты
 */
gulp.task('fonts', function(done){
	gulp.src(path.watch.fonts)
		.pipe(gulp.dest(path.build.fonts));
	return done();
});



/**
 * Основные стили
 */
gulp.task('css', function(done){
	gulp.src(path.watch.css)
		.pipe(sourcemaps.init())
		.pipe(cleanCSS({compatibility: 'ie8'}))
		.pipe(rename({suffix: ".min"}))
		.pipe(sourcemaps.write('/'))
		.pipe(gulp.dest(path.build.css));
	return done();
});


/**
 * Основные JS скрипты
 */
gulp.task('js', function(done){
	gulp.src(path.watch.js)
		.on('error', function (err) { gutil.log(gutil.colors.red('[Error]'), err.toString()); })
		.pipe(rename({suffix: ".min"}))
		.pipe(gulp.dest(path.build.js));
	
	return done();
});


/**
 * Tabler
 */
gulp.task('tabler', function(done) {
	gulp.src('./resources/assets/scss/tabler/dashboard.scss')
		.pipe(sourcemaps.init())
	    .pipe(sass({
	      importer: tildeImporter
	   	 }).on('error', sass.logError))
	    .pipe(sass().on('error', sass.logError))
	    .pipe(cleanCSS({compatibility: 'ie8'}))
		.pipe(rename('tabler.min.css'))
		.pipe(sourcemaps.write('/'))
		.pipe(gulp.dest(path.build.css));
	return done();
});


/**
 * Axios
 */
gulp.task('axios', function(done) {
	gulp.src([
		'./node_modules/axios/dist/axios.js'
	])
	.pipe(uglify())
	.on('error', function (err) { gutil.log(gutil.colors.red('[Error]'), err.toString()); })
	.pipe(rename({suffix: ".min"}))
	.pipe(gulp.dest(path.build.js));

	return done();
});


/**
 * Jquery
 */
gulp.task('jquery', function(done) {
	gulp.src([
		'./node_modules/jquery/dist/jquery.js'
	])
	.pipe(uglify())
	.on('error', function (err) { gutil.log(gutil.colors.red('[Error]'), err.toString()); })
	.pipe(rename({suffix: ".min"}))
	.pipe(gulp.dest(path.build.js));

	return done();
});


/**
 * Bootstrap
 */
gulp.task('bootstrap', function(done){
	gulp.src([
		'./node_modules/bootstrap/dist/css/bootstrap.css'
	])
	.pipe(cleanCSS({compatibility: 'ie8'}))
	.pipe(rename({suffix: ".min"}))
	.pipe(gulp.dest(path.build.css));

	gulp.src([
		'./node_modules/bootstrap/dist/js/bootstrap.bundle.js'
	])
	.pipe(uglify())
	.on('error', function (err) { gutil.log(gutil.colors.red('[Error]'), err.toString()); })
	.pipe(concat('bootstrap.js'))
	.pipe(rename({suffix: ".min"}))
	.pipe(gulp.dest(path.build.js));

	return done();
});


/**
 * Boottoaster (bootstrap toaster)
 */
gulp.task('boottoast5', function(done){
	gulp.src([
		'./node_modules/boottoast5/js/boottoast5.js'
	])
	.pipe(uglify())
	.on('error', function (err) { gutil.log(gutil.colors.red('[Error]'), err.toString()); })
	.pipe(rename({suffix: ".min"}))
	.pipe(gulp.dest(path.build.js));

	return done();
});




/**
 * ccshake CSS
 */
gulp.task('ccshake', function(done){
	gulp.src([
		'./node_modules/csshake/dist/csshake-little.css'
	])
	.pipe(cleanCSS({compatibility: 'ie8'}))
	.pipe(rename({suffix: ".min"}))
	.pipe(gulp.dest(path.build.css));

	return done();
});



/**
 * selectize.js
 */
gulp.task('selectize.js', function(done){

	gulp.src([
		'./node_modules/selectize/dist/js/standalone/selectize.js'
	])
	.pipe(uglify())
	.on('error', function (err) { gutil.log(gutil.colors.red('[Error]'), err.toString()); })
	.pipe(concat('selectize.js'))
	.pipe(rename({suffix: ".min"}))
	.pipe(gulp.dest(path.build.js));

	gulp.src([
		'./node_modules/selectize-bootstrap4-theme/dist/css/selectize.bootstrap4.css'
	])
	.pipe(cleanCSS({compatibility: 'ie8'}))
	.pipe(rename({suffix: ".min"}))
	.pipe(gulp.dest(path.build.css));


	return done();
});





/**
 * Datatables.net
 */
gulp.task('datatables', function(done){
	gulp.src([
		'./node_modules/datatables.net/js/jquery.dataTables.js'
	])
	.pipe(uglify())
	.on('error', function (err) { gutil.log(gutil.colors.red('[Error]'), err.toString()); })
	.pipe(rename({suffix: ".min"}))
	.pipe(gulp.dest(path.build.js));

	return done();
});



/**
 * Datatables.net scroller
 */
gulp.task('datatables.scroller', function(done){
	gulp.src([
		'./node_modules/datatables.net-scroller/js/dataTables.scroller.min.js'
	])
	.on('error', function (err) { gutil.log(gutil.colors.red('[Error]'), err.toString()); })
	.pipe(gulp.dest(path.build.js));

	return done();
});




















/**
 * bootbox.js
 */
gulp.task('bootbox.js', function(done){

	gulp.src([
		'./resources/vendor/bootbox.js/js/bootbox.js'
	])
	.pipe(uglify())
	.on('error', function (err) { gutil.log(gutil.colors.red('[Error]'), err.toString()); })
	.pipe(concat('bootbox.js'))
	.pipe(rename({suffix: ".min"}))
	.pipe(gulp.dest(path.build.js));

	return done();
});



/**
 * libphonenubmer.js
 */
gulp.task('libphonenubmer.js', function(done){

	gulp.src([
		'./resources/vendor/libphonenubmer.js/js/libphonenubmer.js'
	])
	.on('error', function (err) { gutil.log(gutil.colors.red('[Error]'), err.toString()); })
	.pipe(rename({suffix: ".min"}))
	.pipe(gulp.dest(path.build.js));

	return done();
});



/**
 * phoneparser
 */
gulp.task('phoneparser', function(done){

	gulp.src([
		'./resources/vendor/phoneparser/js/phoneparser.js'
	])
	.on('error', function (err) { gutil.log(gutil.colors.red('[Error]'), err.toString()); })
	.pipe(rename({suffix: ".min"}))
	.pipe(gulp.dest(path.build.js));

	return done();
});





/**
 * DataTables
 */
//gulp.task('datatables', function(done){
//	gulp.src([
//		'./resources/vendor/datatables/css/datatables.css',
//		'./resources/vendor/datatables/css/buttons.bootstrap4.min.css',
//		'./resources/vendor/datatables/css/keyTable.bootstrap4.min.css',
//		'./resources/vendor/datatables/css/scroller.bootstrap4.min.css',
//		'./resources/vendor/datatables/css/select.bootstrap4.min.css',
//		'./resources/vendor/datatables/css/colReorder.bootstrap4.min.css',
//		'./resources/vendor/datatables/css/datatable-overload.css'
//	])
//	.pipe(concat('datatables.css'))
//	.pipe(cleanCSS({compatibility: 'ie8'}))
//	.pipe(rename({suffix: ".min"}))
//	.pipe(gulp.dest(path.build.css))
//
//	gulp.src([
//		'./resources/vendor/datatables/js/datatables.js',
//		'./resources/vendor/datatables/js/datatables.search-and-mark.js',
//		'./resources/vendor/datatables/js/GenerateDataTable.js',
//		'./resources/vendor/datatables/js/datatables-localization.ru.js'
//	])
//	.on('error', function (err) { gutil.log(gutil.colors.red('[Error]'), err.toString()); })
//	.pipe(concat('datatables.js'))
//	.pipe(rename({suffix: ".min"}))
//	.pipe(gulp.dest(path.build.js));
//
//	return done();
//});




/**
 * Animate CSS
 */
gulp.task('animate_css', function(done){
	gulp.src([
		'./resources/vendor/animate.css/css/animate.css'
	])
	.pipe(cleanCSS({compatibility: 'ie8'}))
	.pipe(rename({suffix: ".min"}))
	.pipe(gulp.dest(path.build.css));

	return done();
});


/**
 * Bootstrap Datetimepicker
 */
gulp.task('bootstrap-datetimepicker', function(done){
	gulp.src([
		'./resources/vendor/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css',
	])
	.pipe(cleanCSS({compatibility: 'ie8'}))
	.pipe(rename({suffix: ".min"}))
	.pipe(gulp.dest(path.build.css));

	gulp.src([
		'./resources/vendor/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js',
		//'./resources/vendor/bootstrap-datetimepicker/js/bootstrap-datetimepicker.ru.js'
	])
	.pipe(uglify())
	.on('error', function (err) { gutil.log(gutil.colors.red('[Error]'), err.toString()); })
	.pipe(concat('bootstrap-datetimepicker.js'))
	.pipe(rename({suffix: ".min"}))
	.pipe(gulp.dest(path.build.js));

	done();
});



/**
 * jquery.rainbowJSON
 */
gulp.task('rainbowJSON', function(done){
	gulp.src([
		'./resources/vendor/jquery.rainbowJSON/css/jquery.rainbowJSON.css',
	])
	.pipe(cleanCSS({compatibility: 'ie8'}))
	.pipe(rename({suffix: ".min"}))
	.pipe(gulp.dest(path.build.css));

	gulp.src([
		'./resources/vendor/jquery.rainbowJSON/js/jquery.rainbowJSON.js',
	])
	.pipe(uglify())
	.on('error', function (err) { gutil.log(gutil.colors.red('[Error]'), err.toString()); })
	.pipe(concat('jquery.rainbowJSON.js'))
	.pipe(rename({suffix: ".min"}))
	.pipe(gulp.dest(path.build.js));

	return done();
});



/**
 * leaflet.js
 */
gulp.task('leaflet.js', function(done){
	gulp.src([
		'./resources/vendor/leaflet.js/css/leaflet.css',
	])
	.pipe(cleanCSS({compatibility: 'ie8'}))
	.pipe(rename({suffix: ".min"}))
	.pipe(gulp.dest(path.build.css));

	gulp.src([
		'./resources/vendor/leaflet.js/js/leaflet.js'
	])
	.pipe(uglify())
	.on('error', function (err) { gutil.log(gutil.colors.red('[Error]'), err.toString()); })
	.pipe(concat('leaflet.js'))
	.pipe(rename({suffix: ".min"}))
	.pipe(gulp.dest(path.build.js));

    gulp.src([
		'./resources/vendor/leaflet.js/images/**/*.*'
	])
    .pipe(gulp.dest(path.build.img));

	return done();
});


/**
 * smooth-scroll
 */
gulp.task('smooth-scroll', function(done){
	gulp.src([
		'./resources/vendor/smooth-scroll/js/smooth-scroll.js'
	])
	.pipe(uglify())
	.on('error', function (err) { gutil.log(gutil.colors.red('[Error]'), err.toString()); })
	.pipe(concat('smooth-scroll.js'))
	.pipe(rename({suffix: ".min"}))
	.pipe(gulp.dest(path.build.js));

	return done();
});


/**
 * gumshoe
 */
gulp.task('gumshoe', function(done){
	gulp.src([
		'./resources/vendor/gumshoe/js/gumshoe.js'
	])
	.pipe(uglify())
	.on('error', function (err) { gutil.log(gutil.colors.red('[Error]'), err.toString()); })
	.pipe(concat('gumshoe.js'))
	.pipe(rename({suffix: ".min"}))
	.pipe(gulp.dest(path.build.js));

	return done();
});


/**
 * clipboard.js
 */
gulp.task('clipboard.js', function(done){
	gulp.src([
		'./resources/vendor/clipboard/js/clipboard.js'
	])
	.pipe(uglify())
	.on('error', function (err) { gutil.log(gutil.colors.red('[Error]'), err.toString()); })
	.pipe(concat('clipboard.js'))
	.pipe(rename({suffix: ".min"}))
	.pipe(gulp.dest(path.build.js));

	return done();
});


/**
 * dropzone.js
 */
gulp.task('dropzone.js', function(done){
	gulp.src([
		'./resources/vendor/dropzone.js/js/dropzone.js'
	])
	.pipe(uglify())
	.on('error', function (err) { gutil.log(gutil.colors.red('[Error]'), err.toString()); })
	.pipe(concat('dropzone.js'))
	.pipe(rename({suffix: ".min"}))
	.pipe(gulp.dest(path.build.js));

	return done();
});



/**
 * Bootstrap Toogle (красивые чекбосы переключатели)
 */
gulp.task('bootstrap-toggle', function(done){
	gulp.src([
		'./resources/vendor/bootstrap-toggle/css/bootstrap-toggle.css'
	])
	.pipe(cleanCSS({compatibility: 'ie8'}))
	.pipe(rename({suffix: ".min"}))
	.pipe(gulp.dest(path.build.css));

	gulp.src([
		'./resources/vendor/bootstrap-toggle/js/bootstrap-toggle.js'
	])
	.pipe(uglify())
	.on('error', function (err) { gutil.log(gutil.colors.red('[Error]'), err.toString()); })
	.pipe(concat('bootstrap-toggle.js'))
	.pipe(rename({suffix: ".min"}))
	.pipe(gulp.dest(path.build.js));

	return done();
});


/**
 * moment.js
 */
gulp.task('moment.js', function(done){
	gulp.src([
		'./resources/vendor/moment.js/js/moment.js',
		'./resources/vendor/moment.js/js/moment-with-locales.js',
	])
	.pipe(uglify())
	.on('error', function (err) { gutil.log(gutil.colors.red('[Error]'), err.toString()); })
	.pipe(concat('moment.js'))
	.pipe(rename({suffix: ".min"}))
	.pipe(gulp.dest(path.build.js));

	
	gulp.src([
		'./resources/vendor/moment.js/js/moment-timezone-with-data.js',
	])
	.pipe(uglify())
	.on('error', function (err) { gutil.log(gutil.colors.red('[Error]'), err.toString()); })
	.pipe(concat('moment-timezone.js'))
	.pipe(rename({suffix: ".min"}))
	.pipe(gulp.dest(path.build.js));


	return done();

});

/**
 * jquery.authanimator
 */
gulp.task('jquery.authanimator', function(done){
	gulp.src([
		'./resources/vendor/jquery.authanimator/js/jquery.authanimator.js'
	])
	.pipe(uglify())
	.on('error', function (err) { gutil.log(gutil.colors.red('[Error]'), err.toString()); })
	.pipe(rename({suffix: ".min"}))
	.pipe(gulp.dest(path.build.js));

	return done();
});



/**
 * Toastr
 */
gulp.task('toastr', function(done){
	gulp.src([
		'./resources/vendor/toastr/css/toastr.css'
	])
	.pipe(cleanCSS({compatibility: 'ie8'}))
	.pipe(rename({suffix: ".min"}))
	.pipe(gulp.dest(path.build.css));

	gulp.src([
		'./resources/vendor/toastr/js/toastr.js'
	])
	.pipe(uglify())
	.on('error', function (err) { gutil.log(gutil.colors.red('[Error]'), err.toString()); })
	.pipe(rename({suffix: ".min"}))
	.pipe(gulp.dest(path.build.js));

	return done();
});


/**
 * lightbox.js
 */
gulp.task('lightbox.js', function(done){
	gulp.src([
		'./resources/vendor/lightbox.js/css/lightbox.css'
	])
	.pipe(cleanCSS({compatibility: 'ie8'}))
	.pipe(rename({suffix: ".min"}))
	.pipe(gulp.dest(path.build.css));

	gulp.src([
		'./resources/vendor/lightbox.js/js/lightbox.js'
	])
	.pipe(uglify())
	.on('error', function (err) { gutil.log(gutil.colors.red('[Error]'), err.toString()); })
	.pipe(rename({suffix: ".min"}))
	.pipe(gulp.dest(path.build.js))

    gulp.src(['./resources/vendor/lightbox.js/images/**/*.*'])
    .pipe(gulp.dest(path.build.img));

	return done();
});


/**
 * Bowser
 */
gulp.task('bowser', function(done){
	gulp.src([
		'./resources/vendor/bowser/js/bowser.js',
		'./resources/vendor/bowser/js/useragents.js'
	])
	.pipe(uglify())
	.on('error', function (err) { gutil.log(gutil.colors.red('[Error]'), err.toString()); })
	.pipe(concat('bowser.js'))
	.pipe(rename({suffix: ".min"}))
	.pipe(gulp.dest(path.build.js));
	return done();
});


/**
 * JQuery Maskedinput
 */
gulp.task('jquery.maskedinput', function(done){
	gulp.src([
		'./resources/vendor/jquery.maskedinput/js/jquery.maskedinput.js'
	])
	.pipe(uglify())
	.on('error', function (err) { gutil.log(gutil.colors.red('[Error]'), err.toString()); })
	.pipe(concat('jquery.maskedinput.js'))
	.pipe(rename({suffix: ".min"}))
	.pipe(gulp.dest(path.build.js));
	return done();
});


/**
 * js.cookie
 */
gulp.task('js.cookie', function(done){
	gulp.src([
		'./resources/vendor/js.cookie/js/js.cookie.js'
	])
	.pipe(uglify())
	.on('error', function (err) { gutil.log(gutil.colors.red('[Error]'), err.toString()); })
	.pipe(concat('js.cookie.js'))
	.pipe(rename({suffix: ".min"}))
	.pipe(gulp.dest(path.build.js));
	return done();
});


/**
 * sortable.js
 */
gulp.task('sortable.js', function(done){
	gulp.src([
		'./resources/vendor/sortable.js/js/sortable.js'
	])
	.pipe(uglify())
	.on('error', function (err) { gutil.log(gutil.colors.red('[Error]'), err.toString()); })
	.pipe(concat('sortable.js'))
	.pipe(rename({suffix: ".min"}))
	.pipe(gulp.dest(path.build.js));
	return done();
});


/**
 * Select2
 */
gulp.task('select2', function(done){
	gulp.src([
		'./resources/vendor/select2/css/select2.css',
		'./resources/vendor/select2/css/select2-bootstrap4.css',
	])
	.pipe(concat('select2.css'))
	.pipe(cleanCSS({compatibility: 'ie8'}))
	.pipe(rename({suffix: ".min"}))
	.pipe(gulp.dest(path.build.css))

	gulp.src([
		'./resources/vendor/select2/js/select2.full.js',
		'./resources/vendor/select2/js/ru.js',
	])
	.pipe(uglify())
	.on('error', function (err) { gutil.log(gutil.colors.red('[Error]'), err.toString()); })
	.pipe(concat('select2.js'))
	.pipe(rename({suffix: ".min"}))
	.pipe(gulp.dest(path.build.js));
	return done();
});






/**
 * Font AWESOME 5 Premium
 */
gulp.task('fontawesome5', function(done){
	gulp.src('./resources/vendor/fontawesome5/webfonts/*.*')
		.pipe(gulp.dest(path.build.fonts));
	gulp.src([
			'./resources/vendor/fontawesome5/css/fontawesome.css',
			'./resources/vendor/fontawesome5/css/all.css',
			'./resources/vendor/fontawesome5/css/brands.css',
		])
		.pipe(concat('fontawesome.css'))
		.pipe(cleanCSS({compatibility: 'ie8'}))
		.pipe(rename({suffix: ".min"}))
		.pipe(gulp.dest(path.build.css));
	return done();
});




/**
 * Вендор библиотеки
 */
gulp.task('vendors', gulp.parallel(
	'jquery',
	'axios',
	'bootstrap',
	'bootbox.js',
	'datatables',
	'animate_css',
	'bootstrap-datetimepicker',
	'bootstrap-toggle',
	'bowser',
	'jquery.maskedinput',
	'js.cookie',
	'toastr',
	'sortable.js',
	'moment.js',
	'jquery.authanimator',
	'fontawesome5',
	'lightbox.js',
	'leaflet.js',
	'rainbowJSON',
	'smooth-scroll',
	'gumshoe',
	'select2',
	'selectize.js',
	'clipboard.js',
	'dropzone.js'
), function(done){
	return done();
});






/**
 * Вызов по-умолчанию
 */
gulp.task('default', gulp.parallel(
    'css',
    'js',
    'vendors',
    'images',
    'fonts'
), function(done){
	return done();
});






