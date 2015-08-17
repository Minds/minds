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
    function Navigation(router, location) {
        this.router = router;
        this.location = location;
    }
    Navigation.prototype.getItems = function () {
        var items = window.Minds.navigation;
        if (!items)
            return [];
        var path = this.location.path();
        for (var _i = 0; _i < items.length; _i++) {
            var item = items[_i];
            if (path == item.path || (path && path.indexOf(item.path) > -1))
                item.active = true;
            else
                item.active = false;
            if (item.submenus) {
                for (var _a = 0, _b = item.submenus; _a < _b.length; _a++) {
                    var subitem = _b[_a];
                    var sub_path = subitem.path;
                    for (var p in subitem.params) {
                        if (subitem.params[p])
                            sub_path += '/' + subitem.params[p];
                    }
                    if (path && path.indexOf(sub_path) > -1)
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
        __param(1, angular2_1.Inject(router_1.Location)), 
        __metadata('design:paramtypes', [router_1.Router, router_1.Location])
    ], Navigation);
    return Navigation;
})();
exports.Navigation = Navigation;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9zZXJ2aWNlcy9uYXZpZ2F0aW9uLnRzIl0sIm5hbWVzIjpbIk5hdmlnYXRpb24iLCJOYXZpZ2F0aW9uLmNvbnN0cnVjdG9yIiwiTmF2aWdhdGlvbi5nZXRJdGVtcyJdLCJtYXBwaW5ncyI6Ijs7Ozs7Ozs7Ozs7Ozs7QUFBQSx5QkFBcUIsbUJBQW1CLENBQUMsQ0FBQTtBQUN6Qyx1QkFBK0IsaUJBQWlCLENBQUMsQ0FBQTtBQUVqRDtJQUVDQSxvQkFBbUNBLE1BQWNBLEVBQTJCQSxRQUFrQkE7UUFBM0RDLFdBQU1BLEdBQU5BLE1BQU1BLENBQVFBO1FBQTJCQSxhQUFRQSxHQUFSQSxRQUFRQSxDQUFVQTtJQUM5RkEsQ0FBQ0E7SUFFREQsNkJBQVFBLEdBQVJBO1FBRUNFLElBQUlBLEtBQUtBLEdBQWdCQSxNQUFNQSxDQUFDQSxLQUFLQSxDQUFDQSxVQUFVQSxDQUFDQTtRQUNqREEsRUFBRUEsQ0FBQUEsQ0FBQ0EsQ0FBQ0EsS0FBS0EsQ0FBQ0E7WUFDVEEsTUFBTUEsQ0FBQ0EsRUFBRUEsQ0FBQ0E7UUFFWEEsSUFBSUEsSUFBSUEsR0FBR0EsSUFBSUEsQ0FBQ0EsUUFBUUEsQ0FBQ0EsSUFBSUEsRUFBRUEsQ0FBQ0E7UUFDaENBLEdBQUdBLENBQUFBLENBQWFBLFVBQUtBLEVBQWpCQSxpQkFBUUEsRUFBUkEsSUFBaUJBLENBQUNBO1lBQWxCQSxJQUFJQSxJQUFJQSxHQUFJQSxLQUFLQSxJQUFUQTtZQUVYQSxFQUFFQSxDQUFBQSxDQUFDQSxJQUFJQSxJQUFJQSxJQUFJQSxDQUFDQSxJQUFJQSxJQUFJQSxDQUFDQSxJQUFJQSxJQUFJQSxJQUFJQSxDQUFDQSxPQUFPQSxDQUFDQSxJQUFJQSxDQUFDQSxJQUFJQSxDQUFDQSxHQUFHQSxDQUFDQSxDQUFDQSxDQUFDQSxDQUFDQTtnQkFDOURBLElBQUlBLENBQUNBLE1BQU1BLEdBQUdBLElBQUlBLENBQUNBO1lBQ3BCQSxJQUFJQTtnQkFDSEEsSUFBSUEsQ0FBQ0EsTUFBTUEsR0FBR0EsS0FBS0EsQ0FBQ0E7WUFJckJBLEVBQUVBLENBQUFBLENBQUNBLElBQUlBLENBQUNBLFFBQVFBLENBQUNBLENBQUFBLENBQUNBO2dCQUNqQkEsR0FBR0EsQ0FBQUEsQ0FBZ0JBLFVBQWFBLEVBQWJBLEtBQUFBLElBQUlBLENBQUNBLFFBQVFBLEVBQTVCQSxjQUFXQSxFQUFYQSxJQUE0QkEsQ0FBQ0E7b0JBQTdCQSxJQUFJQSxPQUFPQSxTQUFBQTtvQkFDZEEsSUFBSUEsUUFBUUEsR0FBR0EsT0FBT0EsQ0FBQ0EsSUFBSUEsQ0FBQ0E7b0JBQzVCQSxHQUFHQSxDQUFBQSxDQUFDQSxHQUFHQSxDQUFDQSxDQUFDQSxJQUFJQSxPQUFPQSxDQUFDQSxNQUFNQSxDQUFDQSxDQUFBQSxDQUFDQTt3QkFDNUJBLEVBQUVBLENBQUFBLENBQUNBLE9BQU9BLENBQUNBLE1BQU1BLENBQUNBLENBQUNBLENBQUNBLENBQUNBOzRCQUNwQkEsUUFBUUEsSUFBS0EsR0FBR0EsR0FBR0EsT0FBT0EsQ0FBQ0EsTUFBTUEsQ0FBQ0EsQ0FBQ0EsQ0FBQ0EsQ0FBQ0E7b0JBQ3ZDQSxDQUFDQTtvQkFDREEsRUFBRUEsQ0FBQUEsQ0FBQ0EsSUFBSUEsSUFBSUEsSUFBSUEsQ0FBQ0EsT0FBT0EsQ0FBQ0EsUUFBUUEsQ0FBQ0EsR0FBR0EsQ0FBQ0EsQ0FBQ0EsQ0FBQ0E7d0JBQ3RDQSxPQUFPQSxDQUFDQSxNQUFNQSxHQUFHQSxJQUFJQSxDQUFDQTtvQkFDdkJBLElBQUlBO3dCQUNIQSxPQUFPQSxDQUFDQSxNQUFNQSxHQUFHQSxLQUFLQSxDQUFDQTtpQkFDeEJBO1lBQ0ZBLENBQUNBO1NBQ0RBO1FBQ0RBLE1BQU1BLENBQUNBLEtBQUtBLENBQUNBO0lBQ2RBLENBQUNBO0lBcENGRjtRQUVhQSxXQUFDQSxpQkFBTUEsQ0FBQ0EsZUFBTUEsQ0FBQ0EsQ0FBQUE7UUFBd0JBLFdBQUNBLGlCQUFNQSxDQUFDQSxpQkFBUUEsQ0FBQ0EsQ0FBQUE7O21CQW9DcEVBO0lBQURBLGlCQUFDQTtBQUFEQSxDQXRDQSxBQXNDQ0EsSUFBQTtBQXRDWSxrQkFBVSxhQXNDdEIsQ0FBQSIsImZpbGUiOiJzcmMvc2VydmljZXMvbmF2aWdhdGlvbi5qcyIsInNvdXJjZXNDb250ZW50IjpbImltcG9ydCB7SW5qZWN0fSBmcm9tICdhbmd1bGFyMi9hbmd1bGFyMic7XG5pbXBvcnQge1JvdXRlciwgTG9jYXRpb259IGZyb20gJ2FuZ3VsYXIyL3JvdXRlcic7XG5cbmV4cG9ydCBjbGFzcyBOYXZpZ2F0aW9uIHtcblxuXHRjb25zdHJ1Y3RvcihASW5qZWN0KFJvdXRlcikgcHVibGljIHJvdXRlcjogUm91dGVyLCBASW5qZWN0KExvY2F0aW9uKSBwdWJsaWMgbG9jYXRpb246IExvY2F0aW9uKXtcblx0fVxuXG5cdGdldEl0ZW1zKCkgOiBBcnJheTxhbnk+IHtcblxuXHRcdHZhciBpdGVtcyA6IEFycmF5PGFueT4gPSB3aW5kb3cuTWluZHMubmF2aWdhdGlvbjtcblx0XHRpZighaXRlbXMpXG5cdFx0XHRyZXR1cm4gW107XG5cblx0XHR2YXIgcGF0aCA9IHRoaXMubG9jYXRpb24ucGF0aCgpO1xuXHRcdGZvcih2YXIgaXRlbSBvZiBpdGVtcyl7XG5cblx0XHRcdGlmKHBhdGggPT0gaXRlbS5wYXRoIHx8IChwYXRoICYmIHBhdGguaW5kZXhPZihpdGVtLnBhdGgpID4gLTEpKVxuXHRcdFx0XHRpdGVtLmFjdGl2ZSA9IHRydWU7XG5cdFx0XHRlbHNlXG5cdFx0XHRcdGl0ZW0uYWN0aXZlID0gZmFsc2U7XG5cblx0XHRcdC8vIGEgcmVjdXJzaXZlIGZ1bmN0aW9uIG5lZWRzIGNyZWF0aW5nIGhlcmVcblx0XHRcdC8vIGEgYml0IG1lc3N5IGFuZCBvbmx5IGFsbG93cyAxIHRpZXJcblx0XHRcdGlmKGl0ZW0uc3VibWVudXMpe1xuXHRcdFx0XHRmb3IodmFyIHN1Yml0ZW0gb2YgaXRlbS5zdWJtZW51cyl7XG5cdFx0XHRcdFx0dmFyIHN1Yl9wYXRoID0gc3ViaXRlbS5wYXRoO1xuXHRcdFx0XHRcdGZvcih2YXIgcCBpbiBzdWJpdGVtLnBhcmFtcyl7XG5cdFx0XHRcdFx0XHRpZihzdWJpdGVtLnBhcmFtc1twXSlcblx0XHRcdFx0XHRcdFx0c3ViX3BhdGggKz0gICcvJyArIHN1Yml0ZW0ucGFyYW1zW3BdO1xuXHRcdFx0XHRcdH1cblx0XHRcdFx0XHRpZihwYXRoICYmIHBhdGguaW5kZXhPZihzdWJfcGF0aCkgPiAtMSlcblx0XHRcdFx0XHRcdHN1Yml0ZW0uYWN0aXZlID0gdHJ1ZTtcblx0XHRcdFx0XHRlbHNlXG5cdFx0XHRcdFx0XHRzdWJpdGVtLmFjdGl2ZSA9IGZhbHNlO1xuXHRcdFx0XHR9XG5cdFx0XHR9XG5cdFx0fVxuXHRcdHJldHVybiBpdGVtcztcblx0fVxuXG59XG4iXSwic291cmNlUm9vdCI6Ii9zb3VyY2UvIn0=