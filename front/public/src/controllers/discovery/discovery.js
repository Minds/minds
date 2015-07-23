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
        this._filter = params.params['filter'];
        if (params.params['type'])
            this._type = params.params['type'];
        this.load();
    }
    Discovery.prototype.load = function () {
        var self = this;
        this.client.get('api/v1/entities/' + this._filter + '/' + this._type, { limit: 12, offset: "" })
            .then(function (data) {
            console.log(data);
            self.entities = data.entities;
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

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9jb250cm9sbGVycy9kaXNjb3ZlcnkvZGlzY292ZXJ5LnRzIl0sIm5hbWVzIjpbIkRpc2NvdmVyeSIsIkRpc2NvdmVyeS5jb25zdHJ1Y3RvciIsIkRpc2NvdmVyeS5sb2FkIl0sIm1hcHBpbmdzIjoiOzs7Ozs7Ozs7Ozs7OztBQUFBLHlCQUE4RSxtQkFBbUIsQ0FBQyxDQUFBO0FBQ2xHLHVCQUFnRCxpQkFBaUIsQ0FBQyxDQUFBO0FBQ2xFLG9CQUF1QixrQkFBa0IsQ0FBQyxDQUFBO0FBQzFDLHlCQUF5Qix5QkFBeUIsQ0FBQyxDQUFBO0FBRW5ELGdDQUErQixrQ0FBa0MsQ0FBQyxDQUFBO0FBQ2xFLHFCQUF5Qiw0QkFBNEIsQ0FBQyxDQUFBO0FBR3REO0lBY0VBLG1CQUFtQkEsTUFBY0EsRUFDUkEsTUFBY0EsRUFDVEEsTUFBbUJBO1FBRjlCQyxXQUFNQSxHQUFOQSxNQUFNQSxDQUFRQTtRQUNSQSxXQUFNQSxHQUFOQSxNQUFNQSxDQUFRQTtRQUNUQSxXQUFNQSxHQUFOQSxNQUFNQSxDQUFhQTtRQU5qREEsWUFBT0EsR0FBWUEsVUFBVUEsQ0FBQ0E7UUFDOUJBLFVBQUtBLEdBQVlBLEtBQUtBLENBQUNBO1FBQ3ZCQSxhQUFRQSxHQUFtQkEsRUFBRUEsQ0FBQ0E7UUFNNUJBLElBQUlBLENBQUNBLE9BQU9BLEdBQUdBLE1BQU1BLENBQUNBLE1BQU1BLENBQUNBLFFBQVFBLENBQUNBLENBQUNBO1FBQ3ZDQSxFQUFFQSxDQUFBQSxDQUFDQSxNQUFNQSxDQUFDQSxNQUFNQSxDQUFDQSxNQUFNQSxDQUFDQSxDQUFDQTtZQUN2QkEsSUFBSUEsQ0FBQ0EsS0FBS0EsR0FBR0EsTUFBTUEsQ0FBQ0EsTUFBTUEsQ0FBQ0EsTUFBTUEsQ0FBQ0EsQ0FBQ0E7UUFDckNBLElBQUlBLENBQUNBLElBQUlBLEVBQUVBLENBQUNBO0lBQ2RBLENBQUNBO0lBRURELHdCQUFJQSxHQUFKQTtRQUNFRSxJQUFJQSxJQUFJQSxHQUFHQSxJQUFJQSxDQUFDQTtRQUNoQkEsSUFBSUEsQ0FBQ0EsTUFBTUEsQ0FBQ0EsR0FBR0EsQ0FBQ0Esa0JBQWtCQSxHQUFDQSxJQUFJQSxDQUFDQSxPQUFPQSxHQUFDQSxHQUFHQSxHQUFDQSxJQUFJQSxDQUFDQSxLQUFLQSxFQUFFQSxFQUFDQSxLQUFLQSxFQUFDQSxFQUFFQSxFQUFFQSxNQUFNQSxFQUFDQSxFQUFFQSxFQUFDQSxDQUFDQTthQUNuRkEsSUFBSUEsQ0FBQ0EsVUFBQ0EsSUFBVUE7WUFDZkEsT0FBT0EsQ0FBQ0EsR0FBR0EsQ0FBQ0EsSUFBSUEsQ0FBQ0EsQ0FBQ0E7WUFDbEJBLElBQUlBLENBQUNBLFFBQVFBLEdBQUdBLElBQUlBLENBQUNBLFFBQVFBLENBQUNBO1FBQzlCQSxDQUFDQSxDQUFDQSxDQUFDQTtJQUNUQSxDQUFDQTtJQS9CSEY7UUFBQ0Esb0JBQVNBLENBQUNBO1lBQ1RBLFFBQVFBLEVBQUVBLGlCQUFpQkE7WUFDM0JBLFlBQVlBLEVBQUVBLENBQUVBLFlBQU1BLENBQUVBO1NBQ3pCQSxDQUFDQTtRQUNEQSxlQUFJQSxDQUFDQTtZQUNKQSxXQUFXQSxFQUFFQSxvQ0FBb0NBO1lBQ2pEQSxVQUFVQSxFQUFFQSxDQUFFQSxtQkFBVUEsRUFBRUEsZ0JBQUtBLEVBQUVBLGVBQUlBLEVBQUVBLG1CQUFRQSxFQUFFQSx5QkFBY0EsRUFBRUEsZ0NBQWNBLEVBQUVBLG1CQUFRQSxFQUFFQSxlQUFRQSxDQUFFQTtTQUN0R0EsQ0FBQ0E7UUFRRUEsV0FBQ0EsaUJBQU1BLENBQUNBLGVBQU1BLENBQUNBLENBQUFBO1FBQ2ZBLFdBQUNBLGlCQUFNQSxDQUFDQSxvQkFBV0EsQ0FBQ0EsQ0FBQUE7O2tCQWlCdkJBO0lBQURBLGdCQUFDQTtBQUFEQSxDQWpDQSxBQWlDQ0EsSUFBQTtBQXhCWSxpQkFBUyxZQXdCckIsQ0FBQSIsImZpbGUiOiJzcmMvY29udHJvbGxlcnMvZGlzY292ZXJ5L2Rpc2NvdmVyeS5qcyIsInNvdXJjZXNDb250ZW50IjpbImltcG9ydCB7IENvbXBvbmVudCwgVmlldywgTmdGb3IsIE5nSWYsIEluamVjdCwgZm9ybURpcmVjdGl2ZXMsIENTU0NsYXNzfSBmcm9tICdhbmd1bGFyMi9hbmd1bGFyMic7XG5pbXBvcnQgeyBSb3V0ZXIsIFJvdXRlUGFyYW1zLCBSb3V0ZXJMaW5rIH0gZnJvbSAnYW5ndWxhcjIvcm91dGVyJztcbmltcG9ydCB7IENsaWVudCB9IGZyb20gJ3NyYy9zZXJ2aWNlcy9hcGknO1xuaW1wb3J0IHsgTWF0ZXJpYWwgfSBmcm9tICdzcmMvZGlyZWN0aXZlcy9tYXRlcmlhbCc7XG5pbXBvcnQgeyBTZXNzaW9uRmFjdG9yeSB9IGZyb20gJy4uLy4uL3NlcnZpY2VzL3Nlc3Npb24nO1xuaW1wb3J0IHsgSW5maW5pdGVTY3JvbGwgfSBmcm9tICcuLi8uLi9kaXJlY3RpdmVzL2luZmluaXRlLXNjcm9sbCc7XG5pbXBvcnQgeyBVc2VyQ2FyZCB9IGZyb20gJ3NyYy9jb250cm9sbGVycy9jYXJkcy91c2VyJztcbmltcG9ydCB7IEFjdGl2aXR5IH0gZnJvbSAnc3JjL2NvbnRyb2xsZXJzL25ld3NmZWVkL2FjdGl2aXR5JztcblxuQENvbXBvbmVudCh7XG4gIHNlbGVjdG9yOiAnbWluZHMtZGlzY292ZXJ5JyxcbiAgdmlld0luamVjdG9yOiBbIENsaWVudCBdXG59KVxuQFZpZXcoe1xuICB0ZW1wbGF0ZVVybDogJ3RlbXBsYXRlcy9kaXNjb3ZlcnkvZGlzY292ZXJ5Lmh0bWwnLFxuICBkaXJlY3RpdmVzOiBbIFJvdXRlckxpbmssIE5nRm9yLCBOZ0lmLCBNYXRlcmlhbCwgZm9ybURpcmVjdGl2ZXMsIEluZmluaXRlU2Nyb2xsLCBDU1NDbGFzcywgVXNlckNhcmQgXVxufSlcblxuZXhwb3J0IGNsYXNzIERpc2NvdmVyeSB7XG4gIF9maWx0ZXIgOiBzdHJpbmcgPSBcImZlYXR1cmVkXCI7XG4gIF90eXBlIDogc3RyaW5nID0gXCJhbGxcIjtcbiAgZW50aXRpZXMgOiBBcnJheTxPYmplY3Q+ID0gW107XG5cbiAgY29uc3RydWN0b3IocHVibGljIGNsaWVudDogQ2xpZW50LFxuICAgIEBJbmplY3QoUm91dGVyKSBwdWJsaWMgcm91dGVyOiBSb3V0ZXIsXG4gICAgQEluamVjdChSb3V0ZVBhcmFtcykgcHVibGljIHBhcmFtczogUm91dGVQYXJhbXNcbiAgICApe1xuICAgIHRoaXMuX2ZpbHRlciA9IHBhcmFtcy5wYXJhbXNbJ2ZpbHRlciddO1xuICAgIGlmKHBhcmFtcy5wYXJhbXNbJ3R5cGUnXSlcbiAgICAgIHRoaXMuX3R5cGUgPSBwYXJhbXMucGFyYW1zWyd0eXBlJ107XG4gICAgdGhpcy5sb2FkKCk7XG4gIH1cblxuICBsb2FkKCl7XG4gICAgdmFyIHNlbGYgPSB0aGlzO1xuICAgIHRoaXMuY2xpZW50LmdldCgnYXBpL3YxL2VudGl0aWVzLycrdGhpcy5fZmlsdGVyKycvJyt0aGlzLl90eXBlLCB7bGltaXQ6MTIsIG9mZnNldDpcIlwifSlcbiAgICAgIC50aGVuKChkYXRhIDogYW55KSA9PiB7XG4gICAgICAgIGNvbnNvbGUubG9nKGRhdGEpO1xuICAgICAgICBzZWxmLmVudGl0aWVzID0gZGF0YS5lbnRpdGllcztcbiAgICAgICAgfSk7XG4gIH1cblxufVxuIl0sInNvdXJjZVJvb3QiOiIvc291cmNlLyJ9