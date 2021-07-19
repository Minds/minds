const Listr = require('listr');
const doco = require('../lib/doco');

module.exports = {
  title: 'Provisioning ElasticSearch',
  task: () => new Listr([
    {
      title: 'Creating indices',
      task: () => doco('run', 'elasticsearch-provisioner')
    },
  ])
};
