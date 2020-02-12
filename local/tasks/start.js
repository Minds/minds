const doco = require('../lib/doco');

module.exports = {
  title: 'Starting containers',
  task: () => doco('up', '-d', 'nginx', 'runners', 'sockets')
};
