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
var router_1 = require("angular2/router");
var api_1 = require('src/services/api');
var session_1 = require('src/services/session');
var material_1 = require('src/directives/material');
var infinite_scroll_1 = require('../../directives/infinite-scroll');
var Groups = (function () {
    function Groups(client, router, params) {
        this.client = client;
        this.router = router;
        this.params = params;
        this.offset = "";
        this.moreDate = true;
        this.inProgress = false;
        this.groups = [];
        this.session = session_1.SessionFactory.build();
        this._filter = "featured";
        this._filter = params.params['filter'];
        this.minds = window.Minds;
        this.load();
    }
    Groups.prototype.load = function (refresh) {
        if (refresh === void 0) { refresh = false; }
        var self = this;
        this.inProgress = true;
        this.client.get('api/v1/groups/' + this._filter, { limit: 12, offset: this.offset })
            .then(function (response) {
            if (!response.groups) {
                self.moreData = false;
                self.inProgress = false;
                return false;
            }
            if (refresh) {
                self.groups = response.groups;
            }
            else {
                if (self.offset)
                    response.groups.shift();
                for (var _i = 0, _a = response.groups; _i < _a.length; _i++) {
                    var group = _a[_i];
                    self.groups.push(group);
                }
            }
            self.offset = response['load-next'];
            self.inProgress = false;
        })
            .catch(function (e) {
        });
    };
    Groups = __decorate([
        angular2_1.Component({
            selector: 'minds-groups',
            viewBindings: [api_1.Client]
        }),
        angular2_1.View({
            templateUrl: 'templates/plugins/groups/groups.html',
            directives: [angular2_1.NgFor, angular2_1.NgIf, angular2_1.NgClass, material_1.Material, router_1.RouterLink, infinite_scroll_1.InfiniteScroll]
        }),
        __param(1, angular2_1.Inject(router_1.Router)),
        __param(2, angular2_1.Inject(router_1.RouteParams)), 
        __metadata('design:paramtypes', [api_1.Client, router_1.Router, router_1.RouteParams])
    ], Groups);
    return Groups;
})();
exports.Groups = Groups;
var groups_profile_1 = require('./groups-profile');
exports.GroupsProfile = groups_profile_1.GroupsProfile;
var groups_creator_1 = require('./groups-creator');
exports.GroupsCreator = groups_creator_1.GroupsCreator;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9wbHVnaW5zL2dyb3Vwcy9ncm91cHMudHMiXSwibmFtZXMiOlsiR3JvdXBzIiwiR3JvdXBzLmNvbnN0cnVjdG9yIiwiR3JvdXBzLmxvYWQiXSwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7Ozs7O0FBQUEseUJBQTBGLG1CQUFtQixDQUFDLENBQUE7QUFDOUcsdUJBQWdELGlCQUFpQixDQUFDLENBQUE7QUFFbEUsb0JBQXVCLGtCQUFrQixDQUFDLENBQUE7QUFDMUMsd0JBQStCLHNCQUFzQixDQUFDLENBQUE7QUFDdEQseUJBQXlCLHlCQUF5QixDQUFDLENBQUE7QUFDbkQsZ0NBQStCLGtDQUFrQyxDQUFDLENBQUE7QUFJbEU7SUFrQkVBLGdCQUFtQkEsTUFBY0EsRUFDUkEsTUFBY0EsRUFDVEEsTUFBbUJBO1FBRjlCQyxXQUFNQSxHQUFOQSxNQUFNQSxDQUFRQTtRQUNSQSxXQUFNQSxHQUFOQSxNQUFNQSxDQUFRQTtRQUNUQSxXQUFNQSxHQUFOQSxNQUFNQSxDQUFhQTtRQVRqREEsV0FBTUEsR0FBWUEsRUFBRUEsQ0FBQ0E7UUFDckJBLGFBQVFBLEdBQWFBLElBQUlBLENBQUNBO1FBQzFCQSxlQUFVQSxHQUFhQSxLQUFLQSxDQUFDQTtRQUM3QkEsV0FBTUEsR0FBZ0JBLEVBQUVBLENBQUNBO1FBQ3pCQSxZQUFPQSxHQUFHQSx3QkFBY0EsQ0FBQ0EsS0FBS0EsRUFBRUEsQ0FBQ0E7UUFDakNBLFlBQU9BLEdBQVlBLFVBQVVBLENBQUNBO1FBTTFCQSxJQUFJQSxDQUFDQSxPQUFPQSxHQUFHQSxNQUFNQSxDQUFDQSxNQUFNQSxDQUFDQSxRQUFRQSxDQUFDQSxDQUFDQTtRQUN2Q0EsSUFBSUEsQ0FBQ0EsS0FBS0EsR0FBR0EsTUFBTUEsQ0FBQ0EsS0FBS0EsQ0FBQ0E7UUFDMUJBLElBQUlBLENBQUNBLElBQUlBLEVBQUVBLENBQUNBO0lBQ2hCQSxDQUFDQTtJQUVERCxxQkFBSUEsR0FBSkEsVUFBS0EsT0FBeUJBO1FBQXpCRSx1QkFBeUJBLEdBQXpCQSxlQUF5QkE7UUFDNUJBLElBQUlBLElBQUlBLEdBQUdBLElBQUlBLENBQUNBO1FBQ2hCQSxJQUFJQSxDQUFDQSxVQUFVQSxHQUFHQSxJQUFJQSxDQUFDQTtRQUN2QkEsSUFBSUEsQ0FBQ0EsTUFBTUEsQ0FBQ0EsR0FBR0EsQ0FBQ0EsZ0JBQWdCQSxHQUFHQSxJQUFJQSxDQUFDQSxPQUFPQSxFQUFFQSxFQUFFQSxLQUFLQSxFQUFFQSxFQUFFQSxFQUFFQSxNQUFNQSxFQUFFQSxJQUFJQSxDQUFDQSxNQUFNQSxFQUFDQSxDQUFDQTthQUNoRkEsSUFBSUEsQ0FBQ0EsVUFBQ0EsUUFBUUE7WUFFYkEsRUFBRUEsQ0FBQUEsQ0FBQ0EsQ0FBQ0EsUUFBUUEsQ0FBQ0EsTUFBTUEsQ0FBQ0EsQ0FBQUEsQ0FBQ0E7Z0JBQ25CQSxJQUFJQSxDQUFDQSxRQUFRQSxHQUFHQSxLQUFLQSxDQUFDQTtnQkFDdEJBLElBQUlBLENBQUNBLFVBQVVBLEdBQUdBLEtBQUtBLENBQUNBO2dCQUN4QkEsTUFBTUEsQ0FBQ0EsS0FBS0EsQ0FBQ0E7WUFDZkEsQ0FBQ0E7WUFFREEsRUFBRUEsQ0FBQUEsQ0FBQ0EsT0FBT0EsQ0FBQ0EsQ0FBQUEsQ0FBQ0E7Z0JBQ1ZBLElBQUlBLENBQUNBLE1BQU1BLEdBQUdBLFFBQVFBLENBQUNBLE1BQU1BLENBQUNBO1lBQ2hDQSxDQUFDQTtZQUFDQSxJQUFJQSxDQUFDQSxDQUFDQTtnQkFDTkEsRUFBRUEsQ0FBQUEsQ0FBQ0EsSUFBSUEsQ0FBQ0EsTUFBTUEsQ0FBQ0E7b0JBQ2JBLFFBQVFBLENBQUNBLE1BQU1BLENBQUNBLEtBQUtBLEVBQUVBLENBQUNBO2dCQUMxQkEsR0FBR0EsQ0FBQUEsQ0FBY0EsVUFBZUEsRUFBZkEsS0FBQUEsUUFBUUEsQ0FBQ0EsTUFBTUEsRUFBNUJBLGNBQVNBLEVBQVRBLElBQTRCQSxDQUFDQTtvQkFBN0JBLElBQUlBLEtBQUtBLFNBQUFBO29CQUNYQSxJQUFJQSxDQUFDQSxNQUFNQSxDQUFDQSxJQUFJQSxDQUFDQSxLQUFLQSxDQUFDQSxDQUFDQTtpQkFBQUE7WUFDNUJBLENBQUNBO1lBRURBLElBQUlBLENBQUNBLE1BQU1BLEdBQUdBLFFBQVFBLENBQUNBLFdBQVdBLENBQUNBLENBQUNBO1lBQ3BDQSxJQUFJQSxDQUFDQSxVQUFVQSxHQUFHQSxLQUFLQSxDQUFDQTtRQUMxQkEsQ0FBQ0EsQ0FBQ0E7YUFDREEsS0FBS0EsQ0FBQ0EsVUFBQ0EsQ0FBQ0E7UUFFVEEsQ0FBQ0EsQ0FBQ0EsQ0FBQ0E7SUFDUEEsQ0FBQ0E7SUF0REhGO1FBQUNBLG9CQUFTQSxDQUFDQTtZQUNUQSxRQUFRQSxFQUFFQSxjQUFjQTtZQUN4QkEsWUFBWUEsRUFBRUEsQ0FBRUEsWUFBTUEsQ0FBRUE7U0FDekJBLENBQUNBO1FBQ0RBLGVBQUlBLENBQUNBO1lBQ0pBLFdBQVdBLEVBQUVBLHNDQUFzQ0E7WUFDbkRBLFVBQVVBLEVBQUVBLENBQUVBLGdCQUFLQSxFQUFFQSxlQUFJQSxFQUFFQSxrQkFBT0EsRUFBRUEsbUJBQVFBLEVBQUVBLG1CQUFVQSxFQUFFQSxnQ0FBY0EsQ0FBRUE7U0FDM0VBLENBQUNBO1FBWUVBLFdBQUNBLGlCQUFNQSxDQUFDQSxlQUFNQSxDQUFDQSxDQUFBQTtRQUNmQSxXQUFDQSxpQkFBTUEsQ0FBQ0Esb0JBQVdBLENBQUNBLENBQUFBOztlQW9DdkJBO0lBQURBLGFBQUNBO0FBQURBLENBeERBLEFBd0RDQSxJQUFBO0FBL0NZLGNBQU0sU0ErQ2xCLENBQUE7QUFFRCwrQkFBOEIsa0JBQWtCLENBQUM7QUFBeEMsdURBQXdDO0FBQ2pELCtCQUE4QixrQkFBa0IsQ0FBQztBQUF4Qyx1REFBd0MiLCJmaWxlIjoic3JjL3BsdWdpbnMvZ3JvdXBzL2dyb3Vwcy5qcyIsInNvdXJjZXNDb250ZW50IjpbImltcG9ydCB7IENvbXBvbmVudCwgVmlldywgTmdGb3IsIE5nSWYsIE5nQ2xhc3MsIE9ic2VydmFibGUsIEluamVjdCwgRk9STV9ESVJFQ1RJVkVTfSBmcm9tICdhbmd1bGFyMi9hbmd1bGFyMic7XG5pbXBvcnQgeyBSb3V0ZXJMaW5rLCBSb3V0ZXIsIFJvdXRlUGFyYW1zIH0gZnJvbSBcImFuZ3VsYXIyL3JvdXRlclwiO1xuXG5pbXBvcnQgeyBDbGllbnQgfSBmcm9tICdzcmMvc2VydmljZXMvYXBpJztcbmltcG9ydCB7IFNlc3Npb25GYWN0b3J5IH0gZnJvbSAnc3JjL3NlcnZpY2VzL3Nlc3Npb24nO1xuaW1wb3J0IHsgTWF0ZXJpYWwgfSBmcm9tICdzcmMvZGlyZWN0aXZlcy9tYXRlcmlhbCc7XG5pbXBvcnQgeyBJbmZpbml0ZVNjcm9sbCB9IGZyb20gJy4uLy4uL2RpcmVjdGl2ZXMvaW5maW5pdGUtc2Nyb2xsJztcblxuaW1wb3J0IHsgR3JvdXBzQ3JlYXRvciB9IGZyb20gJy4vZ3JvdXBzLWNyZWF0b3InO1xuXG5AQ29tcG9uZW50KHtcbiAgc2VsZWN0b3I6ICdtaW5kcy1ncm91cHMnLFxuICB2aWV3QmluZGluZ3M6IFsgQ2xpZW50IF1cbn0pXG5AVmlldyh7XG4gIHRlbXBsYXRlVXJsOiAndGVtcGxhdGVzL3BsdWdpbnMvZ3JvdXBzL2dyb3Vwcy5odG1sJyxcbiAgZGlyZWN0aXZlczogWyBOZ0ZvciwgTmdJZiwgTmdDbGFzcywgTWF0ZXJpYWwsIFJvdXRlckxpbmssIEluZmluaXRlU2Nyb2xsIF1cbn0pXG5cbmV4cG9ydCBjbGFzcyBHcm91cHMge1xuXG4gIG9mZnNldCA6IHN0cmluZyA9IFwiXCI7XG4gIG1vcmVEYXRlIDogYm9vbGVhbiA9IHRydWU7XG4gIGluUHJvZ3Jlc3MgOiBib29sZWFuID0gZmFsc2U7XG4gIGdyb3VwcyA6IEFycmF5PGFueT4gPSBbXTtcbiAgc2Vzc2lvbiA9IFNlc3Npb25GYWN0b3J5LmJ1aWxkKCk7XG4gIF9maWx0ZXIgOiBzdHJpbmcgPSBcImZlYXR1cmVkXCI7XG5cbiAgY29uc3RydWN0b3IocHVibGljIGNsaWVudDogQ2xpZW50LFxuICAgIEBJbmplY3QoUm91dGVyKSBwdWJsaWMgcm91dGVyOiBSb3V0ZXIsXG4gICAgQEluamVjdChSb3V0ZVBhcmFtcykgcHVibGljIHBhcmFtczogUm91dGVQYXJhbXNcbiAgICApe1xuICAgICAgdGhpcy5fZmlsdGVyID0gcGFyYW1zLnBhcmFtc1snZmlsdGVyJ107XG4gICAgICB0aGlzLm1pbmRzID0gd2luZG93Lk1pbmRzO1xuICAgICAgdGhpcy5sb2FkKCk7XG4gIH1cblxuICBsb2FkKHJlZnJlc2ggOiBib29sZWFuID0gZmFsc2Upe1xuICAgIHZhciBzZWxmID0gdGhpcztcbiAgICB0aGlzLmluUHJvZ3Jlc3MgPSB0cnVlO1xuICAgIHRoaXMuY2xpZW50LmdldCgnYXBpL3YxL2dyb3Vwcy8nICsgdGhpcy5fZmlsdGVyLCB7IGxpbWl0OiAxMiwgb2Zmc2V0OiB0aGlzLm9mZnNldH0pXG4gICAgICAudGhlbigocmVzcG9uc2UpID0+IHtcblxuICAgICAgICBpZighcmVzcG9uc2UuZ3JvdXBzKXtcbiAgICAgICAgICBzZWxmLm1vcmVEYXRhID0gZmFsc2U7XG4gICAgICAgICAgc2VsZi5pblByb2dyZXNzID0gZmFsc2U7XG4gICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICB9XG5cbiAgICAgICAgaWYocmVmcmVzaCl7XG4gICAgICAgICAgc2VsZi5ncm91cHMgPSByZXNwb25zZS5ncm91cHM7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgaWYoc2VsZi5vZmZzZXQpXG4gICAgICAgICAgICByZXNwb25zZS5ncm91cHMuc2hpZnQoKTtcbiAgICAgICAgICBmb3IobGV0IGdyb3VwIG9mIHJlc3BvbnNlLmdyb3VwcylcbiAgICAgICAgICAgIHNlbGYuZ3JvdXBzLnB1c2goZ3JvdXApO1xuICAgICAgICB9XG5cbiAgICAgICAgc2VsZi5vZmZzZXQgPSByZXNwb25zZVsnbG9hZC1uZXh0J107XG4gICAgICAgIHNlbGYuaW5Qcm9ncmVzcyA9IGZhbHNlO1xuICAgICAgfSlcbiAgICAgIC5jYXRjaCgoZSk9PntcblxuICAgICAgfSk7XG4gIH1cblxufVxuXG5leHBvcnQgeyBHcm91cHNQcm9maWxlIH0gZnJvbSAnLi9ncm91cHMtcHJvZmlsZSc7XG5leHBvcnQgeyBHcm91cHNDcmVhdG9yIH0gZnJvbSAnLi9ncm91cHMtY3JlYXRvcic7XG4iXSwic291cmNlUm9vdCI6Ii9zb3VyY2UvIn0=