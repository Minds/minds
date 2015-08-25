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
var router_1 = require("angular2/router");
var api_1 = require('src/services/api');
var session_1 = require('src/services/session');
var material_1 = require('src/directives/material');
var remind_1 = require('./remind');
var Activity = (function () {
    function Activity(client) {
        this.client = client;
        this.session = session_1.SessionFactory.build();
    }
    Object.defineProperty(Activity.prototype, "object", {
        set: function (value) {
            this.activity = value;
            if (!this.activity['thumbs:up:user_guids'])
                this.activity['thumbs:up:user_guids'] = [];
            if (!this.activity['thumbs:down:user_guids'])
                this.activity['thumbs:down:user_guids'] = [];
        },
        enumerable: true,
        configurable: true
    });
    Activity.prototype.delete = function () {
        this.client.delete('api/v1/newsfeed/' + this.activity.guid);
        delete this.activity;
    };
    Activity.prototype.toDate = function (timestamp) {
        return new Date(timestamp * 1000);
    };
    Activity.prototype.thumbsUp = function () {
        this.client.put('api/v1/thumbs/' + this.activity.guid + '/up', {});
        if (!this.hasThumbedUp()) {
            this.activity['thumbs:up:user_guids'].push(this.session.getLoggedInUser().guid);
        }
        else {
            for (var key in this.activity['thumbs:up:user_guids']) {
                if (this.activity['thumbs:up:user_guids'][key] == this.session.getLoggedInUser().guid)
                    delete this.activity['thumbs:up:user_guids'][key];
            }
        }
    };
    Activity.prototype.thumbsDown = function () {
        this.client.put('api/v1/thumbs/' + this.activity.guid + '/down', {});
        if (!this.hasThumbedDown()) {
            this.activity['thumbs:down:user_guids'].push(this.session.getLoggedInUser().guid);
        }
        else {
            for (var key in this.activity['thumbs:down:user_guids']) {
                if (this.activity['thumbs:down:user_guids'][key] == this.session.getLoggedInUser().guid)
                    delete this.activity['thumbs:down:user_guids'][key];
            }
        }
    };
    Activity.prototype.remind = function () {
        var self = this;
        this.client.post('api/v1/newsfeed/remind/' + this.activity.guid, {})
            .then(function (data) {
        });
    };
    Activity.prototype.hasThumbedUp = function () {
        for (var _i = 0, _a = this.activity['thumbs:up:user_guids']; _i < _a.length; _i++) {
            var guid = _a[_i];
            if (guid == this.session.getLoggedInUser().guid)
                return true;
        }
        return false;
    };
    Activity.prototype.hasThumbedDown = function () {
        for (var _i = 0, _a = this.activity['thumbs:down:user_guids']; _i < _a.length; _i++) {
            var guid = _a[_i];
            if (guid == this.session.getLoggedInUser().guid)
                return true;
        }
        return false;
    };
    Activity.prototype.hasReminded = function () {
        return false;
    };
    Activity = __decorate([
        angular2_1.Component({
            selector: 'minds-activity',
            viewBindings: [api_1.Client],
            properties: ['object']
        }),
        angular2_1.View({
            templateUrl: 'templates/cards/activity.html',
            directives: [angular2_1.NgFor, angular2_1.NgIf, angular2_1.NgClass, material_1.Material, remind_1.Remind, router_1.RouterLink]
        }), 
        __metadata('design:paramtypes', [Client])
    ], Activity);
    return Activity;
})();
exports.Activity = Activity;
//# sourceMappingURL=activity.js.map