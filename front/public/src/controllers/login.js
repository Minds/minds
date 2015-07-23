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
var material_1 = require('src/directives/material');
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
            templateUrl: 'templates/login.html',
            directives: [material_1.Material]
        }),
        __param(1, angular2_1.Inject(router_1.Router)), 
        __metadata('design:paramtypes', [api_1.Client, router_1.Router])
    ], Login);
    return Login;
})();
exports.Login = Login;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9jb250cm9sbGVycy9sb2dpbi50cyJdLCJuYW1lcyI6WyJMb2dpbiIsIkxvZ2luLmNvbnN0cnVjdG9yIiwiTG9naW4ubG9naW4iXSwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7Ozs7O0FBQUEseUJBQXdDLG1CQUFtQixDQUFDLENBQUE7QUFDNUQsdUJBQXVCLGlCQUFpQixDQUFDLENBQUE7QUFDekMseUJBQXlCLHlCQUF5QixDQUFDLENBQUE7QUFDbkQsb0JBQXVCLGtCQUFrQixDQUFDLENBQUE7QUFDMUMsd0JBQStCLHNCQUFzQixDQUFDLENBQUE7QUFFdEQ7SUFZQ0EsZUFBbUJBLE1BQWVBLEVBQXlCQSxNQUFjQTtRQUF0REMsV0FBTUEsR0FBTkEsTUFBTUEsQ0FBU0E7UUFBeUJBLFdBQU1BLEdBQU5BLE1BQU1BLENBQVFBO1FBRnpFQSxZQUFPQSxHQUFHQSx3QkFBY0EsQ0FBQ0EsS0FBS0EsRUFBRUEsQ0FBQ0E7UUFHaENBLE1BQU1BLENBQUNBLGdCQUFnQkEsQ0FBQ0EsVUFBVUEsRUFBRUEsQ0FBQ0E7SUFDdENBLENBQUNBO0lBRURELHFCQUFLQSxHQUFMQSxVQUFNQSxRQUFRQSxFQUFFQSxRQUFRQTtRQUN2QkUsSUFBSUEsSUFBSUEsR0FBR0EsSUFBSUEsQ0FBQ0E7UUFDaEJBLElBQUlBLENBQUNBLE1BQU1BLENBQUNBLElBQUlBLENBQUNBLHFCQUFxQkEsRUFBRUEsRUFBQ0EsUUFBUUEsRUFBRUEsUUFBUUEsQ0FBQ0EsS0FBS0EsRUFBRUEsUUFBUUEsRUFBRUEsUUFBUUEsQ0FBQ0EsS0FBS0EsRUFBQ0EsQ0FBQ0E7YUFDM0ZBLElBQUlBLENBQUNBLFVBQVNBLElBQVVBO1lBQ3hCLFFBQVEsQ0FBQyxLQUFLLEdBQUcsRUFBRSxDQUFDO1lBQ3BCLFFBQVEsQ0FBQyxLQUFLLEdBQUcsRUFBRSxDQUFDO1lBQ3BCLEVBQUUsQ0FBQSxDQUFDLElBQUksQ0FBQyxNQUFNLElBQUksU0FBUyxDQUFDLENBQUEsQ0FBQztnQkFDNUIsSUFBSSxDQUFDLE9BQU8sQ0FBQyxLQUFLLENBQUMsSUFBSSxDQUFDLElBQUksQ0FBQyxDQUFDO2dCQUM5QixJQUFJLENBQUMsTUFBTSxDQUFDLE1BQU0sQ0FBQyxRQUFRLENBQUMsV0FBVyxDQUFDLENBQUM7WUFDMUMsQ0FBQztZQUFDLElBQUksQ0FBQyxDQUFDO2dCQUNQLElBQUksQ0FBQyxPQUFPLENBQUMsTUFBTSxFQUFFLENBQUM7WUFDdkIsQ0FBQztRQUNGLENBQUMsQ0FBQ0E7YUFDREEsS0FBS0EsQ0FBQ0EsVUFBU0EsQ0FBQ0E7WUFDaEIsS0FBSyxDQUFDLHFCQUFxQixDQUFDLENBQUM7WUFDN0IsT0FBTyxDQUFDLEdBQUcsQ0FBQyxDQUFDLENBQUMsQ0FBQztZQUNmLElBQUksQ0FBQyxPQUFPLENBQUMsTUFBTSxFQUFFLENBQUM7UUFDdkIsQ0FBQyxDQUFDQSxDQUFDQTtJQUNMQSxDQUFDQTtJQWxDRkY7UUFBQ0Esb0JBQVNBLENBQUNBO1lBQ1RBLFlBQVlBLEVBQUVBLENBQUVBLFlBQU1BLENBQUVBO1NBQ3pCQSxDQUFDQTtRQUNEQSxlQUFJQSxDQUFDQTtZQUNKQSxXQUFXQSxFQUFFQSxzQkFBc0JBO1lBQ25DQSxVQUFVQSxFQUFFQSxDQUFFQSxtQkFBUUEsQ0FBRUE7U0FDekJBLENBQUNBO1FBTW1DQSxXQUFDQSxpQkFBTUEsQ0FBQ0EsZUFBTUEsQ0FBQ0EsQ0FBQUE7O2NBdUJuREE7SUFBREEsWUFBQ0E7QUFBREEsQ0FuQ0EsQUFtQ0NBLElBQUE7QUEzQlksYUFBSyxRQTJCakIsQ0FBQSIsImZpbGUiOiJzcmMvY29udHJvbGxlcnMvbG9naW4uanMiLCJzb3VyY2VzQ29udGVudCI6WyJpbXBvcnQgeyBDb21wb25lbnQsIFZpZXcsIEluamVjdCB9IGZyb20gJ2FuZ3VsYXIyL2FuZ3VsYXIyJztcbmltcG9ydCB7IFJvdXRlciB9IGZyb20gJ2FuZ3VsYXIyL3JvdXRlcic7XG5pbXBvcnQgeyBNYXRlcmlhbCB9IGZyb20gJ3NyYy9kaXJlY3RpdmVzL21hdGVyaWFsJztcbmltcG9ydCB7IENsaWVudCB9IGZyb20gJ3NyYy9zZXJ2aWNlcy9hcGknO1xuaW1wb3J0IHsgU2Vzc2lvbkZhY3RvcnkgfSBmcm9tICdzcmMvc2VydmljZXMvc2Vzc2lvbic7XG5cbkBDb21wb25lbnQoe1xuICB2aWV3SW5qZWN0b3I6IFsgQ2xpZW50IF1cbn0pXG5AVmlldyh7XG4gIHRlbXBsYXRlVXJsOiAndGVtcGxhdGVzL2xvZ2luLmh0bWwnLFxuICBkaXJlY3RpdmVzOiBbIE1hdGVyaWFsIF1cbn0pXG5cbmV4cG9ydCBjbGFzcyBMb2dpbiB7XG5cblx0c2Vzc2lvbiA9IFNlc3Npb25GYWN0b3J5LmJ1aWxkKCk7XG5cblx0Y29uc3RydWN0b3IocHVibGljIGNsaWVudCA6IENsaWVudCwgQEluamVjdChSb3V0ZXIpIHB1YmxpYyByb3V0ZXI6IFJvdXRlcil7XG5cdFx0d2luZG93LmNvbXBvbmVudEhhbmRsZXIudXBncmFkZURvbSgpO1xuXHR9XG5cblx0bG9naW4odXNlcm5hbWUsIHBhc3N3b3JkKXtcblx0XHR2YXIgc2VsZiA9IHRoaXM7IC8vdGhpcyA8PT4gdGhhdCBmb3IgcHJvbWlzZXNcblx0XHR0aGlzLmNsaWVudC5wb3N0KCdhcGkvdjEvYXV0aGVudGljYXRlJywge3VzZXJuYW1lOiB1c2VybmFtZS52YWx1ZSwgcGFzc3dvcmQ6IHBhc3N3b3JkLnZhbHVlfSlcblx0XHRcdC50aGVuKGZ1bmN0aW9uKGRhdGEgOiBhbnkpe1xuXHRcdFx0XHR1c2VybmFtZS52YWx1ZSA9ICcnO1xuXHRcdFx0XHRwYXNzd29yZC52YWx1ZSA9ICcnO1xuXHRcdFx0XHRpZihkYXRhLnN0YXR1cyA9PSAnc3VjY2Vzcycpe1xuXHRcdFx0XHRcdHNlbGYuc2Vzc2lvbi5sb2dpbihkYXRhLnVzZXIpO1xuXHRcdFx0XHRcdHNlbGYucm91dGVyLnBhcmVudC5uYXZpZ2F0ZSgnL25ld3NmZWVkJyk7XG5cdFx0XHRcdH0gZWxzZSB7XG5cdFx0XHRcdFx0c2VsZi5zZXNzaW9uLmxvZ291dCgpO1xuXHRcdFx0XHR9XG5cdFx0XHR9KVxuXHRcdFx0LmNhdGNoKGZ1bmN0aW9uKGUpe1xuXHRcdFx0XHRhbGVydCgndGhlcmUgd2FzIGEgcHJvYmxlbScpO1xuXHRcdFx0XHRjb25zb2xlLmxvZyhlKTtcblx0XHRcdFx0c2VsZi5zZXNzaW9uLmxvZ291dCgpO1xuXHRcdFx0fSk7XG5cdH1cbn1cbiJdLCJzb3VyY2VSb290IjoiL3NvdXJjZS8ifQ==