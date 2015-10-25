var gulp = require('gulp');
var git = require('gulp-git');
var shell = require('gulp-shell');

var PLUGINS_LIST = ['gatherings', 'groups', 'notifications', 'oauth2', 'payments'];

gulp.task('init', ['clone', 'install'], function(){

});

gulp.task('clone', ['init.clone.front', 'init.clone.engine', 'init.clone.plugins']);

gulp.task('clone.front', function(){
  git.clone('https://github.com/minds/front.git',  { args: './front' }, function(err){
    console.error(err);
  })
});

gulp.task('clone.engine', function(){
  git.clone('https://github.com/minds/engine.git',  { args: './engine' }, function(err){
    console.error(err);
  })
});

gulp.task('clone.plugins', function(){
  //coming soon
});

gulp.task('install', ['install.front', 'install.engine']);

gulp.task('install.front', shell.task([
  'cd front; npm install;'
]));

gulp.task('install.engine', shell.task([
  'cd engine; composer install; '
]));


gulp.task('build', ['build.front']);

gulp.task('build.front', shell.task([
  'cd front; gulp build.prod'
]));


gulp.task('test', ['test.front', 'test.engine']);

gulp.task('test.front', shell.task([
  'cd front; gulp test.e2e'
]));

gulp.task('test.engine', shell.task([
  'cd engine; bin/phpspec run'
]));
