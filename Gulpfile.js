var gulp             = require('gulp');
var sass             = require('gulp-sass');
var sourcemaps       = require('gulp-sourcemaps');
var autoprefixer     = require('gulp-autoprefixer');
var watch            = require('gulp-watch');
var livereload       = require('gulp-livereload');
var plumber          = require('gulp-plumber');
var wpPot            = require('gulp-wp-pot');
var sassGlob         = require('gulp-sass-glob');
var uglify           = require('gulp-uglify');
var rename           = require('gulp-rename');
var pump             = require('pump');
var csso             = require('gulp-csso');
var stripCssComments = require('gulp-strip-css-comments');

var sassOptions = {
	sourcemap: true,
	errLogToConsole: true,
	outputStyle: 'expanded'
};

var sassExpandedOptions = {
	sourcemap: false,
	errLogToConsole: false,
	outputStyle: 'expanded'
};

var sassProductionOptions = {
	sourcemap: false,
	errLogToConsole: false,
	outputStyle: 'compressed'
};

// define only main scss file for theme/plugins and its destination
var styles = [
	{
		src: './wp-content/themes/pure/lib/scss/pure.scss',
		dest: './wp-content/themes/pure/lib/css/' // Folder only please, css file will be named same as same as scss file.
	},
    {
        src: './wp-content/plugins/pure-elementor/assets/scss/pure-elementor.scss',
        dest: './wp-content/plugins/pure-elementor/assets/css/' // Folder only please, css file will be named same as same as scss file.
    },
    {
        src: './wp-content/plugins/pure-woocommerce/assets/scss/woocommerce.scss',
        dest: './wp-content/plugins/pure-woocommerce/assets/css/' // Folder only please, css file will be named same as same as scss file.
    },
	{
		src: './wp-content/themes/pure-gentleman/scss/css.scss',
		dest: './wp-content/themes/pure-gentleman/css/' // Folder only please, css file will be named same as same as scss file.
	},
];

// compile scss task for both theme and plugins
gulp.task('scss', function () {
	return styles.map(function (style) {
		return gulp.src(style.src)
			.pipe(sassGlob())
			.pipe(plumber())
			.pipe(sourcemaps.init({ loadMap: true }))
			.pipe(sass(sassOptions))
			.pipe(autoprefixer({ browsers: ['last 10 versions'] }))
			.pipe(sourcemaps.write())
			.pipe(gulp.dest(style.dest))
			.pipe(livereload())
	});
});

gulp.task('scss-expanded', function () {
	return styles.map(function (style) {
		return gulp.src(style.src)
			.pipe(sassGlob())
			.pipe(plumber())
			.pipe(sass(sassExpandedOptions))
			.pipe(autoprefixer({ browsers: ['last 10 versions'] }))
			.pipe(gulp.dest(style.dest))
	});
});

gulp.task('scss-min', function () {
	return styles.map(function (style) {
		return gulp.src(style.src)
			.pipe(sassGlob())
			.pipe(plumber())
			.pipe(sass(sassProductionOptions))
			.pipe(autoprefixer({ browsers: ['last 10 versions'] }))
			.pipe(csso())
			.pipe(stripCssComments({ preserve: false }))
			.pipe(rename({ suffix: '.min' }))
			.pipe(gulp.dest(style.dest))
	});
});

gulp.task('watch', function () {
	livereload.listen();
	return watch(['./wp-content/**/*.scss'], function () {
		gulp.start('scss');
	});

});

gulp.task('make-pot', function () {
	return gulp.src('./wp-content/themes/pure/**/*.php')
		.pipe(wpPot({
			domain: 'pure',
			package: 'Pure'
		}))
		.pipe(gulp.dest('./wp-content/themes/pure/lib/languages/pure.pot'));
});

gulp.task('make-childtheme-pot', function () {
    return gulp.src('./wp-content/themes/pure-gentleman/**/*.php')
        .pipe(wpPot({
            domain: 'pure-gentleman',
            package: 'Puregentleman'
        }))
        .pipe(gulp.dest('./wp-content/themes/pure-gentleman/languages/pure-gentleman.pot'));
});

gulp.task('make-pure-woocommerce-pot', function () {
	return gulp.src('./wp-content/plugins/pure-woocommerce/**/*.php')
		.pipe(wpPot({
			domain: 'pure-woocommerce',
			package: 'Pure Woocommerce'
		}))
		.pipe(gulp.dest('./wp-content/plugins/pure-woocommerce/languages/pure-woocommerce.pot'));
});

var jss = [
	{
		src: './wp-content/themes/pure/lib/js/pure.js',
		dest: './wp-content/themes/pure/lib/js/'
	},
	{
		src: './wp-content/themes/pure-gentleman/js/js.js',
		dest: './wp-content/themes/pure-gentleman/js/'
	},
	{
		src: './wp-content/plugins/pure-elementor/assets/js/pure-elementor.js',
		dest: './wp-content/plugins/pure-elementor/assets/js/'
	},
    {
        src: './wp-content/plugins/pure-elementor/assets/js/fontawesome-all.js',
        dest: './wp-content/plugins/pure-elementor/assets/js/'
    },
    {
        src: './wp-content/themes/pure/lib/modules/nivo-slider/assets/js/jquery.nivo.slider.js',
        dest: './wp-content/themes/pure/lib/modules/nivo-slider/assets/js/'
    },

];

gulp.task('compress', function () {
	return jss.map(function (js) {
		pump([
			gulp.src(js.src),
			uglify(),
			rename({ suffix: '.min' }),
			gulp.dest(js.dest)
		]);
	});
});

gulp.task('default', ['watch', 'scss',]);
gulp.task('production', ['scss-expanded', 'scss-min', 'make-pot', 'make-childtheme-pot', 'make-pure-woocommerce-pot', 'compress']);

