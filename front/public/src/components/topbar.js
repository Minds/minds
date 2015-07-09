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
var Topbar = (function () {
    function Topbar(storage) {
        this.storage = storage;
    }
    Topbar.prototype.showLogin = function () {
        return !window.LoggedIn;
    };
    Topbar = __decorate([
        angular2_1.Component({
            selector: 'minds-topbar',
            viewInjector: [storage_1.Storage]
        }),
        angular2_1.View({
            templateUrl: 'templates/components/topbar.html',
            directives: [angular2_1.NgIf, router_1.RouterLink]
        }), 
        __metadata('design:paramtypes', [storage_1.Storage])
    ], Topbar);
    return Topbar;
})();
exports.Topbar = Topbar;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9jb21wb25lbnRzL3RvcGJhci50cyJdLCJuYW1lcyI6WyJUb3BiYXIiLCJUb3BiYXIuY29uc3RydWN0b3IiLCJUb3BiYXIuc2hvd0xvZ2luIl0sIm1hcHBpbmdzIjoiOzs7Ozs7Ozs7OztBQUFBLHlCQUFzQyxtQkFBbUIsQ0FBQyxDQUFBO0FBQzFELHVCQUEyQixpQkFBaUIsQ0FBQyxDQUFBO0FBQzdDLHdCQUF3QixzQkFBc0IsQ0FBQyxDQUFBO0FBRS9DO0lBVUNBLGdCQUFtQkEsT0FBZ0JBO1FBQWhCQyxZQUFPQSxHQUFQQSxPQUFPQSxDQUFTQTtJQUFHQSxDQUFDQTtJQUt2Q0QsMEJBQVNBLEdBQVRBO1FBQ0NFLE1BQU1BLENBQUNBLENBQUNBLE1BQU1BLENBQUNBLFFBQVFBLENBQUNBO0lBQ3pCQSxDQUFDQTtJQWpCRkY7UUFBQ0Esb0JBQVNBLENBQUNBO1lBQ1RBLFFBQVFBLEVBQUVBLGNBQWNBO1lBQ3hCQSxZQUFZQSxFQUFFQSxDQUFDQSxpQkFBT0EsQ0FBQ0E7U0FDeEJBLENBQUNBO1FBQ0RBLGVBQUlBLENBQUNBO1lBQ0pBLFdBQVdBLEVBQUVBLGtDQUFrQ0E7WUFDL0NBLFVBQVVBLEVBQUVBLENBQUNBLGVBQUlBLEVBQUVBLG1CQUFVQSxDQUFDQTtTQUMvQkEsQ0FBQ0E7O2VBV0RBO0lBQURBLGFBQUNBO0FBQURBLENBbEJBLElBa0JDO0FBVFksY0FBTSxTQVNsQixDQUFBIiwiZmlsZSI6InNyYy9jb21wb25lbnRzL3RvcGJhci5qcyIsInNvdXJjZXNDb250ZW50IjpbImltcG9ydCB7IENvbXBvbmVudCwgVmlldywgTmdJZiB9IGZyb20gJ2FuZ3VsYXIyL2FuZ3VsYXIyJztcbmltcG9ydCB7IFJvdXRlckxpbmsgfSBmcm9tICdhbmd1bGFyMi9yb3V0ZXInO1xuaW1wb3J0IHsgU3RvcmFnZSB9IGZyb20gJ3NyYy9zZXJ2aWNlcy9zdG9yYWdlJztcblxuQENvbXBvbmVudCh7XG4gIHNlbGVjdG9yOiAnbWluZHMtdG9wYmFyJyxcbiAgdmlld0luamVjdG9yOiBbU3RvcmFnZV1cbn0pXG5AVmlldyh7XG4gIHRlbXBsYXRlVXJsOiAndGVtcGxhdGVzL2NvbXBvbmVudHMvdG9wYmFyLmh0bWwnLFxuICBkaXJlY3RpdmVzOiBbTmdJZiwgUm91dGVyTGlua11cbn0pXG5cbmV4cG9ydCBjbGFzcyBUb3BiYXIgeyBcblx0Y29uc3RydWN0b3IocHVibGljIHN0b3JhZ2U6IFN0b3JhZ2UpeyB9XG5cdFxuXHQvKipcblx0ICogRGV0ZXJtaW5lIGlmIGxvZ2luIGJ1dHRvbiBzaG91bGQgYmUgc2hvd25cblx0ICovXG5cdHNob3dMb2dpbigpe1xuXHRcdHJldHVybiAhd2luZG93LkxvZ2dlZEluO1xuXHR9XG59Il0sInNvdXJjZVJvb3QiOiIvc291cmNlLyJ9