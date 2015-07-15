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
var angular2_1 = require('angular2/angular2');
var router_1 = require('angular2/router');
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
            directives: [angular2_1.NgIf, router_1.RouterLink]
        }), 
        __metadata('design:paramtypes', [storage_1.Storage, sidebar_1.Sidebar])
    ], Topbar);
    return Topbar;
})();
exports.Topbar = Topbar;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9jb21wb25lbnRzL3RvcGJhci50cyJdLCJuYW1lcyI6WyJUb3BiYXIiLCJUb3BiYXIuY29uc3RydWN0b3IiLCJUb3BiYXIuc2hvd0xvZ2luIiwiVG9wYmFyLm9wZW5OYXYiXSwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7O0FBQUEseUJBQXFDLG1CQUFtQixDQUFDLENBQUE7QUFDekQsdUJBQTJCLGlCQUFpQixDQUFDLENBQUE7QUFDN0Msd0JBQXdCLHNCQUFzQixDQUFDLENBQUE7QUFDL0Msd0JBQXdCLHlCQUF5QixDQUFDLENBQUE7QUFDbEQsd0JBQStCLHNCQUFzQixDQUFDLENBQUE7QUFFdEQ7SUFhQ0EsZ0JBQW1CQSxPQUFnQkEsRUFBU0EsT0FBaUJBO1FBQTFDQyxZQUFPQSxHQUFQQSxPQUFPQSxDQUFTQTtRQUFTQSxZQUFPQSxHQUFQQSxPQUFPQSxDQUFVQTtRQUg3REEsYUFBUUEsR0FBR0EsS0FBS0EsQ0FBQ0E7UUFDakJBLFlBQU9BLEdBQUdBLHdCQUFjQSxDQUFDQSxLQUFLQSxFQUFFQSxDQUFDQTtRQUdoQ0EsSUFBSUEsQ0FBQ0EsU0FBU0EsRUFBRUEsQ0FBQ0E7SUFDbEJBLENBQUNBO0lBS0RELDBCQUFTQSxHQUFUQTtRQUNDRSxJQUFJQSxJQUFJQSxHQUFHQSxJQUFJQSxDQUFDQTtRQUNoQkEsSUFBSUEsQ0FBQ0EsUUFBUUEsR0FBR0EsSUFBSUEsQ0FBQ0EsT0FBT0EsQ0FBQ0EsVUFBVUEsQ0FBQ0EsVUFBQ0EsUUFBUUE7WUFDaERBLE9BQU9BLENBQUNBLEdBQUdBLENBQUNBLFFBQVFBLENBQUNBLENBQUFBO1lBQ3JCQSxJQUFJQSxDQUFDQSxRQUFRQSxHQUFHQSxRQUFRQSxDQUFDQTtRQUN6QkEsQ0FBQ0EsQ0FBQ0EsQ0FBQ0E7SUFDTEEsQ0FBQ0E7SUFLREYsd0JBQU9BLEdBQVBBO1FBQ0NHLElBQUlBLENBQUNBLE9BQU9BLENBQUNBLElBQUlBLEVBQUVBLENBQUNBO0lBQ3JCQSxDQUFDQTtJQWpDRkg7UUFBQ0Esb0JBQVNBLENBQUNBO1lBQ1RBLFFBQVFBLEVBQUVBLGNBQWNBO1lBQ3hCQSxZQUFZQSxFQUFFQSxDQUFDQSxpQkFBT0EsRUFBRUEsaUJBQU9BLENBQUNBO1NBQ2pDQSxDQUFDQTtRQUNEQSxlQUFJQSxDQUFDQTtZQUNKQSxXQUFXQSxFQUFFQSxrQ0FBa0NBO1lBQy9DQSxVQUFVQSxFQUFFQSxDQUFDQSxlQUFJQSxFQUFFQSxtQkFBVUEsQ0FBQ0E7U0FDL0JBLENBQUNBOztlQTJCREE7SUFBREEsYUFBQ0E7QUFBREEsQ0FsQ0EsSUFrQ0M7QUF6QlksY0FBTSxTQXlCbEIsQ0FBQSIsImZpbGUiOiJzcmMvY29tcG9uZW50cy90b3BiYXIuanMiLCJzb3VyY2VzQ29udGVudCI6WyJpbXBvcnQgeyBDb21wb25lbnQsIFZpZXcsIE5nSWZ9IGZyb20gJ2FuZ3VsYXIyL2FuZ3VsYXIyJztcbmltcG9ydCB7IFJvdXRlckxpbmsgfSBmcm9tICdhbmd1bGFyMi9yb3V0ZXInO1xuaW1wb3J0IHsgU3RvcmFnZSB9IGZyb20gJ3NyYy9zZXJ2aWNlcy9zdG9yYWdlJztcbmltcG9ydCB7IFNpZGViYXIgfSBmcm9tICdzcmMvc2VydmljZXMvdWkvc2lkZWJhcic7XG5pbXBvcnQgeyBTZXNzaW9uRmFjdG9yeSB9IGZyb20gJ3NyYy9zZXJ2aWNlcy9zZXNzaW9uJztcblxuQENvbXBvbmVudCh7XG4gIHNlbGVjdG9yOiAnbWluZHMtdG9wYmFyJyxcbiAgdmlld0luamVjdG9yOiBbU3RvcmFnZSwgU2lkZWJhcl1cbn0pXG5AVmlldyh7XG4gIHRlbXBsYXRlVXJsOiAndGVtcGxhdGVzL2NvbXBvbmVudHMvdG9wYmFyLmh0bWwnLFxuICBkaXJlY3RpdmVzOiBbTmdJZiwgUm91dGVyTGlua11cbn0pXG5cbmV4cG9ydCBjbGFzcyBUb3BiYXIge1xuXHRsb2dnZWRpbiA9IGZhbHNlO1xuXHRzZXNzaW9uID0gU2Vzc2lvbkZhY3RvcnkuYnVpbGQoKTtcblxuXHRjb25zdHJ1Y3RvcihwdWJsaWMgc3RvcmFnZTogU3RvcmFnZSwgcHVibGljIHNpZGViYXIgOiBTaWRlYmFyKXtcblx0XHR0aGlzLnNob3dMb2dpbigpO1xuXHR9XG5cblx0LyoqXG5cdCAqIERldGVybWluZSBpZiBsb2dpbiBidXR0b24gc2hvdWxkIGJlIHNob3duXG5cdCAqL1xuXHRzaG93TG9naW4oKXtcblx0XHR2YXIgc2VsZiA9IHRoaXM7XG5cdFx0dGhpcy5sb2dnZWRpbiA9IHRoaXMuc2Vzc2lvbi5pc0xvZ2dlZEluKChsb2dnZWRpbikgPT4ge1xuXHRcdFx0Y29uc29sZS5sb2cobG9nZ2VkaW4pXG5cdFx0XHRzZWxmLmxvZ2dlZGluID0gbG9nZ2VkaW47XG5cdFx0XHR9KTtcblx0fVxuXG5cdC8qKlxuXHQgKiBPcGVuIHRoZSBuYXZpZ2F0aW9uXG5cdCAqL1xuXHRvcGVuTmF2KCl7XG5cdFx0dGhpcy5zaWRlYmFyLm9wZW4oKTtcblx0fVxufVxuIl0sInNvdXJjZVJvb3QiOiIvc291cmNlLyJ9