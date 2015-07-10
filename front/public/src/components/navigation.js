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
var events_1 = require('src/services/events');
var Navigation = (function () {
    function Navigation() {
        this.getUser();
        events_1.LoggedIn.listen(function () {
            console.log('got loggedin event');
        });
    }
    Navigation.prototype.getUser = function () {
        events_1.LoggedIn.emit();
        if (window.Minds.user) {
            this.user = window.Minds.user;
        }
    };
    Navigation = __decorate([
        angular2_1.Component({
            selector: 'minds-navigation'
        }),
        angular2_1.View({
            templateUrl: 'templates/components/navigation.html',
            directives: [router_1.RouterLink, angular2_1.NgIf, angular2_1.NgFor]
        }), 
        __metadata('design:paramtypes', [])
    ], Navigation);
    return Navigation;
})();
exports.Navigation = Navigation;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9jb21wb25lbnRzL25hdmlnYXRpb24udHMiXSwibmFtZXMiOlsiTmF2aWdhdGlvbiIsIk5hdmlnYXRpb24uY29uc3RydWN0b3IiLCJOYXZpZ2F0aW9uLmdldFVzZXIiXSwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7O0FBQUEseUJBQXlELG1CQUFtQixDQUFDLENBQUE7QUFDN0UsdUJBQXlCLGlCQUFpQixDQUFDLENBQUE7QUFDM0MsdUJBQXVCLHFCQUFxQixDQUFDLENBQUE7QUFFN0M7SUFXQ0E7UUFDQ0MsSUFBSUEsQ0FBQ0EsT0FBT0EsRUFBRUEsQ0FBQ0E7UUFDZkEsaUJBQVFBLENBQUNBLE1BQU1BLENBQUNBO1lBQ2ZBLE9BQU9BLENBQUNBLEdBQUdBLENBQUNBLG9CQUFvQkEsQ0FBQ0EsQ0FBQ0E7UUFDbkNBLENBQUNBLENBQUNBLENBQUNBO0lBQ0pBLENBQUNBO0lBRURELDRCQUFPQSxHQUFQQTtRQUVDRSxpQkFBUUEsQ0FBQ0EsSUFBSUEsRUFBRUEsQ0FBQ0E7UUFFaEJBLEVBQUVBLENBQUFBLENBQUNBLE1BQU1BLENBQUNBLEtBQUtBLENBQUNBLElBQUlBLENBQUNBLENBQUFBLENBQUNBO1lBQ3JCQSxJQUFJQSxDQUFDQSxJQUFJQSxHQUFHQSxNQUFNQSxDQUFDQSxLQUFLQSxDQUFDQSxJQUFJQSxDQUFDQTtRQUMvQkEsQ0FBQ0E7SUFDRkEsQ0FBQ0E7SUF6QkZGO1FBQUNBLG9CQUFTQSxDQUFDQTtZQUNUQSxRQUFRQSxFQUFFQSxrQkFBa0JBO1NBQzdCQSxDQUFDQTtRQUNEQSxlQUFJQSxDQUFDQTtZQUNKQSxXQUFXQSxFQUFFQSxzQ0FBc0NBO1lBQ25EQSxVQUFVQSxFQUFFQSxDQUFDQSxtQkFBVUEsRUFBRUEsZUFBSUEsRUFBRUEsZ0JBQUtBLENBQUNBO1NBQ3RDQSxDQUFDQTs7bUJBb0JEQTtJQUFEQSxpQkFBQ0E7QUFBREEsQ0ExQkEsSUEwQkM7QUFsQlksa0JBQVUsYUFrQnRCLENBQUEiLCJmaWxlIjoic3JjL2NvbXBvbmVudHMvbmF2aWdhdGlvbi5qcyIsInNvdXJjZXNDb250ZW50IjpbImltcG9ydCB7Q29tcG9uZW50LCBWaWV3LCBOZ0lmLCBOZ0ZvciwgRXZlbnRFbWl0dGVyfSBmcm9tICdhbmd1bGFyMi9hbmd1bGFyMic7XG5pbXBvcnQge1JvdXRlckxpbmt9IGZyb20gJ2FuZ3VsYXIyL3JvdXRlcic7XG5pbXBvcnQge0xvZ2dlZElufSBmcm9tICdzcmMvc2VydmljZXMvZXZlbnRzJztcblxuQENvbXBvbmVudCh7XG4gIHNlbGVjdG9yOiAnbWluZHMtbmF2aWdhdGlvbidcbn0pXG5AVmlldyh7XG4gIHRlbXBsYXRlVXJsOiAndGVtcGxhdGVzL2NvbXBvbmVudHMvbmF2aWdhdGlvbi5odG1sJyxcbiAgZGlyZWN0aXZlczogW1JvdXRlckxpbmssIE5nSWYsIE5nRm9yXVxufSlcblxuZXhwb3J0IGNsYXNzIE5hdmlnYXRpb24geyBcblx0dXNlcjtcblxuXHRjb25zdHJ1Y3RvcigpeyBcblx0XHR0aGlzLmdldFVzZXIoKTtcblx0XHRMb2dnZWRJbi5saXN0ZW4oKCk9Pntcblx0XHRcdGNvbnNvbGUubG9nKCdnb3QgbG9nZ2VkaW4gZXZlbnQnKTtcblx0XHR9KTtcblx0fVxuXHRcblx0Z2V0VXNlcigpe1xuXG5cdFx0TG9nZ2VkSW4uZW1pdCgpO1xuXHRcdFxuXHRcdGlmKHdpbmRvdy5NaW5kcy51c2VyKXtcblx0XHRcdHRoaXMudXNlciA9IHdpbmRvdy5NaW5kcy51c2VyO1xuXHRcdH1cblx0fVxufSJdLCJzb3VyY2VSb290IjoiL3NvdXJjZS8ifQ==