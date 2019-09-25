var gulp = require('gulp'),
    json = require('json-file'),
    autoprefixer = require('autoprefixer'),
    postcss = require('gulp-postcss'),
    sourcemaps   = require('gulp-sourcemaps'),
    cssnano = require('cssnano'),
    sass = require('gulp-sass'),
    $ = require('gulp-load-plugins')(),
    browserSync = require('browser-sync').create();

const proxy = 'bbsp.loc';
const themeFolder = 'daerisone';

const paths = {
  scss: {
    src: './assets/scss/**/*.scss',
    watch: './assets/scss/**/*.scss',
    dest: './../'+themeFolder+'/css',
    map: './maps'
  },
  scripts: {
    name: 'app.min.js',
    src: './assets/js/app.js',
    watch: './assets/js/**/*.js',
    dest: './../'+themeFolder+'/js',
    map: './maps'
  },
  php: {
    watch: './../'+themeFolder+'/**/*.php',
  }
};

const sassPaths = [
    //'./node_modules/font-awesome/scss',
    './node_modules/bootstrap/scss',
    './node_modules',
];

const scriptPaths = [
    // JQUERY
    './node_modules/jquery/dist/jquery.min.js',
    './node_modules/jquery-migrate/dist/jquery-migrate.min.js',


    // PLYER
    //'./node_modules/plyr/dist/plyr.min.js',

    './assets/js/lib/simple-scrollbar.min.js',

    // BARBA
    './node_modules/barba.js/dist/barba.min.js',

    // ScrollMagic
    './node_modules/scrollmagic/scrollmagic/minified/ScrollMagic.min.js',
    './node_modules/scrollmagic/scrollmagic/minified/plugins/debug.addIndicators.min.js',

    // PACKERY
    //'./node_modules/packery/dist/packery.pkgd.js',

    // SLICK SLIDER
    //'./node_modules/swiper/dist/js/swiper.js',
];


const scss = () => {
    const plugins = [
        autoprefixer({ browsers: ['last 2 versions', 'ie >= 9'] }),
        cssnano()
    ];
    return gulp.src(paths.scss.src)
        .pipe(sourcemaps.init())
        .pipe(sass({ includePaths: sassPaths, sourceComments:true }).on('error', $.sass.logError))
        .pipe(postcss(plugins))
        .pipe($.rename({
            suffix: ".min",
            extname: ".css"
        }))
        .pipe(sourcemaps.write(paths.scss.map))
        .pipe(gulp.dest(paths.scss.dest))
        .pipe(browserSync.stream());
}

const jsLib = () => {
    return gulp.src(scriptPaths)
        .pipe($.sourcemaps.init())
        .pipe($.concat('vendor.min.js', { newLine: ';' }))
        .pipe($.uglify())
        .pipe($.sourcemaps.write(paths.scripts.map))
        .pipe(gulp.dest(paths.scripts.dest))
        .pipe(browserSync.stream());
}

const jsApp = () => {
    return gulp.src(paths.scripts.src)
        .pipe($.sourcemaps.init())
        .pipe($.concat(paths.scripts.name, { newLine: ';' }))
        .pipe($.uglify())
        .pipe($.sourcemaps.write(paths.scripts.map))
        .pipe(gulp.dest(paths.scripts.dest))
        .pipe(browserSync.stream());
}

const createTheme = () => {
    return gulp.src('./theme/**/*')
        .pipe(gulp.dest('./../'+themeFolder));
}

function reload(done) {
  browserSync.reload();
  done();
}

function serve(done) {
  browserSync.init({
      proxy: proxy,
      open: true,
  });
  done();
}

const watchJs = () => gulp.watch(paths.scripts.watch, gulp.series(jsApp));
const watchScss = () => gulp.watch(paths.scss.watch, gulp.series(scss));
const watchPhp = () => gulp.watch(paths.php.watch, gulp.series(reload));


gulp.task('watch', gulp.series(scss, jsApp, jsLib, serve, gulp.parallel(watchJs, watchScss, watchPhp)));

gulp.task('createTheme', createTheme);


gulp.task('default', gulp.parallel(scss, jsApp, jsLib));

