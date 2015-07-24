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
            viewInjector: [api_1.Client],
            properties: ['object']
        }),
        angular2_1.View({
            templateUrl: 'templates/cards/object/video.html',
            directives: [angular2_1.NgFor, angular2_1.NgIf, angular2_1.CSSClass, material_1.Material, router_1.RouterLink]
        }), 
        __metadata('design:paramtypes', [api_1.Client])
    ], VideoCard);
    return VideoCard;
})();
exports.VideoCard = VideoCard;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9jb250cm9sbGVycy9jYXJkcy9vYmplY3QvdmlkZW8udHMiXSwibmFtZXMiOlsiVmlkZW9DYXJkIiwiVmlkZW9DYXJkLmNvbnN0cnVjdG9yIiwiVmlkZW9DYXJkLm9iamVjdCJdLCJtYXBwaW5ncyI6Ijs7Ozs7Ozs7Ozs7QUFBQSx5QkFBa0YsbUJBQW1CLENBQUMsQ0FBQTtBQUN0Ryx1QkFBMkIsaUJBQWlCLENBQUMsQ0FBQTtBQUM3QyxvQkFBdUIsa0JBQWtCLENBQUMsQ0FBQTtBQUMxQyx3QkFBK0Isc0JBQXNCLENBQUMsQ0FBQTtBQUN0RCx5QkFBeUIseUJBQXlCLENBQUMsQ0FBQTtBQUVuRDtJQWVDQSxtQkFBbUJBLE1BQWNBO1FBQWRDLFdBQU1BLEdBQU5BLE1BQU1BLENBQVFBO1FBSGhDQSxZQUFPQSxHQUFHQSx3QkFBY0EsQ0FBQ0EsS0FBS0EsRUFBRUEsQ0FBQ0E7UUFJL0JBLElBQUlBLENBQUNBLEtBQUtBLEdBQUdBLE1BQU1BLENBQUNBLEtBQUtBLENBQUNBO0lBQzdCQSxDQUFDQTtJQUVBRCxzQkFBSUEsNkJBQU1BO2FBQVZBLFVBQVdBLEtBQVVBO1lBQ25CRSxJQUFJQSxDQUFDQSxNQUFNQSxHQUFHQSxLQUFLQSxDQUFDQTtRQUN0QkEsQ0FBQ0E7OztPQUFBRjtJQXJCSEE7UUFBQ0Esb0JBQVNBLENBQUNBO1lBQ1RBLFFBQVFBLEVBQUVBLGtCQUFrQkE7WUFDNUJBLFlBQVlBLEVBQUVBLENBQUVBLFlBQU1BLENBQUVBO1lBQ3hCQSxVQUFVQSxFQUFFQSxDQUFDQSxRQUFRQSxDQUFDQTtTQUN2QkEsQ0FBQ0E7UUFDREEsZUFBSUEsQ0FBQ0E7WUFDSkEsV0FBV0EsRUFBRUEsbUNBQW1DQTtZQUNoREEsVUFBVUEsRUFBRUEsQ0FBRUEsZ0JBQUtBLEVBQUVBLGVBQUlBLEVBQUVBLG1CQUFRQSxFQUFFQSxtQkFBUUEsRUFBRUEsbUJBQVVBLENBQUNBO1NBQzNEQSxDQUFDQTs7a0JBZURBO0lBQURBLGdCQUFDQTtBQUFEQSxDQXZCQSxBQXVCQ0EsSUFBQTtBQWJZLGlCQUFTLFlBYXJCLENBQUEiLCJmaWxlIjoic3JjL2NvbnRyb2xsZXJzL2NhcmRzL29iamVjdC92aWRlby5qcyIsInNvdXJjZXNDb250ZW50IjpbImltcG9ydCB7IENvbXBvbmVudCwgVmlldywgTmdGb3IsIE5nSWYsIENTU0NsYXNzLCBPYnNlcnZhYmxlLCBmb3JtRGlyZWN0aXZlc30gZnJvbSAnYW5ndWxhcjIvYW5ndWxhcjInO1xuaW1wb3J0IHsgUm91dGVyTGluayB9IGZyb20gXCJhbmd1bGFyMi9yb3V0ZXJcIjtcbmltcG9ydCB7IENsaWVudCB9IGZyb20gJ3NyYy9zZXJ2aWNlcy9hcGknO1xuaW1wb3J0IHsgU2Vzc2lvbkZhY3RvcnkgfSBmcm9tICdzcmMvc2VydmljZXMvc2Vzc2lvbic7XG5pbXBvcnQgeyBNYXRlcmlhbCB9IGZyb20gJ3NyYy9kaXJlY3RpdmVzL21hdGVyaWFsJztcblxuQENvbXBvbmVudCh7XG4gIHNlbGVjdG9yOiAnbWluZHMtY2FyZC12aWRlbycsXG4gIHZpZXdJbmplY3RvcjogWyBDbGllbnQgXSxcbiAgcHJvcGVydGllczogWydvYmplY3QnXVxufSlcbkBWaWV3KHtcbiAgdGVtcGxhdGVVcmw6ICd0ZW1wbGF0ZXMvY2FyZHMvb2JqZWN0L3ZpZGVvLmh0bWwnLFxuICBkaXJlY3RpdmVzOiBbIE5nRm9yLCBOZ0lmLCBDU1NDbGFzcywgTWF0ZXJpYWwsIFJvdXRlckxpbmtdXG59KVxuXG5leHBvcnQgY2xhc3MgVmlkZW9DYXJkIHtcbiAgZW50aXR5IDogYW55O1xuICBzZXNzaW9uID0gU2Vzc2lvbkZhY3RvcnkuYnVpbGQoKTtcbiAgbWluZHM6IHt9O1xuXG5cdGNvbnN0cnVjdG9yKHB1YmxpYyBjbGllbnQ6IENsaWVudCl7XG4gICAgdGhpcy5taW5kcyA9IHdpbmRvdy5NaW5kcztcblx0fVxuXG4gIHNldCBvYmplY3QodmFsdWU6IGFueSkge1xuICAgIHRoaXMuZW50aXR5ID0gdmFsdWU7XG4gIH1cblxufVxuIl0sInNvdXJjZVJvb3QiOiIvc291cmNlLyJ9