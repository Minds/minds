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
var cards_1 = require('src/controllers/cards/cards');
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
        this.load(true);
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
                if (self.offset)
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
            viewBindings: [api_1.Client]
        }),
        angular2_1.View({
            templateUrl: 'templates/discovery/discovery.html',
            directives: [router_1.RouterLink, angular2_1.NgFor, angular2_1.NgIf, material_1.Material, infinite_scroll_1.InfiniteScroll, angular2_1.NgClass, cards_1.UserCard, cards_1.VideoCard]
        }),
        __param(1, angular2_1.Inject(router_1.Router)),
        __param(2, angular2_1.Inject(router_1.RouteParams)), 
        __metadata('design:paramtypes', [api_1.Client, router_1.Router, router_1.RouteParams])
    ], Discovery);
    return Discovery;
})();
exports.Discovery = Discovery;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9jb250cm9sbGVycy9kaXNjb3ZlcnkvZGlzY292ZXJ5LnRzIl0sIm5hbWVzIjpbIkRpc2NvdmVyeSIsIkRpc2NvdmVyeS5jb25zdHJ1Y3RvciIsIkRpc2NvdmVyeS5sb2FkIl0sIm1hcHBpbmdzIjoiOzs7Ozs7Ozs7Ozs7OztBQUFDLHlCQUE2RCxtQkFBbUIsQ0FBQyxDQUFBO0FBQ2xGLHVCQUFnRCxpQkFBaUIsQ0FBQyxDQUFBO0FBQ2xFLG9CQUF1QixrQkFBa0IsQ0FBQyxDQUFBO0FBQzFDLHlCQUF5Qix5QkFBeUIsQ0FBQyxDQUFBO0FBRW5ELGdDQUErQixrQ0FBa0MsQ0FBQyxDQUFBO0FBQ2xFLHNCQUFvQyw2QkFBNkIsQ0FBQyxDQUFBO0FBR2xFO0lBaUJFQSxtQkFBbUJBLE1BQWNBLEVBQ1JBLE1BQWNBLEVBQ1RBLE1BQW1CQTtRQUY5QkMsV0FBTUEsR0FBTkEsTUFBTUEsQ0FBUUE7UUFDUkEsV0FBTUEsR0FBTkEsTUFBTUEsQ0FBUUE7UUFDVEEsV0FBTUEsR0FBTkEsTUFBTUEsQ0FBYUE7UUFUakRBLFlBQU9BLEdBQVlBLFVBQVVBLENBQUNBO1FBQzlCQSxVQUFLQSxHQUFZQSxLQUFLQSxDQUFDQTtRQUN2QkEsYUFBUUEsR0FBbUJBLEVBQUVBLENBQUNBO1FBQzlCQSxhQUFRQSxHQUFhQSxJQUFJQSxDQUFDQTtRQUMxQkEsV0FBTUEsR0FBV0EsRUFBRUEsQ0FBQ0E7UUFDcEJBLGVBQVVBLEdBQWFBLEtBQUtBLENBQUNBO1FBTTNCQSxJQUFJQSxDQUFDQSxPQUFPQSxHQUFHQSxNQUFNQSxDQUFDQSxNQUFNQSxDQUFDQSxRQUFRQSxDQUFDQSxDQUFDQTtRQUN2Q0EsRUFBRUEsQ0FBQUEsQ0FBQ0EsTUFBTUEsQ0FBQ0EsTUFBTUEsQ0FBQ0EsTUFBTUEsQ0FBQ0EsQ0FBQ0E7WUFDdkJBLElBQUlBLENBQUNBLEtBQUtBLEdBQUdBLE1BQU1BLENBQUNBLE1BQU1BLENBQUNBLE1BQU1BLENBQUNBLENBQUNBO1FBQ3JDQSxJQUFJQSxDQUFDQSxJQUFJ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QUFBO0FBcERZLGlCQUFTLFlBb0RyQixDQUFBIiwiZmlsZSI6InNyYy9jb250cm9sbGVycy9kaXNjb3ZlcnkvZGlzY292ZXJ5LmpzIiwic291cmNlc0NvbnRlbnQiOlsiIGltcG9ydCB7IENvbXBvbmVudCwgVmlldywgTmdGb3IsIE5nSWYsIEluamVjdCwgTmdDbGFzc30gZnJvbSAnYW5ndWxhcjIvYW5ndWxhcjInO1xuaW1wb3J0IHsgUm91dGVyLCBSb3V0ZVBhcmFtcywgUm91dGVyTGluayB9IGZyb20gJ2FuZ3VsYXIyL3JvdXRlcic7XG5pbXBvcnQgeyBDbGllbnQgfSBmcm9tICdzcmMvc2VydmljZXMvYXBpJztcbmltcG9ydCB7IE1hdGVyaWFsIH0gZnJvbSAnc3JjL2RpcmVjdGl2ZXMvbWF0ZXJpYWwnO1xuaW1wb3J0IHsgU2Vzc2lvbkZhY3RvcnkgfSBmcm9tICcuLi8uLi9zZXJ2aWNlcy9zZXNzaW9uJztcbmltcG9ydCB7IEluZmluaXRlU2Nyb2xsIH0gZnJvbSAnLi4vLi4vZGlyZWN0aXZlcy9pbmZpbml0ZS1zY3JvbGwnO1xuaW1wb3J0IHsgVXNlckNhcmQsIFZpZGVvQ2FyZCB9IGZyb20gJ3NyYy9jb250cm9sbGVycy9jYXJkcy9jYXJkcyc7XG5pbXBvcnQgeyBBY3Rpdml0eSB9IGZyb20gJ3NyYy9jb250cm9sbGVycy9uZXdzZmVlZC9hY3Rpdml0eSc7XG5cbkBDb21wb25lbnQoe1xuICBzZWxlY3RvcjogJ21pbmRzLWRpc2NvdmVyeScsXG4gIHZpZXdCaW5kaW5nczogWyBDbGllbnQgXVxufSlcbkBWaWV3KHtcbiAgdGVtcGxhdGVVcmw6ICd0ZW1wbGF0ZXMvZGlzY292ZXJ5L2Rpc2NvdmVyeS5odG1sJyxcbiAgZGlyZWN0aXZlczogWyBSb3V0ZXJMaW5rLCBOZ0ZvciwgTmdJZiwgTWF0ZXJpYWwsIEluZmluaXRlU2Nyb2xsLCBOZ0NsYXNzLCBVc2VyQ2FyZCwgVmlkZW9DYXJkIF1cbn0pXG5cbmV4cG9ydCBjbGFzcyBEaXNjb3Zlcnkge1xuICBfZmlsdGVyIDogc3RyaW5nID0gXCJmZWF0dXJlZFwiO1xuICBfdHlwZSA6IHN0cmluZyA9IFwiYWxsXCI7XG4gIGVudGl0aWVzIDogQXJyYXk8T2JqZWN0PiA9IFtdO1xuICBtb3JlRGF0YSA6IGJvb2xlYW4gPSB0cnVlO1xuICBvZmZzZXQ6IHN0cmluZyA9IFwiXCI7XG4gIGluUHJvZ3Jlc3MgOiBib29sZWFuID0gZmFsc2U7XG5cbiAgY29uc3RydWN0b3IocHVibGljIGNsaWVudDogQ2xpZW50LFxuICAgIEBJbmplY3QoUm91dGVyKSBwdWJsaWMgcm91dGVyOiBSb3V0ZXIsXG4gICAgQEluamVjdChSb3V0ZVBhcmFtcykgcHVibGljIHBhcmFtczogUm91dGVQYXJhbXNcbiAgICApe1xuICAgIHRoaXMuX2ZpbHRlciA9IHBhcmFtcy5wYXJhbXNbJ2ZpbHRlciddO1xuICAgIGlmKHBhcmFtcy5wYXJhbXNbJ3R5cGUnXSlcbiAgICAgIHRoaXMuX3R5cGUgPSBwYXJhbXMucGFyYW1zWyd0eXBlJ107XG4gICAgdGhpcy5sb2FkKHRydWUpO1xuICB9XG5cbiAgbG9hZChyZWZyZXNoIDogYm9vbGVhbiA9IGZhbHNlKXtcbiAgICB2YXIgc2VsZiA9IHRoaXM7XG5cbiAgICBpZih0aGlzLmluUHJvZ3Jlc3MpIHJldHVybiBmYWxzZTtcblxuICAgIGlmKHJlZnJlc2gpXG4gICAgICB0aGlzLm9mZnNldCA9IFwiXCI7XG5cbiAgICB0aGlzLmluUHJvZ3Jlc3MgPSB0cnVlO1xuXG4gICAgdGhpcy5jbGllbnQuZ2V0KCdhcGkvdjEvZW50aXRpZXMvJyt0aGlzLl9maWx0ZXIrJy8nK3RoaXMuX3R5cGUsIHtsaW1pdDoxMiwgb2Zmc2V0OnRoaXMub2Zmc2V0fSlcbiAgICAgIC50aGVuKChkYXRhIDogYW55KSA9PiB7XG4gICAgICAgIGNvbnNvbGUubG9nKDEpO1xuICAgICAgICBpZighZGF0YS5lbnRpdGllcyl7XG4gICAgICAgICAgc2VsZi5tb3JlRGF0YSA9IGZhbHNlO1xuICAgICAgICAgIHNlbGYuaW5Qcm9ncmVzcyA9IGZhbHNlO1xuICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgfVxuXG4gICAgICAgIGlmKHJlZnJlc2gpe1xuICAgICAgICAgIHNlbGYuZW50aXRpZXMgPSBkYXRhLmVudGl0aWVzO1xuICAgICAgICB9ZWxzZXtcbiAgICAgICAgICBpZihzZWxmLm9mZnNldClcbiAgICAgICAgICAgIGRhdGEuZW50aXRpZXMuc2hpZnQoKTtcbiAgICAgICAgICBmb3IobGV0IGVudGl0eSBvZiBkYXRhLmVudGl0aWVzKVxuICAgICAgICAgICAgc2VsZi5lbnRpdGllcy5wdXNoKGVudGl0eSk7XG4gICAgICAgIH1cblxuICAgICAgICBzZWxmLm9mZnNldCA9IGRhdGFbJ2xvYWQtbmV4dCddO1xuICAgICAgICBzZWxmLmluUHJvZ3Jlc3MgPSBmYWxzZTtcblxuICAgICAgfSk7XG4gIH1cblxufVxuIl0sInNvdXJjZVJvb3QiOiIvc291cmNlLyJ9