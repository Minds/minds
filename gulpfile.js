'use strict';

var gulp = require('gulp');
var bump = require('gulp-bump');
var concat = require('gulp-concat');
var filter = require('gulp-filter');
var inject = require('gulp-inject');
var sass = require('gulp-sass');
var minifyCSS = require('gulp-minify-css');
var minifyHTML = require('gulp-minify-html');
var plumber = require('gulp-plumber');
var shell = require('gulp-shell');
var sourcemaps = require('gulp-sourcemaps');
var template = require('gulp-template');
var tsc = require('gulp-typescript');
var uglify = require('gulp-uglify');
var watch = require('gulp-watch');

var Builder = require('systemjs-builder');
var del = require('del');
var fs = require('fs');
var join = require('path').join;
var runSequence = require('run-sequence');
var semver = require('semver');
var series = require('stream-series');

var http = require('http');
var connect = require('connect');
var serveStatic = require('serve-static');
var openResource = require('open');

// --------------
// Configuration.
var APP_BASE = '/';
var APP_SRC = 'front/app';
var APP_DEST = 'front/public';
var ANGULAR_BUNDLES = './node_modules/angular2/bundles/';

var PATH = {
  dest: {
    all: APP_DEST,
    dev: {
      all: APP_DEST,
      lib: APP_DEST + '/lib'
    },
    prod: {
      all: APP_DEST + '/',
      lib: APP_DEST + '/lib'
    }
  },
  src: {
    all: APP_SRC,
    // Order is quite important here for the HTML tag injection.
    loader: [
      './node_modules/angular2/node_modules/traceur/bin/traceur-runtime.js',
      './node_modules/es6-module-loader/dist/es6-module-loader-sans-promises.js',
      './node_modules/es6-module-loader/dist/es6-module-loader-sans-promises.js.map',
      './node_modules/reflect-metadata/Reflect.js',
      './node_modules/reflect-metadata/Reflect.js.map',
      './node_modules/systemjs/dist/system.src.js'
    ],
    loaderConfig: [
      APP_SRC + '/system.config.js'
    ],
    angular: [
      ANGULAR_BUNDLES + '/angular2.dev.js',
      ANGULAR_BUNDLES + '/router.dev.js',
      ANGULAR_BUNDLES + '/http.dev.js'
    ],
    plugins: './mod'
  }
};

PATH.src.lib = PATH.src.loader
    .concat(PATH.src.loaderConfig)
    .concat(PATH.src.angular);


var HTMLMinifierOpts = { conditionals: true };

var tsProject = tsc.createProject('tsconfig.json', {
  typescript: require('typescript')
});

var semverReleases = ['major', 'premajor', 'minor', 'preminor', 'patch',
                      'prepatch', 'prerelease'];

// --------------
// Clean.

gulp.task('clean', function (done) {
  del(PATH.dest.all, done);
});

gulp.task('clean.dev', function (done) {
  del(PATH.dest.dev.all, done);
});

gulp.task('clean.app.dev', function (done) {
  del([join(PATH.dest.dev.all, '**/*'), '!' + PATH.dest.dev.lib,
       '!' + join(PATH.dest.dev.lib, '*')], done);
});

gulp.task('clean.test', function(done) {
  del('test', done);
});

gulp.task('clean.tsd_typings', function(done) {
  del('tsd_typings', done);
});

// -------------
// Build plugins.
gulp.task('build.plugins', function (cb) {
//  var result = gulp.src('./front/app/**/*scss');
  var plugins = fs.readdirSync(PATH.src.plugins);
  plugins.map(function(plugin, i){
    var path = PATH.src.plugins + '/' + plugin;
    try {
      var info = require(path + '/plugin.json');

      // ----------
      // Build plugins to source
      gulp.src(path + '/app/**/*ts')
        .pipe(gulp.dest('./front/app/src/plugins/' + plugin));

      gulp.src(path + '/app/templates/**/*html')
        .pipe(gulp.dest('./front/app/templates/plugins/' + plugin));

      gulp.src(path + '/app/stylesheets/**/*scss')
        .pipe(gulp.dest('./front/app/stylesheets/plugins/' + plugin));

    } catch (error) {
      if(error.code != 'MODULE_NOT_FOUND')
        console.log(error);
    }

    if(i == plugins.length -1)
      cb();

  });

});


// ----------
// Builds scss for plugins
gulp.task('build.plugins.scss', function () {
    return gulp.src('./front/app/stylesheets/plugins/**/*scss')
      .pipe(concat('plugins.scss'))
      .pipe(gulp.dest('./front/app/stylesheets/'));
});

/**
 * Build CSS from SCSS
 */
gulp.task('build.scss', ['build.plugins.scss'], function () {
	  var result = gulp.src('./front/app/**/*scss')
	    .pipe(sass().on('error', sass.logError))
	    .pipe(gulp.dest(PATH.dest.dev.all));

	  return result;
});


// --------------
// Build dev.

gulp.task('build.lib.dev', function () {
  return gulp.src(PATH.src.lib)
    .pipe(gulp.dest(PATH.dest.dev.lib));
});

gulp.task('build.js.dev', function () {
  var result = gulp.src([join(PATH.src.all, '**/*ts'),
                         '!' + join(PATH.src.all, '**/*_spec.ts')])
    .pipe(plumber())
    .pipe(sourcemaps.init())
    .pipe(tsc(tsProject));

  return result.js
    .pipe(sourcemaps.write())
    .pipe(template(templateLocals()))
    .pipe(gulp.dest(PATH.dest.dev.all));
});

/**
 * Build assets (Dev)
 */
gulp.task('build.assets.dev', ['build.scss', 'build.js.dev'], function () {
  return gulp.src([join(PATH.src.all, '**/*.html'), join(PATH.src.all, '**/*.css'), join(PATH.src.all, '**/*.png'), join(PATH.src.all, '**/*.jpg')])
    .pipe(gulp.dest(PATH.dest.dev.all));
});

/**
 * Compile index page (Dev)
 */
gulp.task('build.index.dev', function() {
  var target = gulp.src(injectableDevAssetsRef(), { read: false });
  return gulp.src(join(PATH.src.all, 'index.php'))
    .pipe(inject(target, { transform: transformPath('dev') }))
    .pipe(template(templateLocals()))
    .pipe(gulp.dest(PATH.dest.dev.all));
});

gulp.task('build.app.dev', function (done) {
  runSequence('clean.app.dev', 'build.plugins', 'build.assets.dev', 'build.index.dev', done);
});

gulp.task('build.dev', function (done) {
  runSequence('clean.dev', 'build.lib.dev', 'build.app.dev', done);
});

gulp.task('build.bundle', ['build.dev'], function (cb){

  var builder = new Builder();
  builder.config({
    baseURL: './front/public',
    defaultJSExtensions: true,
    paths: {
      '*': './front/public/*.js'
    },
    meta: {
      'angular2/angular2': { build: false },
      'angular2/router': { build: false },
      'http/http': { build: false }
    }
  });
  builder.build('app', './front/public/app.js', {minify: true})
    .then(function(){
        cb();
    })
    .catch(function(e){
        console.error('errored to build', e);
    });

});

gulp.task('build.prod', function(done){
  PATH.src.lib = PATH.src.loader
      .concat(PATH.src.angular);
  runSequence('build.bundle', done);
})

// --------------
// Post install

gulp.task('install.typings', ['clean.tsd_typings'], shell.task([
  'rm -rf node_modules/angular2/bundles/typings/angular2/http.d.ts', //temporary hack because http.d.ts has a syntax error
  'tsd reinstall --overwrite',
  'tsd link',
  'tsd rebundle'
]));

gulp.task('postinstall', function (done) {
  runSequence('install.typings', done);
});

// --------------
// Version.

registerBumpTasks();

gulp.task('bump.reset', function() {
  return gulp.src('package.json')
    .pipe(bump({ version: '0.0.0' }))
    .pipe(gulp.dest('./'));
});

// --------------
// Utils.

function transformPath(env) {
  var v = '?v=' + getVersion();
   return function (filepath) {
     var filename = filepath.replace('/' + PATH.dest[env].all, '') + v;
     arguments[0] = join(APP_BASE, filename);
     return inject.transform.apply(inject.transform, arguments);
   };
}

function injectableDevAssetsRef() {
  var src = PATH.src.lib.map(function(path) {
    return join(PATH.dest.dev.lib, path.split('/').pop());
  });
  src.push(join(PATH.dest.dev.all, '**/*.css'));
  return src;
}

function getVersion(){
  var pkg = JSON.parse(fs.readFileSync('package.json'));
  return pkg.version;
}

function templateLocals() {
  return {
    VERSION: getVersion(),
    APP_BASE: APP_BASE
  };
}

function registerBumpTasks() {
  semverReleases.forEach(function (release) {
    var semverTaskName = 'semver.' + release;
    var bumpTaskName = 'bump.' + release;
    gulp.task(semverTaskName, function() {
      var version = semver.inc(getVersion(), release);
      return gulp.src('package.json')
        .pipe(bump({ version: version }))
        .pipe(gulp.dest('./'));
    });
    gulp.task(bumpTaskName, function(done) {
        runSequence(semverTaskName, 'build.app.prod', done);
    });
  });
}
