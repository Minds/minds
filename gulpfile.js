var gulp = require('gulp');
var git = require('gulp-git');
var runSequence = require('run-sequence');
var shell = require('gulp-shell');

var PLUGINS_LIST = ['gatherings', 'groups', 'notifications', 'oauth2', 'payments'];

gulp.task('init', function(done){
  runSequence('clone', 'install', 'build', done);
});

gulp.task('clone', function(done){
  runSequence('clone.front', 'clone.engine', 'clone.sockets', 'clone.plugins', 'clone.docs', done);
});

gulp.task('clone.front', function(done){
  git.clone('git@github.com:minds/front.git',  { args: './front' }, function(err){
    if(!err)
      return done();
    console.error(err);
    process.exit(1);
  });
});

gulp.task('clone.engine', function(done){
  git.clone('git@github.com:minds/engine.git',  { args: './engine' }, function(err){
    if(!err)
      return done();
    console.error(err);
    process.exit(1);
  })
});

gulp.task('clone.sockets', function(done){
  git.clone('git@github.com:minds/sockets.git',  { args: './sockets' }, function(err){
    if(!err)
      return done();
    console.error(err);
    process.exit(1);
  })
});

gulp.task('clone.plugins', function(done){
  //coming soon
  done();
});

gulp.task('clone.docs', function(done){
  git.clone('git@github.com:minds/docs.git',  { args: './docs' }, function(err){
    if(!err)
      return done();
    console.error(err);
    process.exit(1);
  })
})

gulp.task('install', ['install.front', 'install.engine']);

gulp.task('install.front', ['install.front-typings'], shell.task([
  'cd front; npm install;'
]));

gulp.task('install.front-typings', shell.task([
  'cd front; tsd reinstall --overwrite',
  'cd front; tsd link',
  'cd front; tsd rebundle'
]));

gulp.task('install.engine', shell.task([
  'cd engine; composer install; '
]));


gulp.task('build', ['build.front']);

gulp.task('build.front', shell.task([
  'cd front; gulp build.prod'
]));

gulp.task('build.docs', shell.task([
  'cd docs; ../engine/bin/apigen generate --config apigen.neon'
]));

gulp.task('test', ['test.front', 'test.engine']);

gulp.task('test.front', shell.task([
  'cd front; gulp test.e2e'
]));

gulp.task('test.engine', shell.task([
  'cd engine; bin/phpspec run'
]));
