const Listr = require('listr');
const doco = require('../lib/doco');

module.exports = {
  title: 'Provisioning MySQL',
  task: () => new Listr([
    {
      title: 'Creating tables',
      task: () => doco('run', 'mysql-provisioner')
    },
  ])
};
