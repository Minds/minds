const doco = require('../lib/doco');

module.exports.handler = async argv => {
  let renderer = 'default';

  if (argv.verbose) {
    renderer = 'verbose';
  } else if (argv.silent) {
    renderer = 'silent'
  }

  const subprocess = doco('run', 'phpspec', ...argv._.slice(1));

  subprocess.stdout
    .pipe(process.stdout);

  subprocess.stderr
    .pipe(process.stderr);

  try {
    await subprocess;
  } catch (e) {
    console.log('\nCommand failed...');
    process.exit(e.errorCode);
  }
};

module.exports.builder = {};
