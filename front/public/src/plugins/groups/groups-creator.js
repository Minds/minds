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
var __param = (this && this.__param) || function (paramIndex, decorator) {
    return function (target, key) { decorator(target, key, paramIndex); }
};
var angular2_1 = require('angular2/angular2');
var router_1 = require("angular2/router");
var api_1 = require('src/services/api');
var session_1 = require('src/services/session');
var material_1 = require('src/directives/material');
var GroupsCreator = (function () {
    function GroupsCreator(client, router) {
        this.client = client;
        this.router = router;
        this.session = session_1.SessionFactory.build();
        this.group = {
            name: '',
            description: '',
            membership: 2
        };
    }
    GroupsCreator.prototype.save = function () {
        console.log(this.group);
        var self = this;
        this.client.post('api/v1/groups', this.group)
            .then(function (response) {
        })
            .catch(function (e) {
        });
    };
    GroupsCreator = __decorate([
        angular2_1.Component({
            selector: 'minds-groups-create',
            viewBindings: [api_1.Client]
        }),
        angular2_1.View({
            templateUrl: 'templates/plugins/groups/create.html',
            directives: [angular2_1.NgFor, angular2_1.NgIf, angular2_1.NgClass, material_1.Material, router_1.RouterLink, angular2_1.FORM_DIRECTIVES]
        }),
        __param(1, angular2_1.Inject(router_1.Router)), 
        __metadata('design:paramtypes', [api_1.Client, router_1.Router])
    ], GroupsCreator);
    return GroupsCreator;
})();
exports.GroupsCreator = GroupsCreator;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9wbHVnaW5zL2dyb3Vwcy9ncm91cHMtY3JlYXRvci50cyJdLCJuYW1lcyI6WyJHcm91cHNDcmVhdG9yIiwiR3JvdXBzQ3JlYXRvci5jb25zdHJ1Y3RvciIsIkdyb3Vwc0NyZWF0b3Iuc2F2ZSJdLCJtYXBwaW5ncyI6Ijs7Ozs7Ozs7Ozs7Ozs7QUFBQSx5QkFBMEYsbUJBQW1CLENBQUMsQ0FBQTtBQUM5Ryx1QkFBbUMsaUJBQWlCLENBQUMsQ0FBQTtBQUVyRCxvQkFBdUIsa0JBQWtCLENBQUMsQ0FBQTtBQUMxQyx3QkFBK0Isc0JBQXNCLENBQUMsQ0FBQTtBQUN0RCx5QkFBeUIseUJBQXlCLENBQUMsQ0FBQTtBQUVuRDtJQWtCRUEsdUJBQW1CQSxNQUFjQSxFQUF5QkEsTUFBY0E7UUFBckRDLFdBQU1BLEdBQU5BLE1BQU1BLENBQVFBO1FBQXlCQSxXQUFNQSxHQUFOQSxNQUFNQSxDQUFRQTtRQVB4RUEsWUFBT0EsR0FBR0Esd0JBQWNBLENBQUNBLEtBQUtBLEVBQUVBLENBQUNBO1FBQ2pDQSxVQUFLQSxHQUFHQTtZQUNOQSxJQUFJQSxFQUFFQSxFQUFFQTtZQUNSQSxXQUFXQSxFQUFFQSxFQUFFQTtZQUNmQSxVQUFVQSxFQUFFQSxDQUFDQTtTQUNkQSxDQUFDQTtJQUlGQSxDQUFDQTtJQUVERCw0QkFBSUEsR0FBSkE7UUFDRUUsT0FBT0EsQ0FBQ0EsR0FBR0EsQ0FBQ0EsSUFBSUEsQ0FBQ0EsS0FBS0EsQ0FBQ0EsQ0FBQ0E7UUFDeEJBLElBQUlBLElBQUlBLEdBQUdBLElBQUlBLENBQUNBO1FBQ2hCQSxJQUFJQSxDQUFDQSxNQUFNQSxDQUFDQSxJQUFJQSxDQUFDQSxlQUFlQSxFQUFFQSxJQUFJQSxDQUFDQSxLQUFLQSxDQUFDQTthQUMxQ0EsSUFBSUEsQ0FBQ0EsVUFBQ0EsUUFBUUE7UUFFZkEsQ0FBQ0EsQ0FBQ0E7YUFDREEsS0FBS0EsQ0FBQ0EsVUFBQ0EsQ0FBQ0E7UUFFVEEsQ0FBQ0EsQ0FBQ0EsQ0FBQ0E7SUFDUEEsQ0FBQ0E7SUFoQ0hGO1FBQUNBLG9CQUFTQSxDQUFDQTtZQUNUQSxRQUFRQSxFQUFFQSxxQkFBcUJBO1lBQy9CQSxZQUFZQSxFQUFFQSxDQUFFQSxZQUFNQSxDQUFFQTtTQUN6QkEsQ0FBQ0E7UUFDREEsZUFBSUEsQ0FBQ0E7WUFDSkEsV0FBV0EsRUFBRUEsc0NBQXNDQTtZQUNuREEsVUFBVUEsRUFBRUEsQ0FBRUEsZ0JBQUtBLEVBQUVBLGVBQUlBLEVBQUVBLGtCQUFPQSxFQUFFQSxtQkFBUUEsRUFBRUEsbUJBQVVBLEVBQUVBLDBCQUFlQSxDQUFFQTtTQUM1RUEsQ0FBQ0E7UUFXbUNBLFdBQUNBLGlCQUFNQSxDQUFDQSxlQUFNQSxDQUFDQSxDQUFBQTs7c0JBZ0JuREE7SUFBREEsb0JBQUNBO0FBQURBLENBbENBLEFBa0NDQSxJQUFBO0FBekJZLHFCQUFhLGdCQXlCekIsQ0FBQSIsImZpbGUiOiJzcmMvcGx1Z2lucy9ncm91cHMvZ3JvdXBzLWNyZWF0b3IuanMiLCJzb3VyY2VzQ29udGVudCI6WyJpbXBvcnQgeyBDb21wb25lbnQsIFZpZXcsIE5nRm9yLCBOZ0lmLCBOZ0NsYXNzLCBPYnNlcnZhYmxlLCBJbmplY3QsIEZPUk1fRElSRUNUSVZFU30gZnJvbSAnYW5ndWxhcjIvYW5ndWxhcjInO1xuaW1wb3J0IHsgUm91dGVyLCBSb3V0ZXJMaW5rIH0gZnJvbSBcImFuZ3VsYXIyL3JvdXRlclwiO1xuXG5pbXBvcnQgeyBDbGllbnQgfSBmcm9tICdzcmMvc2VydmljZXMvYXBpJztcbmltcG9ydCB7IFNlc3Npb25GYWN0b3J5IH0gZnJvbSAnc3JjL3NlcnZpY2VzL3Nlc3Npb24nO1xuaW1wb3J0IHsgTWF0ZXJpYWwgfSBmcm9tICdzcmMvZGlyZWN0aXZlcy9tYXRlcmlhbCc7XG5cbkBDb21wb25lbnQoe1xuICBzZWxlY3RvcjogJ21pbmRzLWdyb3Vwcy1jcmVhdGUnLFxuICB2aWV3QmluZGluZ3M6IFsgQ2xpZW50IF1cbn0pXG5AVmlldyh7XG4gIHRlbXBsYXRlVXJsOiAndGVtcGxhdGVzL3BsdWdpbnMvZ3JvdXBzL2NyZWF0ZS5odG1sJyxcbiAgZGlyZWN0aXZlczogWyBOZ0ZvciwgTmdJZiwgTmdDbGFzcywgTWF0ZXJpYWwsIFJvdXRlckxpbmssIEZPUk1fRElSRUNUSVZFUyBdXG59KVxuXG5leHBvcnQgY2xhc3MgR3JvdXBzQ3JlYXRvciB7XG5cbiAgc2Vzc2lvbiA9IFNlc3Npb25GYWN0b3J5LmJ1aWxkKCk7XG4gIGdyb3VwID0ge1xuICAgIG5hbWU6ICcnLFxuICAgIGRlc2NyaXB0aW9uOiAnJyxcbiAgICBtZW1iZXJzaGlwOiAyXG4gIH07XG5cbiAgY29uc3RydWN0b3IocHVibGljIGNsaWVudDogQ2xpZW50LCBASW5qZWN0KFJvdXRlcikgcHVibGljIHJvdXRlcjogUm91dGVyKXtcblxuICB9XG5cbiAgc2F2ZSgpe1xuICAgIGNvbnNvbGUubG9nKHRoaXMuZ3JvdXApO1xuICAgIHZhciBzZWxmID0gdGhpcztcbiAgICB0aGlzLmNsaWVudC5wb3N0KCdhcGkvdjEvZ3JvdXBzJywgdGhpcy5ncm91cClcbiAgICAgIC50aGVuKChyZXNwb25zZSkgPT4ge1xuXG4gICAgICB9KVxuICAgICAgLmNhdGNoKChlKT0+e1xuXG4gICAgICB9KTtcbiAgfVxuXG59XG4iXSwic291cmNlUm9vdCI6Ii9zb3VyY2UvIn0=