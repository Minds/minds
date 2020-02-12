const execa = require('execa');
const fs = require('fs');
const cwd = require('./cwd');

function exec(relativePath, file, arguments, env = {}, opts = {}) {
  const workingDir = cwd(relativePath);

  if (!fs.existsSync(workingDir) || !fs.lstatSync(workingDir).isDirectory()) {
    throw new Error(`${workingDir} is not a directory`);
  }

  return execa(file, arguments, {
    cwd: workingDir,
    env: {
      ...process.env,
      ...env
    },
    ...opts,
  });
}

module.exports = (file, arguments, env = {}, opts = {}) => {
  return exec('', file, arguments, env, opts);
};

module.exports.in = exec;
