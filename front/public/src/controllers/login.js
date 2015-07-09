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
    function Login(oauth, router) {
        this.oauth = oauth;
        this.router = router;
    }
    Login.prototype.login = function (username, password) {
        var that = this;
        this.oauth.login(username, password)
            .then(function () {
            console.log(this.router);
            that.router.parent.navigate('/newsfeed');
        })
            .catch(function (e) {
            alert('there was a problem');
            console.log(e);
        });
    };
    Login = __decorate([
        angular2_1.Component({
            viewInjector: [api_1.OAuth]
        }),
        angular2_1.View({
            templateUrl: 'templates/login.html'
        }),
        __param(1, di_1.Inject(router_1.Router)), 
        __metadata('design:paramtypes', [api_1.OAuth, (typeof Router !== 'undefined' && Router) || Object])
    ], Login);
    return Login;
})();
exports.Login = Login;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9jb250cm9sbGVycy9sb2dpbi50cyJdLCJuYW1lcyI6WyJMb2dpbiIsIkxvZ2luLmNvbnN0cnVjdG9yIiwiTG9naW4ubG9naW4iXSwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7Ozs7O0FBQUEseUJBQThCLG1CQUFtQixDQUFDLENBQUE7QUFDbEQsdUJBQXFCLGlCQUFpQixDQUFDLENBQUE7QUFDdkMsb0JBQW9CLGtCQUFrQixDQUFDLENBQUE7QUFDdkMsbUJBQXFCLGFBQWEsQ0FBQyxDQUFBO0FBRW5DO0lBU0NBLGVBQW1CQSxLQUFZQSxFQUF5QkEsTUFBY0E7UUFBbkRDLFVBQUtBLEdBQUxBLEtBQUtBLENBQU9BO1FBQXlCQSxXQUFNQSxHQUFOQSxNQUFNQSxDQUFRQTtJQUV0RUEsQ0FBQ0E7SUFFREQscUJBQUtBLEdBQUxBLFVBQU1BLFFBQVFBLEVBQUVBLFFBQVFBO1FBQ3ZCRSxJQUFJQSxJQUFJQSxHQUFHQSxJQUFJQSxDQUFDQTtRQUNoQkEsSUFBSUEsQ0FBQ0EsS0FBS0EsQ0FBQ0EsS0FBS0EsQ0FBQ0EsUUFBUUEsRUFBRUEsUUFBUUEsQ0FBQ0E7YUFDbENBLElBQUlBLENBQUNBO1lBQ0wsT0FBTyxDQUFDLEdBQUcsQ0FBQyxJQUFJLENBQUMsTUFBTSxDQUFDLENBQUM7WUFDekIsSUFBSSxDQUFDLE1BQU0sQ0FBQyxNQUFNLENBQUMsUUFBUSxDQUFDLFdBQVcsQ0FBQyxDQUFDO1FBQzFDLENBQUMsQ0FBQ0E7YUFDREEsS0FBS0EsQ0FBQ0EsVUFBU0EsQ0FBQ0E7WUFDaEIsS0FBSyxDQUFDLHFCQUFxQixDQUFDLENBQUM7WUFDN0IsT0FBTyxDQUFDLEdBQUcsQ0FBQyxDQUFDLENBQUMsQ0FBQztRQUNoQixDQUFDLENBQUNBLENBQUNBO0lBQ0xBLENBQUNBO0lBeEJGRjtRQUFDQSxvQkFBU0EsQ0FBQ0E7WUFDVEEsWUFBWUEsRUFBRUEsQ0FBQ0EsV0FBS0EsQ0FBQ0E7U0FDdEJBLENBQUNBO1FBQ0RBLGVBQUlBLENBQUNBO1lBQ0pBLFdBQVdBLEVBQUVBLHNCQUFzQkE7U0FDcENBLENBQUNBO1FBSWdDQSxXQUFDQSxXQUFNQSxDQUFDQSxlQUFNQSxDQUFDQSxDQUFBQTs7Y0FnQmhEQTtJQUFEQSxZQUFDQTtBQUFEQSxDQXpCQSxJQXlCQztBQWxCWSxhQUFLLFFBa0JqQixDQUFBIiwiZmlsZSI6InNyYy9jb250cm9sbGVycy9sb2dpbi5qcyIsInNvdXJjZXNDb250ZW50IjpbImltcG9ydCB7Q29tcG9uZW50LCBWaWV3fSBmcm9tICdhbmd1bGFyMi9hbmd1bGFyMic7XG5pbXBvcnQge1JvdXRlcn0gZnJvbSAnYW5ndWxhcjIvcm91dGVyJztcbmltcG9ydCB7T0F1dGh9IGZyb20gJ3NyYy9zZXJ2aWNlcy9hcGknO1xuaW1wb3J0IHtJbmplY3R9IGZyb20gJ2FuZ3VsYXIyL2RpJztcblxuQENvbXBvbmVudCh7XG4gIHZpZXdJbmplY3RvcjogW09BdXRoXVxufSlcbkBWaWV3KHtcbiAgdGVtcGxhdGVVcmw6ICd0ZW1wbGF0ZXMvbG9naW4uaHRtbCdcbn0pXG5cbmV4cG9ydCBjbGFzcyBMb2dpbiB7XG5cblx0Y29uc3RydWN0b3IocHVibGljIG9hdXRoOiBPQXV0aCwgQEluamVjdChSb3V0ZXIpIHB1YmxpYyByb3V0ZXI6IFJvdXRlcil7XG5cdFx0XG5cdH1cblxuXHRsb2dpbih1c2VybmFtZSwgcGFzc3dvcmQpe1xuXHRcdHZhciB0aGF0ID0gdGhpczsgLy90aGlzIDw9PiB0aGF0IGZvciBwcm9taXNlc1xuXHRcdHRoaXMub2F1dGgubG9naW4odXNlcm5hbWUsIHBhc3N3b3JkKVxuXHRcdFx0LnRoZW4oZnVuY3Rpb24oKXtcblx0XHRcdFx0Y29uc29sZS5sb2codGhpcy5yb3V0ZXIpO1xuXHRcdFx0XHR0aGF0LnJvdXRlci5wYXJlbnQubmF2aWdhdGUoJy9uZXdzZmVlZCcpO1xuXHRcdFx0fSlcblx0XHRcdC5jYXRjaChmdW5jdGlvbihlKXtcblx0XHRcdFx0YWxlcnQoJ3RoZXJlIHdhcyBhIHByb2JsZW0nKTtcblx0XHRcdFx0Y29uc29sZS5sb2coZSk7XG5cdFx0XHR9KTtcblx0fVxufSJdLCJzb3VyY2VSb290IjoiL3NvdXJjZS8ifQ==