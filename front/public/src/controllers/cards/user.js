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

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9jb250cm9sbGVycy9jYXJkcy91c2VyLnRzIl0sIm5hbWVzIjpbIlVzZXJDYXJkIiwiVXNlckNhcmQuY29uc3RydWN0b3IiLCJVc2VyQ2FyZC5vYmplY3QiXSwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7O0FBQUEseUJBQWtGLG1CQUFtQixDQUFDLENBQUE7QUFDdEcsdUJBQTJCLGlCQUFpQixDQUFDLENBQUE7QUFDN0Msb0JBQXVCLGtCQUFrQixDQUFDLENBQUE7QUFDMUMsd0JBQStCLHNCQUFzQixDQUFDLENBQUE7QUFDdEQseUJBQXlCLHlCQUF5QixDQUFDLENBQUE7QUFFbkQ7SUFjQ0Esa0JBQW1CQSxNQUFjQTtRQUFkQyxXQUFNQSxHQUFOQSxNQUFNQSxDQUFRQTtRQUZoQ0EsWUFBT0EsR0FBR0Esd0JBQWNBLENBQUNBLEtBQUtBLEVBQUVBLENBQUNBO0lBR2xDQSxDQUFDQTtJQUVBRCxzQkFBSUEsNEJBQU1BO2FBQVZBLFVBQVdBLEtBQVVBO1lBQ25CRSxJQUFJQSxDQUFDQSxJQUFJQSxHQUFHQSxLQUFLQSxDQUFDQTtRQUNwQkEsQ0FBQ0E7OztPQUFBRjtJQW5CSEE7UUFBQ0Esb0JBQVNBLENBQUNBO1lBQ1RBLFFBQVFBLEVBQUVBLGlCQUFpQkE7WUFDM0JBLFlBQVlBLEVBQUVBLENBQUVBLFlBQU1BLENBQUVBO1lBQ3hCQSxVQUFVQSxFQUFFQSxDQUFDQSxRQUFRQSxDQUFDQTtTQUN2QkEsQ0FBQ0E7UUFDREEsZUFBSUEsQ0FBQ0E7WUFDSkEsV0FBV0EsRUFBRUEsMkJBQTJCQTtZQUN4Q0EsVUFBVUEsRUFBRUEsQ0FBRUEsZ0JBQUtBLEVBQUVBLGVBQUlBLEVBQUVBLG1CQUFRQSxFQUFFQSxtQkFBUUEsRUFBRUEsbUJBQVVBLENBQUNBO1NBQzNEQSxDQUFDQTs7aUJBYURBO0lBQURBLGVBQUNBO0FBQURBLENBckJBLEFBcUJDQSxJQUFBO0FBWFksZ0JBQVEsV0FXcEIsQ0FBQSIsImZpbGUiOiJzcmMvY29udHJvbGxlcnMvY2FyZHMvdXNlci5qcyIsInNvdXJjZXNDb250ZW50IjpbImltcG9ydCB7IENvbXBvbmVudCwgVmlldywgTmdGb3IsIE5nSWYsIENTU0NsYXNzLCBPYnNlcnZhYmxlLCBmb3JtRGlyZWN0aXZlc30gZnJvbSAnYW5ndWxhcjIvYW5ndWxhcjInO1xuaW1wb3J0IHsgUm91dGVyTGluayB9IGZyb20gXCJhbmd1bGFyMi9yb3V0ZXJcIjtcbmltcG9ydCB7IENsaWVudCB9IGZyb20gJ3NyYy9zZXJ2aWNlcy9hcGknO1xuaW1wb3J0IHsgU2Vzc2lvbkZhY3RvcnkgfSBmcm9tICdzcmMvc2VydmljZXMvc2Vzc2lvbic7XG5pbXBvcnQgeyBNYXRlcmlhbCB9IGZyb20gJ3NyYy9kaXJlY3RpdmVzL21hdGVyaWFsJztcblxuQENvbXBvbmVudCh7XG4gIHNlbGVjdG9yOiAnbWluZHMtY2FyZC11c2VyJyxcbiAgdmlld0luamVjdG9yOiBbIENsaWVudCBdLFxuICBwcm9wZXJ0aWVzOiBbJ29iamVjdCddXG59KVxuQFZpZXcoe1xuICB0ZW1wbGF0ZVVybDogJ3RlbXBsYXRlcy9jYXJkcy91c2VyLmh0bWwnLFxuICBkaXJlY3RpdmVzOiBbIE5nRm9yLCBOZ0lmLCBDU1NDbGFzcywgTWF0ZXJpYWwsIFJvdXRlckxpbmtdXG59KVxuXG5leHBvcnQgY2xhc3MgVXNlckNhcmQge1xuICB1c2VyIDogYW55O1xuICBzZXNzaW9uID0gU2Vzc2lvbkZhY3RvcnkuYnVpbGQoKTtcblxuXHRjb25zdHJ1Y3RvcihwdWJsaWMgY2xpZW50OiBDbGllbnQpe1xuXHR9XG5cbiAgc2V0IG9iamVjdCh2YWx1ZTogYW55KSB7XG4gICAgdGhpcy51c2VyID0gdmFsdWU7XG4gIH1cblxufVxuIl0sInNvdXJjZVJvb3QiOiIvc291cmNlLyJ9