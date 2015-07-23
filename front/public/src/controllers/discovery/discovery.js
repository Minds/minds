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

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9jb250cm9sbGVycy9kaXNjb3ZlcnkvZGlzY292ZXJ5LnRzIl0sIm5hbWVzIjpbIkRpc2NvdmVyeSIsIkRpc2NvdmVyeS5jb25zdHJ1Y3RvciIsIkRpc2NvdmVyeS5sb2FkIl0sIm1hcHBpbmdzIjoiOzs7Ozs7Ozs7Ozs7OztBQUFBLHlCQUE4RSxtQkFBbUIsQ0FBQyxDQUFBO0FBQ2xHLHVCQUFnRCxpQkFBaUIsQ0FBQyxDQUFBO0FBQ2xFLG9CQUF1QixrQkFBa0IsQ0FBQyxDQUFBO0FBQzFDLHlCQUF5Qix5QkFBeUIsQ0FBQyxDQUFBO0FBRW5ELGdDQUErQixrQ0FBa0MsQ0FBQyxDQUFBO0FBQ2xFLHFCQUF5Qiw0QkFBNEIsQ0FBQyxDQUFBO0FBR3REO0lBaUJFQSxtQkFBbUJBLE1BQWNBLEVBQ1JBLE1BQWNBLEVBQ1RBLE1BQW1CQTtRQUY5QkMsV0FBTUEsR0FBTkEsTUFBTUEsQ0FBUUE7UUFDUkEsV0FBTUEsR0FBTkEsTUFBTUEsQ0FBUUE7UUFDVEEsV0FBTUEsR0FBTkEsTUFBTUEsQ0FBYUE7UUFUakRBLFlBQU9BLEdBQVlBLFVBQVVBLENBQUNBO1FBQzlCQSxVQUFLQSxHQUFZQSxLQUFLQSxDQUFDQTtRQUN2QkEsYUFBUUEsR0FBbUJBLEVBQUVBLENBQUNBO1FBQzlCQSxhQUFRQSxHQUFhQSxJQUFJQSxDQUFDQTtRQUMxQkEsV0FBTUEsR0FBV0EsRUFBRUEsQ0FBQ0E7UUFDcEJBLGVBQVVBLEdBQWFBLEtBQUtBLENBQUNBO1FBTTNCQSxJQUFJQSxDQUFDQSxPQUFPQSxHQUFHQSxNQUFNQSxDQUFDQSxNQUFNQSxDQUFDQSxRQUFRQSxDQUFDQSxDQUFDQTtRQUN2Q0EsRUFBRUEsQ0FBQUEsQ0FBQ0EsTUFBTUEsQ0FBQ0EsTUFBTUEsQ0FBQ0EsTUFBTUEsQ0FBQ0EsQ0FBQ0E7WUFDdkJBLElBQUlBLENBQUNBLEtBQUtBLEdBQUdBLE1BQU1BLENBQUNBLE1BQU1BLENBQUNBLE1BQU1BLENBQUNBLENBQUNBO1FBQ3JDQSxJQUFJQSxDQUFDQSxJQUFJQSxFQUFFQSxDQUFDQTtJQUNkQSxDQUFDQTtJQUVERCx3QkFBSUEsR0FBSkEsVUFBS0EsT0FBeUJBO1FBQXpCRSx1QkFBeUJBLEdBQXpCQSxlQUF5QkE7UUFDNUJBLElBQUlBLElBQUlBLEdBQUdBLElBQUlBLENBQUNBO1FBRWhCQSxFQUFFQSxDQUFBQSxDQUFDQSxJQUFJQSxDQUFDQSxVQUFVQSxDQUFDQTtZQUFDQSxNQUFNQSxDQUFDQSxLQUFLQSxDQUFDQTtRQUVqQ0EsRUFBRUEsQ0FBQUEsQ0FBQ0EsT0FBT0EsQ0FBQ0E7WUFDVEEsSUFBSUEsQ0FBQ0EsTUFBTUEsR0FBR0EsRUFBRUEsQ0FBQ0E7UUFFbkJBLElBQUlBLENBQUNBLFVBQVVBLEdBQUdBLElBQUlBLENBQUNBO1FBRXZCQSxJQUFJQSxDQUFDQSxNQUFNQSxDQUFDQSxHQUFHQSxDQUFDQSxrQkFBa0JBLEdBQUNBLElBQUlBLENBQUNBLE9BQU9BLEdBQUNBLEdBQUdBLEdBQUNBLElBQUlBLENBQUNBLEtBQUtBLEVBQUVBLEVBQUNBLEtBQUtBLEVBQUNBLEVBQUVBLEVBQUVBLE1BQU1BLEVBQUNBLElBQUlBLENBQUNBLE1BQU1BLEVBQUNBLENBQUNBO2FBQzVGQSxJQUFJQSxDQUFDQSxVQUFDQSxJQUFVQTtZQUNmQSxPQUFPQSxDQUFDQSxHQUFHQSxDQUFDQSxDQUFDQSxDQUFDQSxDQUFDQTtZQUNmQSxFQUFFQSxDQUFBQSxDQUFDQSxDQUFDQSxJQUFJQSxDQUFDQSxRQUFRQSxDQUFDQSxDQUFBQSxDQUFDQTtnQkFDakJBLElBQUlBLENBQUNBLFFBQVFBLEdBQUdBLEtBQUtBLENBQUNBO2dCQUN0QkEsSUFBSUEsQ0FBQ0EsVUFBVUEsR0FBR0EsS0FBS0EsQ0FBQ0E7Z0JBQ3hCQSxNQUFNQSxDQUFDQSxLQUFLQSxDQUFDQTtZQUNmQSxDQUFDQTtZQUVEQSxFQUFFQSxDQUFBQSxDQUFDQSxPQUFPQSxDQUFDQSxDQUFBQSxDQUFDQTtnQkFDVkEsSUFBSUEsQ0FBQ0EsUUFBUUEsR0FBR0EsSUFBSUEsQ0FBQ0EsUUFBUUEsQ0FBQ0E7WUFDaENBLENBQUNBO1lBQUFBLElBQUlBLENBQUFBLENBQUNBO2dCQUNKQSxHQUFHQSxDQUFBQSxDQUFlQSxVQUFhQSxFQUFiQSxLQUFBQSxJQUFJQSxDQUFDQSxRQUFRQSxFQUEzQkEsY0FBVUEsRUFBVkEsSUFBMkJBLENBQUNBO29CQUE1QkEsSUFBSUEsTUFBTUEsU0FBQUE7b0JBQ1pBLElBQUlBLENBQUNBLFFBQVFBLENBQUNBLElBQUlBLENBQUNBLE1BQU1BLENBQUNBLENBQUNBO2lCQUFBQTtZQUMvQkEsQ0FBQ0E7WUFFREEsSUFBSUEsQ0FBQ0EsTUFBTUEsR0FBR0EsSUFBSUEsQ0FBQ0EsV0FBV0EsQ0FBQ0EsQ0FBQ0E7WUFDaENBLElBQUlBLENBQUNBLFVBQVVBLEdBQUdBLEtBQUtBLENBQUNBO1FBRTFCQSxDQUFDQSxDQUFDQSxDQUFDQTtJQUNQQSxDQUFDQTtJQXpESEY7UUFBQ0Esb0JBQVNBLENBQUNBO1lBQ1RBLFFBQVFBLEVBQUVBLGlCQUFpQkE7WUFDM0JBLFlBQVlBLEVBQUVBLENBQUVBLFlBQU1BLENBQUVBO1NBQ3pCQSxDQUFDQTtRQUNEQSxlQUFJQSxDQUFDQTtZQUNKQSxXQUFXQSxFQUFFQSxvQ0FBb0NBO1lBQ2pEQSxVQUFVQSxFQUFFQSxDQUFFQSxtQkFBVUEsRUFBRUEsZ0JBQUtBLEVBQUVBLGVBQUlBLEVBQUVBLG1CQUFRQSxFQUFFQSx5QkFBY0EsRUFBRUEsZ0NBQWNBLEVBQUVBLG1CQUFRQSxFQUFFQSxlQUFRQSxDQUFFQTtTQUN0R0EsQ0FBQ0E7UUFXRUEsV0FBQ0EsaUJBQU1BLENBQUNBLGVBQU1BLENBQUNBLENBQUFBO1FBQ2ZBLFdBQUNBLGlCQUFNQSxDQUFDQSxvQkFBV0EsQ0FBQ0EsQ0FBQUE7O2tCQXdDdkJBO0lBQURBLGdCQUFDQTtBQUFEQSxDQTNEQSxBQTJEQ0EsSUFBQTtBQWxEWSxpQkFBUyxZQWtEckIsQ0FBQSIsImZpbGUiOiJzcmMvY29udHJvbGxlcnMvZGlzY292ZXJ5L2Rpc2NvdmVyeS5qcyIsInNvdXJjZXNDb250ZW50IjpbImltcG9ydCB7IENvbXBvbmVudCwgVmlldywgTmdGb3IsIE5nSWYsIEluamVjdCwgZm9ybURpcmVjdGl2ZXMsIENTU0NsYXNzfSBmcm9tICdhbmd1bGFyMi9hbmd1bGFyMic7XG5pbXBvcnQgeyBSb3V0ZXIsIFJvdXRlUGFyYW1zLCBSb3V0ZXJMaW5rIH0gZnJvbSAnYW5ndWxhcjIvcm91dGVyJztcbmltcG9ydCB7IENsaWVudCB9IGZyb20gJ3NyYy9zZXJ2aWNlcy9hcGknO1xuaW1wb3J0IHsgTWF0ZXJpYWwgfSBmcm9tICdzcmMvZGlyZWN0aXZlcy9tYXRlcmlhbCc7XG5pbXBvcnQgeyBTZXNzaW9uRmFjdG9yeSB9IGZyb20gJy4uLy4uL3NlcnZpY2VzL3Nlc3Npb24nO1xuaW1wb3J0IHsgSW5maW5pdGVTY3JvbGwgfSBmcm9tICcuLi8uLi9kaXJlY3RpdmVzL2luZmluaXRlLXNjcm9sbCc7XG5pbXBvcnQgeyBVc2VyQ2FyZCB9IGZyb20gJ3NyYy9jb250cm9sbGVycy9jYXJkcy91c2VyJztcbmltcG9ydCB7IEFjdGl2aXR5IH0gZnJvbSAnc3JjL2NvbnRyb2xsZXJzL25ld3NmZWVkL2FjdGl2aXR5JztcblxuQENvbXBvbmVudCh7XG4gIHNlbGVjdG9yOiAnbWluZHMtZGlzY292ZXJ5JyxcbiAgdmlld0luamVjdG9yOiBbIENsaWVudCBdXG59KVxuQFZpZXcoe1xuICB0ZW1wbGF0ZVVybDogJ3RlbXBsYXRlcy9kaXNjb3ZlcnkvZGlzY292ZXJ5Lmh0bWwnLFxuICBkaXJlY3RpdmVzOiBbIFJvdXRlckxpbmssIE5nRm9yLCBOZ0lmLCBNYXRlcmlhbCwgZm9ybURpcmVjdGl2ZXMsIEluZmluaXRlU2Nyb2xsLCBDU1NDbGFzcywgVXNlckNhcmQgXVxufSlcblxuZXhwb3J0IGNsYXNzIERpc2NvdmVyeSB7XG4gIF9maWx0ZXIgOiBzdHJpbmcgPSBcImZlYXR1cmVkXCI7XG4gIF90eXBlIDogc3RyaW5nID0gXCJhbGxcIjtcbiAgZW50aXRpZXMgOiBBcnJheTxPYmplY3Q+ID0gW107XG4gIG1vcmVEYXRhIDogYm9vbGVhbiA9IHRydWU7XG4gIG9mZnNldDogc3RyaW5nID0gXCJcIjtcbiAgaW5Qcm9ncmVzcyA6IGJvb2xlYW4gPSBmYWxzZTtcblxuICBjb25zdHJ1Y3RvcihwdWJsaWMgY2xpZW50OiBDbGllbnQsXG4gICAgQEluamVjdChSb3V0ZXIpIHB1YmxpYyByb3V0ZXI6IFJvdXRlcixcbiAgICBASW5qZWN0KFJvdXRlUGFyYW1zKSBwdWJsaWMgcGFyYW1zOiBSb3V0ZVBhcmFtc1xuICAgICl7XG4gICAgdGhpcy5fZmlsdGVyID0gcGFyYW1zLnBhcmFtc1snZmlsdGVyJ107XG4gICAgaWYocGFyYW1zLnBhcmFtc1sndHlwZSddKVxuICAgICAgdGhpcy5fdHlwZSA9IHBhcmFtcy5wYXJhbXNbJ3R5cGUnXTtcbiAgICB0aGlzLmxvYWQoKTtcbiAgfVxuXG4gIGxvYWQocmVmcmVzaCA6IGJvb2xlYW4gPSBmYWxzZSl7XG4gICAgdmFyIHNlbGYgPSB0aGlzO1xuXG4gICAgaWYodGhpcy5pblByb2dyZXNzKSByZXR1cm4gZmFsc2U7XG5cbiAgICBpZihyZWZyZXNoKVxuICAgICAgdGhpcy5vZmZzZXQgPSBcIlwiO1xuXG4gICAgdGhpcy5pblByb2dyZXNzID0gdHJ1ZTtcblxuICAgIHRoaXMuY2xpZW50LmdldCgnYXBpL3YxL2VudGl0aWVzLycrdGhpcy5fZmlsdGVyKycvJyt0aGlzLl90eXBlLCB7bGltaXQ6MTIsIG9mZnNldDp0aGlzLm9mZnNldH0pXG4gICAgICAudGhlbigoZGF0YSA6IGFueSkgPT4ge1xuICAgICAgICBjb25zb2xlLmxvZygxKTtcbiAgICAgICAgaWYoIWRhdGEuZW50aXRpZXMpe1xuICAgICAgICAgIHNlbGYubW9yZURhdGEgPSBmYWxzZTtcbiAgICAgICAgICBzZWxmLmluUHJvZ3Jlc3MgPSBmYWxzZTtcbiAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgIH1cblxuICAgICAgICBpZihyZWZyZXNoKXtcbiAgICAgICAgICBzZWxmLmVudGl0aWVzID0gZGF0YS5lbnRpdGllcztcbiAgICAgICAgfWVsc2V7XG4gICAgICAgICAgZm9yKGxldCBlbnRpdHkgb2YgZGF0YS5lbnRpdGllcylcbiAgICAgICAgICAgIHNlbGYuZW50aXRpZXMucHVzaChlbnRpdHkpO1xuICAgICAgICB9XG5cbiAgICAgICAgc2VsZi5vZmZzZXQgPSBkYXRhWydsb2FkLW5leHQnXTtcbiAgICAgICAgc2VsZi5pblByb2dyZXNzID0gZmFsc2U7XG5cbiAgICAgIH0pO1xuICB9XG5cbn1cbiJdLCJzb3VyY2VSb290IjoiL3NvdXJjZS8ifQ==