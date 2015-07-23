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
            viewInjector: [storage_1.Storage, sidebar_1.Sidebar]
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

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9jb21wb25lbnRzL3RvcGJhci50cyJdLCJuYW1lcyI6WyJUb3BiYXIiLCJUb3BiYXIuY29uc3RydWN0b3IiLCJUb3BiYXIuc2hvd0xvZ2luIiwiVG9wYmFyLm9wZW5OYXYiXSwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7O0FBQUEseUJBQXFDLG1CQUFtQixDQUFDLENBQUE7QUFDekQsdUJBQTJCLGlCQUFpQixDQUFDLENBQUE7QUFDN0MseUJBQXlCLHlCQUF5QixDQUFDLENBQUE7QUFDbkQsd0JBQXdCLHNCQUFzQixDQUFDLENBQUE7QUFDL0Msd0JBQXdCLHlCQUF5QixDQUFDLENBQUE7QUFDbEQsd0JBQStCLHNCQUFzQixDQUFDLENBQUE7QUFFdEQ7SUFhQ0EsZ0JBQW1CQSxPQUFnQkEsRUFBU0EsT0FBaUJBO1FBQTFDQyxZQUFPQSxHQUFQQSxPQUFPQSxDQUFTQTtRQUFTQSxZQUFPQSxHQUFQQSxPQUFPQSxDQUFVQTtRQUg3REEsYUFBUUEsR0FBR0EsS0FBS0EsQ0FBQ0E7UUFDakJBLFlBQU9BLEdBQUdBLHdCQUFjQSxDQUFDQSxLQUFLQSxFQUFFQSxDQUFDQTtRQUdoQ0EsSUFBSUEsQ0FBQ0EsU0FBU0EsRUFBRUEsQ0FBQ0E7SUFDbEJBLENBQUNBO0lBS0RELDBCQUFTQSxHQUFUQTtRQUNDRSxJQUFJQSxJQUFJQSxHQUFHQSxJQUFJQSxDQUFDQTtRQUNoQkEsSUFBSUEsQ0FBQ0EsUUFBUUEsR0FBR0EsSUFBSUEsQ0FBQ0EsT0FBT0EsQ0FBQ0EsVUFBVUEsQ0FBQ0EsVUFBQ0EsUUFBUUE7WUFDaERBLE9BQU9BLENBQUNBLEdBQUdBLENBQUNBLFFBQVFBLENBQUNBLENBQUFBO1lBQ3JCQSxJQUFJQSxDQUFDQSxRQUFRQSxHQUFHQSxRQUFRQSxDQUFDQTtRQUN6QkEsQ0FBQ0EsQ0FBQ0EsQ0FBQ0E7SUFDTEEsQ0FBQ0E7SUFLREYsd0JBQU9BLEdBQVBBO1FBQ0NHLElBQUlBLENBQUNBLE9BQU9BLENBQUNBLElBQUlBLEVBQUVBLENBQUNBO0lBQ3JCQSxDQUFDQTtJQWpDRkg7UUFBQ0Esb0JBQVNBLENBQUNBO1lBQ1RBLFFBQVFBLEVBQUVBLGNBQWNBO1lBQ3hCQSxZQUFZQSxFQUFFQSxDQUFFQSxpQkFBT0EsRUFBRUEsaUJBQU9BLENBQUVBO1NBQ25DQSxDQUFDQTtRQUNEQSxlQUFJQSxDQUFDQTtZQUNKQSxXQUFXQSxFQUFFQSxrQ0FBa0NBO1lBQy9DQSxVQUFVQSxFQUFFQSxDQUFFQSxlQUFJQSxFQUFFQSxtQkFBVUEsRUFBRUEsbUJBQVFBLENBQUVBO1NBQzNDQSxDQUFDQTs7ZUEyQkRBO0lBQURBLGFBQUNBO0FBQURBLENBbENBLEFBa0NDQSxJQUFBO0FBekJZLGNBQU0sU0F5QmxCLENBQUEiLCJmaWxlIjoic3JjL2NvbXBvbmVudHMvdG9wYmFyLmpzIiwic291cmNlc0NvbnRlbnQiOlsiaW1wb3J0IHsgQ29tcG9uZW50LCBWaWV3LCBOZ0lmfSBmcm9tICdhbmd1bGFyMi9hbmd1bGFyMic7XG5pbXBvcnQgeyBSb3V0ZXJMaW5rIH0gZnJvbSAnYW5ndWxhcjIvcm91dGVyJztcbmltcG9ydCB7IE1hdGVyaWFsIH0gZnJvbSAnc3JjL2RpcmVjdGl2ZXMvbWF0ZXJpYWwnO1xuaW1wb3J0IHsgU3RvcmFnZSB9IGZyb20gJ3NyYy9zZXJ2aWNlcy9zdG9yYWdlJztcbmltcG9ydCB7IFNpZGViYXIgfSBmcm9tICdzcmMvc2VydmljZXMvdWkvc2lkZWJhcic7XG5pbXBvcnQgeyBTZXNzaW9uRmFjdG9yeSB9IGZyb20gJ3NyYy9zZXJ2aWNlcy9zZXNzaW9uJztcblxuQENvbXBvbmVudCh7XG4gIHNlbGVjdG9yOiAnbWluZHMtdG9wYmFyJyxcbiAgdmlld0luamVjdG9yOiBbIFN0b3JhZ2UsIFNpZGViYXIgXVxufSlcbkBWaWV3KHtcbiAgdGVtcGxhdGVVcmw6ICd0ZW1wbGF0ZXMvY29tcG9uZW50cy90b3BiYXIuaHRtbCcsXG4gIGRpcmVjdGl2ZXM6IFsgTmdJZiwgUm91dGVyTGluaywgTWF0ZXJpYWwgXVxufSlcblxuZXhwb3J0IGNsYXNzIFRvcGJhciB7XG5cdGxvZ2dlZGluID0gZmFsc2U7XG5cdHNlc3Npb24gPSBTZXNzaW9uRmFjdG9yeS5idWlsZCgpO1xuXG5cdGNvbnN0cnVjdG9yKHB1YmxpYyBzdG9yYWdlOiBTdG9yYWdlLCBwdWJsaWMgc2lkZWJhciA6IFNpZGViYXIpe1xuXHRcdHRoaXMuc2hvd0xvZ2luKCk7XG5cdH1cblxuXHQvKipcblx0ICogRGV0ZXJtaW5lIGlmIGxvZ2luIGJ1dHRvbiBzaG91bGQgYmUgc2hvd25cblx0ICovXG5cdHNob3dMb2dpbigpe1xuXHRcdHZhciBzZWxmID0gdGhpcztcblx0XHR0aGlzLmxvZ2dlZGluID0gdGhpcy5zZXNzaW9uLmlzTG9nZ2VkSW4oKGxvZ2dlZGluKSA9PiB7XG5cdFx0XHRjb25zb2xlLmxvZyhsb2dnZWRpbilcblx0XHRcdHNlbGYubG9nZ2VkaW4gPSBsb2dnZWRpbjtcblx0XHRcdH0pO1xuXHR9XG5cblx0LyoqXG5cdCAqIE9wZW4gdGhlIG5hdmlnYXRpb25cblx0ICovXG5cdG9wZW5OYXYoKXtcblx0XHR0aGlzLnNpZGViYXIub3BlbigpO1xuXHR9XG59XG4iXSwic291cmNlUm9vdCI6Ii9zb3VyY2UvIn0=