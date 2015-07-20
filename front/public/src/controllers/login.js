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
var session_1 = require('src/services/session');
var Login = (function () {
    function Login(client, router) {
        this.client = client;
        this.router = router;
        this.session = session_1.SessionFactory.build();
        window.componentHandler.upgradeDom();
    }
    Login.prototype.login = function (username, password) {
        var self = this;
        this.client.post('api/v1/authenticate', { username: username.value, password: password.value })
            .then(function (data) {
            username.value = '';
            password.value = '';
            if (data.status == 'success') {
                self.session.login(data.user);
                self.router.parent.navigate('/newsfeed');
            }
            else {
                self.session.logout();
            }
        })
            .catch(function (e) {
            alert('there was a problem');
            console.log(e);
            self.session.logout();
        });
    };
    Login = __decorate([
        angular2_1.Component({
            viewInjector: [api_1.Client]
        }),
        angular2_1.View({
            templateUrl: 'templates/login.html'
        }),
        __param(1, angular2_1.Inject(router_1.Router)), 
        __metadata('design:paramtypes', [api_1.Client, router_1.Router])
    ], Login);
    return Login;
})();
exports.Login = Login;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9jb250cm9sbGVycy9sb2dpbi50cyJdLCJuYW1lcyI6WyJMb2dpbiIsIkxvZ2luLmNvbnN0cnVjdG9yIiwiTG9naW4ubG9naW4iXSwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7Ozs7O0FBQUEseUJBQXdDLG1CQUFtQixDQUFDLENBQUE7QUFDNUQsdUJBQXVCLGlCQUFpQixDQUFDLENBQUE7QUFDekMsb0JBQXVCLGtCQUFrQixDQUFDLENBQUE7QUFDMUMsd0JBQStCLHNCQUFzQixDQUFDLENBQUE7QUFFdEQ7SUFXQ0EsZUFBbUJBLE1BQWVBLEVBQXlCQSxNQUFjQTtRQUF0REMsV0FBTUEsR0FBTkEsTUFBTUEsQ0FBU0E7UUFBeUJBLFdBQU1BLEdBQU5BLE1BQU1BLENBQVFBO1FBRnpFQSxZQUFPQSxHQUFHQSx3QkFBY0EsQ0FBQ0EsS0FBS0EsRUFBRUEsQ0FBQ0E7UUFHaENBLE1BQU1BLENBQUNBLGdCQUFnQkEsQ0FBQ0EsVUFBVUEsRUFBRUEsQ0FBQ0E7SUFDdENBLENBQUNBO0lBRURELHFCQUFLQSxHQUFMQSxVQUFNQSxRQUFRQSxFQUFFQSxRQUFRQTtRQUN2QkUsSUFBSUEsSUFBSUEsR0FBR0EsSUFBSUEsQ0FBQ0E7UUFDaEJBLElBQUlBLENBQUNBLE1BQU1BLENBQUNBLElBQUlBLENBQUNBLHFCQUFxQkEsRUFBRUEsRUFBQ0EsUUFBUUEsRUFBRUEsUUFBUUEsQ0FBQ0EsS0FBS0EsRUFBRUEsUUFBUUEsRUFBRUEsUUFBUUEsQ0FBQ0EsS0FBS0EsRUFBQ0EsQ0FBQ0E7YUFDM0ZBLElBQUlBLENBQUNBLFVBQVNBLElBQVVBO1lBQ3hCLFFBQVEsQ0FBQyxLQUFLLEdBQUcsRUFBRSxDQUFDO1lBQ3BCLFFBQVEsQ0FBQyxLQUFLLEdBQUcsRUFBRSxDQUFDO1lBQ3BCLEVBQUUsQ0FBQSxDQUFDLElBQUksQ0FBQyxNQUFNLElBQUksU0FBUyxDQUFDLENBQUEsQ0FBQztnQkFDNUIsSUFBSSxDQUFDLE9BQU8sQ0FBQyxLQUFLLENBQUMsSUFBSSxDQUFDLElBQUksQ0FBQyxDQUFDO2dCQUM5QixJQUFJLENBQUMsTUFBTSxDQUFDLE1BQU0sQ0FBQyxRQUFRLENBQUMsV0FBVyxDQUFDLENBQUM7WUFDMUMsQ0FBQztZQUFDLElBQUksQ0FBQyxDQUFDO2dCQUNQLElBQUksQ0FBQyxPQUFPLENBQUMsTUFBTSxFQUFFLENBQUM7WUFDdkIsQ0FBQztRQUNGLENBQUMsQ0FBQ0E7YUFDREEsS0FBS0EsQ0FBQ0EsVUFBU0EsQ0FBQ0E7WUFDaEIsS0FBSyxDQUFDLHFCQUFxQixDQUFDLENBQUM7WUFDN0IsT0FBTyxDQUFDLEdBQUcsQ0FBQyxDQUFDLENBQUMsQ0FBQztZQUNmLElBQUksQ0FBQyxPQUFPLENBQUMsTUFBTSxFQUFFLENBQUM7UUFDdkIsQ0FBQyxDQUFDQSxDQUFDQTtJQUNMQSxDQUFDQTtJQWpDRkY7UUFBQ0Esb0JBQVNBLENBQUNBO1lBQ1RBLFlBQVlBLEVBQUVBLENBQUNBLFlBQU1BLENBQUNBO1NBQ3ZCQSxDQUFDQTtRQUNEQSxlQUFJQSxDQUFDQTtZQUNKQSxXQUFXQSxFQUFFQSxzQkFBc0JBO1NBQ3BDQSxDQUFDQTtRQU1tQ0EsV0FBQ0EsaUJBQU1BLENBQUNBLGVBQU1BLENBQUNBLENBQUFBOztjQXVCbkRBO0lBQURBLFlBQUNBO0FBQURBLENBbENBLEFBa0NDQSxJQUFBO0FBM0JZLGFBQUssUUEyQmpCLENBQUEiLCJmaWxlIjoic3JjL2NvbnRyb2xsZXJzL2xvZ2luLmpzIiwic291cmNlc0NvbnRlbnQiOlsiaW1wb3J0IHsgQ29tcG9uZW50LCBWaWV3LCBJbmplY3QgfSBmcm9tICdhbmd1bGFyMi9hbmd1bGFyMic7XG5pbXBvcnQgeyBSb3V0ZXIgfSBmcm9tICdhbmd1bGFyMi9yb3V0ZXInO1xuaW1wb3J0IHsgQ2xpZW50IH0gZnJvbSAnc3JjL3NlcnZpY2VzL2FwaSc7XG5pbXBvcnQgeyBTZXNzaW9uRmFjdG9yeSB9IGZyb20gJ3NyYy9zZXJ2aWNlcy9zZXNzaW9uJztcblxuQENvbXBvbmVudCh7XG4gIHZpZXdJbmplY3RvcjogW0NsaWVudF1cbn0pXG5AVmlldyh7XG4gIHRlbXBsYXRlVXJsOiAndGVtcGxhdGVzL2xvZ2luLmh0bWwnXG59KVxuXG5leHBvcnQgY2xhc3MgTG9naW4ge1xuXG5cdHNlc3Npb24gPSBTZXNzaW9uRmFjdG9yeS5idWlsZCgpO1xuXG5cdGNvbnN0cnVjdG9yKHB1YmxpYyBjbGllbnQgOiBDbGllbnQsIEBJbmplY3QoUm91dGVyKSBwdWJsaWMgcm91dGVyOiBSb3V0ZXIpe1xuXHRcdHdpbmRvdy5jb21wb25lbnRIYW5kbGVyLnVwZ3JhZGVEb20oKTtcblx0fVxuXG5cdGxvZ2luKHVzZXJuYW1lLCBwYXNzd29yZCl7XG5cdFx0dmFyIHNlbGYgPSB0aGlzOyAvL3RoaXMgPD0+IHRoYXQgZm9yIHByb21pc2VzXG5cdFx0dGhpcy5jbGllbnQucG9zdCgnYXBpL3YxL2F1dGhlbnRpY2F0ZScsIHt1c2VybmFtZTogdXNlcm5hbWUudmFsdWUsIHBhc3N3b3JkOiBwYXNzd29yZC52YWx1ZX0pXG5cdFx0XHQudGhlbihmdW5jdGlvbihkYXRhIDogYW55KXtcblx0XHRcdFx0dXNlcm5hbWUudmFsdWUgPSAnJztcblx0XHRcdFx0cGFzc3dvcmQudmFsdWUgPSAnJztcblx0XHRcdFx0aWYoZGF0YS5zdGF0dXMgPT0gJ3N1Y2Nlc3MnKXtcblx0XHRcdFx0XHRzZWxmLnNlc3Npb24ubG9naW4oZGF0YS51c2VyKTtcblx0XHRcdFx0XHRzZWxmLnJvdXRlci5wYXJlbnQubmF2aWdhdGUoJy9uZXdzZmVlZCcpO1xuXHRcdFx0XHR9IGVsc2Uge1xuXHRcdFx0XHRcdHNlbGYuc2Vzc2lvbi5sb2dvdXQoKTtcblx0XHRcdFx0fVxuXHRcdFx0fSlcblx0XHRcdC5jYXRjaChmdW5jdGlvbihlKXtcblx0XHRcdFx0YWxlcnQoJ3RoZXJlIHdhcyBhIHByb2JsZW0nKTtcblx0XHRcdFx0Y29uc29sZS5sb2coZSk7XG5cdFx0XHRcdHNlbGYuc2Vzc2lvbi5sb2dvdXQoKTtcblx0XHRcdH0pO1xuXHR9XG59XG4iXSwic291cmNlUm9vdCI6Ii9zb3VyY2UvIn0=