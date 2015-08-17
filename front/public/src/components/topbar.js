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
            selector: 'minds-topbar',
            viewBindings: [storage_1.Storage, sidebar_1.Sidebar]
        }),
        angular2_1.View({
            templateUrl: 'templates/components/topbar.html',
            directives: [angular2_1.NgIf, router_1.RouterLink, material_1.Material]
        }), 
        __metadata('design:paramtypes', [storage_1.Storage, sidebar_1.Sidebar])
    ], Topbar);
    return Topbar;
})();
exports.Topbar = Topbar;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9jb21wb25lbnRzL3RvcGJhci50cyJdLCJuYW1lcyI6WyJUb3BiYXIiLCJUb3BiYXIuY29uc3RydWN0b3IiLCJUb3BiYXIuc2hvd0xvZ2luIiwiVG9wYmFyLm9wZW5OYXYiXSwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7O0FBQUEseUJBQXFDLG1CQUFtQixDQUFDLENBQUE7QUFDekQsdUJBQTJCLGlCQUFpQixDQUFDLENBQUE7QUFDN0MseUJBQXlCLHlCQUF5QixDQUFDLENBQUE7QUFDbkQsd0JBQXdCLHNCQUFzQixDQUFDLENBQUE7QUFDL0Msd0JBQXdCLHlCQUF5QixDQUFDLENBQUE7QUFDbEQsd0JBQStCLHNCQUFzQixDQUFDLENBQUE7QUFFdEQ7SUFhQ0EsZ0JBQW1CQSxPQUFnQkEsRUFBU0EsT0FBaUJBO1FBQTFDQyxZQUFPQSxHQUFQQSxPQUFPQSxDQUFTQTtRQUFTQSxZQUFPQSxHQUFQQSxPQUFPQSxDQUFVQTtRQUg3REEsYUFBUUEsR0FBR0EsS0FBS0EsQ0FBQ0E7UUFDakJBLFlBQU9BLEdBQUdBLHdCQUFjQSxDQUFDQSxLQUFLQSxFQUFFQSxDQUFDQTtRQUdoQ0EsSUFBSUEsQ0FBQ0EsU0FBU0EsRUFBRUEsQ0FBQ0E7SUFDbEJBLENBQUNBO0lBS0RELDBCQUFTQSxHQUFUQTtRQUNDRSxJQUFJQSxJQUFJQSxHQUFHQSxJQUFJQSxDQUFDQTtRQUNoQkEsSUFBSUEsQ0FBQ0EsUUFBUUEsR0FBR0EsSUFBSUEsQ0FBQ0EsT0FBT0EsQ0FBQ0EsVUFBVUEsQ0FBQ0EsVUFBQ0EsUUFBUUE7WUFDaERBLE9BQU9BLENBQUNBLEdBQUdBLENBQUNBLFFBQVFBLENBQUNBLENBQUFBO1lBQ3JCQSxJQUFJQSxDQUFDQSxRQUFRQSxHQUFHQSxRQUFRQSxDQUFDQTtRQUN6QkEsQ0FBQ0EsQ0FBQ0EsQ0FBQ0E7SUFDTEEsQ0FBQ0E7SUFLREYsd0JBQU9BLEdBQVBBO1FBQ0NHLElBQUlBLENBQUNBLE9BQU9BLENBQUNBLElBQUlBLEVBQUVBLENBQUNBO0lBQ3JCQSxDQUFDQTtJQWpDRkg7UUFBQ0Esb0JBQVNBLENBQUNBO1lBQ1RBLFFBQVFBLEVBQUVBLGNBQWNBO1lBQ3hCQSxZQUFZQSxFQUFFQSxDQUFFQSxpQkFBT0EsRUFBRUEsaUJBQU9BLENBQUVBO1NBQ25DQSxDQUFDQTtRQUNEQSxlQUFJQSxDQUFDQTtZQUNKQSxXQUFXQSxFQUFFQSxrQ0FBa0NBO1lBQy9DQSxVQUFVQSxFQUFFQSxDQUFFQSxlQUFJQSxFQUFFQSxtQkFBVUEsRUFBRUEsbUJBQVFBLENBQUVBO1NBQzNDQSxDQUFDQTs7ZUEyQkRBO0lBQURBLGFBQUNBO0FBQURBLENBbENBLEFBa0NDQSxJQUFBO0FBekJZLGNBQU0sU0F5QmxCLENBQUEiLCJmaWxlIjoic3JjL2NvbXBvbmVudHMvdG9wYmFyLmpzIiwic291cmNlc0NvbnRlbnQiOlsiaW1wb3J0IHsgQ29tcG9uZW50LCBWaWV3LCBOZ0lmfSBmcm9tICdhbmd1bGFyMi9hbmd1bGFyMic7XG5pbXBvcnQgeyBSb3V0ZXJMaW5rIH0gZnJvbSAnYW5ndWxhcjIvcm91dGVyJztcbmltcG9ydCB7IE1hdGVyaWFsIH0gZnJvbSAnc3JjL2RpcmVjdGl2ZXMvbWF0ZXJpYWwnO1xuaW1wb3J0IHsgU3RvcmFnZSB9IGZyb20gJ3NyYy9zZXJ2aWNlcy9zdG9yYWdlJztcbmltcG9ydCB7IFNpZGViYXIgfSBmcm9tICdzcmMvc2VydmljZXMvdWkvc2lkZWJhcic7XG5pbXBvcnQgeyBTZXNzaW9uRmFjdG9yeSB9IGZyb20gJ3NyYy9zZXJ2aWNlcy9zZXNzaW9uJztcblxuQENvbXBvbmVudCh7XG4gIHNlbGVjdG9yOiAnbWluZHMtdG9wYmFyJyxcbiAgdmlld0JpbmRpbmdzOiBbIFN0b3JhZ2UsIFNpZGViYXIgXVxufSlcbkBWaWV3KHtcbiAgdGVtcGxhdGVVcmw6ICd0ZW1wbGF0ZXMvY29tcG9uZW50cy90b3BiYXIuaHRtbCcsXG4gIGRpcmVjdGl2ZXM6IFsgTmdJZiwgUm91dGVyTGluaywgTWF0ZXJpYWwgXVxufSlcblxuZXhwb3J0IGNsYXNzIFRvcGJhcntcblx0bG9nZ2VkaW4gPSBmYWxzZTtcblx0c2Vzc2lvbiA9IFNlc3Npb25GYWN0b3J5LmJ1aWxkKCk7XG5cblx0Y29uc3RydWN0b3IocHVibGljIHN0b3JhZ2U6IFN0b3JhZ2UsIHB1YmxpYyBzaWRlYmFyIDogU2lkZWJhcil7XG5cdFx0dGhpcy5zaG93TG9naW4oKTtcblx0fVxuXG5cdC8qKlxuXHQgKiBEZXRlcm1pbmUgaWYgbG9naW4gYnV0dG9uIHNob3VsZCBiZSBzaG93blxuXHQgKi9cblx0c2hvd0xvZ2luKCl7XG5cdFx0dmFyIHNlbGYgPSB0aGlzO1xuXHRcdHRoaXMubG9nZ2VkaW4gPSB0aGlzLnNlc3Npb24uaXNMb2dnZWRJbigobG9nZ2VkaW4pID0+IHtcblx0XHRcdGNvbnNvbGUubG9nKGxvZ2dlZGluKVxuXHRcdFx0c2VsZi5sb2dnZWRpbiA9IGxvZ2dlZGluO1xuXHRcdFx0fSk7XG5cdH1cblxuXHQvKipcblx0ICogT3BlbiB0aGUgbmF2aWdhdGlvblxuXHQgKi9cblx0b3Blbk5hdigpe1xuXHRcdHRoaXMuc2lkZWJhci5vcGVuKCk7XG5cdH1cbn1cbiJdLCJzb3VyY2VSb290IjoiL3NvdXJjZS8ifQ==