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
var Newsfeed = (function () {
    function Newsfeed(client) {
        this.client = client;
        this.offset = "";
        this.load();
    }
    Newsfeed.prototype.load = function () {
        var self = this;
        this.client.get('api/v1/newsfeed', { limit: 12 }, { cache: true })
            .then(function (data) {
            self.newsfeed = data.activity;
            self.offset = data['load-next'];
        })
            .catch(function (e) {
            console.log(e);
        });
    };
    Newsfeed = __decorate([
        angular2_1.Component({
            selector: 'minds-newsfeed',
            viewInjector: [api_1.Client]
        }),
        angular2_1.View({
            templateUrl: 'templates/newsfeed/list.html',
            directives: [angular2_1.NgFor]
        }), 
        __metadata('design:paramtypes', [api_1.Client])
    ], Newsfeed);
    return Newsfeed;
})();
exports.Newsfeed = Newsfeed;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9jb250cm9sbGVycy9uZXdzZmVlZC50cyJdLCJuYW1lcyI6WyJOZXdzZmVlZCIsIk5ld3NmZWVkLmNvbnN0cnVjdG9yIiwiTmV3c2ZlZWQubG9hZCJdLCJtYXBwaW5ncyI6Ijs7Ozs7Ozs7Ozs7QUFBQSx5QkFBcUMsbUJBQW1CLENBQUMsQ0FBQTtBQUN6RCxvQkFBcUIsa0JBQWtCLENBQUMsQ0FBQTtBQUV4QztJQWNDQSxrQkFBbUJBLE1BQWNBO1FBQWRDLFdBQU1BLEdBQU5BLE1BQU1BLENBQVFBO1FBRmpDQSxXQUFNQSxHQUFZQSxFQUFFQSxDQUFDQTtRQUdwQkEsSUFBSUEsQ0FBQ0EsSUFBSUEsRUFBRUEsQ0FBQ0E7SUFDYkEsQ0FBQ0E7SUFLREQsdUJBQUlBLEdBQUpBO1FBQ0NFLElBQUlBLElBQUlBLEdBQUdBLElBQUlBLENBQUNBO1FBQ2hCQSxJQUFJQSxDQUFDQSxNQUFNQSxDQUFDQSxHQUFHQSxDQUFDQSxpQkFBaUJBLEVBQUVBLEVBQUNBLEtBQUtBLEVBQUNBLEVBQUVBLEVBQUNBLEVBQUVBLEVBQUNBLEtBQUtBLEVBQUVBLElBQUlBLEVBQUNBLENBQUNBO2FBQzFEQSxJQUFJQSxDQUFDQSxVQUFTQSxJQUFJQTtZQUNsQixJQUFJLENBQUMsUUFBUSxHQUFHLElBQUksQ0FBQyxRQUFRLENBQUE7WUFDN0IsSUFBSSxDQUFDLE1BQU0sR0FBRyxJQUFJLENBQUMsV0FBVyxDQUFDLENBQUM7UUFDakMsQ0FBQyxDQUFDQTthQUNEQSxLQUFLQSxDQUFDQSxVQUFTQSxDQUFDQTtZQUNoQixPQUFPLENBQUMsR0FBRyxDQUFDLENBQUMsQ0FBQyxDQUFDO1FBQ2hCLENBQUMsQ0FBQ0EsQ0FBQ0E7SUFDTkEsQ0FBQ0E7SUEvQkZGO1FBQUNBLG9CQUFTQSxDQUFDQTtZQUNUQSxRQUFRQSxFQUFFQSxnQkFBZ0JBO1lBQzFCQSxZQUFZQSxFQUFFQSxDQUFFQSxZQUFNQSxDQUFFQTtTQUN6QkEsQ0FBQ0E7UUFDREEsZUFBSUEsQ0FBQ0E7WUFDSkEsV0FBV0EsRUFBRUEsOEJBQThCQTtZQUMzQ0EsVUFBVUEsRUFBRUEsQ0FBRUEsZ0JBQUtBLENBQUVBO1NBQ3RCQSxDQUFDQTs7aUJBeUJEQTtJQUFEQSxlQUFDQTtBQUFEQSxDQWhDQSxJQWdDQztBQXZCWSxnQkFBUSxXQXVCcEIsQ0FBQSIsImZpbGUiOiJzcmMvY29udHJvbGxlcnMvbmV3c2ZlZWQuanMiLCJzb3VyY2VzQ29udGVudCI6WyJpbXBvcnQge0NvbXBvbmVudCwgVmlldywgTmdGb3J9IGZyb20gJ2FuZ3VsYXIyL2FuZ3VsYXIyJztcbmltcG9ydCB7Q2xpZW50fSBmcm9tICdzcmMvc2VydmljZXMvYXBpJztcblxuQENvbXBvbmVudCh7XG4gIHNlbGVjdG9yOiAnbWluZHMtbmV3c2ZlZWQnLFxuICB2aWV3SW5qZWN0b3I6IFsgQ2xpZW50IF1cbn0pXG5AVmlldyh7XG4gIHRlbXBsYXRlVXJsOiAndGVtcGxhdGVzL25ld3NmZWVkL2xpc3QuaHRtbCcsXG4gIGRpcmVjdGl2ZXM6IFsgTmdGb3IgXVxufSlcblxuZXhwb3J0IGNsYXNzIE5ld3NmZWVkIHtcblxuXHRuZXdzZmVlZCA6IEFycmF5O1xuXHRvZmZzZXQgOiBTdHJpbmcgPSBcIlwiO1xuXG5cdGNvbnN0cnVjdG9yKHB1YmxpYyBjbGllbnQ6IENsaWVudCl7XG5cdFx0dGhpcy5sb2FkKCk7XG5cdH1cblxuXHQvKipcblx0ICogTG9hZCBuZXdzZmVlZFxuXHQgKi9cblx0bG9hZCgpe1xuXHRcdHZhciBzZWxmID0gdGhpcztcblx0XHR0aGlzLmNsaWVudC5nZXQoJ2FwaS92MS9uZXdzZmVlZCcsIHtsaW1pdDoxMn0sIHtjYWNoZTogdHJ1ZX0pXG5cdFx0XHRcdC50aGVuKGZ1bmN0aW9uKGRhdGEpe1xuXHRcdFx0XHRcdHNlbGYubmV3c2ZlZWQgPSBkYXRhLmFjdGl2aXR5XG5cdFx0XHRcdFx0c2VsZi5vZmZzZXQgPSBkYXRhWydsb2FkLW5leHQnXTtcblx0XHRcdFx0fSlcblx0XHRcdFx0LmNhdGNoKGZ1bmN0aW9uKGUpe1xuXHRcdFx0XHRcdGNvbnNvbGUubG9nKGUpO1xuXHRcdFx0XHR9KTtcblx0fVxufSJdLCJzb3VyY2VSb290IjoiL3NvdXJjZS8ifQ==