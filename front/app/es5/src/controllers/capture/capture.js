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
var client_1 = require('src/services/api/client');
var Capture = (function () {
    function Capture(upload, client, http) {
        this.upload = upload;
        this.client = client;
        this.http = http;
        this.uploads = [];
        this.postMeta = {};
        this.domListeners();
    }
    Capture.prototype.domListeners = function () {
    };
    Capture.prototype.uploadFile = function () {
        var self = this;
        var data = {
            guid: null,
            state: 'created',
            progress: 0
        };
        var fileInfo = document.getElementById("file").files[0];
        if (fileInfo.type.indexOf('image') > -1) {
            data.type = "image";
        }
        else if (fileInfo.type.indexOf('video') > -1) {
            data.type = "video";
        }
        else if (fileInfo.type.indexOf('audio') > -1) {
            data.type = "audio";
        }
        else {
            data.type = "unknown";
        }
        data.name = fileInfo.name;
        var index = this.uploads.push(data) - 1;
        this.upload.post('api/v1/archive', [fileInfo], data, function (progress) {
            console.log('progress update');
            console.log(progress);
            self.uploads[index].progress = progress;
        })
            .then(function (response) {
            console.log(response, response.guid);
            self.uploads[index].guid = response.guid;
            self.uploads[index].state = 'uploaded';
            self.uploads[index].progress = 100;
        })
            .catch(function (e) {
            console.error(e);
        });
    };
    Capture.prototype.modify = function (index) {
        var self = this;
        var promise = new Promise(function (resolve, reject) {
            if (self.uploads[index].guid) {
                resolve();
                return;
            }
            var interval = setInterval(function () {
                if (self.uploads[index].guid) {
                    resolve();
                    clearInterval(interval);
                }
            }, 1000);
        });
        promise.then(function () {
            self.client.post('api/v1/archive/' + self.uploads[index].guid, self.upload[index])
                .then(function (response) {
                console.log('response from modify', response);
            });
        });
    };
    Capture = __decorate([
        angular2_1.Component({
            selector: 'minds-capture',
            viewBindings: [upload_1.Upload, client_1.Client, http_1.Http]
        }),
        angular2_1.View({
            templateUrl: 'templates/capture/capture.html',
            directives: [angular2_1.NgFor, angular2_1.FORM_DIRECTIVES]
        }), 
        __metadata('design:paramtypes', [Upload, Client, Http])
    ], Capture);
    return Capture;
})();
exports.Capture = Capture;
//# sourceMappingURL=capture.js.map