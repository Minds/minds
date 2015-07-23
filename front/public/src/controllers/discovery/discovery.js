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
var infinite_scroll_1 = require('../../directives/infinite-scroll');
var user_1 = require('src/controllers/cards/user');
var Discovery = (function () {
    function Discovery(client, router, params) {
        this.client = client;
        this.router = router;
        this.params = params;
        this._filter = "featured";
        this._type = "all";
        this.entities = [];
        this.moreData = true;
        this.offset = "";
        this.inProgress = false;
        this._filter = params.params['filter'];
        if (params.params['type'])
            this._type = params.params['type'];
        this.load();
    }
    Discovery.prototype.load = function (refresh) {
        if (refresh === void 0) { refresh = false; }
        var self = this;
        if (this.inProgress)
            return false;
        if (refresh)
            this.offset = "";
        this.inProgress = true;
        this.client.get('api/v1/entities/' + this._filter + '/' + this._type, { limit: 12, offset: this.offset })
            .then(function (data) {
            console.log(1);
            if (!data.entities) {
                self.moreData = false;
                self.inProgress = false;
                return false;
            }
            if (refresh) {
                self.entities = data.entities;
            }
            else {
                data.entities.shift();
                for (var _i = 0, _a = data.entities; _i < _a.length; _i++) {
                    var entity = _a[_i];
                    self.entities.push(entity);
                }
            }
            self.offset = data['load-next'];
            self.inProgress = false;
        });
    };
    Discovery = __decorate([
        angular2_1.Component({
            selector: 'minds-discovery',
            viewInjector: [api_1.Client]
        }),
        angular2_1.View({
            templateUrl: 'templates/discovery/discovery.html',
            directives: [router_1.RouterLink, angular2_1.NgFor, angular2_1.NgIf, material_1.Material, angular2_1.formDirectives, infinite_scroll_1.InfiniteScroll, angular2_1.CSSClass, user_1.UserCard]
        }),
        __param(1, angular2_1.Inject(router_1.Router)),
        __param(2, angular2_1.Inject(router_1.RouteParams)), 
        __metadata('design:paramtypes', [api_1.Client, router_1.Router, router_1.RouteParams])
    ], Discovery);
    return Discovery;
})();
exports.Discovery = Discovery;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9jb250cm9sbGVycy9kaXNjb3ZlcnkvZGlzY292ZXJ5LnRzIl0sIm5hbWVzIjpbIkRpc2NvdmVyeSIsIkRpc2NvdmVyeS5jb25zdHJ1Y3RvciIsIkRpc2NvdmVyeS5sb2FkIl0sIm1hcHBpbmdzIjoiOzs7Ozs7Ozs7Ozs7OztBQUFBLHlCQUE4RSxtQkFBbUIsQ0FBQyxDQUFBO0FBQ2xHLHVCQUFnRCxpQkFBaUIsQ0FBQyxDQUFBO0FBQ2xFLG9CQUF1QixrQkFBa0IsQ0FBQyxDQUFBO0FBQzFDLHlCQUF5Qix5QkFBeUIsQ0FBQyxDQUFBO0FBRW5ELGdDQUErQixrQ0FBa0MsQ0FBQyxDQUFBO0FBQ2xFLHFCQUF5Qiw0QkFBNEIsQ0FBQyxDQUFBO0FBR3REO0lBaUJFQSxtQkFBbUJBLE1BQWNBLEVBQ1JBLE1BQWNBLEVBQ1RBLE1BQW1CQTtRQUY5QkMsV0FBTUEsR0FBTkEsTUFBTUEsQ0FBUUE7UUFDUkEsV0FBTUEsR0FBTkEsTUFBTUEsQ0FBUUE7UUFDVEEsV0FBTUEsR0FBTkEsTUFBTUEsQ0FBYUE7UUFUakRBLFlBQU9BLEdBQVlBLFVBQVVBLENBQUNBO1FBQzlCQSxVQUFLQSxHQUFZQSxLQUFLQSxDQUFDQTtRQUN2QkEsYUFBUUEsR0FBbUJBLEVBQUVBLENBQUNBO1FBQzlCQSxhQUFRQSxHQUFhQSxJQUFJQSxDQUFDQTtRQUMxQkEsV0FBTUEsR0FBV0EsRUFBRUEsQ0FBQ0E7UUFDcEJBLGVBQVVBLEdBQWFBLEtBQUtBLENBQUNBO1FBTTNCQSxJQUFJQSxDQUFDQSxPQUFPQSxHQUFHQSxNQUFNQSxDQUFDQSxNQUFNQSxDQUFDQSxRQUFRQSxDQUFDQSxDQUFDQTtRQUN2Q0EsRUFBRUEsQ0FBQUEsQ0FBQ0EsTUFBTUEsQ0FBQ0EsTUFBTUEsQ0FBQ0EsTUFBTUEsQ0FBQ0EsQ0FBQ0E7WUFDdkJBLElBQUlBLENBQUNBLEtBQUtBLEdBQUdBLE1BQU1BLENBQUNBLE1BQU1BLENBQUNBLE1BQU1BLENBQUNBLENBQUNBO1FBQ3JDQSxJQUFJQSxDQUFDQSxJQUFJ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ZGlzY292ZXJ5LmpzIiwic291cmNlc0NvbnRlbnQiOlsiaW1wb3J0IHsgQ29tcG9uZW50LCBWaWV3LCBOZ0ZvciwgTmdJZiwgSW5qZWN0LCBmb3JtRGlyZWN0aXZlcywgQ1NTQ2xhc3N9IGZyb20gJ2FuZ3VsYXIyL2FuZ3VsYXIyJztcbmltcG9ydCB7IFJvdXRlciwgUm91dGVQYXJhbXMsIFJvdXRlckxpbmsgfSBmcm9tICdhbmd1bGFyMi9yb3V0ZXInO1xuaW1wb3J0IHsgQ2xpZW50IH0gZnJvbSAnc3JjL3NlcnZpY2VzL2FwaSc7XG5pbXBvcnQgeyBNYXRlcmlhbCB9IGZyb20gJ3NyYy9kaXJlY3RpdmVzL21hdGVyaWFsJztcbmltcG9ydCB7IFNlc3Npb25GYWN0b3J5IH0gZnJvbSAnLi4vLi4vc2VydmljZXMvc2Vzc2lvbic7XG5pbXBvcnQgeyBJbmZpbml0ZVNjcm9sbCB9IGZyb20gJy4uLy4uL2RpcmVjdGl2ZXMvaW5maW5pdGUtc2Nyb2xsJztcbmltcG9ydCB7IFVzZXJDYXJkIH0gZnJvbSAnc3JjL2NvbnRyb2xsZXJzL2NhcmRzL3VzZXInO1xuaW1wb3J0IHsgQWN0aXZpdHkgfSBmcm9tICdzcmMvY29udHJvbGxlcnMvbmV3c2ZlZWQvYWN0aXZpdHknO1xuXG5AQ29tcG9uZW50KHtcbiAgc2VsZWN0b3I6ICdtaW5kcy1kaXNjb3ZlcnknLFxuICB2aWV3SW5qZWN0b3I6IFsgQ2xpZW50IF1cbn0pXG5AVmlldyh7XG4gIHRlbXBsYXRlVXJsOiAndGVtcGxhdGVzL2Rpc2NvdmVyeS9kaXNjb3ZlcnkuaHRtbCcsXG4gIGRpcmVjdGl2ZXM6IFsgUm91dGVyTGluaywgTmdGb3IsIE5nSWYsIE1hdGVyaWFsLCBmb3JtRGlyZWN0aXZlcywgSW5maW5pdGVTY3JvbGwsIENTU0NsYXNzLCBVc2VyQ2FyZCBdXG59KVxuXG5leHBvcnQgY2xhc3MgRGlzY292ZXJ5IHtcbiAgX2ZpbHRlciA6IHN0cmluZyA9IFwiZmVhdHVyZWRcIjtcbiAgX3R5cGUgOiBzdHJpbmcgPSBcImFsbFwiO1xuICBlbnRpdGllcyA6IEFycmF5PE9iamVjdD4gPSBbXTtcbiAgbW9yZURhdGEgOiBib29sZWFuID0gdHJ1ZTtcbiAgb2Zmc2V0OiBzdHJpbmcgPSBcIlwiO1xuICBpblByb2dyZXNzIDogYm9vbGVhbiA9IGZhbHNlO1xuXG4gIGNvbnN0cnVjdG9yKHB1YmxpYyBjbGllbnQ6IENsaWVudCxcbiAgICBASW5qZWN0KFJvdXRlcikgcHVibGljIHJvdXRlcjogUm91dGVyLFxuICAgIEBJbmplY3QoUm91dGVQYXJhbXMpIHB1YmxpYyBwYXJhbXM6IFJvdXRlUGFyYW1zXG4gICAgKXtcbiAgICB0aGlzLl9maWx0ZXIgPSBwYXJhbXMucGFyYW1zWydmaWx0ZXInXTtcbiAgICBpZihwYXJhbXMucGFyYW1zWyd0eXBlJ10pXG4gICAgICB0aGlzLl90eXBlID0gcGFyYW1zLnBhcmFtc1sndHlwZSddO1xuICAgIHRoaXMubG9hZCgpO1xuICB9XG5cbiAgbG9hZChyZWZyZXNoIDogYm9vbGVhbiA9IGZhbHNlKXtcbiAgICB2YXIgc2VsZiA9IHRoaXM7XG5cbiAgICBpZih0aGlzLmluUHJvZ3Jlc3MpIHJldHVybiBmYWxzZTtcblxuICAgIGlmKHJlZnJlc2gpXG4gICAgICB0aGlzLm9mZnNldCA9IFwiXCI7XG5cbiAgICB0aGlzLmluUHJvZ3Jlc3MgPSB0cnVlO1xuXG4gICAgdGhpcy5jbGllbnQuZ2V0KCdhcGkvdjEvZW50aXRpZXMvJyt0aGlzLl9maWx0ZXIrJy8nK3RoaXMuX3R5cGUsIHtsaW1pdDoxMiwgb2Zmc2V0OnRoaXMub2Zmc2V0fSlcbiAgICAgIC50aGVuKChkYXRhIDogYW55KSA9PiB7XG4gICAgICAgIGNvbnNvbGUubG9nKDEpO1xuICAgICAgICBpZighZGF0YS5lbnRpdGllcyl7XG4gICAgICAgICAgc2VsZi5tb3JlRGF0YSA9IGZhbHNlO1xuICAgICAgICAgIHNlbGYuaW5Qcm9ncmVzcyA9IGZhbHNlO1xuICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgfVxuXG4gICAgICAgIGlmKHJlZnJlc2gpe1xuICAgICAgICAgIHNlbGYuZW50aXRpZXMgPSBkYXRhLmVudGl0aWVzO1xuICAgICAgICB9ZWxzZXtcbiAgICAgICAgICBkYXRhLmVudGl0aWVzLnNoaWZ0KCk7XG4gICAgICAgICAgZm9yKGxldCBlbnRpdHkgb2YgZGF0YS5lbnRpdGllcylcbiAgICAgICAgICAgIHNlbGYuZW50aXRpZXMucHVzaChlbnRpdHkpO1xuICAgICAgICB9XG5cbiAgICAgICAgc2VsZi5vZmZzZXQgPSBkYXRhWydsb2FkLW5leHQnXTtcbiAgICAgICAgc2VsZi5pblByb2dyZXNzID0gZmFsc2U7XG5cbiAgICAgIH0pO1xuICB9XG5cbn1cbiJdLCJzb3VyY2VSb290IjoiL3NvdXJjZS8ifQ==