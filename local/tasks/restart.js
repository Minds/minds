const Listr = require('listr');

module.exports = {
  title: 'Restarting containers',
  task: () => new Listr([
    require('./stop'),
    require('./start'),
  ])
};
