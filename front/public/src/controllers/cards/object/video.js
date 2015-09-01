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
var VideoCard = (function () {
    function VideoCard(client) {
        this.client = client;
        this.session = session_1.SessionFactory.build();
        this.minds = window.Minds;
    }
    Object.defineProperty(VideoCard.prototype, "object", {
        set: function (value) {
            this.entity = value;
        },
        enumerable: true,
        configurable: true
    });
    VideoCard = __decorate([
        angular2_1.Component({
            selector: 'minds-card-video',
            viewBindings: [api_1.Client],
            properties: ['object']
        }),
        angular2_1.View({
            templateUrl: 'templates/cards/object/video.html',
            directives: [angular2_1.NgFor, angular2_1.NgIf, angular2_1.NgClass, angular2_1.NgStyle, material_1.Material, router_1.RouterLink]
        }), 
        __metadata('design:paramtypes', [api_1.Client])
    ], VideoCard);
    return VideoCard;
})();
exports.VideoCard = VideoCard;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9jb250cm9sbGVycy9jYXJkcy9vYmplY3QvdmlkZW8udHMiXSwibmFtZXMiOlsiVmlkZW9DYXJkIiwiVmlkZW9DYXJkLmNvbnN0cnVjdG9yIiwiVmlkZW9DYXJkLm9iamVjdCJdLCJtYXBwaW5ncyI6Ijs7Ozs7Ozs7Ozs7QUFBQSx5QkFBMEUsbUJBQW1CLENBQUMsQ0FBQTtBQUM5Rix1QkFBMkIsaUJBQWlCLENBQUMsQ0FBQTtBQUM3QyxvQkFBdUIsa0JBQWtCLENBQUMsQ0FBQTtBQUMxQyx3QkFBK0Isc0JBQXNCLENBQUMsQ0FBQTtBQUN0RCx5QkFBeUIseUJBQXlCLENBQUMsQ0FBQTtBQUVuRDtJQWVDQSxtQkFBbUJBLE1BQWNBO1FBQWRDLFdBQU1BLEdBQU5BLE1BQU1BLENBQVFBO1FBSGhDQSxZQUFPQSxHQUFHQSx3QkFBY0EsQ0FBQ0EsS0FBS0EsRUFBRUEsQ0FBQ0E7UUFJL0JBLElBQUlBLENBQUNBLEtBQUtBLEdBQUdBLE1BQU1BLENBQUNBLEtBQUtBLENBQUNBO0lBQzdCQSxDQUFDQTtJQUVBRCxzQkFBSUEsNkJBQU1BO2FBQVZBLFVBQVdBLEtBQVVBO1lBQ25CRSxJQUFJQSxDQUFDQSxNQUFNQSxHQUFHQSxLQUFLQSxDQUFDQTtRQUN0QkEsQ0FBQ0E7OztPQUFBRjtJQXJCSEE7UUFBQ0Esb0JBQVNBLENBQUNBO1lBQ1RBLFFBQVFBLEVBQUVBLGtCQUFrQkE7WUFDNUJBLFlBQVlBLEVBQUVBLENBQUVBLFlBQU1BLENBQUVBO1lBQ3hCQSxVQUFVQSxFQUFFQSxDQUFDQSxRQUFRQSxDQUFDQTtTQUN2QkEsQ0FBQ0E7UUFDREEsZUFBSUEsQ0FBQ0E7WUFDSkEsV0FBV0EsRUFBRUEsbUNBQW1DQTtZQUNoREEsVUFBVUEsRUFBRUEsQ0FBRUEsZ0JBQUtBLEVBQUVBLGVBQUlBLEVBQUVBLGtCQUFPQSxFQUFFQSxrQkFBT0EsRUFBRUEsbUJBQVFBLEVBQUVBLG1CQUFVQSxDQUFDQTtTQUNuRUEsQ0FBQ0E7O2tCQWVEQTtJQUFEQSxnQkFBQ0E7QUFBREEsQ0F2QkEsQUF1QkNBLElBQUE7QUFiWSxpQkFBUyxZQWFyQixDQUFBIiwiZmlsZSI6InNyYy9jb250cm9sbGVycy9jYXJkcy9vYmplY3QvdmlkZW8uanMiLCJzb3VyY2VzQ29udGVudCI6WyJpbXBvcnQgeyBDb21wb25lbnQsIFZpZXcsIE5nRm9yLCBOZ0lmLCBOZ0NsYXNzLCBOZ1N0eWxlLCBPYnNlcnZhYmxlfSBmcm9tICdhbmd1bGFyMi9hbmd1bGFyMic7XG5pbXBvcnQgeyBSb3V0ZXJMaW5rIH0gZnJvbSBcImFuZ3VsYXIyL3JvdXRlclwiO1xuaW1wb3J0IHsgQ2xpZW50IH0gZnJvbSAnc3JjL3NlcnZpY2VzL2FwaSc7XG5pbXBvcnQgeyBTZXNzaW9uRmFjdG9yeSB9IGZyb20gJ3NyYy9zZXJ2aWNlcy9zZXNzaW9uJztcbmltcG9ydCB7IE1hdGVyaWFsIH0gZnJvbSAnc3JjL2RpcmVjdGl2ZXMvbWF0ZXJpYWwnO1xuXG5AQ29tcG9uZW50KHtcbiAgc2VsZWN0b3I6ICdtaW5kcy1jYXJkLXZpZGVvJyxcbiAgdmlld0JpbmRpbmdzOiBbIENsaWVudCBdLFxuICBwcm9wZXJ0aWVzOiBbJ29iamVjdCddXG59KVxuQFZpZXcoe1xuICB0ZW1wbGF0ZVVybDogJ3RlbXBsYXRlcy9jYXJkcy9vYmplY3QvdmlkZW8uaHRtbCcsXG4gIGRpcmVjdGl2ZXM6IFsgTmdGb3IsIE5nSWYsIE5nQ2xhc3MsIE5nU3R5bGUsIE1hdGVyaWFsLCBSb3V0ZXJMaW5rXVxufSlcblxuZXhwb3J0IGNsYXNzIFZpZGVvQ2FyZCB7XG4gIGVudGl0eSA6IGFueTtcbiAgc2Vzc2lvbiA9IFNlc3Npb25GYWN0b3J5LmJ1aWxkKCk7XG4gIG1pbmRzOiB7fTtcblxuXHRjb25zdHJ1Y3RvcihwdWJsaWMgY2xpZW50OiBDbGllbnQpe1xuICAgIHRoaXMubWluZHMgPSB3aW5kb3cuTWluZHM7XG5cdH1cblxuICBzZXQgb2JqZWN0KHZhbHVlOiBhbnkpIHtcbiAgICB0aGlzLmVudGl0eSA9IHZhbHVlO1xuICB9XG5cbn1cbiJdLCJzb3VyY2VSb290IjoiL3NvdXJjZS8ifQ==