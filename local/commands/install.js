const Listr = require('listr');
const prompts = require('prompts');

module.exports.handler = async argv => {
  let renderer = 'default';

  if (argv.verbose) {
    renderer = 'verbose';
  } else if (argv.silent) {
    renderer = 'silent'
  }

  const prompt = await prompts([
    {
      type: 'toggle',
      name: 'confirm',
      message: 'This will WIPE your local stack, if exists. Proceed?',
      active: 'Yes',
      inactive: 'No'
    }
  ]);

  console.log('');

  if (!prompt.confirm) {
    console.log('Cancelled by user');
    process.exit(1);
  }

  const tasks = new Listr([
    require('../tasks/stop'),
    require('../tasks/cleanup'),
    require('../tasks/build-front'),
    require('../tasks/provision-elasticsearch'),
    require('../tasks/install-minds'),
    require('../tasks/restart'),
  ], {
    renderer
  });

  await tasks.run();
};

module.exports.builder = {};
