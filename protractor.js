require('babel/register');

var BASE_URL = 'https://new.minds.com/';
var BROWSERS = require('./sauce.conf');
var aliases = BROWSERS.aliases;

var _browsers = process.env.browsers ? process.env.browsers.split(',') : ['chrome'];

exports.config = {
	baseUrl: BASE_URL,
  sauceUser: process.env.SAUCE_USERNAME,
  sauceKey: process.env.SAUCE_ACCESS_KEY,
  //restartBrowserBetweenTests: true,
	onPrepare: function() {
		browser.driver.get(BASE_URL + 'login?beta=angular2');
		beforeEach(function() {
			patchProtractorWait(global.browser);
		 });

    //browser.manage().addCookie({name: 'beta', value: 'angular2', expiry: Date.now()});
	},
	specs: [
		'./tests/e2e/*.js',
		'./tests/e2e/**/*.js'
	],
	exclude: [],
  multiCapabilities: aliases["ALL"].map(function(alias){
    var b = BROWSERS.customLaunchers[alias];
    b.name = 'Minds / Web (' + (new Date()).toDateString() + ' ' + (new Date()).toLocaleTimeString() + ')';
    return b;
  }),
	framework: 'jasmine2',
	jasmineNodeOpts: {
		showColors: true,
		defaultTimeoutInterval: 60000
	}
};

// Disable waiting for Angular as there isn't an integration layer yet.
// Wait for a proper debugging API implementation for Ng2.0, remove this here
// and the sleeps in all tests.
function patchProtractorWait(browser) {
  browser.ignoreSynchronization = true;
  var _get = browser.get;
  var sleepInterval = process.env.TRAVIS || process.env.JENKINS_URL ? 7000 : 3000;
  browser.get = function() {
    var result = _get.apply(this, arguments);
    //browser.sleep(sleepInterval);
    return result;
  }
}
