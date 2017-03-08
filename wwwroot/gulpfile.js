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

gulp.task('main', function() {
  gulp.src('./build/_sass/*.scss')
    .pipe(compass({
      config_file: './config-main.rb',
      css: 'css',
      sass: 'build/_sass'
    }))
    .pipe(gulp.dest('css'));
});

gulp.task('main-dev', function() {
  gulp.src('./build/_sass/*.scss')
    .pipe(compass({
      config_file: './config-main.rb',
      css: 'css',
      sass: 'build/_sass',
      comments: true
    }))
    .pipe(gulp.dest('css'));
});
