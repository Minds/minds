const Listr = require('listr');
const util = require('util');
const fs = require('fs');
const rimraf = require('@alexbinary/rimraf');
const exec = require('../lib/exec');
const cwd = require('../lib/cwd');
const doco = require('../lib/doco');

const exists = util.promisify(fs.exists);
const rename = util.promisify(fs.rename);

module.exports = {
  title: 'Cleaning up stack',
  task: () => new Listr([
    {
      title: 'Purging containers',
      task: () => doco('down', '-v', '--rmi=all')
    },
    require('../tasks/build-containers'),
    {
      title: 'Pruning engine',
      task: async (ctx, task) => {
        const settingsPhp = cwd('engine', 'settings.php');

        if (await exists(settingsPhp)) {
          const newName = cwd('engine', `settings.php-${Date.now()}.bak`);
          await rename(settingsPhp, newName);
        } else {
          return task.skip('No settings.php file present')
        }
      }
    }
  ])
};
