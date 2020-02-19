const exec = require('./exec');

let upstreamEndpoint;

switch (process.platform) {
  case 'win32':
  case 'darwin':
    upstreamEndpoint = 'host.docker.internal:4200';
    break;

  default:
    upstreamEndpoint = '$remote_addr:4200';
    useFrontContainer = false;
    break;
}

function buildDefaultArgs() {
  return [
    '-f',
    'docker-compose.yml',
    '-f',
    'docker-compose.with-phpspec.yml',
  ];
}

function buildEnv() {
  return {
    UPSTREAM_ENDPOINT: upstreamEndpoint,
  };
}

module.exports = function (...args) {
  return exec('docker-compose', [
    ...buildDefaultArgs(),
    ...args
  ], buildEnv());
};
