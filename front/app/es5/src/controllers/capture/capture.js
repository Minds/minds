var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") return Reflect.decorate(decorators, target, key, desc);
    switch (arguments.length) {
        case 2: return decorators.reduceRight(function(o, d) { return (d && d(o)) || o; }, target);
        case 3: return decorators.reduceRight(function(o, d) { return (d && d(target, key)), void 0; }, void 0);
        case 4: return decorators.reduceRight(function(o, d) { return (d && d(target, key, o)) || o; }, desc);
    }
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};
var angular2_1 = require('angular2/angular2');
var http_1 = require('http/http');
var upload_1 = require('src/services/api/upload');
var Capture = (function () {
    function Capture(upload, http) {
        this.upload = upload;
        this.http = http;
        this.postMeta = {};
    }
    Capture.prototype.uploadFile = function () {
        console.log('called');
        console.log(this.postMeta);
        this.upload.post('api/v1/archive', this.postMeta, function (progress) {
            console.log('progress update');
        })
            .then(function (response) {
            console.log(response);
        })
            .catch(function (e) {
            console.error(e);
        });
    };
    Capture = __decorate([
        angular2_1.Component({
            selector: 'minds-capture',
            viewBindings: [upload_1.Upload, http_1.Http]
        }),
        angular2_1.View({
            templateUrl: 'templates/capture/capture.html',
            directives: [angular2_1.FORM_DIRECTIVES]
        }), 
        __metadata('design:paramtypes', [Upload, Http])
    ], Capture);
    return Capture;
})();
exports.Capture = Capture;
//# sourceMappingURL=capture.js.map