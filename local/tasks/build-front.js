const Listr = require('listr');
const exec = require('../lib/exec');

module.exports = {
  title: 'Setting up front',
  task: () => new Listr([
    {
      title: 'Installing dependencies',
      task: () => exec.in('front', 'npm', ['install'])
    },
    {
      title: 'Building app',
      task: () => exec.in('front', 'npm', ['run', 'build:dev', '--', '--watch=false'], {
        NODE_OPTIONS: '--max_old_space_size=4096'
      })
    },
    {
      title: 'Building SSR server',
      task: () => exec.in('front', 'npx', ['ng', 'run', 'minds:server:production'], {
        NODE_OPTIONS: '--max_old_space_size=4096'
      })
    }
  ])
};
