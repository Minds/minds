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
        this.errorMessage = "";
        this.twofactorToken = "";
        this.hideLogin = false;
        window.componentHandler.upgradeDom();
    }
    Login.prototype.login = function (username, password) {
        this.errorMessage = "";
        var self = this;
        this.client.post('api/v1/authenticate', { username: username.value, password: password.value })
            .then(function (data) {
            username.value = '';
            password.value = '';
            self.session.login(data.user);
            self.router.parent.navigate('/newsfeed');
        })
            .catch(function (e) {
            if (e.status == 'failed') {
                self.errorMessage = "Incorrect username/password. Please try again.";
                self.session.logout();
            }
            if (e.status == 'error') {
                self.twofactorToken = e.message;
                self.hideLogin = true;
            }
        });
    };
    Login.prototype.twofactorAuth = function (code) {
        var self = this;
        this.client.post('api/v1/authenticate/two-factor', { token: this.twofactorToken, code: code.value })
            .then(function (data) {
            self.session.login(data.user);
            self.router.parent.navigate('/newsfeed');
        })
            .catch(function (e) {
            self.errorMessage = "Sorry, we couldn't verify your two factor code. Please try logging again.";
            self.twofactorToken = "";
            self.hideLogin = false;
        });
    };
    Login = __decorate([
        angular2_1.Component({
            viewBindings: [api_1.Client]
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

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9jb250cm9sbGVycy9sb2dpbi50cyJdLCJuYW1lcyI6WyJMb2dpbiIsIkxvZ2luLmNvbnN0cnVjdG9yIiwiTG9naW4ubG9naW4iLCJMb2dpbi50d29mYWN0b3JBdXRoIl0sIm1hcHBpbmdzIjoiOzs7Ozs7Ozs7Ozs7OztBQUFBLHlCQUF3QyxtQkFBbUIsQ0FBQyxDQUFBO0FBQzVELHVCQUF1QixpQkFBaUIsQ0FBQyxDQUFBO0FBQ3pDLHlCQUF5Qix5QkFBeUIsQ0FBQyxDQUFBO0FBQ25ELG9CQUF1QixrQkFBa0IsQ0FBQyxDQUFBO0FBQzFDLHdCQUErQixzQkFBc0IsQ0FBQyxDQUFBO0FBRXREO0lBZUNBLGVBQW1CQSxNQUFlQSxFQUF5QkEsTUFBY0E7UUFBdERDLFdBQU1BLEdBQU5BLE1BQU1BLENBQVNBO1FBQXlCQSxXQUFNQSxHQUFOQSxNQUFNQSxDQUFRQTtRQUx6RUEsWUFBT0EsR0FBR0Esd0JBQWNBLENBQUNBLEtBQUtBLEVBQUVBLENBQUNBO1FBQ2hDQSxpQkFBWUEsR0FBWUEsRUFBRUEsQ0FBQ0E7UUFDM0JBLG1CQUFjQSxHQUFZQSxFQUFFQSxDQUFDQTtRQUM3QkEsY0FBU0EsR0FBYUEsS0FBS0EsQ0FBQ0E7UUFHNUJBLE1BQU1BLENBQUNBLGdCQUFnQkEsQ0FBQ0EsVUFBVUEsRUFBRUEsQ0FBQ0E7SUFDdENBLENBQUNBO0lBRURELHFCQUFLQSxHQUFMQSxVQUFNQSxRQUFRQSxFQUFFQSxRQUFRQTtRQUNyQkUsSUFBSUEsQ0FBQ0EsWUFBWUEsR0FBR0EsRUFBRUEsQ0FBQ0E7UUFDekJBLElBQUlBLElBQUlBLEdBQUdBLElBQUlBLENBQUNBO1FBQ2hCQSxJQUFJQSxDQUFDQSxNQUFNQSxDQUFDQSxJQUFJQSxDQUFDQSxxQkFBcUJBLEVBQUVBLEVBQUNBLFFBQVFBLEVBQUVBLFFBQVFBLENBQUNBLEtBQUtBLEVBQUVBLFFBQVFBLEVBQUVBLFFBQVFBLENBQUNBLEtBQUtBLEVBQUNBLENBQUNBO2FBQzNGQSxJQUFJQSxDQUFDQSxVQUFDQSxJQUFVQTtZQUNoQkEsUUFBUUEsQ0FBQ0EsS0FBS0EsR0FBR0EsRUFBRUEsQ0FBQ0E7WUFDcEJBLFFBQVFBLENBQUNBLEtBQUtBLEdBQUdBLEVBQUVBLENBQUNBO1lBRXBCQSxJQUFJQSxDQUFDQSxPQUFPQSxDQUFDQSxLQUFLQSxDQUFDQSxJQUFJQSxDQUFDQSxJQUFJQSxDQUFDQSxDQUFDQTtZQUM5QkEsSUFBSUEsQ0FBQ0EsTUFBTUEsQ0FBQ0EsTUFBTUEsQ0FBQ0EsUUFBUUEsQ0FBQ0EsV0FBV0EsQ0FBQ0EsQ0FBQ0E7UUFDMUNBLENBQUNBLENBQUNBO2FBQ0RBLEtBQUtBLENBQUNBLFVBQUNBLENBQUNBO1lBQ0pBLEVBQUVBLENBQUFBLENBQUNBLENBQUNBLENBQUNBLE1BQU1BLElBQUlBLFFBQVFBLENBQUNBLENBQUFBLENBQUNBO2dCQUV2QkEsSUFBSUEsQ0FBQ0EsWUFBWUEsR0FBR0EsZ0RBQWdEQSxDQUFDQTtnQkFDckVBLElBQUlBLENBQUNBLE9BQU9BLENBQUNBLE1BQU1BLEVBQUVBLENBQUNBO1lBQ3hCQSxDQUFDQTtZQUVEQSxFQUFFQSxDQUFBQSxDQUFDQSxDQUFDQSxDQUFDQSxNQUFNQSxJQUFJQSxPQUFPQSxDQUFDQSxDQUFBQSxDQUFDQTtnQkFFdEJBLElBQUlBLENBQUNBLGNBQWNBLEdBQUdBLENBQUNBLENBQUNBLE9BQU9BLENBQUNBO2dCQUNoQ0EsSUFBSUEsQ0FBQ0EsU0FBU0EsR0FBR0EsSUFBSUEsQ0FBQ0E7WUFDeEJBLENBQUNBO1FBRU5BLENBQUNBLENBQUNBLENBQUNBO0lBQ0xBLENBQUNBO0lBRUFGLDZCQUFhQSxHQUFiQSxVQUFjQSxJQUFJQTtRQUNoQkcsSUFBSUEsSUFBSUEsR0FBR0EsSUFBSUEsQ0FBQ0E7UUFDaEJBLElBQUlBLENBQUNBLE1BQU1BLENBQUNBLElBQUlBLENBQUNBLGdDQUFnQ0EsRUFBRUEsRUFBQ0EsS0FBS0EsRUFBRUEsSUFBSUEsQ0FBQ0EsY0FBY0EsRUFBRUEsSUFBSUEsRUFBRUEsSUFBSUEsQ0FBQ0EsS0FBS0EsRUFBQ0EsQ0FBQ0E7YUFDN0ZBLElBQUlBLENBQUNBLFVBQUNBLElBQVVBO1lBQ2ZBLElBQUlBLENBQUNBLE9BQU9BLENBQUNBLEtBQUtBLENBQUNBLElBQUlBLENBQUNBLElBQUlBLENBQUNBLENBQUNBO1lBQzlCQSxJQUFJQSxDQUFDQSxNQUFNQSxDQUFDQSxNQUFNQSxDQUFDQSxRQUFRQSxDQUFDQSxXQUFXQSxDQUFDQSxDQUFDQTtRQUMzQ0EsQ0FBQ0EsQ0FBQ0E7YUFDREEsS0FBS0EsQ0FBQ0EsVUFBQ0EsQ0FBQ0E7WUFDUEEsSUFBSUEsQ0FBQ0EsWUFBWUEsR0FBR0EsMkVBQTJFQSxDQUFDQTtZQUNoR0EsSUFBSUEsQ0FBQ0EsY0FBY0EsR0FBR0EsRUFBRUEsQ0FBQ0E7WUFDekJBLElBQUlBLENBQUNBLFNBQVNBLEdBQUdBLEtBQUtBLENBQUNBO1FBQ3pCQSxDQUFDQSxDQUFDQSxDQUFDQTtJQUNUQSxDQUFDQTtJQTFESEg7UUFBQ0Esb0JBQVNBLENBQUNBO1lBQ1RBLFlBQVlBLEVBQUVBLENBQUVBLFlBQU1BLENBQUVBO1NBQ3pCQSxDQUFDQTtRQUNEQSxlQUFJQSxDQUFDQTtZQUNKQSxXQUFXQSxFQUFFQSxzQkFBc0JBO1lBQ25DQSxVQUFVQSxFQUFFQSxDQUFFQSxtQkFBUUEsQ0FBRUE7U0FDekJBLENBQUNBO1FBU21DQSxXQUFDQSxpQkFBTUEsQ0FBQ0EsZUFBTUEsQ0FBQ0EsQ0FBQUE7O2NBNENuREE7SUFBREEsWUFBQ0E7QUFBREEsQ0EzREEsQUEyRENBLElBQUE7QUFuRFksYUFBSyxRQW1EakIsQ0FBQSIsImZpbGUiOiJzcmMvY29udHJvbGxlcnMvbG9naW4uanMiLCJzb3VyY2VzQ29udGVudCI6WyJpbXBvcnQgeyBDb21wb25lbnQsIFZpZXcsIEluamVjdCB9IGZyb20gJ2FuZ3VsYXIyL2FuZ3VsYXIyJztcbmltcG9ydCB7IFJvdXRlciB9IGZyb20gJ2FuZ3VsYXIyL3JvdXRlcic7XG5pbXBvcnQgeyBNYXRlcmlhbCB9IGZyb20gJ3NyYy9kaXJlY3RpdmVzL21hdGVyaWFsJztcbmltcG9ydCB7IENsaWVudCB9IGZyb20gJ3NyYy9zZXJ2aWNlcy9hcGknO1xuaW1wb3J0IHsgU2Vzc2lvbkZhY3RvcnkgfSBmcm9tICdzcmMvc2VydmljZXMvc2Vzc2lvbic7XG5cbkBDb21wb25lbnQoe1xuICB2aWV3QmluZGluZ3M6IFsgQ2xpZW50IF1cbn0pXG5AVmlldyh7XG4gIHRlbXBsYXRlVXJsOiAndGVtcGxhdGVzL2xvZ2luLmh0bWwnLFxuICBkaXJlY3RpdmVzOiBbIE1hdGVyaWFsIF1cbn0pXG5cbmV4cG9ydCBjbGFzcyBMb2dpbiB7XG5cblx0c2Vzc2lvbiA9IFNlc3Npb25GYWN0b3J5LmJ1aWxkKCk7XG4gIGVycm9yTWVzc2FnZSA6IHN0cmluZyA9IFwiXCI7XG4gIHR3b2ZhY3RvclRva2VuIDogc3RyaW5nID0gXCJcIjtcbiAgaGlkZUxvZ2luIDogYm9vbGVhbiA9IGZhbHNlO1xuXG5cdGNvbnN0cnVjdG9yKHB1YmxpYyBjbGllbnQgOiBDbGllbnQsIEBJbmplY3QoUm91dGVyKSBwdWJsaWMgcm91dGVyOiBSb3V0ZXIpe1xuXHRcdHdpbmRvdy5jb21wb25lbnRIYW5kbGVyLnVwZ3JhZGVEb20oKTtcblx0fVxuXG5cdGxvZ2luKHVzZXJuYW1lLCBwYXNzd29yZCl7XG4gICAgdGhpcy5lcnJvck1lc3NhZ2UgPSBcIlwiO1xuXHRcdHZhciBzZWxmID0gdGhpczsgLy90aGlzIDw9PiB0aGF0IGZvciBwcm9taXNlc1xuXHRcdHRoaXMuY2xpZW50LnBvc3QoJ2FwaS92MS9hdXRoZW50aWNhdGUnLCB7dXNlcm5hbWU6IHVzZXJuYW1lLnZhbHVlLCBwYXNzd29yZDogcGFzc3dvcmQudmFsdWV9KVxuXHRcdFx0LnRoZW4oKGRhdGEgOiBhbnkpID0+IHtcblx0XHRcdFx0dXNlcm5hbWUudmFsdWUgPSAnJztcblx0XHRcdFx0cGFzc3dvcmQudmFsdWUgPSAnJztcblxuXHRcdFx0XHRzZWxmLnNlc3Npb24ubG9naW4oZGF0YS51c2VyKTtcblx0XHRcdFx0c2VsZi5yb3V0ZXIucGFyZW50Lm5hdmlnYXRlKCcvbmV3c2ZlZWQnKTtcblx0XHRcdH0pXG5cdFx0XHQuY2F0Y2goKGUpID0+IHtcbiAgICAgICAgaWYoZS5zdGF0dXMgPT0gJ2ZhaWxlZCcpe1xuICAgICAgICAgIC8vaW5jb3JyZWN0IGxvZ2luIGRldGFpbHNcbiAgICAgICAgICBzZWxmLmVycm9yTWVzc2FnZSA9IFwiSW5jb3JyZWN0IHVzZXJuYW1lL3Bhc3N3b3JkLiBQbGVhc2UgdHJ5IGFnYWluLlwiO1xuICAgICAgICAgIHNlbGYuc2Vzc2lvbi5sb2dvdXQoKTtcbiAgICAgICAgfVxuXG4gICAgICAgIGlmKGUuc3RhdHVzID09ICdlcnJvcicpe1xuICAgICAgICAgIC8vdHdvIGZhY3Rvcj9cbiAgICAgICAgICBzZWxmLnR3b2ZhY3RvclRva2VuID0gZS5tZXNzYWdlO1xuICAgICAgICAgIHNlbGYuaGlkZUxvZ2luID0gdHJ1ZTtcbiAgICAgICAgfVxuXG5cdFx0XHR9KTtcblx0fVxuXG4gIHR3b2ZhY3RvckF1dGgoY29kZSl7XG4gICAgdmFyIHNlbGYgPSB0aGlzO1xuICAgIHRoaXMuY2xpZW50LnBvc3QoJ2FwaS92MS9hdXRoZW50aWNhdGUvdHdvLWZhY3RvcicsIHt0b2tlbjogdGhpcy50d29mYWN0b3JUb2tlbiwgY29kZTogY29kZS52YWx1ZX0pXG4gICAgICAgIC50aGVuKChkYXRhIDogYW55KSA9PiB7XG4gICAgICAgICAgc2VsZi5zZXNzaW9uLmxvZ2luKGRhdGEudXNlcik7XG4gICAgICAgICAgc2VsZi5yb3V0ZXIucGFyZW50Lm5hdmlnYXRlKCcvbmV3c2ZlZWQnKTtcbiAgICAgICAgfSlcbiAgICAgICAgLmNhdGNoKChlKSA9PiB7XG4gICAgICAgICAgc2VsZi5lcnJvck1lc3NhZ2UgPSBcIlNvcnJ5LCB3ZSBjb3VsZG4ndCB2ZXJpZnkgeW91ciB0d28gZmFjdG9yIGNvZGUuIFBsZWFzZSB0cnkgbG9nZ2luZyBhZ2Fpbi5cIjtcbiAgICAgICAgICBzZWxmLnR3b2ZhY3RvclRva2VuID0gXCJcIjtcbiAgICAgICAgICBzZWxmLmhpZGVMb2dpbiA9IGZhbHNlO1xuICAgICAgICB9KTtcbiAgfVxufVxuIl0sInNvdXJjZVJvb3QiOiIvc291cmNlLyJ9