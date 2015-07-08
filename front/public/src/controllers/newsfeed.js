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
var api_1 = require('src/services/api');
var Newsfeed = (function () {
    function Newsfeed(api) {
        this.api = api;
        this.load();
    }
    Newsfeed.prototype.load = function () {
        this.api.get();
    };
    Newsfeed = __decorate([
        angular2_1.Component({
            selector: 'minds-newsfeed',
            viewInjector: [api_1.Api]
        }),
        angular2_1.View({
            templateUrl: 'templates/newsfeed/list.html'
        }), 
        __metadata('design:paramtypes', [api_1.Api])
    ], Newsfeed);
    return Newsfeed;
})();
exports.Newsfeed = Newsfeed;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9jb250cm9sbGVycy9uZXdzZmVlZC50cyJdLCJuYW1lcyI6WyJOZXdzZmVlZCIsIk5ld3NmZWVkLmNvbnN0cnVjdG9yIiwiTmV3c2ZlZWQubG9hZCJdLCJtYXBwaW5ncyI6Ijs7Ozs7Ozs7Ozs7QUFBQSx5QkFBOEIsbUJBQW1CLENBQUMsQ0FBQTtBQUVsRCxvQkFBa0Isa0JBQWtCLENBQUMsQ0FBQTtBQUVyQztJQVVDQSxrQkFBbUJBLEdBQVFBO1FBQVJDLFFBQUdBLEdBQUhBLEdBQUdBLENBQUtBO1FBQzFCQSxJQUFJQSxDQUFDQSxJQUFJQSxFQUFFQSxDQUFDQTtJQUNiQSxDQUFDQTtJQUVERCx1QkFBSUEsR0FBSkE7UUFDQ0UsSUFBSUEsQ0FBQ0EsR0FBR0EsQ0FBQ0EsR0FBR0EsRUFBRUEsQ0FBQ0E7SUFDaEJBLENBQUNBO0lBaEJGRjtRQUFDQSxvQkFBU0EsQ0FBQ0E7WUFDVEEsUUFBUUEsRUFBRUEsZ0JBQWdCQTtZQUMxQkEsWUFBWUEsRUFBRUEsQ0FBQ0EsU0FBR0EsQ0FBQ0E7U0FDcEJBLENBQUNBO1FBQ0RBLGVBQUlBLENBQUNBO1lBQ0pBLFdBQVdBLEVBQUVBLDhCQUE4QkE7U0FDNUNBLENBQUNBOztpQkFXREE7SUFBREEsZUFBQ0E7QUFBREEsQ0FqQkEsSUFpQkM7QUFUWSxnQkFBUSxXQVNwQixDQUFBIiwiZmlsZSI6InNyYy9jb250cm9sbGVycy9uZXdzZmVlZC5qcyIsInNvdXJjZXNDb250ZW50IjpbImltcG9ydCB7Q29tcG9uZW50LCBWaWV3fSBmcm9tICdhbmd1bGFyMi9hbmd1bGFyMic7XG5pbXBvcnQge0luamVjdH0gZnJvbSAnYW5ndWxhcjIvZGknO1xuaW1wb3J0IHtBcGl9IGZyb20gJ3NyYy9zZXJ2aWNlcy9hcGknO1xuXG5AQ29tcG9uZW50KHtcbiAgc2VsZWN0b3I6ICdtaW5kcy1uZXdzZmVlZCcsXG4gIHZpZXdJbmplY3RvcjogW0FwaV1cbn0pXG5AVmlldyh7XG4gIHRlbXBsYXRlVXJsOiAndGVtcGxhdGVzL25ld3NmZWVkL2xpc3QuaHRtbCdcbn0pXG5cbmV4cG9ydCBjbGFzcyBOZXdzZmVlZCB7XG5cblx0Y29uc3RydWN0b3IocHVibGljIGFwaTogQXBpKXtcblx0XHR0aGlzLmxvYWQoKTtcblx0fVxuXG5cdGxvYWQoKXtcblx0XHR0aGlzLmFwaS5nZXQoKTtcblx0fVxufSJdLCJzb3VyY2VSb290IjoiL3NvdXJjZS8ifQ==