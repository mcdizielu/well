var gulp = require('gulp');
var uglify = require('gulp-uglify');
var uglifycss = require('gulp-uglifycss');
var concat = require('gulp-concat');
var less = require('gulp-less');
var sourcemaps = require('gulp-sourcemaps');
var order = require('gulp-order');
var merge = require('merge-stream');
var livereload = require('gulp-livereload');

var paths = {
    admin: {
        libs: [
            'web/bundles/fosjsrouting/js/router.js',
            'web/bundles/wellcommerceapp/js/libs/**',
            'web/bundles/wellcommerceapp/js/core/plugin/*.js',
            'web/bundles/wellcommerceapp/js/core/form/*.js',
            'web/bundles/wellcommerceapp/js/core/language/*.js'
        ],
        core: [
            'web/bundles/wellcommerceapp/js/core/gf.js',
            'web/bundles/wellcommerceapp/js/core/init.js'
        ],
        css:  [
            'web/bundles/wellcommerceapp/css/admin.css'
        ]
    }
};

gulp.task('admin-libs', function () {
    return gulp.src(paths.admin.libs)
        .pipe(concat('libs.min.js'))
        .pipe(uglify())
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest('src/WellCommerce/Bundle/AppBundle/Resources/public/js/compiled/'))
        .pipe(livereload());
});

gulp.task('admin-core', function () {
    return gulp.src(paths.admin.core)
        .pipe(concat('core.min.js'))
        .pipe(uglify())
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest('src/WellCommerce/Bundle/AppBundle/Resources/public/js/compiled/'))
        .pipe(livereload());
});

gulp.task('admin-css', function () {
    return gulp.src(paths.admin.css)
        .pipe(concat('admin.min.css'))
        .pipe(uglifycss())
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest('src/WellCommerce/Bundle/AppBundle/Resources/public/css/'))
        .pipe(livereload());
});

gulp.task('watch', function() {
    livereload.listen();
    gulp.watch(paths.admin.libs, ['admin-libs']);
    gulp.watch(paths.admin.css, ['admin-css']);
    gulp.watch(paths.admin.js, ['admin-js']);
});

gulp.task('default', ['admin-libs', 'admin-core', 'admin-css']);

