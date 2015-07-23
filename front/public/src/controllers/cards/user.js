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
var session_1 = require('src/services/session');
var material_1 = require('src/directives/material');
var UserCard = (function () {
    function UserCard(client) {
        this.client = client;
        this.session = session_1.SessionFactory.build();
        this.minds = window.Minds;
    }
    Object.defineProperty(UserCard.prototype, "object", {
        set: function (value) {
            this.user = value;
        },
        enumerable: true,
        configurable: true
    });
    UserCard = __decorate([
        angular2_1.Component({
            selector: 'minds-card-user',
            viewInjector: [api_1.Client],
            properties: ['object']
        }),
        angular2_1.View({
            templateUrl: 'templates/cards/user.html',
            directives: [angular2_1.NgFor, angular2_1.NgIf, angular2_1.CSSClass, material_1.Material, router_1.RouterLink]
        }), 
        __metadata('design:paramtypes', [api_1.Client])
    ], UserCard);
    return UserCard;
})();
exports.UserCard = UserCard;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9jb250cm9sbGVycy9jYXJkcy91c2VyLnRzIl0sIm5hbWVzIjpbIlVzZXJDYXJkIiwiVXNlckNhcmQuY29uc3RydWN0b3IiLCJVc2VyQ2FyZC5vYmplY3QiXSwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7O0FBQUEseUJBQWtGLG1CQUFtQixDQUFDLENBQUE7QUFDdEcsdUJBQTJCLGlCQUFpQixDQUFDLENBQUE7QUFDN0Msb0JBQXVCLGtCQUFrQixDQUFDLENBQUE7QUFDMUMsd0JBQStCLHNCQUFzQixDQUFDLENBQUE7QUFDdEQseUJBQXlCLHlCQUF5QixDQUFDLENBQUE7QUFFbkQ7SUFlQ0Esa0JBQW1CQSxNQUFjQTtRQUFkQyxXQUFNQSxHQUFOQSxNQUFNQSxDQUFRQTtRQUhoQ0EsWUFBT0EsR0FBR0Esd0JBQWNBLENBQUNBLEtBQUtBLEVBQUVBLENBQUNBO1FBSS9CQSxJQUFJQSxDQUFDQSxLQUFLQSxHQUFHQSxNQUFNQSxDQUFDQSxLQUFLQSxDQUFDQTtJQUM3QkEsQ0FBQ0E7SUFFQUQsc0JBQUlBLDRCQUFNQTthQUFWQSxVQUFXQSxLQUFVQTtZQUNuQkUsSUFBSUEsQ0FBQ0EsSUFBSUEsR0FBR0EsS0FBS0EsQ0FBQ0E7UUFDcEJBLENBQUNBOzs7T0FBQUY7SUFyQkhBO1FBQUNBLG9CQUFTQSxDQUFDQTtZQUNUQSxRQUFRQSxFQUFFQSxpQkFBaUJBO1lBQzNCQSxZQUFZQSxFQUFFQSxDQUFFQSxZQUFNQSxDQUFFQTtZQUN4QkEsVUFBVUEsRUFBRUEsQ0FBQ0EsUUFBUUEsQ0FBQ0E7U0FDdkJBLENBQUNBO1FBQ0RBLGVBQUlBLENBQUNBO1lBQ0pBLFdBQVdBLEVBQUVBLDJCQUEyQkE7WUFDeENBLFVBQVVBLEVBQUVBLENBQUVBLGdCQUFLQSxFQUFFQSxlQUFJQSxFQUFFQSxtQkFBUUEsRUFBRUEsbUJBQVFBLEVBQUVBLG1CQUFVQSxDQUFDQTtTQUMzREEsQ0FBQ0E7O2lCQWVEQTtJQUFEQSxlQUFDQTtBQUFEQSxDQXZCQSxBQXVCQ0EsSUFBQTtBQWJZLGdCQUFRLFdBYXBCLENBQUEiLCJmaWxlIjoic3JjL2NvbnRyb2xsZXJzL2NhcmRzL3VzZXIuanMiLCJzb3VyY2VzQ29udGVudCI6WyJpbXBvcnQgeyBDb21wb25lbnQsIFZpZXcsIE5nRm9yLCBOZ0lmLCBDU1NDbGFzcywgT2JzZXJ2YWJsZSwgZm9ybURpcmVjdGl2ZXN9IGZyb20gJ2FuZ3VsYXIyL2FuZ3VsYXIyJztcbmltcG9ydCB7IFJvdXRlckxpbmsgfSBmcm9tIFwiYW5ndWxhcjIvcm91dGVyXCI7XG5pbXBvcnQgeyBDbGllbnQgfSBmcm9tICdzcmMvc2VydmljZXMvYXBpJztcbmltcG9ydCB7IFNlc3Npb25GYWN0b3J5IH0gZnJvbSAnc3JjL3NlcnZpY2VzL3Nlc3Npb24nO1xuaW1wb3J0IHsgTWF0ZXJpYWwgfSBmcm9tICdzcmMvZGlyZWN0aXZlcy9tYXRlcmlhbCc7XG5cbkBDb21wb25lbnQoe1xuICBzZWxlY3RvcjogJ21pbmRzLWNhcmQtdXNlcicsXG4gIHZpZXdJbmplY3RvcjogWyBDbGllbnQgXSxcbiAgcHJvcGVydGllczogWydvYmplY3QnXVxufSlcbkBWaWV3KHtcbiAgdGVtcGxhdGVVcmw6ICd0ZW1wbGF0ZXMvY2FyZHMvdXNlci5odG1sJyxcbiAgZGlyZWN0aXZlczogWyBOZ0ZvciwgTmdJZiwgQ1NTQ2xhc3MsIE1hdGVyaWFsLCBSb3V0ZXJMaW5rXVxufSlcblxuZXhwb3J0IGNsYXNzIFVzZXJDYXJkIHtcbiAgdXNlciA6IGFueTtcbiAgc2Vzc2lvbiA9IFNlc3Npb25GYWN0b3J5LmJ1aWxkKCk7XG4gIG1pbmRzOiB7fTtcblxuXHRjb25zdHJ1Y3RvcihwdWJsaWMgY2xpZW50OiBDbGllbnQpe1xuICAgIHRoaXMubWluZHMgPSB3aW5kb3cuTWluZHM7XG5cdH1cblxuICBzZXQgb2JqZWN0KHZhbHVlOiBhbnkpIHtcbiAgICB0aGlzLnVzZXIgPSB2YWx1ZTtcbiAgfVxuXG59XG4iXSwic291cmNlUm9vdCI6Ii9zb3VyY2UvIn0=