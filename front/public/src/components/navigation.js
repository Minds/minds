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
        this.getUser();
    }
    Navigation.prototype.getUser = function () {
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

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9jb21wb25lbnRzL25hdmlnYXRpb24udHMiXSwibmFtZXMiOlsiTmF2aWdhdGlvbiIsIk5hdmlnYXRpb24uY29uc3RydWN0b3IiLCJOYXZpZ2F0aW9uLmdldFVzZXIiXSwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7O0FBQUEseUJBQTJDLG1CQUFtQixDQUFDLENBQUE7QUFDL0QsdUJBQXlCLGlCQUFpQixDQUFDLENBQUE7QUFFM0M7SUFVQ0E7UUFDR0MsSUFBSUEsQ0FBQ0EsT0FBT0EsRUFBRUEsQ0FBQ0E7SUFDbEJBLENBQUNBO0lBRURELDRCQUFPQSxHQUFQQTtRQUNDRSxFQUFFQSxDQUFBQSxDQUFDQSxNQUFNQSxDQUFDQSxLQUFLQSxDQUFDQSxJQUFJQSxDQUFDQSxDQUFBQSxDQUFDQTtZQUNyQkEsSUFBSUEsQ0FBQ0EsSUFBSUEsR0FBR0EsTUFBTUEsQ0FBQ0EsS0FBS0EsQ0FBQ0EsSUFBSUEsQ0FBQ0E7UUFDL0JBLENBQUNBO0lBQ0ZBLENBQUNBO0lBbEJGRjtRQUFDQSxvQkFBU0EsQ0FBQ0E7WUFDVEEsUUFBUUEsRUFBRUEsa0JBQWtCQTtTQUM3QkEsQ0FBQ0E7UUFDREEsZUFBSUEsQ0FBQ0E7WUFDSkEsV0FBV0EsRUFBRUEsc0NBQXNDQTtZQUNuREEsVUFBVUEsRUFBRUEsQ0FBQ0EsbUJBQVVBLEVBQUVBLGVBQUlBLEVBQUVBLGdCQUFLQSxDQUFDQTtTQUN0Q0EsQ0FBQ0E7O21CQWFEQTtJQUFEQSxpQkFBQ0E7QUFBREEsQ0FuQkEsSUFtQkM7QUFYWSxrQkFBVSxhQVd0QixDQUFBIiwiZmlsZSI6InNyYy9jb21wb25lbnRzL25hdmlnYXRpb24uanMiLCJzb3VyY2VzQ29udGVudCI6WyJpbXBvcnQge0NvbXBvbmVudCwgVmlldywgTmdJZiwgTmdGb3J9IGZyb20gJ2FuZ3VsYXIyL2FuZ3VsYXIyJztcbmltcG9ydCB7Um91dGVyTGlua30gZnJvbSAnYW5ndWxhcjIvcm91dGVyJztcblxuQENvbXBvbmVudCh7XG4gIHNlbGVjdG9yOiAnbWluZHMtbmF2aWdhdGlvbidcbn0pXG5AVmlldyh7XG4gIHRlbXBsYXRlVXJsOiAndGVtcGxhdGVzL2NvbXBvbmVudHMvbmF2aWdhdGlvbi5odG1sJyxcbiAgZGlyZWN0aXZlczogW1JvdXRlckxpbmssIE5nSWYsIE5nRm9yXVxufSlcblxuZXhwb3J0IGNsYXNzIE5hdmlnYXRpb24geyBcblx0dXNlcjtcblx0Y29uc3RydWN0b3IoKXsgXG5cdFx0ICB0aGlzLmdldFVzZXIoKTtcblx0fVxuXHRcblx0Z2V0VXNlcigpe1xuXHRcdGlmKHdpbmRvdy5NaW5kcy51c2VyKXtcblx0XHRcdHRoaXMudXNlciA9IHdpbmRvdy5NaW5kcy51c2VyO1xuXHRcdH1cblx0fVxufSJdLCJzb3VyY2VSb290IjoiL3NvdXJjZS8ifQ==