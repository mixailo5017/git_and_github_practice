'use strict';

console.time("Loading plugins"); //start measuring

var gulp = require('gulp'),
    compass = require('gulp-compass'),
    watch = require('gulp-watch'),
    livereload = require('gulp-livereload'),
    fileinclude = require('gulp-file-include'),
    notify = require('gulp-notify'),
    concat = require('gulp-concat'),
    uglify = require('gulp-uglify'),
    hologram = require('gulp-hologram'),
    rename = require('gulp-rename'),
    jshint = require('gulp-jshint'),
    browserify = require('browserify'), // The following three dependencies (source, buffer, sourcemaps) all support Browserify
    source = require('vinyl-source-stream'),
    buffer = require('vinyl-buffer'),
    sourcemaps = require('gulp-sourcemaps'),
    environments = require('gulp-environments');

console.timeEnd('Loading plugins');

// https://www.npmjs.com/package/gulp-environments
var development = environments.development;
var production = environments.production;

gulp.task('compass', function() {
  gulp.src('./css/sass/*.scss')
    .pipe(compass({
      config_file: './config.rb',
      css: 'css',
      sass: 'css/sass'
    }))
    .pipe(gulp.dest('css'))
    .pipe(notify('Compass recompiled!'));
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

//paths
var sass_build        = 'build/_sass',
  sass_build_v1       = 'css/sass',
  html_build          = 'build/_html/templates/*.html',
  html_build_watch    = 'build/_html/**/*.html',
  html_output         = 'html/',
  css_output          = 'css/',
  style_css_output    = 'style_guide/',
  js_build            = 'build/_js/',
  js_output           = 'js/',
  js_output_lib       = 'js/lib/',
  node_modules        = 'node_modules/',
  non_bundled_modules = 'assets/';



gulp.task('sass', function () {
  var sass = require('gulp-sass'),
    csscomb = require('gulp-csscomb'),
    please = require('gulp-pleeease'),
    gcmq = require('gulp-group-css-media-queries'); // Combine CSS Media Queries at end of sheet in correct order.

  return gulp.src(sass_build+'/**/*.scss')
  .pipe(sass({
      require:['susy'],
      "sourcemap=none": true
    }))
  .on("error", notify.onError("Error: <%= error.message %>"))
  .pipe(please({
    minifier:false,
    autoprefixer:{"browsers": ["last 5 versions", "ie 9", "ios 6"]},
    mqpacker:true
  }))
  // Groups media queries together in the correct order
  .pipe(gcmq())
  .pipe(csscomb())
  .pipe(gulp.dest(css_output))
  .pipe(rename({
    suffix:'.min'
  }))
  .pipe(please({
    minifier:true
  }))
  .pipe(gulp.dest(css_output))
  .pipe(livereload({ auto: true }))
  .pipe(notify('Sass recompiled!'));
  });


gulp.task('fileinclude', function() {
  gulp.src([html_build])
    .pipe(fileinclude({
      prefix: '@@',
      basepath: '@file'
    }))
    .pipe(gulp.dest(html_output))
    .pipe(livereload({ auto: true }));
});

gulp.task('js-libs', function() {
  // First, copy across any modules that will be included directly, not bundled
  gulp.src(node_modules + 'leaflet-draw/dist/**/*')
    .pipe(gulp.dest(non_bundled_modules + 'leaflet-draw'));

  // set up the browserify instance on a task basis
  var b = browserify({
    entries: js_build + '_custom/plugins.js',
    debug: true
  });

  return b.bundle()
    .pipe(source('plugins.js'))
    .pipe(buffer())
    .pipe(sourcemaps.init({loadMaps: true}))
    // .pipe(jshint())
    // .pipe(jshint.reporter('jshint-stylish'))
    // .pipe(jshint.reporter('fail'))
    .on("error", notify.onError(function (error) {
        return error.message;
    }))
    .pipe(uglify())
    .on('error', notify.onError("Error: <%= error.message %>"))
    .pipe(sourcemaps.write('./'))
    .pipe(gulp.dest(js_output));
});


gulp.task('valid', function () {
  var htmlv = require('gulp-html-validator');

  gulp.src(html_output+'/*.html')
    .pipe(htmlv())
    .pipe(gulp.dest('./reports'));
});

gulp.task('hologram', function() {
  gulp.src('hologram_config.yml')
    .pipe(hologram({
      bundler:true, 
      logging:true
    })
  );
});

gulp.task('watch', function(){
  livereload.listen();
  gulp.watch(sass_build+'/**/*.scss', ['sass','hologram']);
  gulp.watch(html_build_watch, ['fileinclude']);
  gulp.watch(js_build+'_lib/*.js', ['js-libs']);
  gulp.watch(js_build+'_custom/*.js', ['js-browserify-v1', 'js-browserify']);
  gulp.watch(sass_build_v1+'/**/*.scss', ['compass']);
});

gulp.task('default',['watch']);
gulp.task('reports', ['valid']);