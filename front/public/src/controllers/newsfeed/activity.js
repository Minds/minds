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
var session_1 = require('src/services/session');
var material_1 = require('src/directives/material');
var remind_1 = require('./remind');
var Activity = (function () {
    function Activity(client) {
        this.client = client;
        this.session = session_1.SessionFactory.build();
    }
    Object.defineProperty(Activity.prototype, "object", {
        set: function (value) {
            this.activity = value;
            if (!this.activity['thumbs:up:user_guids'])
                this.activity['thumbs:up:user_guids'] = [];
            if (!this.activity['thumbs:down:user_guids'])
                this.activity['thumbs:down:user_guids'] = [];
        },
        enumerable: true,
        configurable: true
    });
    Activity.prototype.toDate = function (timestamp) {
        return new Date(timestamp * 1000);
    };
    Activity.prototype.thumbsUp = function () {
        this.client.put('api/v1/thumbs/' + this.activity.guid + '/up', {});
        if (!this.hasThumbedUp()) {
            this.activity['thumbs:up:user_guids'].push(this.session.getLoggedInUser().guid);
        }
        else {
            for (var key in this.activity['thumbs:up:user_guids']) {
                if (this.activity['thumbs:up:user_guids'][key] == this.session.getLoggedInUser().guid)
                    delete this.activity['thumbs:up:user_guids'][key];
            }
        }
    };
    Activity.prototype.thumbsDown = function () {
        this.client.put('api/v1/thumbs/' + this.activity.guid + '/down', {});
        if (!this.hasThumbedDown()) {
            this.activity['thumbs:down:user_guids'].push(this.session.getLoggedInUser().guid);
        }
        else {
            for (var key in this.activity['thumbs:down:user_guids']) {
                if (this.activity['thumbs:down:user_guids'][key] == this.session.getLoggedInUser().guid)
                    delete this.activity['thumbs:down:user_guids'][key];
            }
        }
    };
    Activity.prototype.remind = function () {
        var self = this;
        this.client.post('api/v1/newsfeed/remind/' + this.activity.guid, {})
            .then(function (data) {
            alert('reminded');
        });
    };
    Activity.prototype.hasThumbedUp = function () {
        if (this.activity['thumbs:up:user_guids'].indexOf(this.session.getLoggedInUser().guid) > -1)
            return true;
        return false;
    };
    Activity.prototype.hasThumbedDown = function () {
        if (this.activity['thumbs:down:user_guids'].indexOf(this.session.getLoggedInUser().guid) > -1)
            return true;
        return false;
    };
    Activity.prototype.hasReminded = function () {
        return false;
    };
    Activity = __decorate([
        angular2_1.Component({
            selector: 'minds-activity',
            viewInjector: [api_1.Client],
            properties: ['object']
        }),
        angular2_1.View({
            templateUrl: 'templates/entities/activity.html',
            directives: [angular2_1.NgFor, angular2_1.NgIf, angular2_1.CSSClass, material_1.Material, remind_1.Remind, router_1.RouterLink]
        }), 
        __metadata('design:paramtypes', [api_1.Client])
    ], Activity);
    return Activity;
})();
exports.Activity = Activity;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9jb250cm9sbGVycy9uZXdzZmVlZC9hY3Rpdml0eS50cyJdLCJuYW1lcyI6WyJBY3Rpdml0eSIsIkFjdGl2aXR5LmNvbnN0cnVjdG9yIiwiQWN0aXZpdHkub2JqZWN0IiwiQWN0aXZpdHkudG9EYXRlIiwiQWN0aXZpdHkudGh1bWJzVXAiLCJBY3Rpdml0eS50aHVtYnNEb3duIiwiQWN0aXZpdHkucmVtaW5kIiwiQWN0aXZpdHkuaGFzVGh1bWJlZFVwIiwiQWN0aXZpdHkuaGFzVGh1bWJlZERvd24iLCJBY3Rpdml0eS5oYXNSZW1pbmRlZCJdLCJtYXBwaW5ncyI6Ijs7Ozs7Ozs7Ozs7QUFBQSx5QkFBa0YsbUJBQW1CLENBQUMsQ0FBQTtBQUN0Ryx1QkFBMkIsaUJBQWlCLENBQUMsQ0FBQTtBQUM3QyxvQkFBdUIsa0JBQWtCLENBQUMsQ0FBQTtBQUMxQyx3QkFBK0Isc0JBQXNCLENBQUMsQ0FBQTtBQUN0RCx5QkFBeUIseUJBQXlCLENBQUMsQ0FBQTtBQUNuRCx1QkFBdUIsVUFBVSxDQUFDLENBQUE7QUFFbEM7SUFjQ0Esa0JBQW1CQSxNQUFjQTtRQUFkQyxXQUFNQSxHQUFOQSxNQUFNQSxDQUFRQTtRQUZoQ0EsWUFBT0EsR0FBR0Esd0JBQWNBLENBQUNBLEtBQUtBLEVBQUVBLENBQUNBO0lBR2xDQSxDQUFDQTtJQUVBRCxzQkFBSUEsNEJBQU1BO2FBQVZBLFVBQVdBLEtBQVVBO1lBQ25CRSxJQUFJQSxDQUFDQSxRQUFRQSxHQUFHQSxLQUFLQSxDQUFDQTtZQUN0QkEsRUFBRUEsQ0FBQUEsQ0FBQ0EsQ0FBQ0EsSUFBSUEsQ0FBQ0EsUUFBUUEsQ0FBQ0Esc0JBQXNCQSxDQUFDQSxDQUFDQTtnQkFDeENBLElBQUlBLENBQUNBLFFBQVFBLENBQUNBLHNCQUFzQkEsQ0FBQ0EsR0FBR0EsRUFBRUEsQ0FBQ0E7WUFDN0NBLEVBQUVBLENBQUFBLENBQUNBLENBQUNBLElBQUlBLENBQUNBLFFBQVFBLENBQUNBLHdCQUF3QkEsQ0FBQ0EsQ0FBQ0E7Z0JBQzFDQSxJ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QUlBLEVBQUVBLG1CQUFRQSxFQUFFQSxtQkFBUUEsRUFBRUEsZUFBTUEsRUFBRUEsbUJBQVVBLENBQUNBO1NBQ25FQSxDQUFDQTs7aUJBMEVEQTtJQUFEQSxlQUFDQTtBQUFEQSxDQWxGQSxJQWtGQztBQXhFWSxnQkFBUSxXQXdFcEIsQ0FBQSIsImZpbGUiOiJzcmMvY29udHJvbGxlcnMvbmV3c2ZlZWQvYWN0aXZpdHkuanMiLCJzb3VyY2VzQ29udGVudCI6WyJpbXBvcnQgeyBDb21wb25lbnQsIFZpZXcsIE5nRm9yLCBOZ0lmLCBDU1NDbGFzcywgT2JzZXJ2YWJsZSwgZm9ybURpcmVjdGl2ZXN9IGZyb20gJ2FuZ3VsYXIyL2FuZ3VsYXIyJztcbmltcG9ydCB7IFJvdXRlckxpbmsgfSBmcm9tIFwiYW5ndWxhcjIvcm91dGVyXCI7XG5pbXBvcnQgeyBDbGllbnQgfSBmcm9tICdzcmMvc2VydmljZXMvYXBpJztcbmltcG9ydCB7IFNlc3Npb25GYWN0b3J5IH0gZnJvbSAnc3JjL3NlcnZpY2VzL3Nlc3Npb24nO1xuaW1wb3J0IHsgTWF0ZXJpYWwgfSBmcm9tICdzcmMvZGlyZWN0aXZlcy9tYXRlcmlhbCc7XG5pbXBvcnQgeyBSZW1pbmQgfSBmcm9tICcuL3JlbWluZCc7XG5cbkBDb21wb25lbnQoe1xuICBzZWxlY3RvcjogJ21pbmRzLWFjdGl2aXR5JyxcbiAgdmlld0luamVjdG9yOiBbIENsaWVudCBdLFxuICBwcm9wZXJ0aWVzOiBbJ29iamVjdCddXG59KVxuQFZpZXcoe1xuICB0ZW1wbGF0ZVVybDogJ3RlbXBsYXRlcy9lbnRpdGllcy9hY3Rpdml0eS5odG1sJyxcbiAgZGlyZWN0aXZlczogWyBOZ0ZvciwgTmdJZiwgQ1NTQ2xhc3MsIE1hdGVyaWFsLCBSZW1pbmQsIFJvdXRlckxpbmtdXG59KVxuXG5leHBvcnQgY2xhc3MgQWN0aXZpdHkge1xuICBhY3Rpdml0eSA6IGFueTtcbiAgc2Vzc2lvbiA9IFNlc3Npb25GYWN0b3J5LmJ1aWxkKCk7XG5cblx0Y29uc3RydWN0b3IocHVibGljIGNsaWVudDogQ2xpZW50KXtcblx0fVxuXG4gIHNldCBvYmplY3QodmFsdWU6IGFueSkge1xuICAgIHRoaXMuYWN0aXZpdHkgPSB2YWx1ZTtcbiAgICBpZighdGhpcy5hY3Rpdml0eVsndGh1bWJzOnVwOnVzZXJfZ3VpZHMnXSlcbiAgICAgIHRoaXMuYWN0aXZpdHlbJ3RodW1iczp1cDp1c2VyX2d1aWRzJ10gPSBbXTtcbiAgICBpZighdGhpcy5hY3Rpdml0eVsndGh1bWJzOmRvd246dXNlcl9ndWlkcyddKVxuICAgICAgdGhpcy5hY3Rpdml0eVsndGh1bWJzOmRvd246dXNlcl9ndWlkcyddID0gW107XG4gIH1cblxuXHQvKipcblx0ICogQSB0ZW1wb3JhcnkgaGFjaywgYmVjYXVzZSBwaXBlcyBkb24ndCBzZWVtIHRvIHdvcmtcblx0ICovXG5cdHRvRGF0ZSh0aW1lc3RhbXApe1xuXHRcdHJldHVybiBuZXcgRGF0ZSh0aW1lc3RhbXAqMTAwMCk7XG5cdH1cblxuICB0aHVtYnNVcCgpe1xuICAgIHRoaXMuY2xpZW50LnB1dCgnYXBpL3YxL3RodW1icy8nICsgdGhpcy5hY3Rpdml0eS5ndWlkICsgJy91cCcsIHt9KTtcbiAgICBpZighdGhpcy5oYXNUaHVtYmVkVXAoKSl7XG4gICAgICB0aGlzLmFjdGl2aXR5Wyd0aHVtYnM6dXA6dXNlcl9ndWlkcyddLnB1c2godGhpcy5zZXNzaW9uLmdldExvZ2dlZEluVXNlcigpLmd1aWQpO1xuICAgIH0gZWxzZSB7XG4gICAgICBmb3IobGV0IGtleSBpbiB0aGlzLmFjdGl2aXR5Wyd0aHVtYnM6dXA6dXNlcl9ndWlkcyddKXtcbiAgICAgICAgaWYodGhpcy5hY3Rpdml0eVsndGh1bWJzOnVwOnVzZXJfZ3VpZHMnXVtrZXldID09IHRoaXMuc2Vzc2lvbi5nZXRMb2dnZWRJblVzZXIoKS5ndWlkKVxuICAgICAgICAgIGRlbGV0ZSB0aGlzLmFjdGl2aXR5Wyd0aHVtYnM6dXA6dXNlcl9ndWlkcyddW2tleV07XG4gICAgICB9XG4gICAgfVxuICB9XG5cbiAgdGh1bWJzRG93bigpe1xuICAgIHRoaXMuY2xpZW50LnB1dCgnYXBpL3YxL3RodW1icy8nICsgdGhpcy5hY3Rpdml0eS5ndWlkICsgJy9kb3duJywge30pO1xuICAgIGlmKCF0aGlzLmhhc1RodW1iZWREb3duKCkpe1xuICAgICAgdGhpcy5hY3Rpdml0eVsndGh1bWJzOmRvd246dXNlcl9ndWlkcyddLnB1c2godGhpcy5zZXNzaW9uLmdldExvZ2dlZEluVXNlcigpLmd1aWQpO1xuICAgIH0gZWxzZSB7XG4gICAgICBmb3IobGV0IGtleSBpbiB0aGlzLmFjdGl2aXR5Wyd0aHVtYnM6ZG93bjp1c2VyX2d1aWRzJ10pe1xuICAgICAgICBpZih0aGlzLmFjdGl2aXR5Wyd0aHVtYnM6ZG93bjp1c2VyX2d1aWRzJ11ba2V5XSA9PSB0aGlzLnNlc3Npb24uZ2V0TG9nZ2VkSW5Vc2VyKCkuZ3VpZClcbiAgICAgICAgICBkZWxldGUgdGhpcy5hY3Rpdml0eVsndGh1bWJzOmRvd246dXNlcl9ndWlkcyddW2tleV07XG4gICAgICB9XG4gICAgfVxuICB9XG5cbiAgcmVtaW5kKCl7XG4gICAgbGV0IHNlbGYgPSB0aGlzO1xuICAgIHRoaXMuY2xpZW50LnBvc3QoJ2FwaS92MS9uZXdzZmVlZC9yZW1pbmQvJyArIHRoaXMuYWN0aXZpdHkuZ3VpZCwge30pXG4gICAgICAgICAgLnRoZW4oKGRhdGEpPT4ge1xuICAgICAgICAgICAgICBhbGVydCgncmVtaW5kZWQnKTtcbiAgICAgICAgICB9KTtcbiAgfVxuXG4gIC8qKlxuICAgKiBIYXMgdGh1bWJlZCB1cFxuICAgKi9cbiAgaGFzVGh1bWJlZFVwKCl7XG4gICAgaWYodGhpcy5hY3Rpdml0eVsndGh1bWJzOnVwOnVzZXJfZ3VpZHMnXS5pbmRleE9mKHRoaXMuc2Vzc2lvbi5nZXRMb2dnZWRJblVzZXIoKS5ndWlkKSA+IC0xKVxuICAgICAgcmV0dXJuIHRydWU7XG4gICAgcmV0dXJuIGZhbHNlO1xuICB9XG5cbiAgaGFzVGh1bWJlZERvd24oKXtcbiAgICBpZih0aGlzLmFjdGl2aXR5Wyd0aHVtYnM6ZG93bjp1c2VyX2d1aWRzJ10uaW5kZXhPZih0aGlzLnNlc3Npb24uZ2V0TG9nZ2VkSW5Vc2VyKCkuZ3VpZCkgPiAtMSlcbiAgICAgIHJldHVybiB0cnVlO1xuICAgIHJldHVybiBmYWxzZTtcbiAgfVxuXG4gIGhhc1JlbWluZGVkKCl7XG4gICAgcmV0dXJuIGZhbHNlO1xuICB9XG59XG4iXSwic291cmNlUm9vdCI6Ii9zb3VyY2UvIn0=