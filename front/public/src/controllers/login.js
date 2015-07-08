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
var di_1 = require('angular2/di');
var Login = (function () {
    function Login(api, oauth, router) {
        this.api = api;
        this.oauth = oauth;
        this.router = router;
    }
    Login.prototype.login = function (username, password) {
        this.oauth.login()
            .then(function () {
            this.router.parent.navigate('/newsfeed');
        })
            .catch(function (e) {
            alert('there was a problem');
            console.log(e);
        });
    };
    Login = __decorate([
        angular2_1.Component({
            viewInjector: [api_1.Api, api_1.OAuth]
        }),
        angular2_1.View({
            templateUrl: 'templates/login.html'
        }),
        __param(2, di_1.Inject(router_1.Router)), 
        __metadata('design:paramtypes', [api_1.Api, api_1.OAuth, (typeof Router !== 'undefined' && Router) || Object])
    ], Login);
    return Login;
})();
exports.Login = Login;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9jb250cm9sbGVycy9sb2dpbi50cyJdLCJuYW1lcyI6WyJMb2dpbiIsIkxvZ2luLmNvbnN0cnVjdG9yIiwiTG9naW4ubG9naW4iXSwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7Ozs7O0FBQUEseUJBQThCLG1CQUFtQixDQUFDLENBQUE7QUFDbEQsdUJBQXFCLGlCQUFpQixDQUFDLENBQUE7QUFDdkMsb0JBQXlCLGtCQUFrQixDQUFDLENBQUE7QUFDNUMsbUJBQXFCLGFBQWEsQ0FBQyxDQUFBO0FBRW5DO0lBU0NBLGVBQW1CQSxHQUFRQSxFQUFTQSxLQUFZQSxFQUF5QkEsTUFBY0E7UUFBcEVDLFFBQUdBLEdBQUhBLEdBQUdBLENBQUtBO1FBQVNBLFVBQUtBLEdBQUxBLEtBQUtBLENBQU9BO1FBQXlCQSxXQUFNQSxHQUFOQSxNQUFNQSxDQUFRQTtJQUV2RkEsQ0FBQ0E7SUFFREQscUJBQUtBLEdBQUxBLFVBQU1BLFFBQVFBLEVBQUVBLFFBQVFBO1FBRXZCRSxJQUFJQSxDQUFDQSxLQUFLQSxDQUFDQSxLQUFLQSxFQUFFQTthQUNoQkEsSUFBSUEsQ0FBQ0E7WUFDTCxJQUFJLENBQUMsTUFBTSxDQUFDLE1BQU0sQ0FBQyxRQUFRLENBQUMsV0FBVyxDQUFDLENBQUM7UUFDMUMsQ0FBQyxDQUFDQTthQUNEQSxLQUFLQSxDQUFDQSxVQUFTQSxDQUFDQTtZQUNoQixLQUFLLENBQUMscUJBQXFCLENBQUMsQ0FBQztZQUM3QixPQUFPLENBQUMsR0FBRyxDQUFDLENBQUMsQ0FBQyxDQUFDO1FBQ2hCLENBQUMsQ0FBQ0EsQ0FBQ0E7SUFDTEEsQ0FBQ0E7SUF2QkZGO1FBQUNBLG9CQUFTQSxDQUFDQTtZQUNUQSxZQUFZQSxFQUFFQSxDQUFDQSxTQUFHQSxFQUFFQSxXQUFLQSxDQUFDQTtTQUMzQkEsQ0FBQ0E7UUFDREEsZUFBSUEsQ0FBQ0E7WUFDSkEsV0FBV0EsRUFBRUEsc0JBQXNCQTtTQUNwQ0EsQ0FBQ0E7UUFJaURBLFdBQUNBLFdBQU1BLENBQUNBLGVBQU1BLENBQUNBLENBQUFBOztjQWVqRUE7SUFBREEsWUFBQ0E7QUFBREEsQ0F4QkEsSUF3QkM7QUFqQlksYUFBSyxRQWlCakIsQ0FBQSIsImZpbGUiOiJzcmMvY29udHJvbGxlcnMvbG9naW4uanMiLCJzb3VyY2VzQ29udGVudCI6WyJpbXBvcnQge0NvbXBvbmVudCwgVmlld30gZnJvbSAnYW5ndWxhcjIvYW5ndWxhcjInO1xuaW1wb3J0IHtSb3V0ZXJ9IGZyb20gJ2FuZ3VsYXIyL3JvdXRlcic7XG5pbXBvcnQge0FwaSwgT0F1dGh9IGZyb20gJ3NyYy9zZXJ2aWNlcy9hcGknO1xuaW1wb3J0IHtJbmplY3R9IGZyb20gJ2FuZ3VsYXIyL2RpJztcblxuQENvbXBvbmVudCh7XG4gIHZpZXdJbmplY3RvcjogW0FwaSwgT0F1dGhdXG59KVxuQFZpZXcoe1xuICB0ZW1wbGF0ZVVybDogJ3RlbXBsYXRlcy9sb2dpbi5odG1sJ1xufSlcblxuZXhwb3J0IGNsYXNzIExvZ2luIHtcblxuXHRjb25zdHJ1Y3RvcihwdWJsaWMgYXBpOiBBcGksIHB1YmxpYyBvYXV0aDogT0F1dGgsIEBJbmplY3QoUm91dGVyKSBwdWJsaWMgcm91dGVyOiBSb3V0ZXIpe1xuXHRcdFxuXHR9XG5cblx0bG9naW4odXNlcm5hbWUsIHBhc3N3b3JkKXtcblx0XHQvL3RyeSB0aGUgb2F1dGggbG9naW5cblx0XHR0aGlzLm9hdXRoLmxvZ2luKClcblx0XHRcdC50aGVuKGZ1bmN0aW9uKCl7XG5cdFx0XHRcdHRoaXMucm91dGVyLnBhcmVudC5uYXZpZ2F0ZSgnL25ld3NmZWVkJyk7XG5cdFx0XHR9KVxuXHRcdFx0LmNhdGNoKGZ1bmN0aW9uKGUpe1xuXHRcdFx0XHRhbGVydCgndGhlcmUgd2FzIGEgcHJvYmxlbScpO1xuXHRcdFx0XHRjb25zb2xlLmxvZyhlKTtcblx0XHRcdH0pO1xuXHR9XG59Il0sInNvdXJjZVJvb3QiOiIvc291cmNlLyJ9