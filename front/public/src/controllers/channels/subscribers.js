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
var ChannelSubscribers = (function () {
    function ChannelSubscribers(client, router, params) {
        this.client = client;
        this.router = router;
        this.params = params;
        this.session = session_1.SessionFactory.build();
        this.users = [];
        this.offset = "";
        this.moreData = true;
        this.inProgress = false;
        this.guid = params.params['guid'];
        this.load();
    }
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
            viewBindings: [api_1.Client]
        }),
        angular2_1.View({
            templateUrl: 'templates/channels/subscribers.html',
            directives: [angular2_1.NgFor, angular2_1.NgIf, material_1.Material, infinite_scroll_1.InfiniteScroll]
        }),
        __param(1, angular2_1.Inject(router_1.Router)),
        __param(2, angular2_1.Inject(router_1.RouteParams)), 
        __metadata('design:paramtypes', [api_1.Client, router_1.Router, router_1.RouteParams])
    ], ChannelSubscribers);
    return ChannelSubscribers;
})();
exports.ChannelSubscribers = ChannelSubscribers;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9jb250cm9sbGVycy9jaGFubmVscy9zdWJzY3JpYmVycy50cyJdLCJuYW1lcyI6WyJDaGFubmVsU3Vic2NyaWJlcnMiLCJDaGFubmVsU3Vic2NyaWJlcnMuY29uc3RydWN0b3IiLCJDaGFubmVsU3Vic2NyaWJlcnMubG9hZCJdLCJtYXBwaW5ncyI6Ijs7Ozs7Ozs7Ozs7Ozs7QUFBQSx5QkFBcUUsbUJBQW1CLENBQUMsQ0FBQTtBQUN6Rix1QkFBb0MsaUJBQWlCLENBQUMsQ0FBQTtBQUN0RCxvQkFBdUIsa0JBQWtCLENBQUMsQ0FBQTtBQUMxQyx5QkFBeUIseUJBQXlCLENBQUMsQ0FBQTtBQUNuRCx3QkFBK0Isd0JBQXdCLENBQUMsQ0FBQTtBQUN4RCxnQ0FBK0Isa0NBQWtDLENBQUMsQ0FBQTtBQUVsRTtJQWtCRUEsNEJBQW1CQSxNQUFjQSxFQUNSQSxNQUFjQSxFQUNUQSxNQUFtQkE7UUFGOUJDLFdBQU1BLEdBQU5BLE1BQU1BLENBQVFBO1FBQ1JBLFdBQU1BLEdBQU5BLE1BQU1BLENBQVFBO1FBQ1RBLFdBQU1BLEdBQU5BLE1BQU1BLENBQWFBO1FBVmpEQSxZQUFPQSxHQUFHQSx3QkFBY0EsQ0FBQ0EsS0FBS0EsRUFBRUEsQ0FBQ0E7UUFFakNBLFVBQUtBLEdBQWdCQSxFQUFFQSxDQUFDQTtRQUV4QkEsV0FBTUEsR0FBWUEsRUFBRUEsQ0FBQ0E7UUFDckJBLGFBQVFBLEdBQWFBLElBQUlBLENBQUNBO1FBQzFCQSxlQUFVQSxHQUFhQSxLQUFLQSxDQUFDQTtRQU16QkEsSUFBSUEsQ0FBQ0EsSUFBSUEsR0FBR0EsTUFBTUEsQ0FBQ0EsTUFBTUEsQ0FBQ0EsTUFBTUEsQ0FBQ0EsQ0FBQ0E7UUFDbENBLElBQUlBLENBQUNBLElBQUlBLEVBQUVBLENBQUNBO0lBQ2hCQSxDQUFDQTtJQUVERCxpQ0FBSUEsR0FBSkE7UUFDRUUsSUFBSUEsSUFBSUEsR0FBR0EsSUFBSUEsQ0FBQ0E7UUFDaEJBLElBQUlBLENBQUNBLFVBQVVBLEdBQUdBLElBQUlBLENBQUNBO1FBQ3ZCQSxJQUFJQSxDQUFDQSxNQUFNQSxDQUFDQSxHQUFHQSxDQUFDQSwrQkFBK0JBLEdBQUdBLElBQUlBLENBQUNBLElBQUlBLEVBQUVBLEVBQUVBLENBQUNBO2FBQzdEQSxJQUFJQSxDQUFDQSxVQUFDQSxRQUFjQTtZQUVuQkEsRUFBRUEsQ0FBQUEsQ0FBQ0EsUUFBUUEsQ0FBQ0EsTUFBTUEsSUFBSUEsU0FBU0EsQ0FBQ0EsQ0FBQUEsQ0FBQ0E7Z0JBQy9CQSxNQUFNQSxDQUFDQSxLQUFLQSxDQUFDQTtZQUNmQSxDQUFDQTtZQUVEQSxFQUFFQSxDQUFBQSxDQUFDQSxJQUFJQSxDQUFDQSxNQUFNQSxDQUFDQSxDQUFBQSxDQUFDQSxDQUFBQSxDQUFDQTtZQUNqQkEsR0FBR0EsQ0FBQUEsQ0FBYUEsVUFBY0EsRUFBZEEsS0FBQUEsUUFBUUEsQ0FBQ0EsS0FBS0EsRUFBMUJBLGNBQVFBLEVBQVJBLElBQTBCQSxDQUFDQTtnQkFBM0JBLElBQUlBLElBQUlBLFNBQUFBO2dCQUNWQSxJQUFJQSxDQUFDQSxLQUFLQSxDQUFDQSxJQUFJQSxDQUFDQSxJQUFJQSxDQUFDQSxDQUFDQTthQUN2QkE7WUFFREEsSUFBSUEsQ0FBQ0EsTUFBTUEsR0FBR0EsUUFBUUEsQ0FBQ0EsV0FBV0EsQ0FBQ0EsQ0FBQ0E7WUFDcENBLElBQUlBLENBQUNBLFVBQVVBLEdBQUdBLEtBQUtBLENBQUNBO1FBQzFCQSxDQUFDQSxDQUFDQTthQUNEQSxLQUFLQSxDQUFDQSxVQUFDQSxDQUFDQTtZQUNQQSxPQUFPQSxDQUFDQSxHQUFHQSxDQUFDQSxzQkFBc0JBLEVBQUVBLENBQUNBLENBQUNBLENBQUNBO1FBQ3pDQSxDQUFDQSxDQUFDQSxDQUFDQTtJQUNQQSxDQUFDQTtJQS9DSEY7UUFBQ0Esb0JBQVNBLENBQUNBO1lBQ1RBLFFBQVFBLEVBQUVBLDJCQUEyQkE7WUFDckNBLFlBQVlBLEVBQUVBLENBQUVBLFlBQU1BLENBQUVBO1NBQ3pCQSxDQUFDQTtRQUNEQSxlQUFJQSxDQUFDQTtZQUNKQSxXQUFXQSxFQUFFQSxxQ0FBcUNBO1lBQ2xEQSxVQUFVQSxFQUFFQSxDQUFFQSxnQkFBS0EsRUFBRUEsZUFBSUEsRUFBRUEsbUJBQVFBLEVBQUVBLGdDQUFjQSxDQUFFQTtTQUN0REEsQ0FBQ0E7UUFZRUEsV0FBQ0EsaUJBQU1BLENBQUNBLGVBQU1BLENBQUNBLENBQUFBO1FBQ2ZBLFdBQUNBLGlCQUFNQSxDQUFDQSxvQkFBV0EsQ0FBQ0EsQ0FBQUE7OzJCQTZCdkJBO0lBQURBLHlCQUFDQTtBQUFEQSxDQWpEQSxBQWlEQ0EsSUFBQTtBQXhDWSwwQkFBa0IscUJBd0M5QixDQUFBIiwiZmlsZSI6InNyYy9jb250cm9sbGVycy9jaGFubmVscy9zdWJzY3JpYmVycy5qcyIsInNvdXJjZXNDb250ZW50IjpbImltcG9ydCB7IENvbXBvbmVudCwgVmlldywgTmdGb3IsIE5nSWYsIEluamVjdCwgRk9STV9ESVJFQ1RJVkVTfSBmcm9tICdhbmd1bGFyMi9hbmd1bGFyMic7XG5pbXBvcnQgeyBSb3V0ZXIsIFJvdXRlUGFyYW1zIH0gZnJvbSAnYW5ndWxhcjIvcm91dGVyJztcbmltcG9ydCB7IENsaWVudCB9IGZyb20gJ3NyYy9zZXJ2aWNlcy9hcGknO1xuaW1wb3J0IHsgTWF0ZXJpYWwgfSBmcm9tICdzcmMvZGlyZWN0aXZlcy9tYXRlcmlhbCc7XG5pbXBvcnQgeyBTZXNzaW9uRmFjdG9yeSB9IGZyb20gJy4uLy4uL3NlcnZpY2VzL3Nlc3Npb24nO1xuaW1wb3J0IHsgSW5maW5pdGVTY3JvbGwgfSBmcm9tICcuLi8uLi9kaXJlY3RpdmVzL2luZmluaXRlLXNjcm9sbCc7XG5cbkBDb21wb25lbnQoe1xuICBzZWxlY3RvcjogJ21pbmRzLWNoYW5uZWwtc3Vic2NyaWJlcnMnLFxuICB2aWV3QmluZGluZ3M6IFsgQ2xpZW50IF1cbn0pXG5AVmlldyh7XG4gIHRlbXBsYXRlVXJsOiAndGVtcGxhdGVzL2NoYW5uZWxzL3N1YnNjcmliZXJzLmh0bWwnLFxuICBkaXJlY3RpdmVzOiBbIE5nRm9yLCBOZ0lmLCBNYXRlcmlhbCwgSW5maW5pdGVTY3JvbGwgXVxufSlcblxuZXhwb3J0IGNsYXNzIENoYW5uZWxTdWJzY3JpYmVycyB7XG4gIHNlc3Npb24gPSBTZXNzaW9uRmFjdG9yeS5idWlsZCgpO1xuICBndWlkIDogc3RyaW5nO1xuICB1c2VycyA6IEFycmF5PGFueT4gPSBbXTtcblxuICBvZmZzZXQgOiBzdHJpbmcgPSBcIlwiO1xuICBtb3JlRGF0YSA6IGJvb2xlYW4gPSB0cnVlO1xuICBpblByb2dyZXNzIDogYm9vbGVhbiA9IGZhbHNlO1xuXG4gIGNvbnN0cnVjdG9yKHB1YmxpYyBjbGllbnQ6IENsaWVudCxcbiAgICBASW5qZWN0KFJvdXRlcikgcHVibGljIHJvdXRlcjogUm91dGVyLFxuICAgIEBJbmplY3QoUm91dGVQYXJhbXMpIHB1YmxpYyBwYXJhbXM6IFJvdXRlUGFyYW1zXG4gICAgKXtcbiAgICAgIHRoaXMuZ3VpZCA9IHBhcmFtcy5wYXJhbXNbJ2d1aWQnXTtcbiAgICAgIHRoaXMubG9hZCgpO1xuICB9XG5cbiAgbG9hZCgpe1xuICAgIHZhciBzZWxmID0gdGhpcztcbiAgICB0aGlzLmluUHJvZ3Jlc3MgPSB0cnVlO1xuICAgIHRoaXMuY2xpZW50LmdldCgnYXBpL3YxL3N1YnNjcmliZS9zdWJzY3JpYmVycy8nICsgdGhpcy5ndWlkLCB7fSlcbiAgICAgIC50aGVuKChyZXNwb25zZSA6IGFueSkgPT4ge1xuXG4gICAgICAgIGlmKHJlc3BvbnNlLnN0YXR1cyAhPSBcInN1Y2Nlc3NcIil7XG4gICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICB9XG5cbiAgICAgICAgaWYoc2VsZi5vZmZzZXQpe31cbiAgICAgICAgZm9yKGxldCB1c2VyIG9mIHJlc3BvbnNlLnVzZXJzKXtcbiAgICAgICAgICBzZWxmLnVzZXJzLnB1c2godXNlcik7XG4gICAgICAgIH1cblxuICAgICAgICBzZWxmLm9mZnNldCA9IHJlc3BvbnNlWydsb2FkLW5leHQnXTtcbiAgICAgICAgc2VsZi5pblByb2dyZXNzID0gZmFsc2U7XG4gICAgICB9KVxuICAgICAgLmNhdGNoKChlKSA9PiB7XG4gICAgICAgIGNvbnNvbGUubG9nKCdjb3VsZG50IGxvYWQgY2hhbm5lbCcsIGUpO1xuICAgICAgfSk7XG4gIH1cblxufVxuIl0sInNvdXJjZVJvb3QiOiIvc291cmNlLyJ9