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
var Activity = (function () {
    function Activity(client) {
        this.client = client;
    }
    Object.defineProperty(Activity.prototype, "object", {
        set: function (value) {
            this.activity = value;
        },
        enumerable: true,
        configurable: true
    });
    Activity.prototype.toDate = function (timestamp) {
        return new Date(timestamp * 1000);
    };
    Activity.prototype.thumbsUp = function () {
        console.log('you hit the thumbsup for ' + this.activity.guid);
    };
    Activity = __decorate([
        angular2_1.Component({
            selector: 'minds-activity',
            viewInjector: [api_1.Client],
            properties: ['object']
        }),
        angular2_1.View({
            templateUrl: 'templates/entities/activity.html',
            directives: [angular2_1.NgFor, angular2_1.NgIf, material_1.Material]
        }), 
        __metadata('design:paramtypes', [api_1.Client])
    ], Activity);
    return Activity;
})();
exports.Activity = Activity;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9jb250cm9sbGVycy9uZXdzZmVlZC9hY3Rpdml0eS50cyJdLCJuYW1lcyI6WyJBY3Rpdml0eSIsIkFjdGl2aXR5LmNvbnN0cnVjdG9yIiwiQWN0aXZpdHkub2JqZWN0IiwiQWN0aXZpdHkudG9EYXRlIiwiQWN0aXZpdHkudGh1bWJzVXAiXSwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7O0FBQUEseUJBQXdFLG1CQUFtQixDQUFDLENBQUE7QUFDNUYsb0JBQXVCLGtCQUFrQixDQUFDLENBQUE7QUFDMUMseUJBQXlCLHlCQUF5QixDQUFDLENBQUE7QUFFbkQ7SUFhQ0Esa0JBQW1CQSxNQUFjQTtRQUFkQyxXQUFNQSxHQUFOQSxNQUFNQSxDQUFRQTtJQUNqQ0EsQ0FBQ0E7SUFFQUQsc0JBQUlBLDRCQUFNQTthQUFWQSxVQUFXQSxLQUFVQTtZQUNuQkUsSUFBSUEsQ0FBQ0EsUUFBUUEsR0FBR0EsS0FBS0EsQ0FBQ0E7UUFDeEJBLENBQUNBOzs7T0FBQUY7SUFLRkEseUJBQU1BLEdBQU5BLFVBQU9BLFNBQVNBO1FBQ2ZHLE1BQU1BLENBQUNBLElBQUlBLElBQUlBLENBQUNBLFNBQVNBLEdBQUNBLElBQUlBLENBQUNBLENBQUNBO0lBQ2pDQSxDQUFDQTtJQUVBSCwyQkFBUUEsR0FBUkE7UUFDRUksT0FBT0EsQ0FBQ0EsR0FBR0EsQ0FBQ0EsMkJBQTJCQSxHQUFHQSxJQUFJQSxDQUFDQSxRQUFRQSxDQUFDQSxJQUFJQSxDQUFDQSxDQUFDQTtJQUNoRUEsQ0FBQ0E7SUE3QkhKO1FBQUNBLG9CQUFTQSxDQUFDQTtZQUNUQSxRQUFRQSxFQUFFQSxnQkFBZ0JBO1lBQzFCQSxZQUFZQSxFQUFFQSxDQUFFQSxZQUFNQSxDQUFFQTtZQUN4QkEsVUFBVUEsRUFBRUEsQ0FBQ0EsUUFBUUEsQ0FBQ0E7U0FDdkJBLENBQUNBO1FBQ0RBLGVBQUlBLENBQUNBO1lBQ0pBLFdBQVdBLEVBQUVBLGtDQUFrQ0E7WUFDL0NBLFVBQVVBLEVBQUVBLENBQUVBLGdCQUFLQSxFQUFFQSxlQUFJQSxFQUFFQSxtQkFBUUEsQ0FBQ0E7U0FDckNBLENBQUNBOztpQkFzQkRBO0lBQURBLGVBQUNBO0FBQURBLENBOUJBLElBOEJDO0FBcEJZLGdCQUFRLFdBb0JwQixDQUFBIiwiZmlsZSI6InNyYy9jb250cm9sbGVycy9uZXdzZmVlZC9hY3Rpdml0eS5qcyIsInNvdXJjZXNDb250ZW50IjpbImltcG9ydCB7IENvbXBvbmVudCwgVmlldywgTmdGb3IsIE5nSWYsIE9ic2VydmFibGUsIGZvcm1EaXJlY3RpdmVzfSBmcm9tICdhbmd1bGFyMi9hbmd1bGFyMic7XG5pbXBvcnQgeyBDbGllbnQgfSBmcm9tICdzcmMvc2VydmljZXMvYXBpJztcbmltcG9ydCB7IE1hdGVyaWFsIH0gZnJvbSAnc3JjL2RpcmVjdGl2ZXMvbWF0ZXJpYWwnO1xuXG5AQ29tcG9uZW50KHtcbiAgc2VsZWN0b3I6ICdtaW5kcy1hY3Rpdml0eScsXG4gIHZpZXdJbmplY3RvcjogWyBDbGllbnQgXSxcbiAgcHJvcGVydGllczogWydvYmplY3QnXVxufSlcbkBWaWV3KHtcbiAgdGVtcGxhdGVVcmw6ICd0ZW1wbGF0ZXMvZW50aXRpZXMvYWN0aXZpdHkuaHRtbCcsXG4gIGRpcmVjdGl2ZXM6IFsgTmdGb3IsIE5nSWYsIE1hdGVyaWFsXVxufSlcblxuZXhwb3J0IGNsYXNzIEFjdGl2aXR5IHtcbiAgYWN0aXZpdHkgOiBPYmplY3Q7XG5cblx0Y29uc3RydWN0b3IocHVibGljIGNsaWVudDogQ2xpZW50KXtcblx0fVxuXG4gIHNldCBvYmplY3QodmFsdWU6IGFueSkge1xuICAgIHRoaXMuYWN0aXZpdHkgPSB2YWx1ZTtcbiAgfVxuXG5cdC8qKlxuXHQgKiBBIHRlbXBvcmFyeSBoYWNrLCBiZWNhdXNlIHBpcGVzIGRvbid0IHNlZW0gdG8gd29ya1xuXHQgKi9cblx0dG9EYXRlKHRpbWVzdGFtcCl7XG5cdFx0cmV0dXJuIG5ldyBEYXRlKHRpbWVzdGFtcCoxMDAwKTtcblx0fVxuXG4gIHRodW1ic1VwKCl7XG4gICAgY29uc29sZS5sb2coJ3lvdSBoaXQgdGhlIHRodW1ic3VwIGZvciAnICsgdGhpcy5hY3Rpdml0eS5ndWlkKTtcbiAgfVxufVxuIl0sInNvdXJjZVJvb3QiOiIvc291cmNlLyJ9