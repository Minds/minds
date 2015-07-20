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
var router_1 = require("angular2/router");
var api_1 = require('src/services/api');
var material_1 = require('src/directives/material');
var Remind = (function () {
    function Remind(client) {
        this.client = client;
        this.hideTabs = true;
    }
    Object.defineProperty(Remind.prototype, "object", {
        set: function (value) {
            this.activity = value;
        },
        enumerable: true,
        configurable: true
    });
    Remind.prototype.toDate = function (timestamp) {
        return new Date(timestamp * 1000);
    };
    Remind = __decorate([
        angular2_1.Component({
            selector: 'minds-remind',
            viewInjector: [api_1.Client],
            properties: ['object']
        }),
        angular2_1.View({
            templateUrl: 'templates/entities/activity.html',
            directives: [angular2_1.NgFor, angular2_1.NgIf, material_1.Material, router_1.RouterLink]
        }), 
        __metadata('design:paramtypes', [api_1.Client])
    ], Remind);
    return Remind;
})();
exports.Remind = Remind;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9jb250cm9sbGVycy9uZXdzZmVlZC9yZW1pbmQudHMiXSwibmFtZXMiOlsiUmVtaW5kIiwiUmVtaW5kLmNvbnN0cnVjdG9yIiwiUmVtaW5kLm9iamVjdCIsIlJlbWluZC50b0RhdGUiXSwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7O0FBQUEseUJBQXdFLG1CQUFtQixDQUFDLENBQUE7QUFDNUYsdUJBQTJCLGlCQUFpQixDQUFDLENBQUE7QUFDN0Msb0JBQXVCLGtCQUFrQixDQUFDLENBQUE7QUFDMUMseUJBQXlCLHlCQUF5QixDQUFDLENBQUE7QUFHbkQ7SUFjQ0EsZ0JBQW1CQSxNQUFjQTtRQUFkQyxXQUFNQSxHQUFOQSxNQUFNQSxDQUFRQTtRQUM5QkEsSUFBSUEsQ0FBQ0EsUUFBUUEsR0FBR0EsSUFBSUEsQ0FBQ0E7SUFDeEJBLENBQUNBO0lBRUFELHNCQUFJQSwwQkFBTUE7YUFBVkEsVUFBV0EsS0FBVUE7WUFDbkJFLElBQUlBLENBQUNBLFFBQVFBLEdBQUdBLEtBQUtBLENBQUNBO1FBQ3hCQSxDQUFDQTs7O09BQUFGO0lBRURBLHVCQUFNQSxHQUFOQSxVQUFPQSxTQUFTQTtRQUNkRyxNQUFNQSxDQUFDQSxJQUFJQSxJQUFJQSxDQUFDQSxTQUFTQSxHQUFDQSxJQUFJQSxDQUFDQSxDQUFDQTtJQUNsQ0EsQ0FBQ0E7SUF4QkhIO1FBQUNBLG9CQUFTQSxDQUFDQTtZQUNUQSxRQUFRQSxFQUFFQSxjQUFjQTtZQUN4QkEsWUFBWUEsRUFBRUEsQ0FBRUEsWUFBTUEsQ0FBRUE7WUFDeEJBLFVBQVVBLEVBQUVBLENBQUNBLFFBQVFBLENBQUNBO1NBQ3ZCQSxDQUFDQTtRQUNEQSxlQUFJQSxDQUFDQTtZQUNKQSxXQUFXQSxFQUFFQSxrQ0FBa0NBO1lBQy9DQSxVQUFVQSxFQUFFQSxDQUFFQSxnQkFBS0EsRUFBRUEsZUFBSUEsRUFBRUEsbUJBQVFBLEVBQUVBLG1CQUFVQSxDQUFDQTtTQUNqREEsQ0FBQ0E7O2VBaUJEQTtJQUFEQSxhQUFDQTtBQUFEQSxDQXpCQSxBQXlCQ0EsSUFBQTtBQWZZLGNBQU0sU0FlbEIsQ0FBQSIsImZpbGUiOiJzcmMvY29udHJvbGxlcnMvbmV3c2ZlZWQvcmVtaW5kLmpzIiwic291cmNlc0NvbnRlbnQiOlsiaW1wb3J0IHsgQ29tcG9uZW50LCBWaWV3LCBOZ0ZvciwgTmdJZiwgT2JzZXJ2YWJsZSwgZm9ybURpcmVjdGl2ZXN9IGZyb20gJ2FuZ3VsYXIyL2FuZ3VsYXIyJztcbmltcG9ydCB7IFJvdXRlckxpbmsgfSBmcm9tIFwiYW5ndWxhcjIvcm91dGVyXCI7XG5pbXBvcnQgeyBDbGllbnQgfSBmcm9tICdzcmMvc2VydmljZXMvYXBpJztcbmltcG9ydCB7IE1hdGVyaWFsIH0gZnJvbSAnc3JjL2RpcmVjdGl2ZXMvbWF0ZXJpYWwnO1xuaW1wb3J0IHsgQWN0aXZpdHkgfSBmcm9tICcuL2FjdGl2aXR5JztcblxuQENvbXBvbmVudCh7XG4gIHNlbGVjdG9yOiAnbWluZHMtcmVtaW5kJyxcbiAgdmlld0luamVjdG9yOiBbIENsaWVudCBdLFxuICBwcm9wZXJ0aWVzOiBbJ29iamVjdCddXG59KVxuQFZpZXcoe1xuICB0ZW1wbGF0ZVVybDogJ3RlbXBsYXRlcy9lbnRpdGllcy9hY3Rpdml0eS5odG1sJyxcbiAgZGlyZWN0aXZlczogWyBOZ0ZvciwgTmdJZiwgTWF0ZXJpYWwsIFJvdXRlckxpbmtdXG59KVxuXG5leHBvcnQgY2xhc3MgUmVtaW5kIHtcbiAgYWN0aXZpdHkgOiBhbnk7XG4gIGhpZGVUYWJzIDogYm9vbGVhbjtcblxuXHRjb25zdHJ1Y3RvcihwdWJsaWMgY2xpZW50OiBDbGllbnQpe1xuICAgIHRoaXMuaGlkZVRhYnMgPSB0cnVlO1xuXHR9XG5cbiAgc2V0IG9iamVjdCh2YWx1ZTogYW55KSB7XG4gICAgdGhpcy5hY3Rpdml0eSA9IHZhbHVlO1xuICB9XG5cbiAgdG9EYXRlKHRpbWVzdGFtcCl7XG4gICAgcmV0dXJuIG5ldyBEYXRlKHRpbWVzdGFtcCoxMDAwKTtcbiAgfVxufVxuIl0sInNvdXJjZVJvb3QiOiIvc291cmNlLyJ9