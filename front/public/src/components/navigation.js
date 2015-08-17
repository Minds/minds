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
            viewBindings: [navigation_1.Navigation]
        }),
        angular2_1.View({
            templateUrl: 'templates/components/navigation.html',
            directives: [router_1.RouterLink, angular2_1.NgIf, angular2_1.NgFor, angular2_1.NgClass]
        }), 
        __metadata('design:paramtypes', [navigation_1.Navigation])
    ], Navigation);
    return Navigation;
})();
exports.Navigation = Navigation;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9jb21wb25lbnRzL25hdmlnYXRpb24udHMiXSwibmFtZXMiOlsiTmF2aWdhdGlvbiIsIk5hdmlnYXRpb24uY29uc3RydWN0b3IiLCJOYXZpZ2F0aW9uLmdldFVzZXIiXSwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7O0FBQUEseUJBQW9FLG1CQUFtQixDQUFDLENBQUE7QUFDeEYsdUJBQTJCLGlCQUFpQixDQUFDLENBQUE7QUFFN0MsMkJBQWdELHlCQUF5QixDQUFDLENBQUE7QUFDMUUsd0JBQStCLHNCQUFzQixDQUFDLENBQUE7QUFFdEQ7SUFhQ0Esb0JBQW1CQSxVQUE4QkE7UUFBOUJDLGVBQVVBLEdBQVZBLFVBQVVBLENBQW9CQTtRQUZqREEsWUFBT0EsR0FBR0Esd0JBQWNBLENBQUNBLEtBQUtBLEVBQUVBLENBQUNBO1FBR2hDQSxJQUFJQSxJQUFJQSxHQUFHQSxJQUFJQSxDQUFDQTtRQUNkQSxJQUFJQSxDQUFDQSxLQUFLQSxHQUFHQSxVQUFVQSxDQUFDQSxRQUFRQSxFQUFFQSxDQUFDQTtRQUtyQ0EsSUFBSUEsQ0FBQ0EsT0FBT0EsRUFBRUEsQ0FBQ0E7SUFHaEJBLENBQUNBO0lBRURELDRCQUFPQSxHQUFQQTtRQUNDRSxJQUFJQSxJQUFJQSxHQUFHQSxJQUFJQSxDQUFDQTtRQUNoQkEsSUFBSUEsQ0FBQ0EsSUFBSUEsR0FBR0EsSUFBSUEsQ0FBQ0EsT0FBT0EsQ0FBQ0EsZUFBZUEsQ0FBQ0EsVUFBQ0EsSUFBSUE7WUFDN0NBLE9BQU9BLENBQUNBLEdBQUdBLENBQUNBLElBQUlBLENBQUNBLENBQUNBO1lBQ2pCQSxJQUFJQSxDQUFDQSxJQUFJQSxHQUFHQSxJQUFJQSxDQUFDQTtRQUNsQkEsQ0FBQ0EsQ0FBQ0EsQ0FBQ0E7SUFDTEEsQ0FBQ0E7SUEvQkZGO1FBQUNBLG9CQUFTQSxDQUFDQTtZQUNUQSxRQUFRQSxFQUFFQSxrQkFBa0JBO1lBQzVCQSxZQUFZQSxFQUFFQSxDQUFDQSx1QkFBaUJBLENBQUNBO1NBQ2xDQSxDQUFDQTtRQUNEQSxlQUFJQSxDQUFDQTtZQUNKQSxXQUFXQSxFQUFFQSxzQ0FBc0NBO1lBQ25EQSxVQUFVQSxFQUFFQSxDQUFDQSxtQkFBVUEsRUFBRUEsZUFBSUEsRUFBRUEsZ0JBQUtBLEVBQUVBLGtCQUFPQSxDQUFDQTtTQUMvQ0EsQ0FBQ0E7O21CQXlCREE7SUFBREEsaUJBQUNBO0FBQURBLENBaENBLEFBZ0NDQSxJQUFBO0FBdkJZLGtCQUFVLGFBdUJ0QixDQUFBIiwiZmlsZSI6InNyYy9jb21wb25lbnRzL25hdmlnYXRpb24uanMiLCJzb3VyY2VzQ29udGVudCI6WyJpbXBvcnQgeyBDb21wb25lbnQsIFZpZXcsIE5nSWYsIE5nRm9yLCBOZ0NsYXNzLCBFdmVudEVtaXR0ZXIgfSBmcm9tICdhbmd1bGFyMi9hbmd1bGFyMic7XG5pbXBvcnQgeyBSb3V0ZXJMaW5rIH0gZnJvbSAnYW5ndWxhcjIvcm91dGVyJztcbmltcG9ydCB7IEZhY3RvcnksIExvZ2dlZEluIH0gZnJvbSAnc3JjL3NlcnZpY2VzL2V2ZW50cyc7XG5pbXBvcnQgeyBOYXZpZ2F0aW9uIGFzIE5hdmlnYXRpb25TZXJ2aWNlIH0gZnJvbSAnc3JjL3NlcnZpY2VzL25hdmlnYXRpb24nO1xuaW1wb3J0IHsgU2Vzc2lvbkZhY3RvcnkgfSBmcm9tICdzcmMvc2VydmljZXMvc2Vzc2lvbic7XG5cbkBDb21wb25lbnQoe1xuICBzZWxlY3RvcjogJ21pbmRzLW5hdmlnYXRpb24nLFxuICB2aWV3QmluZGluZ3M6IFtOYXZpZ2F0aW9uU2VydmljZV1cbn0pXG5AVmlldyh7XG4gIHRlbXBsYXRlVXJsOiAndGVtcGxhdGVzL2NvbXBvbmVudHMvbmF2aWdhdGlvbi5odG1sJyxcbiAgZGlyZWN0aXZlczogW1JvdXRlckxpbmssIE5nSWYsIE5nRm9yLCBOZ0NsYXNzXVxufSlcblxuZXhwb3J0IGNsYXNzIE5hdmlnYXRpb24ge1xuXHR1c2VyO1xuXHRzZXNzaW9uID0gU2Vzc2lvbkZhY3RvcnkuYnVpbGQoKTtcblx0aXRlbXM7XG5cdGNvbnN0cnVjdG9yKHB1YmxpYyBuYXZpZ2F0aW9uIDogTmF2aWdhdGlvblNlcnZpY2Upe1xuXHRcdHZhciBzZWxmID0gdGhpcztcbiAgICB0aGlzLml0ZW1zID0gbmF2aWdhdGlvbi5nZXRJdGVtcygpO1xuXHRcdC8vRmFjdG9yeS5idWlsZChMb2dnZWRJbikubGlzdGVuKCgpPT57XG5cdFx0Ly9cdGNvbnNvbGUubG9nKCdyZWNlaWV2ZWQgc2Vzc2lvbiBldmVudCcpO1xuXHRcdC8vXHR0aGlzLmdldFVzZXIoKTtcblx0XHQvL30pXG5cdFx0dGhpcy5nZXRVc2VyKCk7XG5cblx0XHQvL2xpc3RlbiB0byBjbGljayBldmVudHMgdG8gY2xvc2UgbmF2XG5cdH1cblxuXHRnZXRVc2VyKCl7XG5cdFx0dmFyIHNlbGYgPSB0aGlzO1xuXHRcdHRoaXMudXNlciA9IHRoaXMuc2Vzc2lvbi5nZXRMb2dnZWRJblVzZXIoKHVzZXIpID0+IHtcblx0XHRcdGNvbnNvbGUubG9nKHVzZXIpO1xuXHRcdFx0XHRzZWxmLnVzZXIgPSB1c2VyO1xuXHRcdFx0fSk7XG5cdH1cbn1cbiJdLCJzb3VyY2VSb290IjoiL3NvdXJjZS8ifQ==