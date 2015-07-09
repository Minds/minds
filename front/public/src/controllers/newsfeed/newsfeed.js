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

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9jb250cm9sbGVycy9uZXdzZmVlZC9uZXdzZmVlZC50cyJdLCJuYW1lcyI6WyJOZXdzZmVlZCIsIk5ld3NmZWVkLmNvbnN0cnVjdG9yIiwiTmV3c2ZlZWQubG9hZCIsIk5ld3NmZWVkLnBvc3QiXSwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7O0FBQUEseUJBQXFDLG1CQUFtQixDQUFDLENBQUE7QUFDekQsb0JBQXFCLGtCQUFrQixDQUFDLENBQUE7QUFFeEM7SUFjQ0Esa0JBQW1CQSxNQUFjQTtRQUFkQyxXQUFNQSxHQUFOQSxNQUFNQSxDQUFRQTtRQUhqQ0EsYUFBUUEsR0FBbUJBLEVBQUVBLENBQUNBO1FBQzlCQSxXQUFNQSxHQUFZQSxFQUFFQSxDQUFDQTtRQUdwQkEsSUFBSUEsQ0FBQ0EsSUFBSUEsRUFBRUEsQ0FBQ0E7SUFDYkEsQ0FBQ0E7SUFLREQsdUJBQUlBLEdBQUpBO1FBQ0NFLElBQUlBLElBQUlBLEdBQUdBLElBQUlBLENBQUNBO1FBQ2hCQSxJQUFJQSxDQUFDQSxNQUFNQSxDQUFDQSxHQUFHQSxDQUFDQSxpQkFBaUJBLEVBQUVBLEVBQUNBLEtBQUtBLEVBQUNBLEVBQUVBLEVBQUNBLEVBQUVBLEVBQUNBLEtBQUtBLEVBQUVBLElBQUlBLEVBQUNBLENBQUNBO2FBQzFEQSxJQUFJQSxDQUFDQSxVQUFTQSxJQUFJQTtZQUNsQixFQUFFLENBQUEsQ0FBQyxDQUFDLElBQUksQ0FBQyxRQUFRLENBQUMsQ0FBQSxDQUFDO2dCQUNsQixNQUFNLENBQUMsS0FBSyxDQUFDO1lBQ2QsQ0FBQztZQUNELElBQUksQ0FBQyxRQUFRLEdBQUcsSUFBSSxDQUFDLFFBQVEsQ0FBQztZQUM5QixJQUFJLENBQUMsTUFBTSxHQUFHLElBQUksQ0FBQyxXQUFXLENBQUMsQ0FBQztRQUNqQyxDQUFDLENBQUNBO2FBQ0RBLEtBQUtBLENBQUNBLFVBQVNBLENBQUNBO1lBQ2hCLE9BQU8sQ0FBQyxHQUFHLENBQUMsQ0FBQyxDQUFDLENBQUM7UUFDaEIsQ0FBQyxDQUFDQSxDQUFDQTtJQUNOQSxDQUFDQTtJQUtERix1QkFBSUEsR0FBSkEsVUFBS0EsT0FBT0E7UUFDWEcsSUFBSUEsSUFBSUEsR0FBR0EsSUFBSUEsQ0FBQ0E7UUFDaEJBLElBQUlBLENBQUNBLE1BQU1BLENBQUNBLElBQUlBLENBQUNBLGlCQUFpQkEsRUFBRUEsRUFBQ0EsT0FBT0EsRUFBRUEsT0FBT0EsRUFBQ0EsQ0FBQ0E7YUFDcERBLElBQUlBLENBQUNBLFVBQVNBLElBQUlBO1lBQ2xCLElBQUksQ0FBQyxJQUFJLEVBQUUsQ0FBQztRQUNiLENBQUMsQ0FBQ0E7YUFDREEsS0FBS0EsQ0FBQ0EsVUFBU0EsQ0FBQ0E7WUFDaEIsT0FBTyxDQUFDLEdBQUcsQ0FBQyxDQUFDLENBQUMsQ0FBQztRQUNoQixDQUFDLENBQUNBLENBQUNBO0lBQ05BLENBQUNBO0lBaERGSDtRQUFDQSxvQkFBU0EsQ0FBQ0E7WUFDVEEsUUFBUUEsRUFBRUEsZ0JBQWdCQTtZQUMxQkEsWUFBWUEsRUFBRUEsQ0FBRUEsWUFBTUEsQ0FBRUE7U0FDekJBLENBQUNBO1FBQ0RBLGVBQUlBLENBQUNBO1lBQ0pBLFdBQVdBLEVBQUVBLDhCQUE4QkE7WUFDM0NBLFVBQVVBLEVBQUVBLENBQUVBLGdCQUFLQSxDQUFFQTtTQUN0QkEsQ0FBQ0E7O2lCQTBDREE7SUFBREEsZUFBQ0E7QUFBREEsQ0FqREEsSUFpREM7QUF4Q1ksZ0JBQVEsV0F3Q3BCLENBQUEiLCJmaWxlIjoic3JjL2NvbnRyb2xsZXJzL25ld3NmZWVkL25ld3NmZWVkLmpzIiwic291cmNlc0NvbnRlbnQiOlsiaW1wb3J0IHtDb21wb25lbnQsIFZpZXcsIE5nRm9yfSBmcm9tICdhbmd1bGFyMi9hbmd1bGFyMic7XG5pbXBvcnQge0NsaWVudH0gZnJvbSAnc3JjL3NlcnZpY2VzL2FwaSc7XG5cbkBDb21wb25lbnQoe1xuICBzZWxlY3RvcjogJ21pbmRzLW5ld3NmZWVkJyxcbiAgdmlld0luamVjdG9yOiBbIENsaWVudCBdXG59KVxuQFZpZXcoe1xuICB0ZW1wbGF0ZVVybDogJ3RlbXBsYXRlcy9uZXdzZmVlZC9saXN0Lmh0bWwnLFxuICBkaXJlY3RpdmVzOiBbIE5nRm9yIF1cbn0pXG5cbmV4cG9ydCBjbGFzcyBOZXdzZmVlZCB7XG5cblx0bmV3c2ZlZWQgOiBBcnJheTxPYmplY3Q+ID0gW107XG5cdG9mZnNldCA6IHN0cmluZyA9IFwiXCI7XG5cblx0Y29uc3RydWN0b3IocHVibGljIGNsaWVudDogQ2xpZW50KXtcblx0XHR0aGlzLmxvYWQoKTtcblx0fVxuXG5cdC8qKlxuXHQgKiBMb2FkIG5ld3NmZWVkXG5cdCAqL1xuXHRsb2FkKCl7XG5cdFx0dmFyIHNlbGYgPSB0aGlzO1xuXHRcdHRoaXMuY2xpZW50LmdldCgnYXBpL3YxL25ld3NmZWVkJywge2xpbWl0OjEyfSwge2NhY2hlOiB0cnVlfSlcblx0XHRcdFx0LnRoZW4oZnVuY3Rpb24oZGF0YSl7XG5cdFx0XHRcdFx0aWYoIWRhdGEuYWN0aXZpdHkpe1xuXHRcdFx0XHRcdFx0cmV0dXJuIGZhbHNlO1xuXHRcdFx0XHRcdH1cblx0XHRcdFx0XHRzZWxmLm5ld3NmZWVkID0gZGF0YS5hY3Rpdml0eTtcblx0XHRcdFx0XHRzZWxmLm9mZnNldCA9IGRhdGFbJ2xvYWQtbmV4dCddO1xuXHRcdFx0XHR9KVxuXHRcdFx0XHQuY2F0Y2goZnVuY3Rpb24oZSl7XG5cdFx0XHRcdFx0Y29uc29sZS5sb2coZSk7XG5cdFx0XHRcdH0pO1xuXHR9XG5cdFxuXHQvKipcblx0ICogUG9zdCB0byB0aGUgbmV3c2ZlZWRcblx0ICovXG5cdHBvc3QobWVzc2FnZSl7XG5cdFx0dmFyIHNlbGYgPSB0aGlzO1xuXHRcdHRoaXMuY2xpZW50LnBvc3QoJ2FwaS92MS9uZXdzZmVlZCcsIHttZXNzYWdlOiBtZXNzYWdlfSlcblx0XHRcdFx0LnRoZW4oZnVuY3Rpb24oZGF0YSl7XG5cdFx0XHRcdFx0c2VsZi5sb2FkKCk7XG5cdFx0XHRcdH0pXG5cdFx0XHRcdC5jYXRjaChmdW5jdGlvbihlKXtcblx0XHRcdFx0XHRjb25zb2xlLmxvZyhlKTtcblx0XHRcdFx0fSk7XG5cdH1cbn0iXSwic291cmNlUm9vdCI6Ii9zb3VyY2UvIn0=