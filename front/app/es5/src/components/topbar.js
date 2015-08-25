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
var material_1 = require('src/directives/material');
var storage_1 = require('src/services/storage');
var sidebar_1 = require('src/services/ui/sidebar');
var session_1 = require('src/services/session');
var Topbar = (function () {
    function Topbar(storage, sidebar) {
        this.storage = storage;
        this.sidebar = sidebar;
        this.loggedin = false;
        this.session = session_1.SessionFactory.build();
        this.showLogin();
    }
    Topbar.prototype.showLogin = function () {
        var self = this;
        this.loggedin = this.session.isLoggedIn(function (loggedin) {
            console.log(loggedin);
            self.loggedin = loggedin;
        });
    };
    Topbar.prototype.openNav = function () {
        this.sidebar.open();
    };
    Topbar = __decorate([
        angular2_1.Component({
            selector: 'minds-topbar'
        }),
        angular2_1.View({
            templateUrl: 'templates/components/topbar.html',
            directives: [angular2_1.NgIf, router_1.RouterLink, material_1.Material]
        }), 
        __metadata('design:paramtypes', [Storage, Sidebar])
    ], Topbar);
    return Topbar;
})();
exports.Topbar = Topbar;
//# sourceMappingURL=topbar.js.map