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
            templateUrl: 'templates/cards/activity.html',
            directives: [angular2_1.NgFor, angular2_1.NgIf, angular2_1.CSSClass, material_1.Material, router_1.RouterLink]
        }), 
        __metadata('design:paramtypes', [api_1.Client])
    ], Remind);
    return Remind;
})();
exports.Remind = Remind;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9jb250cm9sbGVycy9uZXdzZmVlZC9yZW1pbmQudHMiXSwibmFtZXMiOlsiUmVtaW5kIiwiUmVtaW5kLmNvbnN0cnVjdG9yIiwiUmVtaW5kLm9iamVjdCIsIlJlbWluZC50b0RhdGUiXSwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7O0FBQUEseUJBQWtGLG1CQUFtQixDQUFDLENBQUE7QUFDdEcsdUJBQTJCLGlCQUFpQixDQUFDLENBQUE7QUFDN0Msb0JBQXVCLGtCQUFrQixDQUFDLENBQUE7QUFDMUMseUJBQXlCLHlCQUF5QixDQUFDLENBQUE7QUFHbkQ7SUFjQ0EsZ0JBQW1CQSxNQUFjQTtRQUFkQyxXQUFNQSxHQUFOQSxNQUFNQSxDQUFRQTtRQUM5QkEsSUFBSUEsQ0FBQ0EsUUFBUUEsR0FBR0EsSUFBSUEsQ0FBQ0E7SUFDeEJBLENBQUNBO0lBRUFELHNCQUFJQSwwQkFBTUE7YUFBVkEsVUFBV0EsS0FBVUE7WUFDbkJFLElBQUlBLENBQUNBLFFBQVFBLEdBQUdBLEtBQUtBLENBQUNBO1FBQ3hCQSxDQUFDQTs7O09BQUFGO0lBRURBLHVCQUFNQSxHQUFOQSxVQUFPQSxTQUFTQTtRQUNkRyxNQUFNQSxDQUFDQSxJQUFJQSxJQUFJQSxDQUFDQSxTQUFTQSxHQUFDQSxJQUFJQSxDQUFDQSxDQUFDQTtJQUNsQ0EsQ0FBQ0E7SUF4QkhIO1FBQUNBLG9CQUFTQSxDQUFDQTtZQUNUQSxRQUFRQSxFQUFFQSxjQUFjQTtZQUN4QkEsWUFBWUEsRUFBRUEsQ0FBRUEsWUFBTUEsQ0FBRUE7WUFDeEJBLFVBQVVBLEVBQUVBLENBQUNBLFFBQVFBLENBQUNBO1NBQ3ZCQSxDQUFDQTtRQUNEQSxlQUFJQSxDQUFDQTtZQUNKQSxXQUFXQSxFQUFFQSwrQkFBK0JBO1lBQzVDQSxVQUFVQSxFQUFFQSxDQUFFQSxnQkFBS0EsRUFBRUEsZUFBSUEsRUFBRUEsbUJBQVFBLEVBQUVBLG1CQUFRQSxFQUFFQSxtQkFBVUEsQ0FBQ0E7U0FDM0RBLENBQUNBOztlQWlCREE7SUFBREEsYUFBQ0E7QUFBREEsQ0F6QkEsQUF5QkNBLElBQUE7QUFmWSxjQUFNLFNBZWxCLENBQUEiLCJmaWxlIjoic3JjL2NvbnRyb2xsZXJzL25ld3NmZWVkL3JlbWluZC5qcyIsInNvdXJjZXNDb250ZW50IjpbImltcG9ydCB7IENvbXBvbmVudCwgVmlldywgTmdGb3IsIE5nSWYsIENTU0NsYXNzLCBPYnNlcnZhYmxlLCBmb3JtRGlyZWN0aXZlc30gZnJvbSAnYW5ndWxhcjIvYW5ndWxhcjInO1xuaW1wb3J0IHsgUm91dGVyTGluayB9IGZyb20gXCJhbmd1bGFyMi9yb3V0ZXJcIjtcbmltcG9ydCB7IENsaWVudCB9IGZyb20gJ3NyYy9zZXJ2aWNlcy9hcGknO1xuaW1wb3J0IHsgTWF0ZXJpYWwgfSBmcm9tICdzcmMvZGlyZWN0aXZlcy9tYXRlcmlhbCc7XG5pbXBvcnQgeyBBY3Rpdml0eSB9IGZyb20gJy4vYWN0aXZpdHknO1xuXG5AQ29tcG9uZW50KHtcbiAgc2VsZWN0b3I6ICdtaW5kcy1yZW1pbmQnLFxuICB2aWV3SW5qZWN0b3I6IFsgQ2xpZW50IF0sXG4gIHByb3BlcnRpZXM6IFsnb2JqZWN0J11cbn0pXG5AVmlldyh7XG4gIHRlbXBsYXRlVXJsOiAndGVtcGxhdGVzL2NhcmRzL2FjdGl2aXR5Lmh0bWwnLFxuICBkaXJlY3RpdmVzOiBbIE5nRm9yLCBOZ0lmLCBDU1NDbGFzcywgTWF0ZXJpYWwsIFJvdXRlckxpbmtdXG59KVxuXG5leHBvcnQgY2xhc3MgUmVtaW5kIHtcbiAgYWN0aXZpdHkgOiBhbnk7XG4gIGhpZGVUYWJzIDogYm9vbGVhbjtcblxuXHRjb25zdHJ1Y3RvcihwdWJsaWMgY2xpZW50OiBDbGllbnQpe1xuICAgIHRoaXMuaGlkZVRhYnMgPSB0cnVlO1xuXHR9XG5cbiAgc2V0IG9iamVjdCh2YWx1ZTogYW55KSB7XG4gICAgdGhpcy5hY3Rpdml0eSA9IHZhbHVlO1xuICB9XG5cbiAgdG9EYXRlKHRpbWVzdGFtcCl7XG4gICAgcmV0dXJuIG5ldyBEYXRlKHRpbWVzdGFtcCoxMDAwKTtcbiAgfVxufVxuIl0sInNvdXJjZVJvb3QiOiIvc291cmNlLyJ9