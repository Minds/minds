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
var infinite_scroll_1 = require('../../directives/infinite-scroll');
var Discovery = (function () {
    function Discovery(client, router, params) {
        this.client = client;
        this.router = router;
        this.params = params;
        this._filter = "featured";
        this._type = "all";
        this._filter = params.params['filter'];
        if (params.params['type'])
            this._type = params.params['type'];
        this.load();
    }
    Discovery.prototype.load = function () {
        console.log("loading " + this._filter + ' (' + this._type + ')');
        this.client.get('api/v1/entities/' + this._filter + '/' + this._type, { limit: 12, offset: "" })
            .then(function (data) {
            console.log(data);
        });
    };
    Discovery = __decorate([
        angular2_1.Component({
            selector: 'minds-discovery',
            viewInjector: [api_1.Client]
        }),
        angular2_1.View({
            templateUrl: 'templates/discovery/discovery.html',
            directives: [router_1.RouterLink, angular2_1.NgFor, angular2_1.NgIf, material_1.Material, angular2_1.formDirectives, infinite_scroll_1.InfiniteScroll, angular2_1.CSSClass]
        }),
        __param(1, angular2_1.Inject(router_1.Router)),
        __param(2, angular2_1.Inject(router_1.RouteParams)), 
        __metadata('design:paramtypes', [api_1.Client, router_1.Router, router_1.RouteParams])
    ], Discovery);
    return Discovery;
})();
exports.Discovery = Discovery;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9jb250cm9sbGVycy9kaXNjb3ZlcnkvZGlzY292ZXJ5LnRzIl0sIm5hbWVzIjpbIkRpc2NvdmVyeSIsIkRpc2NvdmVyeS5jb25zdHJ1Y3RvciIsIkRpc2NvdmVyeS5sb2FkIl0sIm1hcHBpbmdzIjoiOzs7Ozs7Ozs7Ozs7OztBQUFBLHlCQUE4RSxtQkFBbUIsQ0FBQyxDQUFBO0FBQ2xHLHVCQUFnRCxpQkFBaUIsQ0FBQyxDQUFBO0FBQ2xFLG9CQUF1QixrQkFBa0IsQ0FBQyxDQUFBO0FBQzFDLHlCQUF5Qix5QkFBeUIsQ0FBQyxDQUFBO0FBRW5ELGdDQUErQixrQ0FBa0MsQ0FBQyxDQUFBO0FBR2xFO0lBYUVBLG1CQUFtQkEsTUFBY0EsRUFDUkEsTUFBY0EsRUFDVEEsTUFBbUJBO1FBRjlCQyxXQUFNQSxHQUFOQSxNQUFNQSxDQUFRQTtRQUNSQSxXQUFNQSxHQUFOQSxNQUFNQSxDQUFRQTtRQUNUQSxXQUFNQSxHQUFOQSxNQUFNQSxDQUFhQTtRQUxqREEsWUFBT0EsR0FBWUEsVUFBVUEsQ0FBQ0E7UUFDOUJBLFVBQUtBLEdBQVlBLEtBQUtBLENBQUNBO1FBTXJCQSxJQUFJQSxDQUFDQSxPQUFPQSxHQUFHQSxNQUFNQSxDQUFDQSxNQUFNQSxDQUFDQSxRQUFRQSxDQUFDQSxDQUFDQTtRQUN2Q0EsRUFBRUEsQ0FBQUEsQ0FBQ0EsTUFBTUEsQ0FBQ0EsTUFBTUEsQ0FBQ0EsTUFBTUEsQ0FBQ0EsQ0FBQ0E7WUFDdkJBLElBQUlBLENBQUNBLEtBQUtBLEdBQUdBLE1BQU1BLENBQUNBLE1BQU1BLENBQUNBLE1BQU1BLENBQUNBLENBQUNBO1FBQ3JDQSxJQUFJQSxDQUFDQSxJQUFJQSxFQUFFQSxDQUFDQTtJQUNkQSxDQUFDQTtJQUVERCx3QkFBSUEsR0FBSkE7UUFDRUUsT0FBT0EsQ0FBQ0EsR0FBR0EsQ0FBQ0EsVUFBVUEsR0FBR0EsSUFBSUEsQ0FBQ0EsT0FBT0EsR0FBR0EsSUFBSUEsR0FBR0EsSUFBSUEsQ0FBQ0EsS0FBS0EsR0FBR0EsR0FBR0EsQ0FBQ0EsQ0FBQ0E7UUFDakVBLElBQUlBLENBQUNBLE1BQU1BLENBQUNBLEdBQUdBLENBQUNBLGtCQUFrQkEsR0FBQ0EsSUFBSUEsQ0FBQ0EsT0FBT0EsR0FBQ0EsR0FBR0EsR0FBQ0EsSUFBSUEsQ0FBQ0EsS0FBS0EsRUFBRUEsRUFBQ0EsS0FBS0EsRUFBQ0EsRUFBRUEsRUFBRUEsTUFBTUEsRUFBQ0EsRUFBRUEsRUFBQ0EsQ0FBQ0E7YUFDbkZBLElBQUlBLENBQUNBLFVBQUNBLElBQVVBO1lBQ2ZBLE9BQU9BLENBQUNBLEdBQUdBLENBQUNBLElBQUlBLENBQUNBLENBQUNBO1FBQ2xCQSxDQUFDQSxDQUFDQSxDQUFDQTtJQUNUQSxDQUFDQTtJQTdCSEY7UUFBQ0Esb0JBQVNBLENBQUNBO1lBQ1RBLFFBQVFBLEVBQUVBLGlCQUFpQkE7WUFDM0JBLFlBQVlBLEVBQUVBLENBQUVBLFlBQU1BLENBQUVBO1NBQ3pCQSxDQUFDQTtRQUNEQSxlQUFJQSxDQUFDQTtZQUNKQSxXQUFXQSxFQUFFQSxvQ0FBb0NBO1lBQ2pEQSxVQUFVQSxFQUFFQSxDQUFFQSxtQkFBVUEsRUFBRUEsZ0JBQUtBLEVBQUVBLGVBQUlBLEVBQUVBLG1CQUFRQSxFQUFFQSx5QkFBY0EsRUFBRUEsZ0NBQWNBLEVBQUVBLG1CQUFRQSxDQUFFQTtTQUM1RkEsQ0FBQ0E7UUFPRUEsV0FBQ0EsaUJBQU1BLENBQUNBLGVBQU1BLENBQUNBLENBQUFBO1FBQ2ZBLFdBQUNBLGlCQUFNQSxDQUFDQSxvQkFBV0EsQ0FBQ0EsQ0FBQUE7O2tCQWdCdkJBO0lBQURBLGdCQUFDQTtBQUFEQSxDQS9CQSxJQStCQztBQXRCWSxpQkFBUyxZQXNCckIsQ0FBQSIsImZpbGUiOiJzcmMvY29udHJvbGxlcnMvZGlzY292ZXJ5L2Rpc2NvdmVyeS5qcyIsInNvdXJjZXNDb250ZW50IjpbImltcG9ydCB7IENvbXBvbmVudCwgVmlldywgTmdGb3IsIE5nSWYsIEluamVjdCwgZm9ybURpcmVjdGl2ZXMsIENTU0NsYXNzfSBmcm9tICdhbmd1bGFyMi9hbmd1bGFyMic7XG5pbXBvcnQgeyBSb3V0ZXIsIFJvdXRlUGFyYW1zLCBSb3V0ZXJMaW5rIH0gZnJvbSAnYW5ndWxhcjIvcm91dGVyJztcbmltcG9ydCB7IENsaWVudCB9IGZyb20gJ3NyYy9zZXJ2aWNlcy9hcGknO1xuaW1wb3J0IHsgTWF0ZXJpYWwgfSBmcm9tICdzcmMvZGlyZWN0aXZlcy9tYXRlcmlhbCc7XG5pbXBvcnQgeyBTZXNzaW9uRmFjdG9yeSB9IGZyb20gJy4uLy4uL3NlcnZpY2VzL3Nlc3Npb24nO1xuaW1wb3J0IHsgSW5maW5pdGVTY3JvbGwgfSBmcm9tICcuLi8uLi9kaXJlY3RpdmVzL2luZmluaXRlLXNjcm9sbCc7XG5pbXBvcnQgeyBBY3Rpdml0eSB9IGZyb20gJ3NyYy9jb250cm9sbGVycy9uZXdzZmVlZC9hY3Rpdml0eSc7XG5cbkBDb21wb25lbnQoe1xuICBzZWxlY3RvcjogJ21pbmRzLWRpc2NvdmVyeScsXG4gIHZpZXdJbmplY3RvcjogWyBDbGllbnQgXVxufSlcbkBWaWV3KHtcbiAgdGVtcGxhdGVVcmw6ICd0ZW1wbGF0ZXMvZGlzY292ZXJ5L2Rpc2NvdmVyeS5odG1sJyxcbiAgZGlyZWN0aXZlczogWyBSb3V0ZXJMaW5rLCBOZ0ZvciwgTmdJZiwgTWF0ZXJpYWwsIGZvcm1EaXJlY3RpdmVzLCBJbmZpbml0ZVNjcm9sbCwgQ1NTQ2xhc3MgXVxufSlcblxuZXhwb3J0IGNsYXNzIERpc2NvdmVyeSB7XG4gIF9maWx0ZXIgOiBzdHJpbmcgPSBcImZlYXR1cmVkXCI7XG4gIF90eXBlIDogc3RyaW5nID0gXCJhbGxcIjtcblxuICBjb25zdHJ1Y3RvcihwdWJsaWMgY2xpZW50OiBDbGllbnQsXG4gICAgQEluamVjdChSb3V0ZXIpIHB1YmxpYyByb3V0ZXI6IFJvdXRlcixcbiAgICBASW5qZWN0KFJvdXRlUGFyYW1zKSBwdWJsaWMgcGFyYW1zOiBSb3V0ZVBhcmFtc1xuICAgICl7XG4gICAgdGhpcy5fZmlsdGVyID0gcGFyYW1zLnBhcmFtc1snZmlsdGVyJ107XG4gICAgaWYocGFyYW1zLnBhcmFtc1sndHlwZSddKVxuICAgICAgdGhpcy5fdHlwZSA9IHBhcmFtcy5wYXJhbXNbJ3R5cGUnXTtcbiAgICB0aGlzLmxvYWQoKTtcbiAgfVxuXG4gIGxvYWQoKXtcbiAgICBjb25zb2xlLmxvZyhcImxvYWRpbmcgXCIgKyB0aGlzLl9maWx0ZXIgKyAnICgnICsgdGhpcy5fdHlwZSArICcpJyk7XG4gICAgdGhpcy5jbGllbnQuZ2V0KCdhcGkvdjEvZW50aXRpZXMvJyt0aGlzLl9maWx0ZXIrJy8nK3RoaXMuX3R5cGUsIHtsaW1pdDoxMiwgb2Zmc2V0OlwiXCJ9KVxuICAgICAgLnRoZW4oKGRhdGEgOiBhbnkpID0+IHtcbiAgICAgICAgY29uc29sZS5sb2coZGF0YSk7XG4gICAgICAgIH0pO1xuICB9XG5cbn1cbiJdLCJzb3VyY2VSb290IjoiL3NvdXJjZS8ifQ==