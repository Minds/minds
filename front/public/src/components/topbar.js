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
var angular2_1 = require('angular2/angular2');
var storage_1 = require('src/services/storage');
var Topbar = (function () {
    function Topbar(storage) {
        this.storage = storage;
    }
    Topbar.prototype.isLoggedIn = function () {
        console.log('checking ng-if');
        if (this.storage.get('loggedin'))
            return true;
        return false;
    };
    Topbar = __decorate([
        angular2_1.Component({
            selector: 'minds-topbar',
            viewInjector: [storage_1.Storage]
        }),
        angular2_1.View({
            templateUrl: 'templates/components/topbar.html',
            directives: [angular2_1.NgIf]
        }), 
        __metadata('design:paramtypes', [storage_1.Storage])
    ], Topbar);
    return Topbar;
})();
exports.Topbar = Topbar;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9jb21wb25lbnRzL3RvcGJhci50cyJdLCJuYW1lcyI6WyJUb3BiYXIiLCJUb3BiYXIuY29uc3RydWN0b3IiLCJUb3BiYXIuaXNMb2dnZWRJbiJdLCJtYXBwaW5ncyI6Ijs7Ozs7Ozs7Ozs7QUFBQSx5QkFBb0MsbUJBQW1CLENBQUMsQ0FBQTtBQUN4RCx3QkFBc0Isc0JBQXNCLENBQUMsQ0FBQTtBQUU3QztJQVVDQSxnQkFBbUJBLE9BQWdCQTtRQUFoQkMsWUFBT0EsR0FBUEEsT0FBT0EsQ0FBU0E7SUFFbkNBLENBQUNBO0lBQ0RELDJCQUFVQSxHQUFWQTtRQUNDRSxPQUFPQSxDQUFDQSxHQUFHQSxDQUFDQSxnQkFBZ0JBLENBQUNBLENBQUNBO1FBQzlCQSxFQUFFQSxDQUFBQSxDQUFDQSxJQUFJQSxDQUFDQSxPQUFPQSxDQUFDQSxHQUFHQSxDQUFDQSxVQUFVQSxDQUFDQSxDQUFDQTtZQUMvQkEsTUFBTUEsQ0FBQ0EsSUFBSUEsQ0FBQ0E7UUFDYkEsTUFBTUEsQ0FBQ0EsS0FBS0EsQ0FBQ0E7SUFDZEEsQ0FBQ0E7SUFsQkZGO1FBQUNBLG9CQUFTQSxDQUFDQTtZQUNUQSxRQUFRQSxFQUFFQSxjQUFjQTtZQUN4QkEsWUFBWUEsRUFBRUEsQ0FBQ0EsaUJBQU9BLENBQUNBO1NBQ3hCQSxDQUFDQTtRQUNEQSxlQUFJQSxDQUFDQTtZQUNKQSxXQUFXQSxFQUFFQSxrQ0FBa0NBO1lBQy9DQSxVQUFVQSxFQUFFQSxDQUFDQSxlQUFJQSxDQUFDQTtTQUNuQkEsQ0FBQ0E7O2VBWURBO0lBQURBLGFBQUNBO0FBQURBLENBbkJBLEFBbUJDQSxJQUFBO0FBVlksY0FBTSxTQVVsQixDQUFBIiwiZmlsZSI6InNyYy9jb21wb25lbnRzL3RvcGJhci5qcyIsInNvdXJjZXNDb250ZW50IjpbImltcG9ydCB7Q29tcG9uZW50LCBWaWV3LCBOZ0lmfSBmcm9tICdhbmd1bGFyMi9hbmd1bGFyMic7XG5pbXBvcnQge1N0b3JhZ2V9IGZyb20gJ3NyYy9zZXJ2aWNlcy9zdG9yYWdlJztcblxuQENvbXBvbmVudCh7XG4gIHNlbGVjdG9yOiAnbWluZHMtdG9wYmFyJyxcbiAgdmlld0luamVjdG9yOiBbU3RvcmFnZV1cbn0pXG5AVmlldyh7XG4gIHRlbXBsYXRlVXJsOiAndGVtcGxhdGVzL2NvbXBvbmVudHMvdG9wYmFyLmh0bWwnLFxuICBkaXJlY3RpdmVzOiBbTmdJZl1cbn0pXG5cbmV4cG9ydCBjbGFzcyBUb3BiYXIgeyBcblx0Y29uc3RydWN0b3IocHVibGljIHN0b3JhZ2U6IFN0b3JhZ2Upe1xuXHRcdFxuXHR9XG5cdGlzTG9nZ2VkSW4oKXtcblx0XHRjb25zb2xlLmxvZygnY2hlY2tpbmcgbmctaWYnKTtcblx0XHRpZih0aGlzLnN0b3JhZ2UuZ2V0KCdsb2dnZWRpbicpKVxuXHRcdFx0cmV0dXJuIHRydWU7XG5cdFx0cmV0dXJuIGZhbHNlO1xuXHR9XG59Il0sInNvdXJjZVJvb3QiOiIvc291cmNlLyJ9