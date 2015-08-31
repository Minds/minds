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
var session_1 = require('../../services/session');
var infinite_scroll_1 = require('../../directives/infinite-scroll');
var ChannelSubscribers = (function () {
    function ChannelSubscribers(client) {
        this.client = client;
        this.session = session_1.SessionFactory.build();
        this.users = [];
        this.offset = "";
        this.moreData = true;
        this.inProgress = false;
    }
    Object.defineProperty(ChannelSubscribers.prototype, "channel", {
        set: function (value) {
            this.guid = value.guid;
            this.load();
        },
        enumerable: true,
        configurable: true
    });
    ChannelSubscribers.prototype.load = function () {
        var self = this;
        this.inProgress = true;
        this.client.get('api/v1/subscribe/subscribers/' + this.guid, {})
            .then(function (response) {
            if (response.status != "success") {
                return false;
            }
            if (self.offset) { }
            for (var _i = 0, _a = response.users; _i < _a.length; _i++) {
                var user = _a[_i];
                self.users.push(user);
            }
            self.offset = response['load-next'];
            self.inProgress = false;
        })
            .catch(function (e) {
            console.log('couldnt load channel', e);
        });
    };
    ChannelSubscribers = __decorate([
        angular2_1.Component({
            selector: 'minds-channel-subscribers',
            viewBindings: [api_1.Client],
            properties: ['channel']
        }),
        angular2_1.View({
            templateUrl: 'templates/channels/subscribers.html',
            directives: [angular2_1.NgFor, angular2_1.NgIf, material_1.Material, infinite_scroll_1.InfiniteScroll]
        }), 
        __metadata('design:paramtypes', [Client])
    ], ChannelSubscribers);
    return ChannelSubscribers;
})();
exports.ChannelSubscribers = ChannelSubscribers;
//# sourceMappingURL=subscribers.js.map