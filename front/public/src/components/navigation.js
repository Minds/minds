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
var Navigation = (function () {
    function Navigation() {
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

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9jb21wb25lbnRzL25hdmlnYXRpb24udHMiXSwibmFtZXMiOlsiTmF2aWdhdGlvbiIsIk5hdmlnYXRpb24uY29uc3RydWN0b3IiLCJOYXZpZ2F0aW9uLmdldFVzZXIiXSwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7O0FBQUEseUJBQXlELG1CQUFtQixDQUFDLENBQUE7QUFDN0UsdUJBQXlCLGlCQUFpQixDQUFDLENBQUE7QUFHM0M7SUFXQ0E7UUFDQ0MsSUFBSUEsR0FBR0EsSUFBSUEsQ0FBQ0E7UUFLWkEsSUFBSUEsQ0FBQ0EsT0FBT0EsRUFBRUEsQ0FBQ0E7SUFDaEJBLENBQUNBO0lBRURELDRCQUFPQSxHQUFQQTtRQUVDRSxxQ0FBcUNBO1FBRXJDQSxFQUFFQSxDQUFBQSxDQUFDQSxNQUFNQSxDQUFDQSxLQUFLQSxDQUFDQSxJQUFJQSxDQUFDQSxDQUFBQSxDQUFDQTtZQUNyQkEsSUFBSUEsQ0FBQ0EsSUFBSUEsR0FBR0EsTUFBTUEsQ0FBQ0EsS0FBS0EsQ0FBQ0EsSUFBSUEsQ0FBQ0E7UUFDL0JBLENBQUNBO0lBQ0ZBLENBQUNBO0lBM0JGRjtRQUFDQSxvQkFBU0EsQ0FBQ0E7WUFDVEEsUUFBUUEsRUFBRUEsa0JBQWtCQTtTQUM3QkEsQ0FBQ0E7UUFDREEsZUFBSUEsQ0FBQ0E7WUFDSkEsV0FBV0EsRUFBRUEsc0NBQXNDQTtZQUNuREEsVUFBVUEsRUFBRUEsQ0FBQ0EsbUJBQVVBLEVBQUVBLGVBQUlBLEVBQUVBLGdCQUFLQSxDQUFDQTtTQUN0Q0EsQ0FBQ0E7O21CQXNCREE7SUFBREEsaUJBQUNBO0FBQURBLENBNUJBLElBNEJDO0FBcEJZLGtCQUFVLGFBb0J0QixDQUFBIiwiZmlsZSI6InNyYy9jb21wb25lbnRzL25hdmlnYXRpb24uanMiLCJzb3VyY2VzQ29udGVudCI6WyJpbXBvcnQge0NvbXBvbmVudCwgVmlldywgTmdJZiwgTmdGb3IsIEV2ZW50RW1pdHRlcn0gZnJvbSAnYW5ndWxhcjIvYW5ndWxhcjInO1xuaW1wb3J0IHtSb3V0ZXJMaW5rfSBmcm9tICdhbmd1bGFyMi9yb3V0ZXInO1xuaW1wb3J0IHtGYWN0b3J5LCBMb2dnZWRJbn0gZnJvbSAnc3JjL3NlcnZpY2VzL2V2ZW50cyc7XG5cbkBDb21wb25lbnQoe1xuICBzZWxlY3RvcjogJ21pbmRzLW5hdmlnYXRpb24nXG59KVxuQFZpZXcoe1xuICB0ZW1wbGF0ZVVybDogJ3RlbXBsYXRlcy9jb21wb25lbnRzL25hdmlnYXRpb24uaHRtbCcsXG4gIGRpcmVjdGl2ZXM6IFtSb3V0ZXJMaW5rLCBOZ0lmLCBOZ0Zvcl1cbn0pXG5cbmV4cG9ydCBjbGFzcyBOYXZpZ2F0aW9uIHsgXG5cdHVzZXI7XG5cblx0Y29uc3RydWN0b3IoKXtcblx0XHRzZWxmID0gdGhpcztcblx0XHQvL0ZhY3RvcnkuYnVpbGQoTG9nZ2VkSW4pLmxpc3RlbigoKT0+e1xuXHRcdC8vXHRjb25zb2xlLmxvZygncmVjZWlldmVkIHNlc3Npb24gZXZlbnQnKTtcblx0XHQvL1x0dGhpcy5nZXRVc2VyKCk7XG5cdFx0Ly99KVxuXHRcdHRoaXMuZ2V0VXNlcigpO1xuXHR9XG5cdFxuXHRnZXRVc2VyKCl7XG5cblx0XHQvL0ZhY3RvcnkuYnVpbGQoTG9nZ2VkSW4pLmVtaXQoXCJva1wiKTtcblx0XHRcblx0XHRpZih3aW5kb3cuTWluZHMudXNlcil7XG5cdFx0XHR0aGlzLnVzZXIgPSB3aW5kb3cuTWluZHMudXNlcjtcblx0XHR9XG5cdH1cbn0iXSwic291cmNlUm9vdCI6Ii9zb3VyY2UvIn0=