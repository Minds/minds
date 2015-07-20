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
var remind_1 = require('./remind');
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
    Activity.prototype.remind = function () {
        var self = this;
        this.client.post('api/v1/newsfeed/remind/' + this.activity.guid, {})
            .then(function (data) {
            alert('reminded');
        });
    };
    Activity = __decorate([
        angular2_1.Component({
            selector: 'minds-activity',
            viewInjector: [api_1.Client],
            properties: ['object']
        }),
        angular2_1.View({
            templateUrl: 'templates/entities/activity.html',
            directives: [angular2_1.NgFor, angular2_1.NgIf, material_1.Material, remind_1.Remind, router_1.RouterLink]
        }), 
        __metadata('design:paramtypes', [api_1.Client])
    ], Activity);
    return Activity;
})();
exports.Activity = Activity;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9jb250cm9sbGVycy9uZXdzZmVlZC9hY3Rpdml0eS50cyJdLCJuYW1lcyI6WyJBY3Rpdml0eSIsIkFjdGl2aXR5LmNvbnN0cnVjdG9yIiwiQWN0aXZpdHkub2JqZWN0IiwiQWN0aXZpdHkudG9EYXRlIiwiQWN0aXZpdHkudGh1bWJzVXAiLCJBY3Rpdml0eS5yZW1pbmQiXSwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7O0FBQUEseUJBQXdFLG1CQUFtQixDQUFDLENBQUE7QUFDNUYsdUJBQTJCLGlCQUFpQixDQUFDLENBQUE7QUFDN0Msb0JBQXVCLGtCQUFrQixDQUFDLENBQUE7QUFDMUMseUJBQXlCLHlCQUF5QixDQUFDLENBQUE7QUFDbkQsdUJBQXVCLFVBQVUsQ0FBQyxDQUFBO0FBRWxDO0lBYUNBLGtCQUFtQkEsTUFBY0E7UUFBZEMsV0FBTUEsR0FBTkEsTUFBTUEsQ0FBUUE7SUFDakNBLENBQUNBO0lBRUFELHNCQUFJQSw0QkFBTUE7YUFBVkEsVUFBV0EsS0FBVUE7WUFDbkJFLElBQUlBLENBQUNBLFFBQVFBLEdBQUdBLEtBQUtBLENBQUNBO1FBQ3hCQSxDQUFDQTs7O09BQUFGO0lBS0ZBLHlCQUFNQSxHQUFOQSxVQUFPQSxTQUFTQTtRQUNmRyxNQUFNQSxDQUFDQSxJQUFJQSxJQUFJQSxDQUFDQSxTQUFTQSxHQUFDQSxJQUFJQSxDQUFDQSxDQUFDQTtJQUNqQ0EsQ0FBQ0E7SUFFQUgsMkJBQVFBLEdBQVJBO1FBQ0VJLE9BQU9BLENBQUNBLEdBQUdBLENBQUNBLDJCQUEyQkEsR0FBR0EsSUFBSUEsQ0FBQ0EsUUFBUUEsQ0FBQ0EsSUFBSUEsQ0FBQ0EsQ0FBQ0E7SUFDaEVBLENBQUNBO0lBRURKLHlCQUFNQSxHQUFOQTtRQUNFSyxJQUFJQSxJQUFJQSxHQUFHQSxJQUFJQSxDQUFDQTtRQUNoQkEsSUFBSUEsQ0FBQ0EsTUFBTUEsQ0FBQ0EsSUFBSUEsQ0FBQ0EseUJBQXlCQSxHQUFHQSxJQUFJQSxDQUFDQSxRQUFRQSxDQUFDQSxJQUFJQSxFQUFFQSxFQUFFQSxDQUFDQTthQUM3REEsSUFBSUEsQ0FBQ0EsVUFBQ0EsSUFBSUE7WUFDUEEsS0FBS0EsQ0FBQ0EsVUFBVUEsQ0FBQ0EsQ0FBQ0E7UUFDdEJBLENBQUNBLENBQUNBLENBQUNBO0lBQ1hBLENBQUNBO0lBckNITDtRQUFDQSxvQkFBU0EsQ0FBQ0E7WUFDVEEsUUFBUUEsRUFBRUEsZ0JBQWdCQTtZQUMxQkEsWUFBWUEsRUFBRUEsQ0FBRUEsWUFBTUEsQ0FBRUE7WUFDeEJBLFVBQVVBLEVBQUVBLENBQUNBLFFBQVFBLENBQUNBO1NBQ3ZCQSxDQUFDQTtRQUNEQSxlQUFJQSxDQUFDQTtZQUNKQSxXQUFXQSxFQUFFQSxrQ0FBa0NBO1lBQy9DQSxVQUFVQSxFQUFFQSxDQUFFQSxnQkFBS0EsRUFBRUEsZUFBSUEsRUFBRUEsbUJBQVFBLEVBQUVBLGVBQU1BLEVBQUVBLG1CQUFVQSxDQUFDQTtTQUN6REEsQ0FBQ0E7O2lCQThCREE7SUFBREEsZUFBQ0E7QUFBREEsQ0F0Q0EsQUFzQ0NBLElBQUE7QUE1QlksZ0JBQVEsV0E0QnBCLENBQUEiLCJmaWxlIjoic3JjL2NvbnRyb2xsZXJzL25ld3NmZWVkL2FjdGl2aXR5LmpzIiwic291cmNlc0NvbnRlbnQiOlsiaW1wb3J0IHsgQ29tcG9uZW50LCBWaWV3LCBOZ0ZvciwgTmdJZiwgT2JzZXJ2YWJsZSwgZm9ybURpcmVjdGl2ZXN9IGZyb20gJ2FuZ3VsYXIyL2FuZ3VsYXIyJztcbmltcG9ydCB7IFJvdXRlckxpbmsgfSBmcm9tIFwiYW5ndWxhcjIvcm91dGVyXCI7XG5pbXBvcnQgeyBDbGllbnQgfSBmcm9tICdzcmMvc2VydmljZXMvYXBpJztcbmltcG9ydCB7IE1hdGVyaWFsIH0gZnJvbSAnc3JjL2RpcmVjdGl2ZXMvbWF0ZXJpYWwnO1xuaW1wb3J0IHsgUmVtaW5kIH0gZnJvbSAnLi9yZW1pbmQnO1xuXG5AQ29tcG9uZW50KHtcbiAgc2VsZWN0b3I6ICdtaW5kcy1hY3Rpdml0eScsXG4gIHZpZXdJbmplY3RvcjogWyBDbGllbnQgXSxcbiAgcHJvcGVydGllczogWydvYmplY3QnXVxufSlcbkBWaWV3KHtcbiAgdGVtcGxhdGVVcmw6ICd0ZW1wbGF0ZXMvZW50aXRpZXMvYWN0aXZpdHkuaHRtbCcsXG4gIGRpcmVjdGl2ZXM6IFsgTmdGb3IsIE5nSWYsIE1hdGVyaWFsLCBSZW1pbmQsIFJvdXRlckxpbmtdXG59KVxuXG5leHBvcnQgY2xhc3MgQWN0aXZpdHkge1xuICBhY3Rpdml0eSA6IGFueTtcblxuXHRjb25zdHJ1Y3RvcihwdWJsaWMgY2xpZW50OiBDbGllbnQpe1xuXHR9XG5cbiAgc2V0IG9iamVjdCh2YWx1ZTogYW55KSB7XG4gICAgdGhpcy5hY3Rpdml0eSA9IHZhbHVlO1xuICB9XG5cblx0LyoqXG5cdCAqIEEgdGVtcG9yYXJ5IGhhY2ssIGJlY2F1c2UgcGlwZXMgZG9uJ3Qgc2VlbSB0byB3b3JrXG5cdCAqL1xuXHR0b0RhdGUodGltZXN0YW1wKXtcblx0XHRyZXR1cm4gbmV3IERhdGUodGltZXN0YW1wKjEwMDApO1xuXHR9XG5cbiAgdGh1bWJzVXAoKXtcbiAgICBjb25zb2xlLmxvZygneW91IGhpdCB0aGUgdGh1bWJzdXAgZm9yICcgKyB0aGlzLmFjdGl2aXR5Lmd1aWQpO1xuICB9XG5cbiAgcmVtaW5kKCl7XG4gICAgbGV0IHNlbGYgPSB0aGlzO1xuICAgIHRoaXMuY2xpZW50LnBvc3QoJ2FwaS92MS9uZXdzZmVlZC9yZW1pbmQvJyArIHRoaXMuYWN0aXZpdHkuZ3VpZCwge30pXG4gICAgICAgICAgLnRoZW4oKGRhdGEpPT4ge1xuICAgICAgICAgICAgICBhbGVydCgncmVtaW5kZWQnKTtcbiAgICAgICAgICB9KTtcbiAgfVxufVxuIl0sInNvdXJjZVJvb3QiOiIvc291cmNlLyJ9