var gulp = require('gulp');
var sass = require('gulp-sass');

gulp.task('scss', function() {
  gulp.src('src/scss/app.scss')
    .pipe(sass().on('error', sass.logError))
    .pipe(gulp.dest('../assets/css'));
});

gulp.task('default', function() {
  gulp.run("scss");

  gulp.watch('src/scss/**/*.scss', function() {
    gulp.run('scss');
  });
});
