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
var events_1 = require('src/services/events');
var Navigation = (function () {
    function Navigation() {
        var _this = this;
        self = this;
        events_1.Factory.build(events_1.LoggedIn).listen(function () {
            console.log('receieved session event');
            _this.getUser();
        });
        this.getUser();
    }
    Navigation.prototype.getUser = function () {
        events_1.Factory.build(events_1.LoggedIn).emit("ok");
        if (window.Minds.user) {
            this.user = window.Minds.user;
        }
    };
    Navigation = __decorate([
        angular2_1.Component({
            selector: 'minds-navigation'
        }),
        angular2_1.View({
            templateUrl: 'templates/components/navigation.html',
            directives: [router_1.RouterLink, angular2_1.NgIf, angular2_1.NgFor]
        }), 
        __metadata('design:paramtypes', [])
    ], Navigation);
    return Navigation;
})();
exports.Navigation = Navigation;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9jb21wb25lbnRzL25hdmlnYXRpb24udHMiXSwibmFtZXMiOlsiTmF2aWdhdGlvbiIsIk5hdmlnYXRpb24uY29uc3RydWN0b3IiLCJOYXZpZ2F0aW9uLmdldFVzZXIiXSwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7O0FBQUEseUJBQXlELG1CQUFtQixDQUFDLENBQUE7QUFDN0UsdUJBQXlCLGlCQUFpQixDQUFDLENBQUE7QUFDM0MsdUJBQWdDLHFCQUFxQixDQUFDLENBQUE7QUFFdEQ7SUFXQ0E7UUFYREMsaUJBNEJDQTtRQWhCQ0EsSUFBSUEsR0FBR0EsSUFBSUEsQ0FBQ0E7UUFDWkEsZ0JBQU9BLENBQUNBLEtBQUtBLENBQUNBLGlCQUFRQSxDQUFDQSxDQUFDQSxNQUFNQSxDQUFDQTtZQUM5QkEsT0FBT0EsQ0FBQ0EsR0FBR0EsQ0FBQ0EseUJBQXlCQSxDQUFDQSxDQUFDQTtZQUN2Q0EsS0FBSUEsQ0FBQ0EsT0FBT0EsRUFBRUEsQ0FBQ0E7UUFDaEJBLENBQUNBLENBQUNBLENBQUFBO1FBQ0ZBLElBQUlBLENBQUNBLE9BQU9BLEVBQUVBLENBQUNBO0lBQ2hCQSxDQUFDQTtJQUVERCw0QkFBT0EsR0FBUEE7UUFFQ0UsZ0JBQU9BLENBQUNBLEtBQUtBLENBQUNBLGlCQUFRQSxDQUFDQSxDQUFDQSxJQUFJQSxDQUFDQSxJQUFJQSxDQUFDQSxDQUFDQTtRQUVuQ0EsRUFBRUEsQ0FBQUEsQ0FBQ0EsTUFBTUEsQ0FBQ0EsS0FBS0EsQ0FBQ0EsSUFBSUEsQ0FBQ0EsQ0FBQUEsQ0FBQ0E7WUFDckJBLElBQUlBLENBQUNBLElBQUlBLEdBQUdBLE1BQU1BLENBQUNBLEtBQUtBLENBQUNBLElBQUlBLENBQUNBO1FBQy9CQSxDQUFDQTtJQUNGQSxDQUFDQTtJQTNCRkY7UUFBQ0Esb0JBQVNBLENBQUNBO1lBQ1RBLFFBQVFBLEVBQUVBLGtCQUFrQkE7U0FDN0JBLENBQUNBO1FBQ0RBLGVBQUlBLENBQUNBO1lBQ0pBLFdBQVdBLEVBQUVBLHNDQUFzQ0E7WUFDbkRBLFVBQVVBLEVBQUVBLENBQUNBLG1CQUFVQSxFQUFFQSxlQUFJQSxFQUFFQSxnQkFBS0EsQ0FBQ0E7U0FDdENBLENBQUNBOzttQkFzQkRBO0lBQURBLGlCQUFDQTtBQUFEQSxDQTVCQSxJQTRCQztBQXBCWSxrQkFBVSxhQW9CdEIsQ0FBQSIsImZpbGUiOiJzcmMvY29tcG9uZW50cy9uYXZpZ2F0aW9uLmpzIiwic291cmNlc0NvbnRlbnQiOlsiaW1wb3J0IHtDb21wb25lbnQsIFZpZXcsIE5nSWYsIE5nRm9yLCBFdmVudEVtaXR0ZXJ9IGZyb20gJ2FuZ3VsYXIyL2FuZ3VsYXIyJztcbmltcG9ydCB7Um91dGVyTGlua30gZnJvbSAnYW5ndWxhcjIvcm91dGVyJztcbmltcG9ydCB7RmFjdG9yeSwgTG9nZ2VkSW59IGZyb20gJ3NyYy9zZXJ2aWNlcy9ldmVudHMnO1xuXG5AQ29tcG9uZW50KHtcbiAgc2VsZWN0b3I6ICdtaW5kcy1uYXZpZ2F0aW9uJ1xufSlcbkBWaWV3KHtcbiAgdGVtcGxhdGVVcmw6ICd0ZW1wbGF0ZXMvY29tcG9uZW50cy9uYXZpZ2F0aW9uLmh0bWwnLFxuICBkaXJlY3RpdmVzOiBbUm91dGVyTGluaywgTmdJZiwgTmdGb3JdXG59KVxuXG5leHBvcnQgY2xhc3MgTmF2aWdhdGlvbiB7IFxuXHR1c2VyO1xuXG5cdGNvbnN0cnVjdG9yKCl7XG5cdFx0c2VsZiA9IHRoaXM7XG5cdFx0RmFjdG9yeS5idWlsZChMb2dnZWRJbikubGlzdGVuKCgpPT57XG5cdFx0XHRjb25zb2xlLmxvZygncmVjZWlldmVkIHNlc3Npb24gZXZlbnQnKTtcblx0XHRcdHRoaXMuZ2V0VXNlcigpO1xuXHRcdH0pXG5cdFx0dGhpcy5nZXRVc2VyKCk7XG5cdH1cblx0XG5cdGdldFVzZXIoKXtcblxuXHRcdEZhY3RvcnkuYnVpbGQoTG9nZ2VkSW4pLmVtaXQoXCJva1wiKTtcblx0XHRcblx0XHRpZih3aW5kb3cuTWluZHMudXNlcil7XG5cdFx0XHR0aGlzLnVzZXIgPSB3aW5kb3cuTWluZHMudXNlcjtcblx0XHR9XG5cdH1cbn0iXSwic291cmNlUm9vdCI6Ii9zb3VyY2UvIn0=