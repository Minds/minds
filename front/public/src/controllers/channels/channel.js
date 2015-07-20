if (typeof __decorate !== "function") __decorate = function (decorators, target, key, desc) {
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") return Reflect.decorate(decorators, target, key, desc);
    switch (arguments.length) {
        case 2: return decorators.reduceRight(function(o, d) { return (d && d(o)) || o; }, target);
        case 3: return decorators.reduceRight(function(o, d) { return (d && d(target, key)), void 0; }, void 0);
        case 4: return decorators.reduceRight(function(o, d) { return (d && d(target, key, o)) || o; }, desc);
    }
};
if (typeof __metadata !== "function") __metadata = function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};
if (typeof __param !== "function") __param = function (paramIndex, decorator) {
    return function (target, key) { decorator(target, key, paramIndex); }
};
var angular2_1 = require('angular2/angular2');
var router_1 = require('angular2/router');
var api_1 = require('src/services/api');
var material_1 = require('src/directives/material');
var session_1 = require('../../services/session');
var infinite_scroll_1 = require('../../directives/infinite-scroll');
var activity_1 = require('src/controllers/newsfeed/activity');
var Channel = (function () {
    function Channel(client, router, params) {
        this.client = client;
        this.router = router;
        this.params = params;
        this.session = session_1.SessionFactory.build();
        this.feed = [];
        this.offset = "";
        this.moreData = true;
        this.inProgress = false;
        this.error = "";
        this.username = params.params['username'];
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
            self.loadFeed(true);
        })
            .catch(function () {
            console.log('couldnt load channel');
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
        return this.session.isLoggedIn();
    };
    Channel = __decorate([
        angular2_1.Component({
            selector: 'minds-channel',
            viewInjector: [api_1.Client]
        }),
        angular2_1.View({
            templateUrl: 'templates/channels/channel.html',
            directives: [angular2_1.NgFor, angular2_1.NgIf, material_1.Material, angular2_1.formDirectives, infinite_scroll_1.InfiniteScroll, activity_1.Activity]
        }),
        __param(1, angular2_1.Inject(router_1.Router)),
        __param(2, angular2_1.Inject(router_1.RouteParams)), 
        __metadata('design:paramtypes', [api_1.Client, router_1.Router, router_1.RouteParams])
    ], Channel);
    return Channel;
})();
exports.Channel = Channel;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9jb250cm9sbGVycy9jaGFubmVscy9jaGFubmVsLnRzIl0sIm5hbWVzIjpbIkNoYW5uZWwiLCJDaGFubmVsLmNvbnN0cnVjdG9yIiwiQ2hhbm5lbC5sb2FkIiwiQ2hhbm5lbC5sb2FkRmVlZCIsIkNoYW5uZWwuaXNPd25lciJdLCJtYXBwaW5ncyI6Ijs7Ozs7Ozs7Ozs7Ozs7QUFBQSx5QkFBb0UsbUJBQW1CLENBQUMsQ0FBQTtBQUN4Rix1QkFBb0MsaUJBQWlCLENBQUMsQ0FBQTtBQUN0RCxvQkFBdUIsa0JBQWtCLENBQUMsQ0FBQTtBQUMxQyx5QkFBeUIseUJBQXlCLENBQUMsQ0FBQTtBQUNuRCx3QkFBK0Isd0JBQXdCLENBQUMsQ0FBQTtBQUN4RCxnQ0FBK0Isa0NBQWtDLENBQUMsQ0FBQTtBQUNsRSx5QkFBeUIsbUNBQW1DLENBQUMsQ0FBQTtBQUU3RDtJQW1CRUEsaUJBQW1CQSxNQUFjQSxFQUNSQSxNQUFjQSxFQUNUQSxNQUFtQkE7UUFGOUJDLFdBQU1BLEdBQU5BLE1BQU1BLENBQVFBO1FBQ1JBLFdBQU1BLEdBQU5BLE1BQU1BLENBQVFBO1FBQ1RBLFdBQU1BLEdBQU5BLE1BQU1BLENBQWFBO1FBWGpEQSxZQUFPQSxHQUFHQSx3QkFBY0EsQ0FBQ0EsS0FBS0EsRUFBRUEsQ0FBQ0E7UUFHakNBLFNBQUlBLEdBQW1CQSxFQUFFQSxDQUFDQTtRQUMxQkEsV0FBTUEsR0FBWUEsRUFBRUEsQ0FBQ0E7UUFDckJBLGFBQVFBLEdBQWFBLElBQUlBLENBQUNBO1FBQzFCQSxlQUFVQSxHQUFhQSxLQUFLQSxDQUFDQTtRQUM3QkEsVUFBS0EsR0FBV0EsRUFBRUEsQ0FBQ0E7UUFNZkEsSUFBSUEsQ0FBQ0EsUUFBUUEsR0FBR0EsTUFBTUEsQ0FBQ0EsTUFBTUEsQ0FBQ0EsVUFBVUEsQ0FBQ0EsQ0FBQ0E7UUFDMUNBLElBQUlBLENBQUNBLElBQUlBLEVBQUVBLENBQUNBO0lBQ2hCQSxDQUFDQTtJQUVERCxzQkFBSUEsR0FBSkE7UUFDRUUsSUFBSUEsSUFBSUEs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QzFCQSxDQUFDQSxDQUFDQTthQUNEQSxLQUFLQSxDQUFDQSxVQUFTQSxDQUFDQTtZQUNmLE9BQU8sQ0FBQyxHQUFHLENBQUMsQ0FBQyxDQUFDLENBQUM7UUFDakIsQ0FBQyxDQUFDQSxDQUFDQTtJQUNUQSxDQUFDQTtJQUVESCx5QkFBT0EsR0FBUEE7UUFDRUksTUFBTUEsQ0FBQ0EsSUFBSUEsQ0FBQ0EsT0FBT0EsQ0FBQ0EsVUFBVUEsRUFBRUEsQ0FBQ0E7SUFDbkNBLENBQUNBO0lBL0VISjtRQUFDQSxvQkFBU0EsQ0FBQ0E7WUFDVEEsUUFBUUEsRUFBRUEsZUFBZUE7WUFDekJBLFlBQVlBLEVBQUVBLENBQUVBLFlBQU1BLENBQUVBO1NBQ3pCQSxDQUFDQTtRQUNEQSxlQUFJQSxDQUFDQTtZQUNKQSxXQUFXQSxFQUFFQSxpQ0FBaUNBO1lBQzlDQSxVQUFVQSxFQUFFQSxDQUFFQSxnQkFBS0EsRUFBRUEsZUFBSUEsRUFBRUEsbUJBQVFBLEVBQUVBLHlCQUFjQSxFQUFFQSxnQ0FBY0EsRUFBRUEsbUJBQVFBLENBQUVBO1NBQ2hGQSxDQUFDQTtRQWFFQSxXQUFDQSxpQkFBTUEsQ0FBQ0EsZUFBTUEsQ0FBQ0EsQ0FBQUE7UUFDZkEsV0FBQ0EsaUJBQU1BLENBQUNBLG9CQUFXQSxDQUFDQSxDQUFBQTs7Z0JBNER2QkE7SUFBREEsY0FBQ0E7QUFBREEsQ0FqRkEsQUFpRkNBLElBQUE7QUF4RVksZUFBTyxVQXdFbkIsQ0FBQSIsImZpbGUiOiJzcmMvY29udHJvbGxlcnMvY2hhbm5lbHMvY2hhbm5lbC5qcyIsInNvdXJjZXNDb250ZW50IjpbImltcG9ydCB7IENvbXBvbmVudCwgVmlldywgTmdGb3IsIE5nSWYsIEluamVjdCwgZm9ybURpcmVjdGl2ZXN9IGZyb20gJ2FuZ3VsYXIyL2FuZ3VsYXIyJztcbmltcG9ydCB7IFJvdXRlciwgUm91dGVQYXJhbXMgfSBmcm9tICdhbmd1bGFyMi9yb3V0ZXInO1xuaW1wb3J0IHsgQ2xpZW50IH0gZnJvbSAnc3JjL3NlcnZpY2VzL2FwaSc7XG5pbXBvcnQgeyBNYXRlcmlhbCB9IGZyb20gJ3NyYy9kaXJlY3RpdmVzL21hdGVyaWFsJztcbmltcG9ydCB7IFNlc3Npb25GYWN0b3J5IH0gZnJvbSAnLi4vLi4vc2VydmljZXMvc2Vzc2lvbic7XG5pbXBvcnQgeyBJbmZpbml0ZVNjcm9sbCB9IGZyb20gJy4uLy4uL2RpcmVjdGl2ZXMvaW5maW5pdGUtc2Nyb2xsJztcbmltcG9ydCB7IEFjdGl2aXR5IH0gZnJvbSAnc3JjL2NvbnRyb2xsZXJzL25ld3NmZWVkL2FjdGl2aXR5JztcblxuQENvbXBvbmVudCh7XG4gIHNlbGVjdG9yOiAnbWluZHMtY2hhbm5lbCcsXG4gIHZpZXdJbmplY3RvcjogWyBDbGllbnQgXVxufSlcbkBWaWV3KHtcbiAgdGVtcGxhdGVVcmw6ICd0ZW1wbGF0ZXMvY2hhbm5lbHMvY2hhbm5lbC5odG1sJyxcbiAgZGlyZWN0aXZlczogWyBOZ0ZvciwgTmdJZiwgTWF0ZXJpYWwsIGZvcm1EaXJlY3RpdmVzLCBJbmZpbml0ZVNjcm9sbCwgQWN0aXZpdHkgXVxufSlcblxuZXhwb3J0IGNsYXNzIENoYW5uZWwge1xuICBzZXNzaW9uID0gU2Vzc2lvbkZhY3RvcnkuYnVpbGQoKTtcbiAgdXNlcm5hbWUgOiBzdHJpbmc7XG4gIHVzZXIgOiBPYmplY3Q7XG4gIGZlZWQgOiBBcnJheTxPYmplY3Q+ID0gW107XG4gIG9mZnNldCA6IHN0cmluZyA9IFwiXCI7XG4gIG1vcmVEYXRhIDogYm9vbGVhbiA9IHRydWU7XG4gIGluUHJvZ3Jlc3MgOiBib29sZWFuID0gZmFsc2U7XG4gIGVycm9yOiBzdHJpbmcgPSBcIlwiO1xuXG4gIGNvbnN0cnVjdG9yKHB1YmxpYyBjbGllbnQ6IENsaWVudCxcbiAgICBASW5qZWN0KFJvdXRlcikgcHVibGljIHJvdXRlcjogUm91dGVyLFxuICAgIEBJbmplY3QoUm91dGVQYXJhbXMpIHB1YmxpYyBwYXJhbXM6IFJvdXRlUGFyYW1zXG4gICAgKXtcbiAgICAgIHRoaXMudXNlcm5hbWUgPSBwYXJhbXMucGFyYW1zWyd1c2VybmFtZSddO1xuICAgICAgdGhpcy5sb2FkKCk7XG4gIH1cblxuICBsb2FkKCl7XG4gICAgdmFyIHNlbGYgPSB0aGlzO1xuICAgIHRoaXMuY2xpZW50LmdldCgnYXBpL3YxL2NoYW5uZWwvJyArIHRoaXMudXNlcm5hbWUsIHt9KVxuICAgICAgICAgICAgICAudGhlbigoZGF0YSA6IEFycmF5PGFueT4pID0+IHtcbiAgICAgICAgICAgICAgICBpZihkYXRhLnN0YXR1cyAhPSBcInN1Y2Nlc3NcIil7XG4gICAgICAgICAgICAgICAgICBzZWxmLmVycm9yID0gZGF0YS5tZXNzYWdlO1xuICAgICAgICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBzZWxmLnVzZXIgPSBkYXRhLmNoYW5uZWw7XG4gICAgICAgICAgICAgICAgc2VsZi5sb2FkRmVlZCh0cnVlKTtcbiAgICAgICAgICAgICAgICB9KVxuICAgICAgICAgICAgICAuY2F0Y2goKCkgPT4ge1xuICAgICAgICAgICAgICAgIGNvbnNvbGUubG9nKCdjb3VsZG50IGxvYWQgY2hhbm5lbCcpO1xuICAgICAgICAgICAgICAgIH0pO1xuICB9XG5cbiAgbG9hZEZlZWQocmVmcmVzaCA6IGJvb2xlYW4gPSBmYWxzZSl7XG4gICAgdmFyIHNlbGYgPSB0aGlzO1xuICAgIGlmKHRoaXMuaW5Qcm9ncmVzcyl7XG4gICAgICAvL2NvbnNvbGUubG9nKCdhbHJlYWR5IGxvYWRpbmcgbW9yZS4uJyk7XG4gICAgICByZXR1cm4gZmFsc2U7XG4gICAgfVxuXG4gICAgaWYocmVmcmVzaCl7XG4gICAgICB0aGlzLm9mZnNldCA9IFwiXCI7XG4gICAgfVxuXG4gICAgdGhpcy5pblByb2dyZXNzID0gdHJ1ZTtcblxuICAgIHRoaXMuY2xpZW50LmdldCgnYXBpL3YxL25ld3NmZWVkL3BlcnNvbmFsLycgKyB0aGlzLnVzZXIuZ3VpZCwge2xpbWl0OjEyLCBvZmZzZXQ6IHRoaXMub2Zmc2V0fSwge2NhY2hlOiB0cnVlfSlcbiAgICAgICAgLnRoZW4oKGRhdGEgOiBNaW5kc0FjdGl2aXR5T2JqZWN0KSA9PiB7XG4gICAgICAgICAgaWYoIWRhdGEuYWN0aXZpdHkpe1xuICAgICAgICAgICAgc2VsZi5tb3JlRGF0YSA9IGZhbHNlO1xuICAgICAgICAgICAgc2VsZi5pblByb2dyZXNzID0gZmFsc2U7XG4gICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgICAgfVxuICAgICAgICAgIGlmKHNlbGYuZmVlZCAmJiAhcmVmcmVzaCl7XG4gICAgICAgICAgICBmb3IobGV0IGFjdGl2aXR5IG9mIGRhdGEuYWN0aXZpdHkpXG4gICAgICAgICAgICAgIHNlbGYuZmVlZC5wdXNoKGFjdGl2aXR5KTtcbiAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgc2VsZi5mZWVkID0gZGF0YS5hY3Rpdml0eTtcbiAgICAgICAgICB9XG4gICAgICAgICAgc2VsZi5vZmZzZXQgPSBkYXRhWydsb2FkLW5leHQnXTtcbiAgICAgICAgICBzZWxmLmluUHJvZ3Jlc3MgPSBmYWxzZTtcbiAgICAgICAgfSlcbiAgICAgICAgLmNhdGNoKGZ1bmN0aW9uKGUpe1xuICAgICAgICAgIGNvbnNvbGUubG9nKGUpO1xuICAgICAgICB9KTtcbiAgfVxuXG4gIGlzT3duZXIoKXtcbiAgICByZXR1cm4gdGhpcy5zZXNzaW9uLmlzTG9nZ2VkSW4oKTtcbiAgfVxuXG59XG4iXSwic291cmNlUm9vdCI6Ii9zb3VyY2UvIn0=