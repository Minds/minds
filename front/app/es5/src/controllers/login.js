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
        __metadata('design:paramtypes', [Client, Router])
    ], Login);
    return Login;
})();
exports.Login = Login;
//# sourceMappingURL=login.js.map