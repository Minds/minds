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
var angular2_1 = require('angular2/angular2');
var router_1 = require('angular2/router');
var navigation_1 = require('src/services/navigation');
var session_1 = require('src/services/session');
var Navigation = (function () {
    function Navigation(navigation) {
        this.navigation = navigation;
        this.session = session_1.SessionFactory.build();
        var self = this;
        this.items = navigation.getItems();
        this.getUser();
    }
    Navigation.prototype.getUser = function () {
        var self = this;
        this.user = this.session.getLoggedInUser(function (user) {
            console.log(user);
            self.user = user;
        });
    };
    Navigation = __decorate([
        angular2_1.Component({
            selector: 'minds-navigation',
            viewInjector: [navigation_1.Navigation]
        }),
        angular2_1.View({
            templateUrl: 'templates/components/navigation.html',
            directives: [router_1.RouterLink, angular2_1.NgIf, angular2_1.NgFor, angular2_1.CSSClass]
        }), 
        __metadata('design:paramtypes', [NavigationService])
    ], Navigation);
    return Navigation;
})();
exports.Navigation = Navigation;
//# sourceMappingURL=navigation.js.map