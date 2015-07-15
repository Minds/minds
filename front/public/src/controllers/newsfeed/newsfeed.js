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
    Newsfeed.prototype.getPostPreview = function (message) {
        console.log("you said " + message.value);
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

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9jb250cm9sbGVycy9uZXdzZmVlZC9uZXdzZmVlZC50cyJdLCJuYW1lcyI6WyJOZXdzZmVlZCIsIk5ld3NmZWVkLmNvbnN0cnVjdG9yIiwiTmV3c2ZlZWQubG9hZCIsIk5ld3NmZWVkLnBvc3QiLCJOZXdzZmVlZC5nZXRQb3N0UHJldmlldyIsIk5ld3NmZWVkLnRvRGF0ZSJdLCJtYXBwaW5ncyI6Ijs7Ozs7Ozs7Ozs7QUFBQSx5QkFBNkMsbUJBQW1CLENBQUMsQ0FBQTtBQUNqRSxvQkFBdUIsa0JBQWtCLENBQUMsQ0FBQTtBQUUxQztJQWNDQSxrQkFBbUJBLE1BQWNBO1FBQWRDLFdBQU1BLEdBQU5BLE1BQU1BLENBQVFBO1FBSGpDQSxhQUFRQSxHQUFtQkEsRUFBRUEsQ0FBQ0E7UUFDOUJBLFdBQU1BLEdBQVlBLEVBQUVBLENBQUNBO1FBR3BCQSxJQUFJQSxDQUFDQSxJQUFJQSxFQUFFQSxDQUFDQTtJQUNiQSxDQUFDQTtJQUtERCx1QkFBSUEsR0FBSkE7UUFDQ0UsSUFBSUEsSUFBSUEsR0FBR0EsSUFBSUEsQ0FBQ0E7UUFDaEJBLElBQUlBLENBQUNBLE1BQU1BLENBQUNBLEdBQUdBLENBQUNBLGlCQUFpQkEsRUFBRUEsRUFBQ0EsS0FBS0EsRUFBQ0EsRUFBRUEsRUFBQ0EsRUFBRUEsRUFBQ0EsS0FBS0EsRUFBRUEsSUFBSUEsRUFBQ0EsQ0FBQ0E7YUFDMURBLElBQUlBLENBQUNBLFVBQVNBLElBQTBCQTtZQUN4QyxFQUFFLENBQUEsQ0FBQyxDQUFDLElBQUksQ0FBQyxRQUFRLENBQUMsQ0FBQSxDQUFDO2dCQUNsQixNQUFNLENBQUMsS0FBSyxDQUFDO1lBQ2QsQ0FBQztZQUNELElBQUksQ0FBQyxRQUFRLEdBQUcsSUFBSSxDQUFDLFFBQVEsQ0FBQztZQUM5QixJQUFJLENBQUMsTUFBTSxHQUFHLElBQUksQ0FBQyxXQUFXLENBQUMsQ0FBQztRQUNqQyxDQUFDLENBQUNBO2FBQ0RBLEtBQUtBLENBQUNBLFVBQVNBLENBQUNBO1lBQ2hCLE9BQU8sQ0FBQyxHQUFHLENBQUMsQ0FBQyxDQUFDLENBQUM7UUFDaEIsQ0FBQyxDQUFDQSxDQUFDQTtJQUNOQSxDQUFDQTtJQUtERix1QkFBSUEsR0FBSkEsVUFBS0EsT0FBT0E7UUFDWEcsSUFBSUEsSUFBSUEsR0FBR0EsSUFBSUEsQ0FBQ0E7UUFDaEJBLElBQUlBLENBQUNBLE1BQU1BLENBQUNBLElBQUlBLENBQUNBLGlCQUFpQkEsRUFBRUEsRUFBQ0EsT0FBT0EsRUFBRUEsT0FBT0EsRUFBQ0EsQ0FBQ0E7YUFDcERBLElBQUlBLENBQUNBLFVBQVNBLElBQUlBO1lBQ2xCLElBQUksQ0FBQyxJQUFJLEVBQUUsQ0FBQztRQUNiLENBQUMsQ0FBQ0E7YUFDREEsS0FBS0EsQ0FBQ0EsVUFBU0EsQ0FBQ0E7WUFDaEIsT0FBTyxDQUFDLEdBQUcsQ0FBQyxDQUFDLENBQUMsQ0FBQztRQUNoQixDQUFDLENBQUNBLENBQUNBO0lBQ05BLENBQUNBO0lBS0FILGlDQUFjQSxHQUFkQSxVQUFlQSxPQUFPQTtRQUNwQkksT0FBT0EsQ0FBQ0EsR0FBR0EsQ0FBQ0EsV0FBV0EsR0FBR0EsT0FBT0EsQ0FBQ0EsS0FBS0EsQ0FBQ0EsQ0FBQ0E7SUFDM0NBLENBQUNBO0lBS0ZKLHlCQUFNQSxHQUFOQSxVQUFPQSxTQUFTQTtRQUNmSyxNQUFNQSxDQUFDQSxJQUFJQSxJQUFJQSxDQUFDQSxTQUFTQSxHQUFDQSxJQUFJQSxDQUFDQSxDQUFDQTtJQUNqQ0EsQ0FBQ0E7SUE5REZMO1FBQUNBLG9CQUFTQSxDQUFDQTtZQUNUQSxRQUFRQSxFQUFFQSxnQkFBZ0JBO1lBQzFCQSxZQUFZQSxFQUFFQSxDQUFFQSxZQUFNQSxDQUFFQTtTQUN6QkEsQ0FBQ0E7UUFDREEsZUFBSUEsQ0FBQ0E7WUFDSkEsV0FBV0EsRUFBRUEsOEJBQThCQTtZQUMzQ0EsVUFBVUEsRUFBRUEsQ0FBRUEsZ0JBQUtBLEVBQUVBLGVBQUlBLENBQUVBO1NBQzVCQSxDQUFDQTs7aUJBd0REQTtJQUFEQSxlQUFDQTtBQUFEQSxDQS9EQSxJQStEQztBQXREWSxnQkFBUSxXQXNEcEIsQ0FBQSIsImZpbGUiOiJzcmMvY29udHJvbGxlcnMvbmV3c2ZlZWQvbmV3c2ZlZWQuanMiLCJzb3VyY2VzQ29udGVudCI6WyJpbXBvcnQgeyBDb21wb25lbnQsIFZpZXcsIE5nRm9yLCBOZ0lmIH0gZnJvbSAnYW5ndWxhcjIvYW5ndWxhcjInO1xuaW1wb3J0IHsgQ2xpZW50IH0gZnJvbSAnc3JjL3NlcnZpY2VzL2FwaSc7XG5cbkBDb21wb25lbnQoe1xuICBzZWxlY3RvcjogJ21pbmRzLW5ld3NmZWVkJyxcbiAgdmlld0luamVjdG9yOiBbIENsaWVudCBdXG59KVxuQFZpZXcoe1xuICB0ZW1wbGF0ZVVybDogJ3RlbXBsYXRlcy9uZXdzZmVlZC9saXN0Lmh0bWwnLFxuICBkaXJlY3RpdmVzOiBbIE5nRm9yLCBOZ0lmIF1cbn0pXG5cbmV4cG9ydCBjbGFzcyBOZXdzZmVlZCB7XG5cblx0bmV3c2ZlZWQgOiBBcnJheTxPYmplY3Q+ID0gW107XG5cdG9mZnNldCA6IHN0cmluZyA9IFwiXCI7XG5cblx0Y29uc3RydWN0b3IocHVibGljIGNsaWVudDogQ2xpZW50KXtcblx0XHR0aGlzLmxvYWQoKTtcblx0fVxuXG5cdC8qKlxuXHQgKiBMb2FkIG5ld3NmZWVkXG5cdCAqL1xuXHRsb2FkKCl7XG5cdFx0dmFyIHNlbGYgPSB0aGlzO1xuXHRcdHRoaXMuY2xpZW50LmdldCgnYXBpL3YxL25ld3NmZWVkJywge2xpbWl0OjEyfSwge2NhY2hlOiB0cnVlfSlcblx0XHRcdFx0LnRoZW4oZnVuY3Rpb24oZGF0YSA6IE1pbmRzQWN0aXZpdHlPYmplY3Qpe1xuXHRcdFx0XHRcdGlmKCFkYXRhLmFjdGl2aXR5KXtcblx0XHRcdFx0XHRcdHJldHVybiBmYWxzZTtcblx0XHRcdFx0XHR9XG5cdFx0XHRcdFx0c2VsZi5uZXdzZmVlZCA9IGRhdGEuYWN0aXZpdHk7XG5cdFx0XHRcdFx0c2VsZi5vZmZzZXQgPSBkYXRhWydsb2FkLW5leHQnXTtcblx0XHRcdFx0fSlcblx0XHRcdFx0LmNhdGNoKGZ1bmN0aW9uKGUpe1xuXHRcdFx0XHRcdGNvbnNvbGUubG9nKGUpO1xuXHRcdFx0XHR9KTtcblx0fVxuXG5cdC8qKlxuXHQgKiBQb3N0IHRvIHRoZSBuZXdzZmVlZFxuXHQgKi9cblx0cG9zdChtZXNzYWdlKXtcblx0XHR2YXIgc2VsZiA9IHRoaXM7XG5cdFx0dGhpcy5jbGllbnQucG9zdCgnYXBpL3YxL25ld3NmZWVkJywge21lc3NhZ2U6IG1lc3NhZ2V9KVxuXHRcdFx0XHQudGhlbihmdW5jdGlvbihkYXRhKXtcblx0XHRcdFx0XHRzZWxmLmxvYWQoKTtcblx0XHRcdFx0fSlcblx0XHRcdFx0LmNhdGNoKGZ1bmN0aW9uKGUpe1xuXHRcdFx0XHRcdGNvbnNvbGUubG9nKGUpO1xuXHRcdFx0XHR9KTtcblx0fVxuXG4gIC8qKlxuICAgKiBHZXQgcmljaCBlbWJlZCBkYXRhXG4gICAqL1xuICBnZXRQb3N0UHJldmlldyhtZXNzYWdlKXtcbiAgICBjb25zb2xlLmxvZyhcInlvdSBzYWlkIFwiICsgbWVzc2FnZS52YWx1ZSk7XG4gIH1cblxuXHQvKipcblx0ICogQSB0ZW1wb3JhcnkgaGFjaywgYmVjYXVzZSBwaXBlcyBkb24ndCBzZWVtIHRvIHdvcmtcblx0ICovXG5cdHRvRGF0ZSh0aW1lc3RhbXApe1xuXHRcdHJldHVybiBuZXcgRGF0ZSh0aW1lc3RhbXAqMTAwMCk7XG5cdH1cbn1cbiJdLCJzb3VyY2VSb290IjoiL3NvdXJjZS8ifQ==