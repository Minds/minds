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
var remind_1 = require('./remind');
var Activity = (function () {
    function Activity(client) {
        this.client = client;
        this.menuToggle = false;
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
    Activity.prototype.delete = function () {
        this.client.delete('api/v1/newsfeed/' + this.activity.guid);
        delete this.activity;
    };
    Activity.prototype.openMenu = function () {
        this.menuToggle = !this.menuToggle;
        console.log(this.menuToggle);
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
        });
    };
    Activity.prototype.hasThumbedUp = function () {
        for (var _i = 0, _a = this.activity['thumbs:up:user_guids']; _i < _a.length; _i++) {
            var guid = _a[_i];
            if (guid == this.session.getLoggedInUser().guid)
                return true;
        }
        return false;
    };
    Activity.prototype.hasThumbedDown = function () {
        for (var _i = 0, _a = this.activity['thumbs:down:user_guids']; _i < _a.length; _i++) {
            var guid = _a[_i];
            if (guid == this.session.getLoggedInUser().guid)
                return true;
        }
        return false;
    };
    Activity.prototype.hasReminded = function () {
        return false;
    };
    Activity = __decorate([
        angular2_1.Component({
            selector: 'minds-activity',
            viewBindings: [api_1.Client],
            properties: ['object']
        }),
        angular2_1.View({
            templateUrl: 'templates/cards/activity.html',
            directives: [angular2_1.NgFor, angular2_1.NgIf, angular2_1.NgClass, material_1.Material, remind_1.Remind, router_1.RouterLink]
        }), 
        __metadata('design:paramtypes', [api_1.Client])
    ], Activity);
    return Activity;
})();
exports.Activity = Activity;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9jb250cm9sbGVycy9uZXdzZmVlZC9hY3Rpdml0eS50cyJdLCJuYW1lcyI6WyJBY3Rpdml0eSIsIkFjdGl2aXR5LmNvbnN0cnVjdG9yIiwiQWN0aXZpdHkub2JqZWN0IiwiQWN0aXZpdHkuZGVsZXRlIiwiQWN0aXZpdHkub3Blbk1lbnUiLCJBY3Rpdml0eS50aHVtYnNVcCIsIkFjdGl2aXR5LnRodW1ic0Rvd24iLCJBY3Rpdml0eS5yZW1pbmQiLCJBY3Rpdml0eS5oYXNUaHVtYmVkVXAiLCJBY3Rpdml0eS5oYXNUaHVtYmVkRG93biIsIkFjdGl2aXR5Lmhhc1JlbWluZGVkIl0sIm1hcHBpbmdzIjoiOzs7Ozs7Ozs7OztBQUFBLHlCQUFpRSxtQkFBbUIsQ0FBQyxDQUFBO0FBQ3JGLHVCQUEyQixpQkFBaUIsQ0FBQyxDQUFBO0FBQzdDLG9CQUF1QixrQkFBa0IsQ0FBQyxDQUFBO0FBQzFDLHdCQUErQixzQkFBc0IsQ0FBQyxDQUFBO0FBQ3RELHlCQUF5Qix5QkFBeUIsQ0FBQyxDQUFBO0FBQ25ELHVCQUF1QixVQUFVLENBQUMsQ0FBQTtBQUVsQztJQWdCQ0Esa0JBQW1CQSxNQUFjQTtRQUFkQyxXQUFNQSxHQUFOQSxNQUFNQSxDQUFRQTtRQUhoQ0EsZUFBVUEsR0FBYUEsS0FBS0EsQ0FBQ0E7UUFDN0JBLFlBQU9BLEdBQUdBLHdCQUFjQSxDQUFDQSxLQUFLQSxFQUFFQSxDQUFDQTtJQUdsQ0EsQ0FBQ0E7SUFFQUQsc0JBQUlBLDRCQUFNQTthQUFWQSxVQUFXQSxLQUFVQTtZQUNuQkUsSUFBSUEsQ0FBQ0EsUUFBUUEsR0FBR0EsS0FBS0EsQ0FBQ0E7WUFDdEJBLEVBQUVBLENBQUFBLENBQUNBLENBQUNBLElBQUlBLENBQUNBLFFBQVFBLENBQUNBLHNCQUFzQkEsQ0FBQ0EsQ0FBQ0E7Z0JBQ3hDQSxJQUFJQSxDQUFDQSxRQUFRQSxDQUFDQSxzQkFBc0JBLENBQUNBLEdBQUdBLEVBQUVBLENBQUNBO1lBQzdDQSxFQUFFQSxDQUFBQSxDQUFDQSxDQUFDQSxJ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QUFDQSxJQUFJQSxDQUFDQTtnQkFDN0NBLE1BQU1BLENBQUNBLElBQUlBLENBQUNBO1NBQ2ZBO1FBQ0RBLE1BQU1BLENBQUNBLEtBQUtBLENBQUNBO0lBQ2ZBLENBQUNBO0lBRURULDhCQUFXQSxHQUFYQTtRQUNFVSxNQUFNQSxDQUFDQSxLQUFLQSxDQUFDQTtJQUNmQSxDQUFDQTtJQTFGSFY7UUFBQ0Esb0JBQVNBLENBQUNBO1lBQ1RBLFFBQVFBLEVBQUVBLGdCQUFnQkE7WUFDMUJBLFlBQVlBLEVBQUVBLENBQUVBLFlBQU1BLENBQUVBO1lBQ3hCQSxVQUFVQSxFQUFFQSxDQUFDQSxRQUFRQSxDQUFDQTtTQUN2QkEsQ0FBQ0E7UUFDREEsZUFBSUEsQ0FBQ0E7WUFDSkEsV0FBV0EsRUFBRUEsK0JBQStCQTtZQUM1Q0EsVUFBVUEsRUFBRUEsQ0FBRUEsZ0JBQUtBLEVBQUVBLGVBQUlBLEVBQUVBLGtCQUFPQSxFQUFFQSxtQkFBUUEsRUFBRUEsZUFBTUEsRUFBRUEsbUJBQVVBLENBQUNBO1NBQ2xFQSxDQUFDQTs7aUJBbUZEQTtJQUFEQSxlQUFDQTtBQUFEQSxDQTNGQSxBQTJGQ0EsSUFBQTtBQWpGWSxnQkFBUSxXQWlGcEIsQ0FBQSIsImZpbGUiOiJzcmMvY29udHJvbGxlcnMvbmV3c2ZlZWQvYWN0aXZpdHkuanMiLCJzb3VyY2VzQ29udGVudCI6WyJpbXBvcnQgeyBDb21wb25lbnQsIFZpZXcsIE5nRm9yLCBOZ0lmLCBOZ0NsYXNzLCBPYnNlcnZhYmxlfSBmcm9tICdhbmd1bGFyMi9hbmd1bGFyMic7XG5pbXBvcnQgeyBSb3V0ZXJMaW5rIH0gZnJvbSBcImFuZ3VsYXIyL3JvdXRlclwiO1xuaW1wb3J0IHsgQ2xpZW50IH0gZnJvbSAnc3JjL3NlcnZpY2VzL2FwaSc7XG5pbXBvcnQgeyBTZXNzaW9uRmFjdG9yeSB9IGZyb20gJ3NyYy9zZXJ2aWNlcy9zZXNzaW9uJztcbmltcG9ydCB7IE1hdGVyaWFsIH0gZnJvbSAnc3JjL2RpcmVjdGl2ZXMvbWF0ZXJpYWwnO1xuaW1wb3J0IHsgUmVtaW5kIH0gZnJvbSAnLi9yZW1pbmQnO1xuXG5AQ29tcG9uZW50KHtcbiAgc2VsZWN0b3I6ICdtaW5kcy1hY3Rpdml0eScsXG4gIHZpZXdCaW5kaW5nczogWyBDbGllbnQgXSxcbiAgcHJvcGVydGllczogWydvYmplY3QnXVxufSlcbkBWaWV3KHtcbiAgdGVtcGxhdGVVcmw6ICd0ZW1wbGF0ZXMvY2FyZHMvYWN0aXZpdHkuaHRtbCcsXG4gIGRpcmVjdGl2ZXM6IFsgTmdGb3IsIE5nSWYsIE5nQ2xhc3MsIE1hdGVyaWFsLCBSZW1pbmQsIFJvdXRlckxpbmtdXG59KVxuXG5leHBvcnQgY2xhc3MgQWN0aXZpdHkge1xuXG4gIGFjdGl2aXR5IDogYW55O1xuICBtZW51VG9nZ2xlIDogYm9vbGVhbiA9IGZhbHNlO1xuICBzZXNzaW9uID0gU2Vzc2lvbkZhY3RvcnkuYnVpbGQoKTtcblxuXHRjb25zdHJ1Y3RvcihwdWJsaWMgY2xpZW50OiBDbGllbnQpe1xuXHR9XG5cbiAgc2V0IG9iamVjdCh2YWx1ZTogYW55KSB7XG4gICAgdGhpcy5hY3Rpdml0eSA9IHZhbHVlO1xuICAgIGlmKCF0aGlzLmFjdGl2aXR5Wyd0aHVtYnM6dXA6dXNlcl9ndWlkcyddKVxuICAgICAgdGhpcy5hY3Rpdml0eVsndGh1bWJzOnVwOnVzZXJfZ3VpZHMnXSA9IFtdO1xuICAgIGlmKCF0aGlzLmFjdGl2aXR5Wyd0aHVtYnM6ZG93bjp1c2VyX2d1aWRzJ10pXG4gICAgICB0aGlzLmFjdGl2aXR5Wyd0aHVtYnM6ZG93bjp1c2VyX2d1aWRzJ10gPSBbXTtcbiAgfVxuXG4gIGRlbGV0ZSgpe1xuICAgIHRoaXMuY2xpZW50LmRlbGV0ZSgnYXBpL3YxL25ld3NmZWVkLycrdGhpcy5hY3Rpdml0eS5ndWlkKTtcbiAgICBkZWxldGUgdGhpcy5hY3Rpdml0eTtcbiAgfVxuXG4gIG9wZW5NZW51KCl7XG4gICAgdGhpcy5tZW51VG9nZ2xlID0gIXRoaXMubWVudVRvZ2dsZTtcbiAgICBjb25zb2xlLmxvZyh0aGlzLm1lbnVUb2dnbGUpO1xuICB9XG5cbiAgdGh1bWJzVXAoKXtcbiAgICB0aGlzLmNsaWVudC5wdXQoJ2FwaS92MS90aHVtYnMvJyArIHRoaXMuYWN0aXZpdHkuZ3VpZCArICcvdXAnLCB7fSk7XG4gICAgaWYoIXRoaXMuaGFzVGh1bWJlZFVwKCkpe1xuICAgICAgdGhpcy5hY3Rpdml0eVsndGh1bWJzOnVwOnVzZXJfZ3VpZHMnXS5wdXNoKHRoaXMuc2Vzc2lvbi5nZXRMb2dnZWRJblVzZXIoKS5ndWlkKTtcbiAgICB9IGVsc2Uge1xuICAgICAgZm9yKGxldCBrZXkgaW4gdGhpcy5hY3Rpdml0eVsndGh1bWJzOnVwOnVzZXJfZ3VpZHMnXSl7XG4gICAgICAgIGlmKHRoaXMuYWN0aXZpdHlbJ3RodW1iczp1cDp1c2VyX2d1aWRzJ11ba2V5XSA9PSB0aGlzLnNlc3Npb24uZ2V0TG9nZ2VkSW5Vc2VyKCkuZ3VpZClcbiAgICAgICAgICBkZWxldGUgdGhpcy5hY3Rpdml0eVsndGh1bWJzOnVwOnVzZXJfZ3VpZHMnXVtrZXldO1xuICAgICAgfVxuICAgIH1cbiAgfVxuXG4gIHRodW1ic0Rvd24oKXtcbiAgICB0aGlzLmNsaWVudC5wdXQoJ2FwaS92MS90aHVtYnMvJyArIHRoaXMuYWN0aXZpdHkuZ3VpZCArICcvZG93bicsIHt9KTtcbiAgICBpZighdGhpcy5oYXNUaHVtYmVkRG93bigpKXtcbiAgICAgIHRoaXMuYWN0aXZpdHlbJ3RodW1iczpkb3duOnVzZXJfZ3VpZHMnXS5wdXNoKHRoaXMuc2Vzc2lvbi5nZXRMb2dnZWRJblVzZXIoKS5ndWlkKTtcbiAgICB9IGVsc2Uge1xuICAgICAgZm9yKGxldCBrZXkgaW4gdGhpcy5hY3Rpdml0eVsndGh1bWJzOmRvd246dXNlcl9ndWlkcyddKXtcbiAgICAgICAgaWYodGhpcy5hY3Rpdml0eVsndGh1bWJzOmRvd246dXNlcl9ndWlkcyddW2tleV0gPT0gdGhpcy5zZXNzaW9uLmdldExvZ2dlZEluVXNlcigpLmd1aWQpXG4gICAgICAgICAgZGVsZXRlIHRoaXMuYWN0aXZpdHlbJ3RodW1iczpkb3duOnVzZXJfZ3VpZHMnXVtrZXldO1xuICAgICAgfVxuICAgIH1cbiAgfVxuXG4gIHJlbWluZCgpe1xuICAgIGxldCBzZWxmID0gdGhpcztcbiAgICB0aGlzLmNsaWVudC5wb3N0KCdhcGkvdjEvbmV3c2ZlZWQvcmVtaW5kLycgKyB0aGlzLmFjdGl2aXR5Lmd1aWQsIHt9KVxuICAgICAgICAgIC50aGVuKChkYXRhKT0+IHtcblxuICAgICAgICAgIH0pO1xuICB9XG5cbiAgLyoqXG4gICAqIEhhcyB0aHVtYmVkIHVwXG4gICAqL1xuICBoYXNUaHVtYmVkVXAoKXtcbiAgICBmb3IodmFyIGd1aWQgb2YgdGhpcy5hY3Rpdml0eVsndGh1bWJzOnVwOnVzZXJfZ3VpZHMnXSl7XG4gICAgICBpZihndWlkID09IHRoaXMuc2Vzc2lvbi5nZXRMb2dnZWRJblVzZXIoKS5ndWlkKVxuICAgICAgICByZXR1cm4gdHJ1ZTtcbiAgICB9XG4gICAgcmV0dXJuIGZhbHNlO1xuICB9XG5cbiAgaGFzVGh1bWJlZERvd24oKXtcbiAgICBmb3IodmFyIGd1aWQgb2YgdGhpcy5hY3Rpdml0eVsndGh1bWJzOmRvd246dXNlcl9ndWlkcyddKXtcbiAgICAgIGlmKGd1aWQgPT0gdGhpcy5zZXNzaW9uLmdldExvZ2dlZEluVXNlcigpLmd1aWQpXG4gICAgICAgIHJldHVybiB0cnVlO1xuICAgIH1cbiAgICByZXR1cm4gZmFsc2U7XG4gIH1cblxuICBoYXNSZW1pbmRlZCgpe1xuICAgIHJldHVybiBmYWxzZTtcbiAgfVxufVxuIl0sInNvdXJjZVJvb3QiOiIvc291cmNlLyJ9