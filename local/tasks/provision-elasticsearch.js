const Listr = require('listr');
const doco = require('../lib/doco');

module.exports = {
  title: 'Provisioning ElasticSearch',
  task: () => new Listr([
    {
      title: 'Creating 5.x-compatible indices',
      task: () => doco('run', 'elasticsearch-legacy-provisioner')
    },
    {
      title: 'Stopping 5.x container',
      task: () => doco('stop', 'elasticsearch-legacy')
    },
    {
      title: 'Creating indices',
      task: () => doco('run', 'elasticsearch-provisioner')
    },
  ])
};
