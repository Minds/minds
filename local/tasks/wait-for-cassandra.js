const Listr = require('listr');
const doco = require('../lib/doco');

module.exports = {
  title: 'Start Cassandra',
  task: () => new Listr([
    {
      title: 'Waiting for Cassandra',
      task: () => doco('run', 'wait-for-cassandra')
    },
  ])
};
