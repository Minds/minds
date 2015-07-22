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
            if (this.router.lastNavigationAttempt == item.path || this.router.lastNavigationAttempt.indexOf(item.path) > -1)
                item.active = true;
            else
                item.active = false;
            if (item.submenus) {
                for (var _a = 0, _b = item.submenus; _a < _b.length; _a++) {
                    var subitem = _b[_a];
                    var path = subitem.path;
                    for (var p in subitem.params) {
                        path += '/' + subitem.params[p];
                    }
                    if (this.router.lastNavigationAttempt.indexOf(path) > -1)
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

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9zZXJ2aWNlcy9uYXZpZ2F0aW9uLnRzIl0sIm5hbWVzIjpbIk5hdmlnYXRpb24iLCJOYXZpZ2F0aW9uLmNvbnN0cnVjdG9yIiwiTmF2aWdhdGlvbi5nZXRJdGVtcyJdLCJtYXBwaW5ncyI6Ijs7Ozs7Ozs7Ozs7Ozs7QUFBQSx5QkFBcUIsbUJBQW1CLENBQUMsQ0FBQTtBQUN6Qyx1QkFBcUIsaUJBQWlCLENBQUMsQ0FBQTtBQUV2QztJQUVDQSxvQkFBbUNBLE1BQWNBO1FBQWRDLFdBQU1BLEdBQU5BLE1BQU1BLENBQVFBO0lBQ2pEQSxDQUFDQTtJQUVERCw2QkFBUUEsR0FBUkE7UUFDQ0UsSUFBSUEsS0FBS0EsR0FBZ0JBLE1BQU1BLENBQUNBLEtBQUtBLENBQUNBLFVBQVVBLENBQUNBO1FBQ2pEQSxFQUFFQSxDQUFBQSxDQUFDQSxDQUFDQSxLQUFLQSxDQUFDQTtZQUNUQSxNQUFNQSxDQUFDQSxFQUFFQSxDQUFDQTtRQUVYQSxHQUFHQSxDQUFBQSxDQUFhQSxVQUFLQSxFQUFqQkEsaUJBQVFBLEVBQVJBLElBQWlCQSxDQUFDQTtZQUFsQkEsSUFBSUEsSUFBSUEsR0FBSUEsS0FBS0EsSUFBVEE7WUFFWEEsRUFBRUEsQ0FBQUEsQ0FBQ0EsSUFBSUEsQ0FBQ0EsTUFBTUEsQ0FBQ0EscUJBQXFCQSxJQUFJQSxJQUFJQSxDQUFDQSxJQUFJQSxJQUFJQSxJQUFJQSxDQUFDQSxNQUFNQSxDQUFDQSxxQkFBcUJBLENBQUNBLE9BQU9BLENBQUNBLElBQUlBLENBQUNBLElBQUlBLENBQUNBLEdBQUdBLENBQUNBLENBQUNBLENBQUNBO2dCQUM5R0EsSUFBSUEsQ0FBQ0EsTUFBTUEsR0FBR0EsSUFBSUEsQ0FBQ0E7WUFDcEJBLElBQUlBO2dCQUNIQSxJQUFJQSxDQUFDQSxNQUFNQSxHQUFHQSxLQUFLQSxDQUFDQTtZQUlyQkEsRUFBRUEsQ0FBQUEsQ0FBQ0EsSUFBSUEsQ0FBQ0EsUUFBUUEsQ0FBQ0EsQ0FBQUEsQ0FBQ0E7Z0JBQ2pCQSxHQUFHQSxDQUFBQSxDQUFnQkEsVUFBYUEsRUFBYkEsS0FBQUEsSUFBSUEsQ0FBQ0EsUUFBUUEsRUFBNUJBLGNBQVdBLEVBQVhBLElBQTRCQSxDQUFDQTtvQkFBN0JBLElBQUlBLE9BQU9BLFNBQUFBO29CQUNkQSxJQUFJQSxJQUFJQSxHQUFHQSxPQUFPQSxDQUFDQSxJQUFJQSxDQUFDQTtvQkFDeEJBLEdBQUdBLENBQUFBLENBQUNBLEdBQUdBLENBQUNBLENBQUNBLElBQUlBLE9BQU9BLENBQUNBLE1BQU1BLENBQUNBLENBQUFBLENBQUNBO3dCQUM1QkEsSUFBSUEsSUFBS0EsR0FBR0EsR0FBR0EsT0FBT0EsQ0FBQ0EsTUFBTUEsQ0FBQ0EsQ0FBQ0EsQ0FBQ0EsQ0FBQ0E7b0JBQ2xDQSxDQUFDQTtvQkFFREEsRUFBRUEsQ0FBQUEsQ0FBQ0EsSUFBSUEsQ0FBQ0EsTUFBTUEsQ0FBQ0EscUJBQXFCQSxDQUFDQSxPQUFPQSxDQUFDQSxJQUFJQSxDQUFDQSxHQUFHQSxDQUFDQSxDQUFDQSxDQUFDQTt3QkFDdkRBLE9BQU9BLENBQUNBLE1BQU1BLEdBQUdBLElBQUlBLENBQUNBO29CQUN2QkEsSUFBSUE7d0JBQ0hBLE9BQU9BLENBQUNBLE1BQU1BLEdBQUdBLEtBQUtBLENBQUNBO2lCQUN4QkE7WUFDRkEsQ0FBQ0E7U0FDREE7UUFDREEsTUFBTUEsQ0FBQ0EsS0FBS0EsQ0FBQ0E7SUFDZEEsQ0FBQ0E7SUFsQ0ZGO1FBRWFBLFdBQUNBLGlCQUFNQSxDQUFDQSxlQUFNQSxDQUFDQSxDQUFBQTs7bUJBa0MzQkE7SUFBREEsaUJBQUNBO0FBQURBLENBcENBLElBb0NDO0FBcENZLGtCQUFVLGFBb0N0QixDQUFBIiwiZmlsZSI6InNyYy9zZXJ2aWNlcy9uYXZpZ2F0aW9uLmpzIiwic291cmNlc0NvbnRlbnQiOlsiaW1wb3J0IHtJbmplY3R9IGZyb20gJ2FuZ3VsYXIyL2FuZ3VsYXIyJztcbmltcG9ydCB7Um91dGVyfSBmcm9tICdhbmd1bGFyMi9yb3V0ZXInO1xuXG5leHBvcnQgY2xhc3MgTmF2aWdhdGlvbiB7XG5cblx0Y29uc3RydWN0b3IoQEluamVjdChSb3V0ZXIpIHB1YmxpYyByb3V0ZXI6IFJvdXRlcil7XG5cdH1cblxuXHRnZXRJdGVtcygpIDogQXJyYXk8YW55PiB7XG5cdFx0dmFyIGl0ZW1zIDogQXJyYXk8YW55PiA9IHdpbmRvdy5NaW5kcy5uYXZpZ2F0aW9uO1xuXHRcdGlmKCFpdGVtcylcblx0XHRcdHJldHVybiBbXTtcblxuXHRcdGZvcih2YXIgaXRlbSBvZiBpdGVtcyl7XG5cblx0XHRcdGlmKHRoaXMucm91dGVyLmxhc3ROYXZpZ2F0aW9uQXR0ZW1wdCA9PSBpdGVtLnBhdGggfHwgdGhpcy5yb3V0ZXIubGFzdE5hdmlnYXRpb25BdHRlbXB0LmluZGV4T2YoaXRlbS5wYXRoKSA+IC0xKVxuXHRcdFx0XHRpdGVtLmFjdGl2ZSA9IHRydWU7XG5cdFx0XHRlbHNlXG5cdFx0XHRcdGl0ZW0uYWN0aXZlID0gZmFsc2U7XG5cblx0XHRcdC8vIGEgcmVjdXJzaXZlIGZ1bmN0aW9uIG5lZWRzIGNyZWF0aW5nIGhlcmVcblx0XHRcdC8vIGEgYml0IG1lc3N5IGFuZCBvbmx5IGFsbG93cyAxIHRpZXJcblx0XHRcdGlmKGl0ZW0uc3VibWVudXMpe1xuXHRcdFx0XHRmb3IodmFyIHN1Yml0ZW0gb2YgaXRlbS5zdWJtZW51cyl7XG5cdFx0XHRcdFx0dmFyIHBhdGggPSBzdWJpdGVtLnBhdGg7XG5cdFx0XHRcdFx0Zm9yKHZhciBwIGluIHN1Yml0ZW0ucGFyYW1zKXtcblx0XHRcdFx0XHRcdHBhdGggKz0gICcvJyArIHN1Yml0ZW0ucGFyYW1zW3BdO1xuXHRcdFx0XHRcdH1cblxuXHRcdFx0XHRcdGlmKHRoaXMucm91dGVyLmxhc3ROYXZpZ2F0aW9uQXR0ZW1wdC5pbmRleE9mKHBhdGgpID4gLTEpXG5cdFx0XHRcdFx0XHRzdWJpdGVtLmFjdGl2ZSA9IHRydWU7XG5cdFx0XHRcdFx0ZWxzZVxuXHRcdFx0XHRcdFx0c3ViaXRlbS5hY3RpdmUgPSBmYWxzZTtcblx0XHRcdFx0fVxuXHRcdFx0fVxuXHRcdH1cblx0XHRyZXR1cm4gaXRlbXM7XG5cdH1cblxufVxuIl0sInNvdXJjZVJvb3QiOiIvc291cmNlLyJ9