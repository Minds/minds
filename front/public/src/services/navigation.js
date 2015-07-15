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
var Navigation = (function () {
    function Navigation(router) {
        this.router = router;
    }
    Navigation.prototype.getItems = function () {
        var items = window.Minds.navigation;
        if (!items)
            return [];
        for (var _i = 0; _i < items.length; _i++) {
            var item = items[_i];
            if (this.router.lastNavigationAttempt == item.path)
                item.active = true;
            else
                item.active = false;
        }
        return items;
    };
    Navigation = __decorate([
        __param(0, angular2_1.Inject(router_1.Router)), 
        __metadata('design:paramtypes', [router_1.Router])
    ], Navigation);
    return Navigation;
})();
exports.Navigation = Navigation;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9zZXJ2aWNlcy9uYXZpZ2F0aW9uLnRzIl0sIm5hbWVzIjpbIk5hdmlnYXRpb24iLCJOYXZpZ2F0aW9uLmNvbnN0cnVjdG9yIiwiTmF2aWdhdGlvbi5nZXRJdGVtcyJdLCJtYXBwaW5ncyI6Ijs7Ozs7Ozs7Ozs7Ozs7QUFBQSx5QkFBcUIsbUJBQW1CLENBQUMsQ0FBQTtBQUN6Qyx1QkFBcUIsaUJBQWlCLENBQUMsQ0FBQTtBQUV2QztJQUVDQSxvQkFBbUNBLE1BQWNBO1FBQWRDLFdBQU1BLEdBQU5BLE1BQU1BLENBQVFBO0lBQ2pEQSxDQUFDQTtJQUVERCw2QkFBUUEsR0FBUkE7UUFDQ0UsSUFBSUEsS0FBS0EsR0FBZ0JBLE1BQU1BLENBQUNBLEtBQUtBLENBQUNBLFVBQVVBLENBQUNBO1FBQ2pEQSxFQUFFQSxDQUFBQSxDQUFDQSxDQUFDQSxLQUFLQSxDQUFDQTtZQUNUQSxNQUFNQSxDQUFDQSxFQUFFQSxDQUFDQTtRQUVYQSxHQUFHQSxDQUFBQSxDQUFhQSxVQUFLQSxFQUFqQkEsaUJBQVFBLEVBQVJBLElBQWlCQSxDQUFDQTtZQUFsQkEsSUFBSUEsSUFBSUEsR0FBSUEsS0FBS0EsSUFBVEE7WUFDWEEsRUFBRUEsQ0FBQUEsQ0FBQ0EsSUFBSUEsQ0FBQ0EsTUFBTUEsQ0FBQ0EscUJBQXFCQSxJQUFJQSxJQUFJQSxDQUFDQSxJQUFJQSxDQUFDQTtnQkFDakRBLElBQUlBLENBQUNBLE1BQU1BLEdBQUdBLElBQUlBLENBQUNBO1lBQ3BCQSxJQUFJQTtnQkFDSEEsSUFBSUEsQ0FBQ0EsTUFBTUEsR0FBR0EsS0FBS0EsQ0FBQ0E7U0FDckJBO1FBQ0RBLE1BQU1BLENBQUNBLEtBQUtBLENBQUNBO0lBQ2RBLENBQUNBO0lBakJGRjtRQUVhQSxXQUFDQSxpQkFBTUEsQ0FBQ0EsZUFBTUEsQ0FBQ0EsQ0FBQUE7O21CQWlCM0JBO0lBQURBLGlCQUFDQTtBQUFEQSxDQW5CQSxJQW1CQztBQW5CWSxrQkFBVSxhQW1CdEIsQ0FBQSIsImZpbGUiOiJzcmMvc2VydmljZXMvbmF2aWdhdGlvbi5qcyIsInNvdXJjZXNDb250ZW50IjpbImltcG9ydCB7SW5qZWN0fSBmcm9tICdhbmd1bGFyMi9hbmd1bGFyMic7XG5pbXBvcnQge1JvdXRlcn0gZnJvbSAnYW5ndWxhcjIvcm91dGVyJztcblxuZXhwb3J0IGNsYXNzIE5hdmlnYXRpb24ge1xuXG5cdGNvbnN0cnVjdG9yKEBJbmplY3QoUm91dGVyKSBwdWJsaWMgcm91dGVyOiBSb3V0ZXIpe1xuXHR9XG5cblx0Z2V0SXRlbXMoKSA6IEFycmF5PGFueT4ge1xuXHRcdHZhciBpdGVtcyA6IEFycmF5PGFueT4gPSB3aW5kb3cuTWluZHMubmF2aWdhdGlvbjtcblx0XHRpZighaXRlbXMpXG5cdFx0XHRyZXR1cm4gW107XG5cblx0XHRmb3IodmFyIGl0ZW0gb2YgaXRlbXMpe1xuXHRcdFx0aWYodGhpcy5yb3V0ZXIubGFzdE5hdmlnYXRpb25BdHRlbXB0ID09IGl0ZW0ucGF0aClcblx0XHRcdFx0aXRlbS5hY3RpdmUgPSB0cnVlO1xuXHRcdFx0ZWxzZVxuXHRcdFx0XHRpdGVtLmFjdGl2ZSA9IGZhbHNlO1xuXHRcdH1cblx0XHRyZXR1cm4gaXRlbXM7XG5cdH1cblxufVxuIl0sInNvdXJjZVJvb3QiOiIvc291cmNlLyJ9