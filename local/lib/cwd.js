const path = require('path');

module.exports = (...extras) => {
  return path.join(process.cwd(), '..', ...extras);
};
