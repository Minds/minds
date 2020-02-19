const yargs = require('yargs');
const getMissingDeps = require('./helpers/get-missing-deps');
const repoHealth = require('./helpers/repo-health');

return (async () => {
  const missingDeps = await getMissingDeps();

  if (missingDeps.length) {
    process.stderr.write(
      `FATAL: Missing dependencies: ${missingDeps.join(', ')}\n`
    );
    return process.exit(1);
  }

  await repoHealth();

  return yargs
    .option('verbose', {
      description: 'Verbose output',
      boolean: true
    })
    .option('silent', {
      description: 'Silent output',
      boolean: true
    })
    .command(['up', 'start'], 'Start the containers', require('./commands/up'))
    .command(['down', 'stop'], 'Stop the containers', require('./commands/down'))
    .command('restart', 'Restart the containers', require('./commands/restart'))
    .command('rebuild', 'Rebuild the containers', require('./commands/rebuild'))
    .command('install', 'Installs and provisions the compose stack', require('./commands/install'))
    .command('phpspec', 'Runs PHPSpec on a container similar to GitLab CI', require('./commands/phpspec'))
    .demandCommand(1, 'Please, specify a command.')
    .help()
    .argv;
})();
