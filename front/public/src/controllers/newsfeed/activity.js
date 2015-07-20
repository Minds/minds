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
            directives: [angular2_1.NgFor, angular2_1.NgIf, material_1.Material, remind_1.Remind]
        }), 
        __metadata('design:paramtypes', [api_1.Client])
    ], Activity);
    return Activity;
})();
exports.Activity = Activity;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9jb250cm9sbGVycy9uZXdzZmVlZC9hY3Rpdml0eS50cyJdLCJuYW1lcyI6WyJBY3Rpdml0eSIsIkFjdGl2aXR5LmNvbnN0cnVjdG9yIiwiQWN0aXZpdHkub2JqZWN0IiwiQWN0aXZpdHkudG9EYXRlIiwiQWN0aXZpdHkudGh1bWJzVXAiLCJBY3Rpdml0eS5yZW1pbmQiXSwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7O0FBQUEseUJBQXdFLG1CQUFtQixDQUFDLENBQUE7QUFDNUYsb0JBQXVCLGtCQUFrQixDQUFDLENBQUE7QUFDMUMseUJBQXlCLHlCQUF5QixDQUFDLENBQUE7QUFDbkQsdUJBQXVCLFVBQVUsQ0FBQyxDQUFBO0FBRWxDO0lBYUNBLGtCQUFtQkEsTUFBY0E7UUFBZEMsV0FBTUEsR0FBTkEsTUFBTUEsQ0FBUUE7SUFDakNBLENBQUNBO0lBRUFELHNCQUFJQSw0QkFBTUE7YUFBVkEsVUFBV0EsS0FBVUE7WUFDbkJFLElBQUlBLENBQUNBLFFBQVFBLEdBQUdBLEtBQUtBLENBQUNBO1FBQ3hCQSxDQUFDQTs7O09BQUFGO0lBS0ZBLHlCQUFNQSxHQUFOQSxVQUFPQSxTQUFTQTtRQUNmRyxNQUFNQSxDQUFDQSxJQUFJQSxJQUFJQSxDQUFDQSxTQUFTQSxHQUFDQSxJQUFJQSxDQUFDQSxDQUFDQTtJQUNqQ0EsQ0FBQ0E7SUFFQUgsMkJBQVFBLEdBQVJBO1FBQ0VJLE9BQU9BLENBQUNBLEdBQUdBLENBQUNBLDJCQUEyQkEsR0FBR0EsSUFBSUEsQ0FBQ0EsUUFBUUEsQ0FBQ0EsSUFBSUEsQ0FBQ0EsQ0FBQ0E7SUFDaEVBLENBQUNBO0lBRURKLHlCQUFNQSxHQUFOQTtRQUNFSyxJQUFJQSxJQUFJQSxHQUFHQSxJQUFJQSxDQUFDQTtRQUNoQkEsSUFBSUEsQ0FBQ0EsTUFBTUEsQ0FBQ0EsSUFBSUEsQ0FBQ0EseUJBQXlCQSxHQUFHQSxJQUFJQSxDQUFDQSxRQUFRQSxDQUFDQSxJQUFJQSxFQUFFQSxFQUFFQSxDQUFDQTthQUM3REEsSUFBSUEsQ0FBQ0EsVUFBQ0EsSUFBSUE7WUFDUEEsS0FBS0EsQ0FBQ0EsVUFBVUEsQ0FBQ0EsQ0FBQ0E7UUFDdEJBLENBQUNBLENBQUNBLENBQUNBO0lBQ1hBLENBQUNBO0lBckNITDtRQUFDQSxvQkFBU0EsQ0FBQ0E7WUFDVEEsUUFBUUEsRUFBRUEsZ0JBQWdCQTtZQUMxQkEsWUFBWUEsRUFBRUEsQ0FBRUEsWUFBTUEsQ0FBRUE7WUFDeEJBLFVBQVVBLEVBQUVBLENBQUNBLFFBQVFBLENBQUNBO1NBQ3ZCQSxDQUFDQTtRQUNEQSxlQUFJQSxDQUFDQTtZQUNKQSxXQUFXQSxFQUFFQSxrQ0FBa0NBO1lBQy9DQSxVQUFVQSxFQUFFQSxDQUFFQSxnQkFBS0EsRUFBRUEsZUFBSUEsRUFBRUEsbUJBQVFBLEVBQUVBLGVBQU1BLENBQUNBO1NBQzdDQSxDQUFDQTs7aUJBOEJEQTtJQUFEQSxlQUFDQTtBQUFEQSxDQXRDQSxBQXNDQ0EsSUFBQTtBQTVCWSxnQkFBUSxXQTRCcEIsQ0FBQSIsImZpbGUiOiJzcmMvY29udHJvbGxlcnMvbmV3c2ZlZWQvYWN0aXZpdHkuanMiLCJzb3VyY2VzQ29udGVudCI6WyJpbXBvcnQgeyBDb21wb25lbnQsIFZpZXcsIE5nRm9yLCBOZ0lmLCBPYnNlcnZhYmxlLCBmb3JtRGlyZWN0aXZlc30gZnJvbSAnYW5ndWxhcjIvYW5ndWxhcjInO1xuaW1wb3J0IHsgQ2xpZW50IH0gZnJvbSAnc3JjL3NlcnZpY2VzL2FwaSc7XG5pbXBvcnQgeyBNYXRlcmlhbCB9IGZyb20gJ3NyYy9kaXJlY3RpdmVzL21hdGVyaWFsJztcbmltcG9ydCB7IFJlbWluZCB9IGZyb20gJy4vcmVtaW5kJztcblxuQENvbXBvbmVudCh7XG4gIHNlbGVjdG9yOiAnbWluZHMtYWN0aXZpdHknLFxuICB2aWV3SW5qZWN0b3I6IFsgQ2xpZW50IF0sXG4gIHByb3BlcnRpZXM6IFsnb2JqZWN0J11cbn0pXG5AVmlldyh7XG4gIHRlbXBsYXRlVXJsOiAndGVtcGxhdGVzL2VudGl0aWVzL2FjdGl2aXR5Lmh0bWwnLFxuICBkaXJlY3RpdmVzOiBbIE5nRm9yLCBOZ0lmLCBNYXRlcmlhbCwgUmVtaW5kXVxufSlcblxuZXhwb3J0IGNsYXNzIEFjdGl2aXR5IHtcbiAgYWN0aXZpdHkgOiBhbnk7XG5cblx0Y29uc3RydWN0b3IocHVibGljIGNsaWVudDogQ2xpZW50KXtcblx0fVxuXG4gIHNldCBvYmplY3QodmFsdWU6IGFueSkge1xuICAgIHRoaXMuYWN0aXZpdHkgPSB2YWx1ZTtcbiAgfVxuXG5cdC8qKlxuXHQgKiBBIHRlbXBvcmFyeSBoYWNrLCBiZWNhdXNlIHBpcGVzIGRvbid0IHNlZW0gdG8gd29ya1xuXHQgKi9cblx0dG9EYXRlKHRpbWVzdGFtcCl7XG5cdFx0cmV0dXJuIG5ldyBEYXRlKHRpbWVzdGFtcCoxMDAwKTtcblx0fVxuXG4gIHRodW1ic1VwKCl7XG4gICAgY29uc29sZS5sb2coJ3lvdSBoaXQgdGhlIHRodW1ic3VwIGZvciAnICsgdGhpcy5hY3Rpdml0eS5ndWlkKTtcbiAgfVxuXG4gIHJlbWluZCgpe1xuICAgIGxldCBzZWxmID0gdGhpcztcbiAgICB0aGlzLmNsaWVudC5wb3N0KCdhcGkvdjEvbmV3c2ZlZWQvcmVtaW5kLycgKyB0aGlzLmFjdGl2aXR5Lmd1aWQsIHt9KVxuICAgICAgICAgIC50aGVuKChkYXRhKT0+IHtcbiAgICAgICAgICAgICAgYWxlcnQoJ3JlbWluZGVkJyk7XG4gICAgICAgICAgfSk7XG4gIH1cbn1cbiJdLCJzb3VyY2VSb290IjoiL3NvdXJjZS8ifQ==