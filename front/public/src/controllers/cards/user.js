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
            viewBindings: [api_1.Client],
            properties: ['object']
        }),
        angular2_1.View({
            templateUrl: 'templates/cards/user.html',
            directives: [angular2_1.NgFor, angular2_1.NgIf, angular2_1.NgClass, material_1.Material, router_1.RouterLink]
        }), 
        __metadata('design:paramtypes', [api_1.Client])
    ], UserCard);
    return UserCard;
})();
exports.UserCard = UserCard;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9jb250cm9sbGVycy9jYXJkcy91c2VyLnRzIl0sIm5hbWVzIjpbIlVzZXJDYXJkIiwiVXNlckNhcmQuY29uc3RydWN0b3IiLCJVc2VyQ2FyZC5vYmplY3QiXSwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7O0FBQUEseUJBQWlFLG1CQUFtQixDQUFDLENBQUE7QUFDckYsdUJBQTJCLGlCQUFpQixDQUFDLENBQUE7QUFDN0Msb0JBQXVCLGtCQUFrQixDQUFDLENBQUE7QUFDMUMsd0JBQStCLHNCQUFzQixDQUFDLENBQUE7QUFDdEQseUJBQXlCLHlCQUF5QixDQUFDLENBQUE7QUFFbkQ7SUFlQ0Esa0JBQW1CQSxNQUFjQTtRQUFkQyxXQUFNQSxHQUFOQSxNQUFNQSxDQUFRQTtRQUhoQ0EsWUFBT0EsR0FBR0Esd0JBQWNBLENBQUNBLEtBQUtBLEVBQUVBLENBQUNBO1FBSS9CQSxJQUFJQSxDQUFDQSxLQUFLQSxHQUFHQSxNQUFNQSxDQUFDQSxLQUFLQSxDQUFDQTtJQUM3QkEsQ0FBQ0E7SUFFQUQsc0JBQUlBLDRCQUFNQTthQUFWQSxVQUFXQSxLQUFVQTtZQUNuQkUsSUFBSUEsQ0FBQ0EsSUFBSUEsR0FBR0EsS0FBS0EsQ0FBQ0E7UUFDcEJBLENBQUNBOzs7T0FBQUY7SUFyQkhBO1FBQUNBLG9CQUFTQSxDQUFDQTtZQUNUQSxRQUFRQSxFQUFFQSxpQkFBaUJBO1lBQzNCQSxZQUFZQSxFQUFFQSxDQUFFQSxZQUFNQSxDQUFFQTtZQUN4QkEsVUFBVUEsRUFBRUEsQ0FBQ0EsUUFBUUEsQ0FBQ0E7U0FDdkJBLENBQUNBO1FBQ0RBLGVBQUlBLENBQUNBO1lBQ0pBLFdBQVdBLEVBQUVBLDJCQUEyQkE7WUFDeENBLFVBQVVBLEVBQUVBLENBQUVBLGdCQUFLQSxFQUFFQSxlQUFJQSxFQUFFQSxrQkFBT0EsRUFBRUEsbUJBQVFBLEVBQUVBLG1CQUFVQSxDQUFDQTtTQUMxREEsQ0FBQ0E7O2lCQWVEQTtJQUFEQSxlQUFDQTtBQUFEQSxDQXZCQSxBQXVCQ0EsSUFBQTtBQWJZLGdCQUFRLFdBYXBCLENBQUEiLCJmaWxlIjoic3JjL2NvbnRyb2xsZXJzL2NhcmRzL3VzZXIuanMiLCJzb3VyY2VzQ29udGVudCI6WyJpbXBvcnQgeyBDb21wb25lbnQsIFZpZXcsIE5nRm9yLCBOZ0lmLCBOZ0NsYXNzLCBPYnNlcnZhYmxlfSBmcm9tICdhbmd1bGFyMi9hbmd1bGFyMic7XG5pbXBvcnQgeyBSb3V0ZXJMaW5rIH0gZnJvbSBcImFuZ3VsYXIyL3JvdXRlclwiO1xuaW1wb3J0IHsgQ2xpZW50IH0gZnJvbSAnc3JjL3NlcnZpY2VzL2FwaSc7XG5pbXBvcnQgeyBTZXNzaW9uRmFjdG9yeSB9IGZyb20gJ3NyYy9zZXJ2aWNlcy9zZXNzaW9uJztcbmltcG9ydCB7IE1hdGVyaWFsIH0gZnJvbSAnc3JjL2RpcmVjdGl2ZXMvbWF0ZXJpYWwnO1xuXG5AQ29tcG9uZW50KHtcbiAgc2VsZWN0b3I6ICdtaW5kcy1jYXJkLXVzZXInLFxuICB2aWV3QmluZGluZ3M6IFsgQ2xpZW50IF0sXG4gIHByb3BlcnRpZXM6IFsnb2JqZWN0J11cbn0pXG5AVmlldyh7XG4gIHRlbXBsYXRlVXJsOiAndGVtcGxhdGVzL2NhcmRzL3VzZXIuaHRtbCcsXG4gIGRpcmVjdGl2ZXM6IFsgTmdGb3IsIE5nSWYsIE5nQ2xhc3MsIE1hdGVyaWFsLCBSb3V0ZXJMaW5rXVxufSlcblxuZXhwb3J0IGNsYXNzIFVzZXJDYXJkIHtcbiAgdXNlciA6IGFueTtcbiAgc2Vzc2lvbiA9IFNlc3Npb25GYWN0b3J5LmJ1aWxkKCk7XG4gIG1pbmRzOiB7fTtcblxuXHRjb25zdHJ1Y3RvcihwdWJsaWMgY2xpZW50OiBDbGllbnQpe1xuICAgIHRoaXMubWluZHMgPSB3aW5kb3cuTWluZHM7XG5cdH1cblxuICBzZXQgb2JqZWN0KHZhbHVlOiBhbnkpIHtcbiAgICB0aGlzLnVzZXIgPSB2YWx1ZTtcbiAgfVxuXG59XG4iXSwic291cmNlUm9vdCI6Ii9zb3VyY2UvIn0=