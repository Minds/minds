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
var router_1 = require('angular2/router');
var api_1 = require('src/services/api');
var material_1 = require('src/directives/material');
var infinite_scroll_1 = require('../../directives/infinite-scroll');
var Notifications = (function () {
    function Notifications(client) {
        this.client = client;
        this.notificatons = [];
        this.moreData = true;
        this.offset = "";
        this.inProgress = false;
        this.load(true);
    }
    Notifications.prototype.load = function (refresh) {
        if (refresh === void 0) { refresh = false; }
        var self = this;
        if (this.inProgress)
            return false;
        if (refresh)
            this.offset = "";
        this.inProgress = true;
        this.client.get('api/v1/notifications', { limit: 12, offset: this.offset })
            .then(function (data) {
            if (!data.notifications) {
                self.moreData = false;
                self.inProgress = false;
                return false;
            }
            if (refresh) {
                self.notifications = data.notifications;
            }
            else {
                if (self.offset)
                    data.notifications.shift();
                for (var _i = 0, _a = data.notifications; _i < _a.length; _i++) {
                    var entity = _a[_i];
                    self.notifications.push(entity);
                }
            }
            self.offset = data['load-next'];
            self.inProgress = false;
        });
    };
    Notifications = __decorate([
        angular2_1.Component({
            selector: 'minds-notifications',
            viewBindings: [api_1.Client]
        }),
        angular2_1.View({
            templateUrl: 'templates/notifications/list.html',
            directives: [angular2_1.NgFor, angular2_1.NgIf, angular2_1.NgSwitch, angular2_1.NgSwitchWhen, angular2_1.NgSwitchDefault, angular2_1.NgClass, router_1.RouterLink, material_1.Material, infinite_scroll_1.InfiniteScroll]
        }), 
        __metadata('design:paramtypes', [Client])
    ], Notifications);
    return Notifications;
})();
exports.Notifications = Notifications;
//# sourceMappingURL=notifications.js.map