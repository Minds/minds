const exec = require('../lib/exec');

module.exports = async function() {
  const getAutoCrLf = async dir => {
    try {
      const subprocess = await exec.in(dir, 'git', ['config', '--get', 'core.autocrlf']);
      return (subprocess.stdout || '').trim().toLowerCase();
    } catch (e) {
      return false;
    }
  }

  const badAutoCrLfValue = 'true';

  const willCauseIssues = (await Promise.all([
    getAutoCrLf(''),
    getAutoCrLf('front'),
    getAutoCrLf('engine'),
    getAutoCrLf('sockets'),
  ])).some(autocrlf => autocrlf === badAutoCrLfValue);

  if (willCauseIssues) {
    process.stderr.write(
      `\nWARNING: One or more repositories have 'core.autocrlf' set to '${badAutoCrLfValue}'. ` +
      'This will cause issues with containerized scripts and provisioners. Please check ' +
      'Minds Developers documentation.\n\n'
    );
  }
};
