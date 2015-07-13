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
var Navigation = (function () {
    function Navigation(navigation) {
        this.navigation = navigation;
        this.items = navigation.getItems();
        self = this;
        this.getUser();
    }
    Navigation.prototype.getUser = function () {
        //Factory.build(LoggedIn).emit("ok");
        if (window.Minds.user) {
            this.user = window.Minds.user;
        }
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

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9jb21wb25lbnRzL25hdmlnYXRpb24udHMiXSwibmFtZXMiOlsiTmF2aWdhdGlvbiIsIk5hdmlnYXRpb24uY29uc3RydWN0b3IiLCJOYXZpZ2F0aW9uLmdldFVzZXIiXSwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7O0FBQUEseUJBQW1FLG1CQUFtQixDQUFDLENBQUE7QUFDdkYsdUJBQXlCLGlCQUFpQixDQUFDLENBQUE7QUFFM0MsMkJBQWdELHlCQUF5QixDQUFDLENBQUE7QUFFMUU7SUFZQ0Esb0JBQW1CQSxVQUE4QkE7UUFBOUJDLGVBQVVBLEdBQVZBLFVBQVVBLENBQW9CQTtRQURqREEsVUFBS0EsR0FBR0EsVUFBVUEsQ0FBQ0EsUUFBUUEsRUFBRUEsQ0FBQ0E7UUFFN0JBLElBQUlBLEdBQUdBLElBQUlBLENBQUNBO1FBS1pBLElBQUlBLENBQUNBLE9BQU9BLEVBQUVBLENBQUNBO0lBR2hCQSxDQUFDQTtJQUVERCw0QkFBT0EsR0FBUEE7UUFFQ0UscUNBQXFDQTtRQUVyQ0EsRUFBRUEsQ0FBQUEsQ0FBQ0EsTUFBTUEsQ0FBQ0EsS0FBS0EsQ0FBQ0EsSUFBSUEsQ0FBQ0EsQ0FBQUEsQ0FBQ0E7WUFDckJBLElBQUlBLENBQUNBLElBQUlBLEdBQUdBLE1BQU1BLENBQUNBLEtBQUtBLENBQUNBLElBQUlBLENBQUNBO1FBQy9CQSxDQUFDQTtJQUNGQSxDQUFDQTtJQTlCRkY7UUFBQ0Esb0JBQVNBLENBQUNBO1lBQ1RBLFFBQVFBLEVBQUVBLGtCQUFrQkE7WUFDNUJBLFlBQVlBLEVBQUVBLENBQUNBLHVCQUFpQkEsQ0FBQ0E7U0FDbENBLENBQUNBO1FBQ0RBLGVBQUlBLENBQUNBO1lBQ0pBLFdBQVdBLEVBQUVBLHNDQUFzQ0E7WUFDbkRBLFVBQVVBLEVBQUVBLENBQUNBLG1CQUFVQSxFQUFFQSxlQUFJQSxFQUFFQSxnQkFBS0EsRUFBRUEsbUJBQVFBLENBQUNBO1NBQ2hEQSxDQUFDQTs7bUJBd0JEQTtJQUFEQSxpQkFBQ0E7QUFBREEsQ0EvQkEsSUErQkM7QUF0Qlksa0JBQVUsYUFzQnRCLENBQUEiLCJmaWxlIjoic3JjL2NvbXBvbmVudHMvbmF2aWdhdGlvbi5qcyIsInNvdXJjZXNDb250ZW50IjpbImltcG9ydCB7Q29tcG9uZW50LCBWaWV3LCBOZ0lmLCBOZ0ZvciwgQ1NTQ2xhc3MsIEV2ZW50RW1pdHRlcn0gZnJvbSAnYW5ndWxhcjIvYW5ndWxhcjInO1xuaW1wb3J0IHtSb3V0ZXJMaW5rfSBmcm9tICdhbmd1bGFyMi9yb3V0ZXInO1xuaW1wb3J0IHtGYWN0b3J5LCBMb2dnZWRJbn0gZnJvbSAnc3JjL3NlcnZpY2VzL2V2ZW50cyc7XG5pbXBvcnQgeyBOYXZpZ2F0aW9uIGFzIE5hdmlnYXRpb25TZXJ2aWNlIH0gZnJvbSAnc3JjL3NlcnZpY2VzL25hdmlnYXRpb24nO1xuXG5AQ29tcG9uZW50KHtcbiAgc2VsZWN0b3I6ICdtaW5kcy1uYXZpZ2F0aW9uJyxcbiAgdmlld0luamVjdG9yOiBbTmF2aWdhdGlvblNlcnZpY2VdXG59KVxuQFZpZXcoe1xuICB0ZW1wbGF0ZVVybDogJ3RlbXBsYXRlcy9jb21wb25lbnRzL25hdmlnYXRpb24uaHRtbCcsXG4gIGRpcmVjdGl2ZXM6IFtSb3V0ZXJMaW5rLCBOZ0lmLCBOZ0ZvciwgQ1NTQ2xhc3NdXG59KVxuXG5leHBvcnQgY2xhc3MgTmF2aWdhdGlvbiB7IFxuXHR1c2VyO1xuXHRpdGVtcyA9IG5hdmlnYXRpb24uZ2V0SXRlbXMoKTtcblx0Y29uc3RydWN0b3IocHVibGljIG5hdmlnYXRpb24gOiBOYXZpZ2F0aW9uU2VydmljZSl7XG5cdFx0c2VsZiA9IHRoaXM7XG5cdFx0Ly9GYWN0b3J5LmJ1aWxkKExvZ2dlZEluKS5saXN0ZW4oKCk9Pntcblx0XHQvL1x0Y29uc29sZS5sb2coJ3JlY2VpZXZlZCBzZXNzaW9uIGV2ZW50Jyk7XG5cdFx0Ly9cdHRoaXMuZ2V0VXNlcigpO1xuXHRcdC8vfSlcblx0XHR0aGlzLmdldFVzZXIoKTtcblx0XHRcblx0XHQvL2xpc3RlbiB0byBjbGljayBldmVudHMgdG8gY2xvc2UgbmF2XG5cdH1cblx0XG5cdGdldFVzZXIoKXtcblxuXHRcdC8vRmFjdG9yeS5idWlsZChMb2dnZWRJbikuZW1pdChcIm9rXCIpO1xuXHRcdFxuXHRcdGlmKHdpbmRvdy5NaW5kcy51c2VyKXtcblx0XHRcdHRoaXMudXNlciA9IHdpbmRvdy5NaW5kcy51c2VyO1xuXHRcdH1cblx0fVxufSJdLCJzb3VyY2VSb290IjoiL3NvdXJjZS8ifQ==