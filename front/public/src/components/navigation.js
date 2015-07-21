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
        __metadata('design:paramtypes', [navigation_1.Navigation])
    ], Navigation);
    return Navigation;
})();
exports.Navigation = Navigation;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9jb21wb25lbnRzL25hdmlnYXRpb24udHMiXSwibmFtZXMiOlsiTmF2aWdhdGlvbiIsIk5hdmlnYXRpb24uY29uc3RydWN0b3IiLCJOYXZpZ2F0aW9uLmdldFVzZXIiXSwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7O0FBQUEseUJBQXFFLG1CQUFtQixDQUFDLENBQUE7QUFDekYsdUJBQTJCLGlCQUFpQixDQUFDLENBQUE7QUFFN0MsMkJBQWdELHlCQUF5QixDQUFDLENBQUE7QUFDMUUsd0JBQStCLHNCQUFzQixDQUFDLENBQUE7QUFFdEQ7SUFhQ0Esb0JBQW1CQSxVQUE4QkE7UUFBOUJDLGVBQVVBLEdBQVZBLFVBQVVBLENBQW9CQTtRQUZqREEsWUFBT0EsR0FBR0Esd0JBQWNBLENBQUNBLEtBQUtBLEVBQUVBLENBQUNBO1FBR2hDQSxJQUFJQSxJQUFJQSxHQUFHQSxJQUFJQSxDQUFDQTtRQUNkQSxJQUFJQSxDQUFDQSxLQUFLQSxHQUFHQSxVQUFVQSxDQUFDQSxRQUFRQSxFQUFFQSxDQUFDQTtRQUtyQ0EsSUFBSUEsQ0FBQ0EsT0FBT0EsRUFBRUEsQ0FBQ0E7SUFHaEJBLENBQUNBO0lBRURELDRCQUFPQSxHQUFQQTtRQUNDRSxJQUFJQSxJQUFJQSxHQUFHQSxJQUFJQSxDQUFDQTtRQUNoQkEsSUFBSUEsQ0FBQ0EsSUFBSUEsR0FBR0EsSUFBSUEsQ0FBQ0EsT0FBT0EsQ0FBQ0EsZUFBZUEsQ0FBQ0EsVUFBQ0EsSUFBSUE7WUFDN0NBLE9BQU9BLENBQUNBLEdBQUdBLENBQUNBLElBQUlBLENBQUNBLENBQUNBO1lBQ2pCQSxJQUFJQSxDQUFDQSxJQUFJQSxHQUFHQSxJQUFJQSxDQUFDQTtRQUNsQkEsQ0FBQ0EsQ0FBQ0EsQ0FBQ0E7SUFDTEEsQ0FBQ0E7SUEvQkZGO1FBQUNBLG9CQUFTQSxDQUFDQTtZQUNUQSxRQUFRQSxFQUFFQSxrQkFBa0JBO1lBQzVCQSxZQUFZQSxFQUFFQSxDQUFDQSx1QkFBaUJBLENBQUNBO1NBQ2xDQSxDQUFDQTtRQUNEQSxlQUFJQSxDQUFDQTtZQUNKQSxXQUFXQSxFQUFFQSxzQ0FBc0NBO1lBQ25EQSxVQUFVQSxFQUFFQSxDQUFDQSxtQkFBVUEsRUFBRUEsZUFBSUEsRUFBRUEsZ0JBQUtBLEVBQUVBLG1CQUFRQSxDQUFDQTtTQUNoREEsQ0FBQ0E7O21CQXlCREE7SUFBREEsaUJBQUNBO0FBQURBLENBaENBLElBZ0NDO0FBdkJZLGtCQUFVLGFBdUJ0QixDQUFBIiwiZmlsZSI6InNyYy9jb21wb25lbnRzL25hdmlnYXRpb24uanMiLCJzb3VyY2VzQ29udGVudCI6WyJpbXBvcnQgeyBDb21wb25lbnQsIFZpZXcsIE5nSWYsIE5nRm9yLCBDU1NDbGFzcywgRXZlbnRFbWl0dGVyIH0gZnJvbSAnYW5ndWxhcjIvYW5ndWxhcjInO1xuaW1wb3J0IHsgUm91dGVyTGluayB9IGZyb20gJ2FuZ3VsYXIyL3JvdXRlcic7XG5pbXBvcnQgeyBGYWN0b3J5LCBMb2dnZWRJbiB9IGZyb20gJ3NyYy9zZXJ2aWNlcy9ldmVudHMnO1xuaW1wb3J0IHsgTmF2aWdhdGlvbiBhcyBOYXZpZ2F0aW9uU2VydmljZSB9IGZyb20gJ3NyYy9zZXJ2aWNlcy9uYXZpZ2F0aW9uJztcbmltcG9ydCB7IFNlc3Npb25GYWN0b3J5IH0gZnJvbSAnc3JjL3NlcnZpY2VzL3Nlc3Npb24nO1xuXG5AQ29tcG9uZW50KHtcbiAgc2VsZWN0b3I6ICdtaW5kcy1uYXZpZ2F0aW9uJyxcbiAgdmlld0luamVjdG9yOiBbTmF2aWdhdGlvblNlcnZpY2VdXG59KVxuQFZpZXcoe1xuICB0ZW1wbGF0ZVVybDogJ3RlbXBsYXRlcy9jb21wb25lbnRzL25hdmlnYXRpb24uaHRtbCcsXG4gIGRpcmVjdGl2ZXM6IFtSb3V0ZXJMaW5rLCBOZ0lmLCBOZ0ZvciwgQ1NTQ2xhc3NdXG59KVxuXG5leHBvcnQgY2xhc3MgTmF2aWdhdGlvbiB7XG5cdHVzZXI7XG5cdHNlc3Npb24gPSBTZXNzaW9uRmFjdG9yeS5idWlsZCgpO1xuXHRpdGVtcztcblx0Y29uc3RydWN0b3IocHVibGljIG5hdmlnYXRpb24gOiBOYXZpZ2F0aW9uU2VydmljZSl7XG5cdFx0dmFyIHNlbGYgPSB0aGlzO1xuICAgIHRoaXMuaXRlbXMgPSBuYXZpZ2F0aW9uLmdldEl0ZW1zKCk7XG5cdFx0Ly9GYWN0b3J5LmJ1aWxkKExvZ2dlZEluKS5saXN0ZW4oKCk9Pntcblx0XHQvL1x0Y29uc29sZS5sb2coJ3JlY2VpZXZlZCBzZXNzaW9uIGV2ZW50Jyk7XG5cdFx0Ly9cdHRoaXMuZ2V0VXNlcigpO1xuXHRcdC8vfSlcblx0XHR0aGlzLmdldFVzZXIoKTtcblxuXHRcdC8vbGlzdGVuIHRvIGNsaWNrIGV2ZW50cyB0byBjbG9zZSBuYXZcblx0fVxuXG5cdGdldFVzZXIoKXtcblx0XHR2YXIgc2VsZiA9IHRoaXM7XG5cdFx0dGhpcy51c2VyID0gdGhpcy5zZXNzaW9uLmdldExvZ2dlZEluVXNlcigodXNlcikgPT4ge1xuXHRcdFx0Y29uc29sZS5sb2codXNlcik7XG5cdFx0XHRcdHNlbGYudXNlciA9IHVzZXI7XG5cdFx0XHR9KTtcblx0fVxufVxuIl0sInNvdXJjZVJvb3QiOiIvc291cmNlLyJ9