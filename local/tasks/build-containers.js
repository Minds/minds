const doco = require('../lib/doco');

module.exports = {
  title: 'Building containers',
  task: () => doco('build', '--no-cache')
};
