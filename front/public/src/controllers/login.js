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
var Login = (function () {
    function Login(api) {
        this.api = api;
    }
    Login.prototype.login = function (username, password) {
        alert("trying to login");
        console.log(username);
        console.log(password);
    };
    Login = __decorate([
        angular2_1.Component({
            viewInjector: [api_1.Api]
        }),
        angular2_1.View({
            templateUrl: 'templates/login.html'
        }), 
        __metadata('design:paramtypes', [api_1.Api])
    ], Login);
    return Login;
})();
exports.Login = Login;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9jb250cm9sbGVycy9sb2dpbi50cyJdLCJuYW1lcyI6WyJMb2dpbiIsIkxvZ2luLmNvbnN0cnVjdG9yIiwiTG9naW4ubG9naW4iXSwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7O0FBQUEseUJBQThCLG1CQUFtQixDQUFDLENBQUE7QUFFbEQsb0JBQWtCLGtCQUFrQixDQUFDLENBQUE7QUFFckM7SUFTQ0EsZUFBbUJBLEdBQVFBO1FBQVJDLFFBQUdBLEdBQUhBLEdBQUdBLENBQUtBO0lBRTNCQSxDQUFDQTtJQUVERCxxQkFBS0EsR0FBTEEsVUFBTUEsUUFBUUEsRUFBRUEsUUFBUUE7UUFDdkJFLEtBQUtBLENBQUNBLGlCQUFpQkEsQ0FBQ0EsQ0FBQ0E7UUFDekJBLE9BQU9BLENBQUNBLEdBQUdBLENBQUNBLFFBQVFBLENBQUNBLENBQUNBO1FBQ3RCQSxPQUFPQSxDQUFDQSxHQUFHQSxDQUFDQSxRQUFRQSxDQUFDQSxDQUFDQTtJQUN2QkEsQ0FBQ0E7SUFqQkZGO1FBQUNBLG9CQUFTQSxDQUFDQTtZQUNUQSxZQUFZQSxFQUFFQSxDQUFDQSxTQUFHQSxDQUFDQTtTQUNwQkEsQ0FBQ0E7UUFDREEsZUFBSUEsQ0FBQ0E7WUFDSkEsV0FBV0EsRUFBRUEsc0JBQXNCQTtTQUNwQ0EsQ0FBQ0E7O2NBYURBO0lBQURBLFlBQUNBO0FBQURBLENBbEJBLElBa0JDO0FBWFksYUFBSyxRQVdqQixDQUFBIiwiZmlsZSI6InNyYy9jb250cm9sbGVycy9sb2dpbi5qcyIsInNvdXJjZXNDb250ZW50IjpbImltcG9ydCB7Q29tcG9uZW50LCBWaWV3fSBmcm9tICdhbmd1bGFyMi9hbmd1bGFyMic7XG5pbXBvcnQge0luamVjdH0gZnJvbSAnYW5ndWxhcjIvZGknO1xuaW1wb3J0IHtBcGl9IGZyb20gJ3NyYy9zZXJ2aWNlcy9hcGknO1xuXG5AQ29tcG9uZW50KHtcbiAgdmlld0luamVjdG9yOiBbQXBpXVxufSlcbkBWaWV3KHtcbiAgdGVtcGxhdGVVcmw6ICd0ZW1wbGF0ZXMvbG9naW4uaHRtbCdcbn0pXG5cbmV4cG9ydCBjbGFzcyBMb2dpbiB7XG5cblx0Y29uc3RydWN0b3IocHVibGljIGFwaTogQXBpKXtcblx0XHRcblx0fVxuXG5cdGxvZ2luKHVzZXJuYW1lLCBwYXNzd29yZCl7XG5cdFx0YWxlcnQoXCJ0cnlpbmcgdG8gbG9naW5cIik7XG5cdFx0Y29uc29sZS5sb2codXNlcm5hbWUpO1xuXHRcdGNvbnNvbGUubG9nKHBhc3N3b3JkKTtcblx0fVxufSJdLCJzb3VyY2VSb290IjoiL3NvdXJjZS8ifQ==