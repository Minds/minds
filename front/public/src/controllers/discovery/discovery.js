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
var api_1 = require('src/services/api');
var material_1 = require('src/directives/material');
var infinite_scroll_1 = require('../../directives/infinite-scroll');
var Discovery = (function () {
    function Discovery(client) {
        this.client = client;
    }
    Discovery = __decorate([
        angular2_1.Component({
            selector: 'minds-discovery',
            viewInjector: [api_1.Client]
        }),
        angular2_1.View({
            templateUrl: 'templates/discovery/discovery.html',
            directives: [angular2_1.NgFor, angular2_1.NgIf, material_1.Material, angular2_1.formDirectives, infinite_scroll_1.InfiniteScroll]
        }), 
        __metadata('design:paramtypes', [api_1.Client])
    ], Discovery);
    return Discovery;
})();
exports.Discovery = Discovery;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9jb250cm9sbGVycy9kaXNjb3ZlcnkvZGlzY292ZXJ5LnRzIl0sIm5hbWVzIjpbIkRpc2NvdmVyeSIsIkRpc2NvdmVyeS5jb25zdHJ1Y3RvciJdLCJtYXBwaW5ncyI6Ijs7Ozs7Ozs7Ozs7QUFBQSx5QkFBb0UsbUJBQW1CLENBQUMsQ0FBQTtBQUV4RixvQkFBdUIsa0JBQWtCLENBQUMsQ0FBQTtBQUMxQyx5QkFBeUIseUJBQXlCLENBQUMsQ0FBQTtBQUVuRCxnQ0FBK0Isa0NBQWtDLENBQUMsQ0FBQTtBQUdsRTtJQVdFQSxtQkFBbUJBLE1BQWNBO1FBQWRDLFdBQU1BLEdBQU5BLE1BQU1BLENBQVFBO0lBQUVBLENBQUNBO0lBWHRDRDtRQUFDQSxvQkFBU0EsQ0FBQ0E7WUFDVEEsUUFBUUEsRUFBRUEsaUJBQWlCQTtZQUMzQkEsWUFBWUEsRUFBRUEsQ0FBRUEsWUFBTUEsQ0FBRUE7U0FDekJBLENBQUNBO1FBQ0RBLGVBQUlBLENBQUNBO1lBQ0pBLFdBQVdBLEVBQUVBLG9DQUFvQ0E7WUFDakRBLFVBQVVBLEVBQUVBLENBQUVBLGdCQUFLQSxFQUFFQSxlQUFJQSxFQUFFQSxtQkFBUUEsRUFBRUEseUJBQWNBLEVBQUVBLGdDQUFjQSxDQUFFQTtTQUN0RUEsQ0FBQ0E7O2tCQU1EQTtJQUFEQSxnQkFBQ0E7QUFBREEsQ0FiQSxJQWFDO0FBSlksaUJBQVMsWUFJckIsQ0FBQSIsImZpbGUiOiJzcmMvY29udHJvbGxlcnMvZGlzY292ZXJ5L2Rpc2NvdmVyeS5qcyIsInNvdXJjZXNDb250ZW50IjpbImltcG9ydCB7IENvbXBvbmVudCwgVmlldywgTmdGb3IsIE5nSWYsIEluamVjdCwgZm9ybURpcmVjdGl2ZXN9IGZyb20gJ2FuZ3VsYXIyL2FuZ3VsYXIyJztcbmltcG9ydCB7IFJvdXRlciwgUm91dGVQYXJhbXMgfSBmcm9tICdhbmd1bGFyMi9yb3V0ZXInO1xuaW1wb3J0IHsgQ2xpZW50IH0gZnJvbSAnc3JjL3NlcnZpY2VzL2FwaSc7XG5pbXBvcnQgeyBNYXRlcmlhbCB9IGZyb20gJ3NyYy9kaXJlY3RpdmVzL21hdGVyaWFsJztcbmltcG9ydCB7IFNlc3Npb25GYWN0b3J5IH0gZnJvbSAnLi4vLi4vc2VydmljZXMvc2Vzc2lvbic7XG5pbXBvcnQgeyBJbmZpbml0ZVNjcm9sbCB9IGZyb20gJy4uLy4uL2RpcmVjdGl2ZXMvaW5maW5pdGUtc2Nyb2xsJztcbmltcG9ydCB7IEFjdGl2aXR5IH0gZnJvbSAnc3JjL2NvbnRyb2xsZXJzL25ld3NmZWVkL2FjdGl2aXR5JztcblxuQENvbXBvbmVudCh7XG4gIHNlbGVjdG9yOiAnbWluZHMtZGlzY292ZXJ5JyxcbiAgdmlld0luamVjdG9yOiBbIENsaWVudCBdXG59KVxuQFZpZXcoe1xuICB0ZW1wbGF0ZVVybDogJ3RlbXBsYXRlcy9kaXNjb3ZlcnkvZGlzY292ZXJ5Lmh0bWwnLFxuICBkaXJlY3RpdmVzOiBbIE5nRm9yLCBOZ0lmLCBNYXRlcmlhbCwgZm9ybURpcmVjdGl2ZXMsIEluZmluaXRlU2Nyb2xsIF1cbn0pXG5cbmV4cG9ydCBjbGFzcyBEaXNjb3Zlcnkge1xuXG4gIGNvbnN0cnVjdG9yKHB1YmxpYyBjbGllbnQ6IENsaWVudCl7fVxuXG59XG4iXSwic291cmNlUm9vdCI6Ii9zb3VyY2UvIn0=