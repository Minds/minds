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
var Navigation = (function () {
    function Navigation() {
    }
    Navigation = __decorate([
        angular2_1.Component({
            selector: 'minds-navigation'
        }),
        angular2_1.View({
            templateUrl: 'templates/components/navigation.html',
            directives: [router_1.RouterLink]
        }), 
        __metadata('design:paramtypes', [])
    ], Navigation);
    return Navigation;
})();
exports.Navigation = Navigation;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9jb21wb25lbnRzL25hdmlnYXRpb24udHMiXSwibmFtZXMiOlsiTmF2aWdhdGlvbiIsIk5hdmlnYXRpb24uY29uc3RydWN0b3IiXSwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7O0FBQUEseUJBQThCLG1CQUFtQixDQUFDLENBQUE7QUFDbEQsdUJBQXlCLGlCQUFpQixDQUFDLENBQUE7QUFFM0M7SUFBQUE7SUFRMEJDLENBQUNBO0lBUjNCRDtRQUFDQSxvQkFBU0EsQ0FBQ0E7WUFDVEEsUUFBUUEsRUFBRUEsa0JBQWtCQTtTQUM3QkEsQ0FBQ0E7UUFDREEsZUFBSUEsQ0FBQ0E7WUFDSkEsV0FBV0EsRUFBRUEsc0NBQXNDQTtZQUNuREEsVUFBVUEsRUFBRUEsQ0FBQ0EsbUJBQVVBLENBQUNBO1NBQ3pCQSxDQUFDQTs7bUJBRXlCQTtJQUFEQSxpQkFBQ0E7QUFBREEsQ0FSMUIsQUFRMkJBLElBQUE7QUFBZCxrQkFBVSxhQUFJLENBQUEiLCJmaWxlIjoic3JjL2NvbXBvbmVudHMvbmF2aWdhdGlvbi5qcyIsInNvdXJjZXNDb250ZW50IjpbImltcG9ydCB7Q29tcG9uZW50LCBWaWV3fSBmcm9tICdhbmd1bGFyMi9hbmd1bGFyMic7XG5pbXBvcnQge1JvdXRlckxpbmt9IGZyb20gJ2FuZ3VsYXIyL3JvdXRlcic7XG5cbkBDb21wb25lbnQoe1xuICBzZWxlY3RvcjogJ21pbmRzLW5hdmlnYXRpb24nXG59KVxuQFZpZXcoe1xuICB0ZW1wbGF0ZVVybDogJ3RlbXBsYXRlcy9jb21wb25lbnRzL25hdmlnYXRpb24uaHRtbCcsXG4gIGRpcmVjdGl2ZXM6IFtSb3V0ZXJMaW5rXVxufSlcblxuZXhwb3J0IGNsYXNzIE5hdmlnYXRpb24geyB9Il0sInNvdXJjZVJvb3QiOiIvc291cmNlLyJ9