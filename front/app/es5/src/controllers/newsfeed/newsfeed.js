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
var api_1 = require('src/services/api');
var material_1 = require('src/directives/material');
var infinite_scroll_1 = require('../../directives/infinite-scroll');
var activity_1 = require('./activity');
var Newsfeed = (function () {
    function Newsfeed(client) {
        this.client = client;
        this.newsfeed = [];
        this.offset = "";
        this.inProgress = false;
        this.moreData = true;
        this.postMeta = {
            title: "",
            description: "",
            thumbnail: "",
            url: "",
            active: false
        };
        this.load();
    }
    Newsfeed.prototype.load = function (refresh) {
        if (refresh === void 0) { refresh = false; }
        var self = this;
        if (this.inProgress) {
            return false;
        }
        if (refresh) {
            this.offset = "";
        }
        this.inProgress = true;
        this.client.get('api/v1/newsfeed', { limit: 12, offset: this.offset }, { cache: true })
            .then(function (data) {
            if (!data.activity) {
                self.moreData = false;
                self.inProgress = false;
                return false;
            }
            if (self.newsfeed && !refresh) {
                for (var _i = 0, _a = data.activity; _i < _a.length; _i++) {
                    var activity = _a[_i];
                    self.newsfeed.push(activity);
                }
            }
            else {
                self.newsfeed = data.activity;
            }
            self.offset = data['load-next'];
            self.inProgress = false;
        })
            .catch(function (e) {
            console.log(e);
        });
    };
    Newsfeed.prototype.post = function () {
        var self = this;
        this.client.post('api/v1/newsfeed', this.postMeta)
            .then(function (data) {
            self.load();
            self.postMeta = {
                message: "",
                title: "",
                description: "",
                thumbnail: "",
                url: "",
                active: false
            };
        })
            .catch(function (e) {
            console.log(e);
        });
    };
    Newsfeed.prototype.getPostPreview = function (message) {
        var _this = this;
        var self = this;
        var match = message.value.match(/(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig);
        if (!match)
            return;
        var url;
        if (match instanceof Array) {
            url = match[0];
        }
        else {
            url = match;
        }
        if (!url.length)
            return;
        url = url.replace("http://", '');
        url = url.replace("https://", '');
        console.log('found url was ' + url);
        self.postMeta.active = true;
        if (this.timeout)
            clearTimeout(this.timeout);
        this.timeout = setTimeout(function () {
            _this.client.get('api/v1/newsfeed/preview', { url: url })
                .then(function (data) {
                console.log(data);
                self.postMeta.title = data.meta.title;
                self.postMeta.url = data.meta.canonical;
                self.postMeta.description = data.meta.description;
                for (var _i = 0, _a = data.links; _i < _a.length; _i++) {
                    var link = _a[_i];
                    if (link.rel.indexOf('thumbnail') > -1) {
                        self.postMeta.thumbnail = link.href;
                    }
                }
            });
        }, 600);
    };
    Newsfeed.prototype.toDate = function (timestamp) {
        return new Date(timestamp * 1000);
    };
    Newsfeed = __decorate([
        angular2_1.Component({
            selector: 'minds-newsfeed',
            viewInjector: [api_1.Client]
        }),
        angular2_1.View({
            templateUrl: 'templates/newsfeed/list.html',
            directives: [activity_1.Activity, angular2_1.NgFor, angular2_1.NgIf, material_1.Material, angular2_1.formDirectives, infinite_scroll_1.InfiniteScroll]
        }), 
        __metadata('design:paramtypes', [Client])
    ], Newsfeed);
    return Newsfeed;
})();
exports.Newsfeed = Newsfeed;
//# sourceMappingURL=newsfeed.js.map