var gulp = require('gulp'),
    compass = require('gulp-compass');

gulp.task('default', function() {
    console.log("Hi there friend");
});

gulp.task('compass', function() {
  gulp.src('./css/sass/*.scss')
    .pipe(compass({
      config_file: './config.rb',
      css: 'css',
      sass: 'css/sass'
    }))
    .pipe(gulp.dest('css'));
});

gulp.task('compass-dev', function() {
  gulp.src('./css/sass/*.scss')
    .pipe(compass({
      config_file: './config.rb',
      css: 'css',
      sass: 'css/sass',
      comments: true
    }))
    .pipe(gulp.dest('css'));
});