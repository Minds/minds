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
var __param = (this && this.__param) || function (paramIndex, decorator) {
    return function (target, key) { decorator(target, key, paramIndex); }
};
var angular2_1 = require('angular2/angular2');
var http_1 = require('angular2/http');
var cookie_1 = require('src/services/cookie');
var Client = (function () {
    function Client(http) {
        this.http = http;
        this.base = "/";
        this.cookie = new cookie_1.Cookie();
    }
    Client.prototype.buildParams = function (object) {
        return Object.keys(object).map(function (k) {
            return encodeURIComponent(k) + "=" + encodeURIComponent(object[k]);
        }).join('&');
    };
    Client.prototype.buildOptions = function (options) {
        var XSRF_TOKEN = this.cookie.get('XSRF-TOKEN');
        var headers = new http_1.Headers();
        headers.append('X-XSRF-TOKEN', XSRF_TOKEN);
        return Object.assign(options, {
            headers: headers,
            cache: true
        });
    };
    Client.prototype.get = function (endpoint, data, options) {
        var _this = this;
        if (data === void 0) { data = {}; }
        if (options === void 0) { options = {}; }
        var self = this;
        endpoint += "?" + this.buildParams(data);
        return new Promise(function (resolve, reject) {
            self.http.get(self.base + endpoint, _this.buildOptions(options))
                .toRx()
                .subscribe(function (res) {
                if (res.status != 200) {
                    return reject("Header: " + status);
                }
                var data = res.json();
                if (data.status != 'success')
                    return reject(data);
                return resolve(data);
            });
        });
    };
    Client.prototype.post = function (endpoint, data, options) {
        var _this = this;
        if (data === void 0) { data = {}; }
        if (options === void 0) { options = {}; }
        var self = this;
        return new Promise(function (resolve, reject) {
            self.http.post(self.base + endpoint, JSON.stringify(data), _this.buildOptions(options))
                .toRx()
                .subscribe(function (res) {
                if (res.status != 200) {
                    return reject("Header: " + status);
                }
                var data = res.json();
                if (data.status != 'success')
                    return reject(data);
                return resolve(data);
            });
        });
    };
    Client.prototype.put = function (endpoint, data, options) {
        var _this = this;
        if (data === void 0) { data = {}; }
        if (options === void 0) { options = {}; }
        var self = this;
        return new Promise(function (resolve, reject) {
            self.http.put(self.base + endpoint, JSON.stringify(data), _this.buildOptions(options))
                .toRx()
                .subscribe(function (res) {
                if (res.status != 200) {
                    return reject("Header: " + status);
                }
                var data = res.json();
                if (data.status != 'success')
                    return reject(data);
                return resolve(data);
            });
        });
    };
    Client.prototype.delete = function (endpoint, data, options) {
        var _this = this;
        if (data === void 0) { data = {}; }
        if (options === void 0) { options = {}; }
        var self = this;
        return new Promise(function (resolve, reject) {
            self.http.delete(self.base + endpoint, _this.buildOptions(options))
                .toRx()
                .subscribe(function (res) {
                if (res.status != 200) {
                    return reject("Header: " + status);
                }
                var data = res.json();
                if (data.status != 'success')
                    return reject(data);
                return resolve(data);
            });
        });
    };
    Client = __decorate([
        __param(0, angular2_1.Inject(http_1.Http)), 
        __metadata('design:paramtypes', [(typeof Http !== 'undefined' && Http) || Object])
    ], Client);
    return Client;
})();
exports.Client = Client;
//# sourceMappingURL=client.js.map