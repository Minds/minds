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
var Navigation = (function () {
    function Navigation(router) {
        this.router = router;
    }
    Navigation.prototype.getItems = function () {
        var items = window.Minds.navigation;
        if (!items)
            return [];
        var last = this.router.lastNavigationAttempt;
        for (var _i = 0; _i < items.length; _i++) {
            var item = items[_i];
            if (this.router.lastNavigationAttempt == item.path || (last && last.indexOf(item.path) > -1))
                item.active = true;
            else
                item.active = false;
            if (item.submenus) {
                for (var _a = 0, _b = item.submenus; _a < _b.length; _a++) {
                    var subitem = _b[_a];
                    var path = subitem.path;
                    for (var p in subitem.params) {
                        if (subitem.params[p])
                            path += '/' + subitem.params[p];
                    }
                    if (last && last.indexOf(path) > -1)
                        subitem.active = true;
                    else
                        subitem.active = false;
                }
            }
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

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9zZXJ2aWNlcy9uYXZpZ2F0aW9uLnRzIl0sIm5hbWVzIjpbIk5hdmlnYXRpb24iLCJOYXZpZ2F0aW9uLmNvbnN0cnVjdG9yIiwiTmF2aWdhdGlvbi5nZXRJdGVtcyJdLCJtYXBwaW5ncyI6Ijs7Ozs7Ozs7Ozs7Ozs7QUFBQSx5QkFBcUIsbUJBQW1CLENBQUMsQ0FBQTtBQUN6Qyx1QkFBcUIsaUJBQWlCLENBQUMsQ0FBQTtBQUV2QztJQUVDQSxvQkFBbUNBLE1BQWNBO1FBQWRDLFdBQU1BLEdBQU5BLE1BQU1BLENBQVFBO0lBQ2pEQSxDQUFDQTtJQUVERCw2QkFBUUEsR0FBUkE7UUFDQ0UsSUFBSUEsS0FBS0EsR0FBZ0JBLE1BQU1BLENBQUNBLEtBQUtBLENBQUNBLFVBQVVBLENBQUNBO1FBQ2pEQSxFQUFFQSxDQUFBQSxDQUFDQSxDQUFDQSxLQUFLQSxDQUFDQTtZQUNUQSxNQUFNQSxDQUFDQSxFQUFFQSxDQUFDQTtRQUVYQSxJQUFJQSxJQUFJQSxHQUFHQSxJQUFJQSxDQUFDQSxNQUFNQSxDQUFDQSxxQkFBcUJBLENBQUNBO1FBQzdDQSxHQUFHQSxDQUFBQSxDQUFhQSxVQUFLQSxFQUFqQkEsaUJBQVFBLEVBQVJBLElBQWlCQSxDQUFDQTtZQUFsQkEsSUFBSUEsSUFBSUEsR0FBSUEsS0FBS0EsSUFBVEE7WUFFWEEsRUFBRUEsQ0FBQUEsQ0FBQ0EsSUFBSUEsQ0FBQ0EsTUFBTUEsQ0FBQ0EscUJBQXFCQSxJQUFJQSxJQUFJQSxDQUFDQSxJQUFJQSxJQUFJQSxDQUFDQSxJQUFJQSxJQUFJQSxJQUFJQSxDQUFDQSxPQUFPQSxDQUFDQSxJQUFJQSxDQUFDQSxJQUFJQSxDQUFDQSxHQUFHQSxDQUFDQSxDQUFDQSxDQUFDQSxDQUFDQTtnQkFDM0ZBLElBQUlBLENBQUNBLE1BQU1BLEdBQUdBLElBQUlBLENBQUNBO1lBQ3BCQSxJQUFJQTtnQkFDSEEsSUFBSUEsQ0FBQ0EsTUFBTUEsR0FBR0EsS0FBS0EsQ0FBQ0E7WUFJckJBLEVBQUVBLENBQUFBLENBQUNBLElBQUlBLENBQUNBLFFBQVFBLENBQUNBLENBQUFBLENBQUNBO2dCQUNqQkEsR0FBR0EsQ0FBQUEsQ0FBZ0JBLFVBQWFBLEVBQWJBLEtBQUFBLElBQUlBLENBQUNBLFFBQVFBLEVBQTVCQSxjQUFXQSxFQUFYQSxJQUE0QkEsQ0FBQ0E7b0JBQTdCQSxJQUFJQSxPQUFPQSxTQUFBQTtvQkFDZEEsSUFBSUEsSUFBSUEsR0FBR0EsT0FBT0EsQ0FBQ0EsSUFBSUEsQ0FBQ0E7b0JBQ3hCQSxHQUFHQSxDQUFBQSxDQUFDQSxHQUFHQSxDQUFDQSxDQUFDQSxJQUFJQSxPQUFPQSxDQUFDQSxNQUFNQSxDQUFDQSxDQUFBQSxDQUFDQTt3QkFDNUJBLEVBQUVBLENBQUFBLENBQUNBLE9BQU9BLENBQUNBLE1BQU1BLENBQUNBLENBQUNBLENBQUNBLENBQUNBOzRCQUNwQkEsSUFBSUEsSUFBS0EsR0FBR0EsR0FBR0EsT0FBT0EsQ0FBQ0EsTUFBTUEsQ0FBQ0EsQ0FBQ0EsQ0FBQ0EsQ0FBQ0E7b0JBQ25DQSxDQUFDQTtvQkFDREEsRUFBRUEsQ0FBQUEsQ0FBQ0EsSUFBSUEsSUFBSUEsSUFBSUEsQ0FBQ0EsT0FBT0EsQ0FBQ0EsSUFBSUEsQ0FBQ0EsR0FBR0EsQ0FBQ0EsQ0FBQ0EsQ0FBQ0E7d0JBQ2xDQSxPQUFPQSxDQUFDQSxNQUFNQSxHQUFHQSxJQUFJQSxDQUFDQTtvQkFDdkJBLElBQUlBO3dCQUNIQSxPQUFPQSxDQUFDQSxNQUFNQSxHQUFHQSxLQUFLQSxDQUFDQTtpQkFDeEJBO1lBQ0ZBLENBQUNBO1NBQ0RBO1FBQ0RBLE1BQU1BLENBQUNBLEtBQUtBLENBQUNBO0lBQ2RBLENBQUNBO0lBbkNGRjtRQUVhQSxXQUFDQSxpQkFBTUEsQ0FBQ0EsZUFBTUEsQ0FBQ0EsQ0FBQUE7O21CQW1DM0JBO0lBQURBLGlCQUFDQTtBQUFEQSxDQXJDQSxBQXFDQ0EsSUFBQTtBQXJDWSxrQkFBVSxhQXFDdEIsQ0FBQSIsImZpbGUiOiJzcmMvc2VydmljZXMvbmF2aWdhdGlvbi5qcyIsInNvdXJjZXNDb250ZW50IjpbImltcG9ydCB7SW5qZWN0fSBmcm9tICdhbmd1bGFyMi9hbmd1bGFyMic7XG5pbXBvcnQge1JvdXRlcn0gZnJvbSAnYW5ndWxhcjIvcm91dGVyJztcblxuZXhwb3J0IGNsYXNzIE5hdmlnYXRpb24ge1xuXG5cdGNvbnN0cnVjdG9yKEBJbmplY3QoUm91dGVyKSBwdWJsaWMgcm91dGVyOiBSb3V0ZXIpe1xuXHR9XG5cblx0Z2V0SXRlbXMoKSA6IEFycmF5PGFueT4ge1xuXHRcdHZhciBpdGVtcyA6IEFycmF5PGFueT4gPSB3aW5kb3cuTWluZHMubmF2aWdhdGlvbjtcblx0XHRpZighaXRlbXMpXG5cdFx0XHRyZXR1cm4gW107XG5cblx0XHR2YXIgbGFzdCA9IHRoaXMucm91dGVyLmxhc3ROYXZpZ2F0aW9uQXR0ZW1wdDtcblx0XHRmb3IodmFyIGl0ZW0gb2YgaXRlbXMpe1xuXG5cdFx0XHRpZih0aGlzLnJvdXRlci5sYXN0TmF2aWdhdGlvbkF0dGVtcHQgPT0gaXRlbS5wYXRoIHx8IChsYXN0ICYmIGxhc3QuaW5kZXhPZihpdGVtLnBhdGgpID4gLTEpKVxuXHRcdFx0XHRpdGVtLmFjdGl2ZSA9IHRydWU7XG5cdFx0XHRlbHNlXG5cdFx0XHRcdGl0ZW0uYWN0aXZlID0gZmFsc2U7XG5cblx0XHRcdC8vIGEgcmVjdXJzaXZlIGZ1bmN0aW9uIG5lZWRzIGNyZWF0aW5nIGhlcmVcblx0XHRcdC8vIGEgYml0IG1lc3N5IGFuZCBvbmx5IGFsbG93cyAxIHRpZXJcblx0XHRcdGlmKGl0ZW0uc3VibWVudXMpe1xuXHRcdFx0XHRmb3IodmFyIHN1Yml0ZW0gb2YgaXRlbS5zdWJtZW51cyl7XG5cdFx0XHRcdFx0dmFyIHBhdGggPSBzdWJpdGVtLnBhdGg7XG5cdFx0XHRcdFx0Zm9yKHZhciBwIGluIHN1Yml0ZW0ucGFyYW1zKXtcblx0XHRcdFx0XHRcdGlmKHN1Yml0ZW0ucGFyYW1zW3BdKVxuXHRcdFx0XHRcdFx0XHRwYXRoICs9ICAnLycgKyBzdWJpdGVtLnBhcmFtc1twXTtcblx0XHRcdFx0XHR9XG5cdFx0XHRcdFx0aWYobGFzdCAmJiBsYXN0LmluZGV4T2YocGF0aCkgPiAtMSlcblx0XHRcdFx0XHRcdHN1Yml0ZW0uYWN0aXZlID0gdHJ1ZTtcblx0XHRcdFx0XHRlbHNlXG5cdFx0XHRcdFx0XHRzdWJpdGVtLmFjdGl2ZSA9IGZhbHNlO1xuXHRcdFx0XHR9XG5cdFx0XHR9XG5cdFx0fVxuXHRcdHJldHVybiBpdGVtcztcblx0fVxuXG59XG4iXSwic291cmNlUm9vdCI6Ii9zb3VyY2UvIn0=