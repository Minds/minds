const exec = require('../lib/exec');

module.exports = async function() {
  const missingDeps = [];

  try {
    await exec('git', ['--version']);
  } catch (e) {
    missingDeps.push('git');
  }

  try {
    await exec('docker', ['-v']);
  } catch (e) {
    missingDeps.push('docker');
  }

  try {
    await exec('docker-compose', ['-v']);
  } catch (e) {
    missingDeps.push('docker-compose');
  }

  return missingDeps;
};
