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

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9jb21wb25lbnRzL3RvcGJhci50cyJdLCJuYW1lcyI6WyJUb3BiYXIiLCJUb3BiYXIuY29uc3RydWN0b3IiLCJUb3BiYXIuc2hvd0xvZ2luIiwiVG9wYmFyLm9wZW5OYXYiXSwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7O0FBQUEseUJBQXNDLG1CQUFtQixDQUFDLENBQUE7QUFDMUQsdUJBQTJCLGlCQUFpQixDQUFDLENBQUE7QUFDN0Msd0JBQXdCLHNCQUFzQixDQUFDLENBQUE7QUFFL0M7SUFVQ0EsZ0JBQW1CQSxPQUFnQkE7UUFBaEJDLFlBQU9BLEdBQVBBLE9BQU9BLENBQVNBO0lBQUdBLENBQUNBO0lBS3ZDRCwwQkFBU0EsR0FBVEE7UUFDQ0UsTUFBTUEsQ0FBQ0EsZ0JBQWdCQSxDQUFDQSxVQUFVQSxFQUFFQSxDQUFDQTtRQUNyQ0EsTUFBTUEsQ0FBQ0EsQ0FBQ0EsTUFBTUEsQ0FBQ0EsUUFBUUEsQ0FBQ0E7SUFDekJBLENBQUNBO0lBS0RGLHdCQUFPQSxHQUFQQTtRQUNDRyxPQUFPQSxDQUFDQSxHQUFHQSxDQUFDQSxhQUFhQSxDQUFDQSxDQUFDQTtRQUMzQkEsUUFBUUEsQ0FBQ0Esc0JBQXNCQSxDQUFDQSxvQkFBb0JBLENBQUNBLENBQUNBLENBQUNBLENBQUNBLENBQUNBLEtBQUtBLENBQUNBLFdBQVdBLENBQUNBLEdBQUdBLGVBQWVBLENBQUNBO1FBQzlGQSxPQUFPQSxDQUFDQSxHQUFHQSxDQUFDQSxRQUFRQSxDQUFDQSxzQkFBc0JBLENBQUNBLG9CQUFvQkEsQ0FBQ0EsQ0FBQ0EsQ0FBQ0E7SUFDcEVBLENBQUNBO0lBM0JGSDtRQUFDQSxvQkFBU0EsQ0FBQ0E7WUFDVEEsUUFBUUEsRUFBRUEsY0FBY0E7WUFDeEJBLFlBQVlBLEVBQUVBLENBQUNBLGlCQUFPQSxDQUFDQTtTQUN4QkEsQ0FBQ0E7UUFDREEsZUFBSUEsQ0FBQ0E7WUFDSkEsV0FBV0EsRUFBRUEsa0NBQWtDQTtZQUMvQ0EsVUFBVUEsRUFBRUEsQ0FBQ0EsZUFBSUEsRUFBRUEsbUJBQVVBLENBQUNBO1NBQy9CQSxDQUFDQTs7ZUFxQkRBO0lBQURBLGFBQUNBO0FBQURBLENBNUJBLElBNEJDO0FBbkJZLGNBQU0sU0FtQmxCLENBQUEiLCJmaWxlIjoic3JjL2NvbXBvbmVudHMvdG9wYmFyLmpzIiwic291cmNlc0NvbnRlbnQiOlsiaW1wb3J0IHsgQ29tcG9uZW50LCBWaWV3LCBOZ0lmIH0gZnJvbSAnYW5ndWxhcjIvYW5ndWxhcjInO1xuaW1wb3J0IHsgUm91dGVyTGluayB9IGZyb20gJ2FuZ3VsYXIyL3JvdXRlcic7XG5pbXBvcnQgeyBTdG9yYWdlIH0gZnJvbSAnc3JjL3NlcnZpY2VzL3N0b3JhZ2UnO1xuXG5AQ29tcG9uZW50KHtcbiAgc2VsZWN0b3I6ICdtaW5kcy10b3BiYXInLFxuICB2aWV3SW5qZWN0b3I6IFtTdG9yYWdlXVxufSlcbkBWaWV3KHtcbiAgdGVtcGxhdGVVcmw6ICd0ZW1wbGF0ZXMvY29tcG9uZW50cy90b3BiYXIuaHRtbCcsXG4gIGRpcmVjdGl2ZXM6IFtOZ0lmLCBSb3V0ZXJMaW5rXVxufSlcblxuZXhwb3J0IGNsYXNzIFRvcGJhciB7IFxuXHRjb25zdHJ1Y3RvcihwdWJsaWMgc3RvcmFnZTogU3RvcmFnZSl7IH1cblx0XG5cdC8qKlxuXHQgKiBEZXRlcm1pbmUgaWYgbG9naW4gYnV0dG9uIHNob3VsZCBiZSBzaG93blxuXHQgKi9cblx0c2hvd0xvZ2luKCl7XG5cdFx0d2luZG93LmNvbXBvbmVudEhhbmRsZXIudXBncmFkZURvbSgpO1xuXHRcdHJldHVybiAhd2luZG93LkxvZ2dlZEluO1xuXHR9XG5cdFxuXHQvKipcblx0ICogT3BlbiB0aGUgbmF2aWdhdGlvblxuXHQgKi9cblx0b3Blbk5hdigpe1xuXHRcdGNvbnNvbGUubG9nKCdvcGVuaW5nIG5hdicpO1xuXHRcdGRvY3VtZW50LmdldEVsZW1lbnRzQnlDbGFzc05hbWUoJ21kbC1sYXlvdXRfX2RyYXdlcicpWzBdLnN0eWxlWyd0cmFuc2Zvcm0nXSA9IFwidHJhbnNsYXRlWCgwKVwiO1xuXHRcdGNvbnNvbGUubG9nKGRvY3VtZW50LmdldEVsZW1lbnRzQnlDbGFzc05hbWUoJ21kbC1sYXlvdXRfX2RyYXdlcicpKTtcblx0fVxufSJdLCJzb3VyY2VSb290IjoiL3NvdXJjZS8ifQ==