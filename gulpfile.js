var gulp = require('gulp');
var shell = require('gulp-shell');
var autoprefixer = require('gulp-autoprefixer');
var cssbeautify = require('gulp-cssbeautify');
var comb = require('gulp-csscomb');
var csso = require('gulp-csso');
var rename = require('gulp-rename');
var del = require('del');
var uglify = require('gulp-uglify');
var mainBowerFiles = require('main-bower-files');
var replace = require('gulp-replace');
var concat = require('gulp-concat');
var useref = require('gulp-useref');
var apigen = require('gulp-apigen');
var ngAnnotate = require('gulp-ng-annotate');

gulp.paths = {
    dist: 'dist',
    css: 'assets/css',
    js: 'assets/js'
};

var paths = gulp.paths;

/* Documentation JS (JSDoc / Angular Docs) */
gulp.task('docs', shell.task([
    'node_modules/jsdoc/jsdoc.js ' +
            '-c node_modules/angular-jsdoc/common/conf.json ' + // config file
            '-t node_modules/angular-jsdoc/angular-template ' + // template file
            '-d docs/js/ ' + // output directory
            './README.md ' + // to include README.md as index contents
            '-r assets/js' // source code directories
]));

/* Suppression du répertoire dist/ */
gulp.task('clean:dist', function () {
    return del(paths.dist);
});

/* Réorganisation des fichiers .css + beautify + prefixes css3 automatiques */
gulp.task('css', ['clean:dist'], function () {
    return gulp.src('./assets/css/*.css')
            .pipe(comb())
            .pipe(cssbeautify())
            .pipe(autoprefixer())
            .pipe(gulp.dest('./dist/css/'));
});

/* Minification des feuilles de style */
gulp.task('minify', ['css'], function () {
    return gulp.src('./assets/css/*.css')
            .pipe(csso())
            .pipe(rename({
                suffix: '.min'
            }))
            .pipe(gulp.dest('./dist/css/'));
});

/* Copier les fichiers nécessaires des librairies installées via bower */
gulp.task('copy:bower', ['minify'], function () {
    return gulp.src(mainBowerFiles(['**/*.js', '!**/*.min.js']))
            .pipe(gulp.dest(paths.dist + '/js/libs'))
            .pipe(uglify())
            .pipe(rename({suffix: '.min'}))
            .pipe(gulp.dest(paths.dist + '/js/libs'));
});

/* Remplacement, dans les fichiers .php, de '../bower_components' par '../assets/js/libs' */
gulp.task('replace:bower', ['copy:bower'], function () {
    return gulp.src([
        './app/Views/templates/default.php'
    ], {base: './'})
            .pipe(replace(/\.\.\/bower_components+.+(\/[a-z0-9][^/]*\.[a-z0-9]+(\'|\"))/ig, 'dist/js/libs$1'))
            .pipe(gulp.dest(paths.dist));
});

/* Concaténation + minification des scripts JS */
gulp.task('concatJS', ['copy:bower'], function () {
    return gulp.src('./assets/js/*.js')
            .pipe(concat('bigJS.js'))
            .pipe(gulp.dest(paths.dist + '/js/'));
});

/* Uglification */
gulp.task('uglifyJS', ['concatJS'], function () {
    return gulp.src('./dist/js/bigJS.js')
            .pipe(ngAnnotate())
            .pipe(uglify())
            .pipe(rename({suffix: '.min'}))
            .pipe(gulp.dest(paths.dist + '/js/'));
});
/* Useref */
gulp.task('useref', function () {
    return gulp.src('./assets_map.html')
            .pipe(useref())
            .pipe(gulp.dest(paths.dist));
});

/* Fonts */

gulp.task('fonts',['uglifyJS'], function () {
    return gulp.src([
        './bower_components/font-awesome/fonts/fontawesome-webfont.*'])
            .pipe(gulp.dest('dist/fonts/'));
});
gulp.task('img', ['fonts'], function () {
    return gulp.src('./bower_components/jqueryfiletree/dist/images/*')
            .pipe(gulp.dest(paths.dist + '/css/images/'));
});


gulp.task('concatPHP',['img'], function () {
    return gulp.src(['./app/Controller/*.php', './app/Entites/*.php',
        './app/Models/*.php', './app/App.php', './app/Autoloader.php',
        './core/**/*.php', './core/*.php', './public/index.php'])
            .pipe(concat('bigPHP.php'))
            .pipe(gulp.dest(paths.dist + '/php/'));
});

gulp.task('concatViews',['concatPHP'], function () {
    return gulp.src(['./app/views/**/*.html', './app/views/**/*.php', './app/views/**/**/*.html'])
            .pipe(concat('bigViews.php'))
            .pipe(gulp.dest(paths.dist + '/php/'));
});

/* Tâche "récapitulative". Terminal : $ gulp prod */
gulp.task('prod', [
    'concatViews'
]);
