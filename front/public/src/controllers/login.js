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
    function Login(client, router) {
        this.client = client;
        this.router = router;
    }
    Login.prototype.login = function (username, password) {
        var that = this;
        this.client.post('api/v1/authenticate', { username: username, password: password })
            .then(function (data) {
            if (data.status == 'success') {
                window.LoggedIn = true;
                that.router.parent.navigate('/newsfeed');
            }
            else {
                window.LoggedIn = false;
            }
        })
            .catch(function (e) {
            alert('there was a problem');
            console.log(e);
        });
    };
    Login = __decorate([
        angular2_1.Component({
            viewInjector: [api_1.Client]
        }),
        angular2_1.View({
            templateUrl: 'templates/login.html'
        }),
        __param(1, di_1.Inject(router_1.Router)), 
        __metadata('design:paramtypes', [api_1.Client, router_1.Router])
    ], Login);
    return Login;
})();
exports.Login = Login;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9jb250cm9sbGVycy9sb2dpbi50cyJdLCJuYW1lcyI6WyJMb2dpbiIsIkxvZ2luLmNvbnN0cnVjdG9yIiwiTG9naW4ubG9naW4iXSwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7Ozs7O0FBQUEseUJBQThCLG1CQUFtQixDQUFDLENBQUE7QUFDbEQsdUJBQXFCLGlCQUFpQixDQUFDLENBQUE7QUFDdkMsb0JBQXFCLGtCQUFrQixDQUFDLENBQUE7QUFDeEMsbUJBQXFCLGFBQWEsQ0FBQyxDQUFBO0FBRW5DO0lBU0NBLGVBQW1CQSxNQUFlQSxFQUF5QkEsTUFBY0E7UUFBdERDLFdBQU1BLEdBQU5BLE1BQU1BLENBQVNBO1FBQXlCQSxXQUFNQSxHQUFOQSxNQUFNQSxDQUFRQTtJQUFHQSxDQUFDQTtJQUU3RUQscUJBQUtBLEdBQUxBLFVBQU1BLFFBQVFBLEVBQUVBLFFBQVFBO1FBQ3ZCRSxJQUFJQSxJQUFJQSxHQUFHQSxJQUFJQSxDQUFDQTtRQUNoQkEsSUFBSUEsQ0FBQ0EsTUFBTUEsQ0FBQ0EsSUFBSUEsQ0FBQ0EscUJBQXFCQSxFQUFFQSxFQUFDQSxRQUFRQSxFQUFFQSxRQUFRQSxFQUFFQSxRQUFRQSxFQUFFQSxRQUFRQSxFQUFDQSxDQUFDQTthQUMvRUEsSUFBSUEsQ0FBQ0EsVUFBU0EsSUFBSUE7WUFDbEIsRUFBRSxDQUFBLENBQUMsSUFBSSxDQUFDLE1BQU0sSUFBSSxTQUFTLENBQUMsQ0FBQSxDQUFDO2dCQUM1QixNQUFNLENBQUMsUUFBUSxHQUFHLElBQUksQ0FBQztnQkFDdkIsSUFBSSxDQUFDLE1BQU0sQ0FBQyxNQUFNLENBQUMsUUFBUSxDQUFDLFdBQVcsQ0FBQyxDQUFDO1lBQzFDLENBQUM7WUFBQyxJQUFJLENBQUMsQ0FBQztnQkFDUCxNQUFNLENBQUMsUUFBUSxHQUFHLEtBQUssQ0FBQztZQUN6QixDQUFDO1FBQ0YsQ0FBQyxDQUFDQTthQUNEQSxLQUFLQSxDQUFDQSxVQUFTQSxDQUFDQTtZQUNoQixLQUFLLENBQUMscUJBQXFCLENBQUMsQ0FBQztZQUM3QixPQUFPLENBQUMsR0FBRyxDQUFDLENBQUMsQ0FBQyxDQUFDO1FBQ2hCLENBQUMsQ0FBQ0EsQ0FBQ0E7SUFDTEEsQ0FBQ0E7SUExQkZGO1FBQUNBLG9CQUFTQSxDQUFDQTtZQUNUQSxZQUFZQSxFQUFFQSxDQUFDQSxZQUFNQSxDQUFDQTtTQUN2QkEsQ0FBQ0E7UUFDREEsZUFBSUEsQ0FBQ0E7WUFDSkEsV0FBV0EsRUFBRUEsc0JBQXNCQTtTQUNwQ0EsQ0FBQ0E7UUFJbUNBLFdBQUNBLFdBQU1BLENBQUNBLGVBQU1BLENBQUNBLENBQUFBOztjQWtCbkRBO0lBQURBLFlBQUNBO0FBQURBLENBM0JBLElBMkJDO0FBcEJZLGFBQUssUUFvQmpCLENBQUEiLCJmaWxlIjoic3JjL2NvbnRyb2xsZXJzL2xvZ2luLmpzIiwic291cmNlc0NvbnRlbnQiOlsiaW1wb3J0IHtDb21wb25lbnQsIFZpZXd9IGZyb20gJ2FuZ3VsYXIyL2FuZ3VsYXIyJztcbmltcG9ydCB7Um91dGVyfSBmcm9tICdhbmd1bGFyMi9yb3V0ZXInO1xuaW1wb3J0IHtDbGllbnR9IGZyb20gJ3NyYy9zZXJ2aWNlcy9hcGknO1xuaW1wb3J0IHtJbmplY3R9IGZyb20gJ2FuZ3VsYXIyL2RpJztcblxuQENvbXBvbmVudCh7XG4gIHZpZXdJbmplY3RvcjogW0NsaWVudF1cbn0pXG5AVmlldyh7XG4gIHRlbXBsYXRlVXJsOiAndGVtcGxhdGVzL2xvZ2luLmh0bWwnXG59KVxuXG5leHBvcnQgY2xhc3MgTG9naW4ge1xuXG5cdGNvbnN0cnVjdG9yKHB1YmxpYyBjbGllbnQgOiBDbGllbnQsIEBJbmplY3QoUm91dGVyKSBwdWJsaWMgcm91dGVyOiBSb3V0ZXIpeyB9XG5cblx0bG9naW4odXNlcm5hbWUsIHBhc3N3b3JkKXtcblx0XHR2YXIgdGhhdCA9IHRoaXM7IC8vdGhpcyA8PT4gdGhhdCBmb3IgcHJvbWlzZXNcblx0XHR0aGlzLmNsaWVudC5wb3N0KCdhcGkvdjEvYXV0aGVudGljYXRlJywge3VzZXJuYW1lOiB1c2VybmFtZSwgcGFzc3dvcmQ6IHBhc3N3b3JkfSlcblx0XHRcdC50aGVuKGZ1bmN0aW9uKGRhdGEpe1xuXHRcdFx0XHRpZihkYXRhLnN0YXR1cyA9PSAnc3VjY2Vzcycpe1xuXHRcdFx0XHRcdHdpbmRvdy5Mb2dnZWRJbiA9IHRydWU7XG5cdFx0XHRcdFx0dGhhdC5yb3V0ZXIucGFyZW50Lm5hdmlnYXRlKCcvbmV3c2ZlZWQnKTtcblx0XHRcdFx0fSBlbHNlIHtcblx0XHRcdFx0XHR3aW5kb3cuTG9nZ2VkSW4gPSBmYWxzZTtcblx0XHRcdFx0fVxuXHRcdFx0fSlcblx0XHRcdC5jYXRjaChmdW5jdGlvbihlKXtcblx0XHRcdFx0YWxlcnQoJ3RoZXJlIHdhcyBhIHByb2JsZW0nKTtcblx0XHRcdFx0Y29uc29sZS5sb2coZSk7XG5cdFx0XHR9KTtcblx0fVxufSJdLCJzb3VyY2VSb290IjoiL3NvdXJjZS8ifQ==