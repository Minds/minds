/// <reference path="../typings/custom.system.d.ts" />
System.config({
  baseURL: './',
  paths: {
  	'*': '*.js'
  }
});

System.import('app')
  .catch(e => console.error(e, 'Report this error at https://github.com/Minds/Minds/issues'));