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
            viewInjector: [api_1.Client]
        }),
        angular2_1.View({
            templateUrl: 'templates/login.html',
            directives: [material_1.Material]
        }),
        __param(1, angular2_1.Inject(router_1.Router)), 
        __metadata('design:paramtypes', [Client, Router])
    ], Login);
    return Login;
})();
exports.Login = Login;
//# sourceMappingURL=login.js.map