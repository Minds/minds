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
var ui_1 = require('src/services/ui');
var Topbar = (function () {
    function Topbar(storage, sidebar) {
        this.storage = storage;
        this.sidebar = sidebar;
    }
    Topbar.prototype.showLogin = function () {
        window.componentHandler.upgradeDom();
        return !window.LoggedIn;
    };
    Topbar.prototype.openNav = function () {
        console.log('opening nav');
        document.getElementsByClassName('mdl-layout__drawer')[0].style['transform'] = "translateX(0)";
        console.log(document.getElementsByClassName('mdl-layout__drawer'));
    };
    Topbar = __decorate([
        angular2_1.Component({
            selector: 'minds-topbar',
            viewInjector: [storage_1.Storage, ui_1.Sidebar]
        }),
        angular2_1.View({
            templateUrl: 'templates/components/topbar.html',
            directives: [angular2_1.NgIf, router_1.RouterLink]
        }), 
        __metadata('design:paramtypes', [storage_1.Storage, ui_1.Sidebar])
    ], Topbar);
    return Topbar;
})();
exports.Topbar = Topbar;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9jb21wb25lbnRzL3RvcGJhci50cyJdLCJuYW1lcyI6WyJUb3BiYXIiLCJUb3BiYXIuY29uc3RydWN0b3IiLCJUb3BiYXIuc2hvd0xvZ2luIiwiVG9wYmFyLm9wZW5OYXYiXSwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7O0FBQUEseUJBQXNDLG1CQUFtQixDQUFDLENBQUE7QUFDMUQsdUJBQTJCLGlCQUFpQixDQUFDLENBQUE7QUFDN0Msd0JBQXdCLHNCQUFzQixDQUFDLENBQUE7QUFDL0MsbUJBQXNCLGlCQUFpQixDQUFDLENBQUE7QUFFeEM7SUFVQ0EsZ0JBQW1CQSxPQUFnQkEsRUFBU0EsT0FBaUJBO1FBQTFDQyxZQUFPQSxHQUFQQSxPQUFPQSxDQUFTQTtRQUFTQSxZQUFPQSxHQUFQQSxPQUFPQSxDQUFVQTtJQUFHQSxDQUFDQTtJQUtqRUQsMEJBQVNBLEdBQVRBO1FBQ0NFLE1BQU1BLENBQUNBLGdCQUFnQkEsQ0FBQ0EsVUFBVUEsRUFBRUEsQ0FBQ0E7UUFDckNBLE1BQU1BLENBQUNBLENBQUNBLE1BQU1BLENBQUNBLFFBQVFBLENBQUNBO0lBQ3pCQSxDQUFDQTtJQUtERix3QkFBT0EsR0FBUEE7UUFDQ0csT0FBT0EsQ0FBQ0EsR0FBR0EsQ0FBQ0EsYUFBYUEsQ0FBQ0EsQ0FBQ0E7UUFDM0JBLFFBQVFBLENBQUNBLHNCQUFzQkEsQ0FBQ0Esb0JBQW9CQSxDQUFDQSxDQUFDQSxDQUFDQSxDQUFDQSxDQUFDQSxLQUFLQSxDQUFDQSxXQUFXQSxDQUFDQSxHQUFHQSxlQUFlQSxDQUFDQTtRQUM5RkEsT0FBT0EsQ0FBQ0EsR0FBR0EsQ0FBQ0EsUUFBUUEsQ0FBQ0Esc0JBQXNCQSxDQUFDQSxvQkFBb0JBLENBQUNBLENBQUNBLENBQUNBO0lBQ3BFQSxDQUFDQTtJQTNCRkg7UUFBQ0Esb0JBQVNBLENBQUNBO1lBQ1RBLFFBQVFBLEVBQUVBLGNBQWNBO1lBQ3hCQSxZQUFZQSxFQUFFQSxDQUFDQSxpQkFBT0EsRUFBRUEsWUFBT0EsQ0FBQ0E7U0FDakNBLENBQUNBO1FBQ0RBLGVBQUlBLENBQUNBO1lBQ0pBLFdBQVdBLEVBQUVBLGtDQUFrQ0E7WUFDL0NBLFVBQVVBLEVBQUVBLENBQUNBLGVBQUlBLEVBQUVBLG1CQUFVQSxDQUFDQTtTQUMvQkEsQ0FBQ0E7O2VBcUJEQTtJQUFEQSxhQUFDQTtBQUFEQSxDQTVCQSxJQTRCQztBQW5CWSxjQUFNLFNBbUJsQixDQUFBIiwiZmlsZSI6InNyYy9jb21wb25lbnRzL3RvcGJhci5qcyIsInNvdXJjZXNDb250ZW50IjpbImltcG9ydCB7IENvbXBvbmVudCwgVmlldywgTmdJZiB9IGZyb20gJ2FuZ3VsYXIyL2FuZ3VsYXIyJztcbmltcG9ydCB7IFJvdXRlckxpbmsgfSBmcm9tICdhbmd1bGFyMi9yb3V0ZXInO1xuaW1wb3J0IHsgU3RvcmFnZSB9IGZyb20gJ3NyYy9zZXJ2aWNlcy9zdG9yYWdlJztcbmltcG9ydCB7U2lkZWJhcn0gZnJvbSAnc3JjL3NlcnZpY2VzL3VpJztcblxuQENvbXBvbmVudCh7XG4gIHNlbGVjdG9yOiAnbWluZHMtdG9wYmFyJyxcbiAgdmlld0luamVjdG9yOiBbU3RvcmFnZSwgU2lkZWJhcl1cbn0pXG5AVmlldyh7XG4gIHRlbXBsYXRlVXJsOiAndGVtcGxhdGVzL2NvbXBvbmVudHMvdG9wYmFyLmh0bWwnLFxuICBkaXJlY3RpdmVzOiBbTmdJZiwgUm91dGVyTGlua11cbn0pXG5cbmV4cG9ydCBjbGFzcyBUb3BiYXIgeyBcblx0Y29uc3RydWN0b3IocHVibGljIHN0b3JhZ2U6IFN0b3JhZ2UsIHB1YmxpYyBzaWRlYmFyIDogU2lkZWJhcil7IH1cblx0XG5cdC8qKlxuXHQgKiBEZXRlcm1pbmUgaWYgbG9naW4gYnV0dG9uIHNob3VsZCBiZSBzaG93blxuXHQgKi9cblx0c2hvd0xvZ2luKCl7XG5cdFx0d2luZG93LmNvbXBvbmVudEhhbmRsZXIudXBncmFkZURvbSgpO1xuXHRcdHJldHVybiAhd2luZG93LkxvZ2dlZEluO1xuXHR9XG5cdFxuXHQvKipcblx0ICogT3BlbiB0aGUgbmF2aWdhdGlvblxuXHQgKi9cblx0b3Blbk5hdigpe1xuXHRcdGNvbnNvbGUubG9nKCdvcGVuaW5nIG5hdicpO1xuXHRcdGRvY3VtZW50LmdldEVsZW1lbnRzQnlDbGFzc05hbWUoJ21kbC1sYXlvdXRfX2RyYXdlcicpWzBdLnN0eWxlWyd0cmFuc2Zvcm0nXSA9IFwidHJhbnNsYXRlWCgwKVwiO1xuXHRcdGNvbnNvbGUubG9nKGRvY3VtZW50LmdldEVsZW1lbnRzQnlDbGFzc05hbWUoJ21kbC1sYXlvdXRfX2RyYXdlcicpKTtcblx0fVxufSJdLCJzb3VyY2VSb290IjoiL3NvdXJjZS8ifQ==