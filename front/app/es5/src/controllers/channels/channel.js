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
var router_1 = require('angular2/router');
var api_1 = require('src/services/api');
var material_1 = require('src/directives/material');
var session_1 = require('../../services/session');
var infinite_scroll_1 = require('../../directives/infinite-scroll');
var autogrow_1 = require('../../directives/autogrow');
var activity_1 = require('src/controllers/newsfeed/activity');
var subscribers_1 = require('./subscribers');
var subscriptions_1 = require('./subscriptions');
var Channel = (function () {
    function Channel(client, router, params) {
        this.client = client;
        this.router = router;
        this.params = params;
        this._filter = "feed";
        this.session = session_1.SessionFactory.build();
        this.feed = [];
        this.offset = "";
        this.moreData = true;
        this.inProgress = false;
        this.editing = "";
        this.error = "";
        this.username = params.params['username'];
        if (params.params['filter'])
            this._filter = params.params['filter'];
        this.load();
    }
    Channel.prototype.load = function () {
        var self = this;
        this.client.get('api/v1/channel/' + this.username, {})
            .then(function (data) {
            if (data.status != "success") {
                self.error = data.message;
                return false;
            }
            self.user = data.channel;
            if (self._filter == "feed")
                self.loadFeed(true);
        })
            .catch(function (e) {
            console.log('couldnt load channel', e);
        });
    };
    Channel.prototype.loadFeed = function (refresh) {
        if (refresh === void 0) { refresh = false; }
        var self = this;
        if (this.inProgress) {
            return false;
        }
        if (refresh) {
            this.offset = "";
        }
        this.inProgress = true;
        this.client.get('api/v1/newsfeed/personal/' + this.user.guid, { limit: 12, offset: this.offset }, { cache: true })
            .then(function (data) {
            if (!data.activity) {
                self.moreData = false;
                self.inProgress = false;
                return false;
            }
            if (self.feed && !refresh) {
                for (var _i = 0, _a = data.activity; _i < _a.length; _i++) {
                    var activity = _a[_i];
                    self.feed.push(activity);
                }
            }
            else {
                self.feed = data.activity;
            }
            self.offset = data['load-next'];
            self.inProgress = false;
        })
            .catch(function (e) {
            console.log(e);
        });
    };
    Channel.prototype.isOwner = function () {
        return this.session.getLoggedInUser().guid == this.user.guid;
    };
    Channel.prototype.toggleEditing = function (section) {
        if (this.editing == section)
            this.editing = "";
        else
            this.editing = section;
    };
    Channel.prototype.updateField = function (field) {
        if (!field)
            return false;
        var self = this;
        var data = {};
        data[field] = this.user[field];
        this.client.post('api/v1/channel/info', data)
            .then(function (data) {
            if (data.status != "success") {
                alert('error saving');
                return false;
            }
            self.editing = "";
        });
    };
    Channel = __decorate([
        angular2_1.Component({
            selector: 'minds-channel',
            viewBindings: [api_1.Client]
        }),
        angular2_1.View({
            templateUrl: 'templates/channels/channel.html',
            directives: [angular2_1.NgFor, angular2_1.NgIf, material_1.Material, angular2_1.FORM_DIRECTIVES, infinite_scroll_1.InfiniteScroll, activity_1.Activity, autogrow_1.AutoGrow, subscribers_1.ChannelSubscribers, subscriptions_1.ChannelSubscriptions]
        }),
        __param(1, angular2_1.Inject(router_1.Router)),
        __param(2, angular2_1.Inject(router_1.RouteParams)), 
        __metadata('design:paramtypes', [Client, Router, RouteParams])
    ], Channel);
    return Channel;
})();
exports.Channel = Channel;
var subscribers_2 = require('./subscribers');
exports.ChannelSubscribers = subscribers_2.ChannelSubscribers;
var subscriptions_2 = require('./subscriptions');
exports.ChannelSubscriptions = subscriptions_2.ChannelSubscriptions;
//# sourceMappingURL=channel.js.map