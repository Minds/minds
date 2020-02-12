const exec = require('./exec');

let upstreamUrl;
let useFrontContainer;

switch (process.platform) {
  case 'win32':
  case 'darwin':
    upstreamUrl = 'host.docker.internal:4200';
    useFrontContainer = false;
    break;

  default:
    upstreamUrl = 'front-live-server:4200';
    useFrontContainer = true;
    break;
}

function buildDefaultArgs() {
  const args = [
    '-f',
    'docker-compose.yml',
    '-f',
    'docker-compose.with-phpspec.yml',
];

  if (useFrontContainer) {
    args.push(
      '-f',
      'docker-compose.with-front.yml'
    );
  }

  return args;
}

function buildEnv() {
  return {
    UPSTREAM_ENDPOINT: upstreamUrl,
  };
}

module.exports = function (...args) {
  return exec('docker-compose', [
    ...buildDefaultArgs(),
    ...args
  ], buildEnv());
}
