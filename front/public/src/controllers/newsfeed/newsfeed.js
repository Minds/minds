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
        this.newsfeed = [];
        this.offset = "";
        this.load();
    }
    Newsfeed.prototype.load = function () {
        var self = this;
        this.client.get('api/v1/newsfeed', { limit: 12 }, { cache: true })
            .then(function (data) {
            if (!data.activity) {
                return false;
            }
            self.newsfeed = data.activity;
            self.offset = data['load-next'];
        })
            .catch(function (e) {
            console.log(e);
        });
    };
    Newsfeed.prototype.post = function (message) {
        var self = this;
        this.client.post('api/v1/newsfeed', { message: message })
            .then(function (data) {
            self.load();
        })
            .catch(function (e) {
            console.log(e);
        });
    };
    Newsfeed.prototype.toDate = function (timestamp) {
        return new Date(timestamp * 1000);
    };
    Newsfeed = __decorate([
        angular2_1.Component({
            selector: 'minds-newsfeed',
            viewInjector: [api_1.Client]
        }),
        angular2_1.View({
            templateUrl: 'templates/newsfeed/list.html',
            directives: [angular2_1.NgFor, angular2_1.NgIf]
        }), 
        __metadata('design:paramtypes', [api_1.Client])
    ], Newsfeed);
    return Newsfeed;
})();
exports.Newsfeed = Newsfeed;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9jb250cm9sbGVycy9uZXdzZmVlZC9uZXdzZmVlZC50cyJdLCJuYW1lcyI6WyJOZXdzZmVlZCIsIk5ld3NmZWVkLmNvbnN0cnVjdG9yIiwiTmV3c2ZlZWQubG9hZCIsIk5ld3NmZWVkLnBvc3QiLCJOZXdzZmVlZC50b0RhdGUiXSwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7O0FBQUEseUJBQTRELG1CQUFtQixDQUFDLENBQUE7QUFDaEYsb0JBQXFCLGtCQUFrQixDQUFDLENBQUE7QUFFeEM7SUFjQ0Esa0JBQW1CQSxNQUFjQTtRQUFkQyxXQUFNQSxHQUFOQSxNQUFNQSxDQUFRQTtRQUhqQ0EsYUFBUUEsR0FBbUJBLEVBQUVBLENBQUNBO1FBQzlCQSxXQUFNQSxHQUFZQSxFQUFFQSxDQUFDQTtRQUdwQkEsSUFBSUEsQ0FBQ0EsSUFBSUEsRUFBRUEsQ0FBQ0E7SUFDYkEsQ0FBQ0E7SUFLREQsdUJBQUlBLEdBQUpBO1FBQ0NFLElBQUlBLElBQUlBLEdBQUdBLElBQUlBLENBQUNBO1FBQ2hCQSxJQUFJQSxDQUFDQSxNQUFNQSxDQUFDQSxHQUFHQSxDQUFDQSxpQkFBaUJBLEVBQUVBLEVBQUNBLEtBQUtBLEVBQUNBLEVBQUVBLEVBQUNBLEVBQUVBLEVBQUNBLEtBQUtBLEVBQUVBLElBQUlBLEVBQUNBLENBQUNBO2FBQzFEQSxJQUFJQSxDQUFDQSxVQUFTQSxJQUFJQTtZQUNsQixFQUFFLENBQUEsQ0FBQyxDQUFDLElBQUksQ0FBQyxRQUFRLENBQUMsQ0FBQSxDQUFDO2dCQUNsQixNQUFNLENBQUMsS0FBSyxDQUFDO1lBQ2QsQ0FBQztZQUNELElBQUksQ0FBQyxRQUFRLEdBQUcsSUFBSSxDQUFDLFFBQVEsQ0FBQztZQUM5QixJQUFJLENBQUMsTUFBTSxHQUFHLElBQUksQ0FBQyxXQUFXLENBQUMsQ0FBQztRQUNqQyxDQUFDLENBQUNBO2FBQ0RBLEtBQUtBLENBQUNBLFVBQVNBLENBQUNBO1lBQ2hCLE9BQU8sQ0FBQyxHQUFHLENBQUMsQ0FBQyxDQUFDLENBQUM7UUFDaEIsQ0FBQyxDQUFDQSxDQUFDQTtJQUNOQSxDQUFDQTtJQUtERix1QkFBSUEsR0FBSkEsVUFBS0EsT0FBT0E7UUFDWEcsSUFBSUEsSUFBSUEsR0FBR0EsSUFBSUEsQ0FBQ0E7UUFDaEJBLElBQUlBLENBQUNBLE1BQU1BLENBQUNBLElBQUlBLENBQUNBLGlCQUFpQkEsRUFBRUEsRUFBQ0EsT0FBT0EsRUFBRUEsT0FBT0EsRUFBQ0EsQ0FBQ0E7YUFDcERBLElBQUlBLENBQUNBLFVBQVNBLElBQUlBO1lBQ2xCLElBQUksQ0FBQyxJQUFJLEVBQUUsQ0FBQztRQUNiLENBQUMsQ0FBQ0E7YUFDREEsS0FBS0EsQ0FBQ0EsVUFBU0EsQ0FBQ0E7WUFDaEIsT0FBTyxDQUFDLEdBQUcsQ0FBQyxDQUFDLENBQUMsQ0FBQztRQUNoQixDQUFDLENBQUNBLENBQUNBO0lBQ05BLENBQUNBO0lBS0RILHlCQUFNQSxHQUFOQSxVQUFPQSxTQUFTQTtRQUNmSSxNQUFNQSxDQUFDQSxJQUFJQSxJQUFJQSxDQUFDQSxTQUFTQSxHQUFDQSxJQUFJQSxDQUFDQSxDQUFDQTtJQUNqQ0EsQ0FBQ0E7SUF2REZKO1FBQUNBLG9CQUFTQSxDQUFDQTtZQUNUQSxRQUFRQSxFQUFFQSxnQkFBZ0JBO1lBQzFCQSxZQUFZQSxFQUFFQSxDQUFFQSxZQUFNQSxDQUFFQTtTQUN6QkEsQ0FBQ0E7UUFDREEsZUFBSUEsQ0FBQ0E7WUFDSkEsV0FBV0EsRUFBRUEsOEJBQThCQTtZQUMzQ0EsVUFBVUEsRUFBRUEsQ0FBRUEsZ0JBQUtBLEVBQUVBLGVBQUlBLENBQUVBO1NBQzVCQSxDQUFDQTs7aUJBaUREQTtJQUFEQSxlQUFDQTtBQUFEQSxDQXhEQSxJQXdEQztBQS9DWSxnQkFBUSxXQStDcEIsQ0FBQSIsImZpbGUiOiJzcmMvY29udHJvbGxlcnMvbmV3c2ZlZWQvbmV3c2ZlZWQuanMiLCJzb3VyY2VzQ29udGVudCI6WyJpbXBvcnQge0NvbXBvbmVudCwgVmlldywgTmdGb3IsIE5nSWYsIFBpcGVzLCBEYXRlUGlwZX0gZnJvbSAnYW5ndWxhcjIvYW5ndWxhcjInO1xuaW1wb3J0IHtDbGllbnR9IGZyb20gJ3NyYy9zZXJ2aWNlcy9hcGknO1xuXG5AQ29tcG9uZW50KHtcbiAgc2VsZWN0b3I6ICdtaW5kcy1uZXdzZmVlZCcsXG4gIHZpZXdJbmplY3RvcjogWyBDbGllbnQgXVxufSlcbkBWaWV3KHtcbiAgdGVtcGxhdGVVcmw6ICd0ZW1wbGF0ZXMvbmV3c2ZlZWQvbGlzdC5odG1sJyxcbiAgZGlyZWN0aXZlczogWyBOZ0ZvciwgTmdJZiBdXG59KVxuXG5leHBvcnQgY2xhc3MgTmV3c2ZlZWQge1xuXG5cdG5ld3NmZWVkIDogQXJyYXk8T2JqZWN0PiA9IFtdO1xuXHRvZmZzZXQgOiBzdHJpbmcgPSBcIlwiO1xuXG5cdGNvbnN0cnVjdG9yKHB1YmxpYyBjbGllbnQ6IENsaWVudCl7XG5cdFx0dGhpcy5sb2FkKCk7XG5cdH1cblxuXHQvKipcblx0ICogTG9hZCBuZXdzZmVlZFxuXHQgKi9cblx0bG9hZCgpe1xuXHRcdHZhciBzZWxmID0gdGhpcztcblx0XHR0aGlzLmNsaWVudC5nZXQoJ2FwaS92MS9uZXdzZmVlZCcsIHtsaW1pdDoxMn0sIHtjYWNoZTogdHJ1ZX0pXG5cdFx0XHRcdC50aGVuKGZ1bmN0aW9uKGRhdGEpe1xuXHRcdFx0XHRcdGlmKCFkYXRhLmFjdGl2aXR5KXtcblx0XHRcdFx0XHRcdHJldHVybiBmYWxzZTtcblx0XHRcdFx0XHR9XG5cdFx0XHRcdFx0c2VsZi5uZXdzZmVlZCA9IGRhdGEuYWN0aXZpdHk7XG5cdFx0XHRcdFx0c2VsZi5vZmZzZXQgPSBkYXRhWydsb2FkLW5leHQnXTtcblx0XHRcdFx0fSlcblx0XHRcdFx0LmNhdGNoKGZ1bmN0aW9uKGUpe1xuXHRcdFx0XHRcdGNvbnNvbGUubG9nKGUpO1xuXHRcdFx0XHR9KTtcblx0fVxuXHRcblx0LyoqXG5cdCAqIFBvc3QgdG8gdGhlIG5ld3NmZWVkXG5cdCAqL1xuXHRwb3N0KG1lc3NhZ2Upe1xuXHRcdHZhciBzZWxmID0gdGhpcztcblx0XHR0aGlzLmNsaWVudC5wb3N0KCdhcGkvdjEvbmV3c2ZlZWQnLCB7bWVzc2FnZTogbWVzc2FnZX0pXG5cdFx0XHRcdC50aGVuKGZ1bmN0aW9uKGRhdGEpe1xuXHRcdFx0XHRcdHNlbGYubG9hZCgpO1xuXHRcdFx0XHR9KVxuXHRcdFx0XHQuY2F0Y2goZnVuY3Rpb24oZSl7XG5cdFx0XHRcdFx0Y29uc29sZS5sb2coZSk7XG5cdFx0XHRcdH0pO1xuXHR9XG5cdFxuXHQvKipcblx0ICogQSB0ZW1wb3JhcnkgaGFjaywgYmVjYXVzZSBwaXBlcyBkb24ndCBzZWVtIHRvIHdvcmtcblx0ICovXG5cdHRvRGF0ZSh0aW1lc3RhbXApe1xuXHRcdHJldHVybiBuZXcgRGF0ZSh0aW1lc3RhbXAqMTAwMCk7XG5cdH1cbn0iXSwic291cmNlUm9vdCI6Ii9zb3VyY2UvIn0=