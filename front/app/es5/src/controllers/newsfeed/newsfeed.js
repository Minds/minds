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
var Newsfeed = (function () {
    function Newsfeed(client) {
        this.client = client;
        this.newsfeed = [];
        this.offset = "";
        this.load();
    }
    Newsfeed.prototype.load = function () {
        var self = this;
        this.client.get('api/v1/newsfeed', { limit: 12 }, { cache: true })
            .then(function (data) {
            if (!data.activity) {
                return false;
            }
            self.newsfeed = data.activity;
            self.offset = data['load-next'];
        })
            .catch(function (e) {
            console.log(e);
        });
    };
    Newsfeed.prototype.post = function (message) {
        var self = this;
        this.client.post('api/v1/newsfeed', { message: message })
            .then(function (data) {
            self.load();
        })
            .catch(function (e) {
            console.log(e);
        });
    };
    Newsfeed.prototype.getPostPreview = function (message) {
        console.log("you said " + message.value);
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
            directives: [angular2_1.NgFor, angular2_1.NgIf]
        }), 
        __metadata('design:paramtypes', [Client])
    ], Newsfeed);
    return Newsfeed;
})();
exports.Newsfeed = Newsfeed;
//# sourceMappingURL=newsfeed.js.map