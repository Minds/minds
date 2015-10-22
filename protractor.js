require('babel/register');

var BASE_URL = 'https://new.minds.com/';
var BROWSERS = require('./sauce.conf');
var aliases = BROWSERS.aliases;

var _browsers = process.env.browsers ? process.env.browsers.split(',') : ['chrome'];

exports.config = {
	baseUrl: BASE_URL,
  sauceUser: process.env.SAUCE_USERNAME,
  sauceKey: process.env.SAUCE_ACCESS_KEY,
	// restartBrowserBetweenTests: true,
	onPrepare: function() {
		// remove this hack and use the config option
		// restartBrowserBetweenTests once that is not hanging.
		// See https://github.com/angular/protractor/issues/1983
		patchProtractorWait(browser);
    browser.driver.get(BASE_URL);
    browser.manage().addCookie('beta', 'angular2', '/', 'new.minds.com');
	},
	specs: [
		'./tests/e2e/*.js'
	],
	exclude: [],
  multiCapabilities: aliases["DESKTOP"].map(function(alias){
    console.log('testing: ' + BROWSERS.customLaunchers[alias].browserName);
    var b = BROWSERS.customLaunchers[alias];
    b.name = BROWSERS.customLaunchers[alias].browserName + ' (' + b.version + ') test';
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
function patchProtractorWait (browser) {
	browser.ignoreSynchronization = true;
	// Benchmarks never need to wait for Angular 2 to be ready
	var _get = browser.get;
	var sleepInterval = process.env.TRAVIS ? 14000 : 8000;
	browser.get = function() {
		var result = _get.apply(this, arguments);
		browser.driver.wait(
			protractor.until.elementLocated(By.js(function(){
				var isLoading = true;
				if (window.getAllAngularTestabilities) {
					var testabilities = window.getAllAngularTestabilities();
					if (testabilities && testabilities.length > 0) {
						isLoading = false;
						testabilities.forEach(function(testability){
							if (!testability.isStable()) isLoading = true;
						});
					}
				}
				return !isLoading ? document.body.children : null;
			})),
			sleepInterval
		);
		return result;
	}
}

function merge (src, target) {
	for (var prop in src) target[prop] = src[prop];
	return target;
}
