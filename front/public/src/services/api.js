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
if (typeof __param !== "function") __param = function (paramIndex, decorator) {
    return function (target, key) { decorator(target, key, paramIndex); }
};
var angular2_1 = require('angular2/angular2');
var Api = (function () {
    function Api() {
    }
    Api.prototype.get = function () {
        console.log('you ask, you get');
    };
    return Api;
})();
exports.Api = Api;
var OAuth = (function () {
    function OAuth(http) {
        this.http = http;
    }
    OAuth.prototype.login = function (username, password) {
        var http = this.http;
        return new Promise(function (resolve, reject) {
            var request = http.post('https://www.minds.com/oauth2/token', {
                grant_type: 'password',
                username: username,
                password: password
            }, {
                headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' }
            })
                .toRx()
                .map(function (res) { return res.json(); })
                .subscribe(function (res) {
                resolve(true);
                console.log(res);
            });
        });
    };
    OAuth = __decorate([
        __param(0, angular2_1.Inject(angular2_1.Http)), 
        __metadata('design:paramtypes', [(typeof Http !== 'undefined' && Http) || Object])
    ], OAuth);
    return OAuth;
})();
exports.OAuth = OAuth;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9zZXJ2aWNlcy9hcGkudHMiXSwibmFtZXMiOlsiQXBpIiwiQXBpLmNvbnN0cnVjdG9yIiwiQXBpLmdldCIsIk9BdXRoIiwiT0F1dGguY29uc3RydWN0b3IiLCJPQXV0aC5sb2dpbiJdLCJtYXBwaW5ncyI6Ijs7Ozs7Ozs7Ozs7Ozs7QUFBQSx5QkFBMkIsbUJBQW1CLENBQUMsQ0FBQTtBQUsvQztJQUNDQTtJQUVBQyxDQUFDQTtJQUNERCxpQkFBR0EsR0FBSEE7UUFFQ0UsT0FBT0EsQ0FBQ0EsR0FBR0EsQ0FBQ0Esa0JBQWtCQSxDQUFDQSxDQUFDQTtJQUNqQ0EsQ0FBQ0E7SUFDRkYsVUFBQ0E7QUFBREEsQ0FSQSxJQVFDO0FBUlksV0FBRyxNQVFmLENBQUE7QUFFRDtJQUNDRyxlQUFpQ0EsSUFBVUE7UUFBVkMsU0FBSUEsR0FBSkEsSUFBSUEsQ0FBTUE7SUFBR0EsQ0FBQ0E7SUFFL0NELHFCQUFLQSxHQUFMQSxVQUFNQSxRQUFRQSxFQUFFQSxRQUFRQTtRQUN2QkUsSUFBSUEsSUFBSUEsR0FBR0EsSUFBSUEsQ0FBQ0EsSUFBSUEsQ0FBQ0E7UUFDckJBLE1BQU1BLENBQUNBLElBQUlBLE9BQU9BLENBQUNBLFVBQVNBLE9BQU9BLEVBQUVBLE1BQU1BO1lBSzFDLElBQUksT0FBTyxHQUFHLElBQUksQ0FBQyxJQUFJLENBQUMsb0NBQW9DLEVBQ3hEO2dCQUNDLFVBQVUsRUFBQyxVQUFVO2dCQUNyQixRQUFRLEVBQUUsUUFBUTtnQkFDbEIsUUFBUSxFQUFFLFFBQVE7YUFDbEIsRUFDRDtnQkFDQyxPQUFPLEVBQUUsRUFBRSxjQUFjLEVBQUUsa0RBQWtELEVBQUU7YUFDL0UsQ0FBQztpQkFDRCxJQUFJLEVBQUU7aUJBQ04sR0FBRyxDQUFDLFVBQUEsR0FBRyxJQUFJLE9BQUEsR0FBRyxDQUFDLElBQUksRUFBRSxFQUFWLENBQVUsQ0FBQztpQkFDdEIsU0FBUyxDQUFDLFVBQVMsR0FBRztnQkFDdEIsT0FBTyxDQUFDLElBQUksQ0FBQyxDQUFDO2dCQUNkLE9BQU8sQ0FBQyxHQUFHLENBQUMsR0FBRyxDQUFDLENBQUM7WUFDbEIsQ0FBQyxDQUFDLENBQUM7UUFFUixDQUFDLENBQUNBLENBQUNBO0lBQ0pBLENBQUNBO0lBM0JGRjtRQUNhQSxXQUFDQSxpQkFBTUEsQ0FBQ0EsZUFBSUEsQ0FBQ0EsQ0FBQUE7O2NBNEJ6QkE7SUFBREEsWUFBQ0E7QUFBREEsQ0E3QkEsSUE2QkM7QUE3QlksYUFBSyxRQTZCakIsQ0FBQSIsImZpbGUiOiJzcmMvc2VydmljZXMvYXBpLmpzIiwic291cmNlc0NvbnRlbnQiOlsiaW1wb3J0IHtJbmplY3QsIEh0dHB9IGZyb20gJ2FuZ3VsYXIyL2FuZ3VsYXIyJztcblxuLyoqXG4gKiBBUEkgQ2xhc3NcbiAqL1xuZXhwb3J0IGNsYXNzIEFwaSB7XG5cdGNvbnN0cnVjdG9yKCl7XG5cdFx0XG5cdH1cblx0Z2V0KCl7XG5cdFx0Ly9jb25zb2xlLmxvZyhIdHRwKVxuXHRcdGNvbnNvbGUubG9nKCd5b3UgYXNrLCB5b3UgZ2V0Jyk7XG5cdH1cbn1cblxuZXhwb3J0IGNsYXNzIE9BdXRoIHtcblx0Y29uc3RydWN0b3IoQEluamVjdChIdHRwKSBwdWJsaWMgaHR0cDogSHR0cCl7IH1cblx0cmVzdWx0O1xuXHRsb2dpbih1c2VybmFtZSwgcGFzc3dvcmQpe1xuXHRcdHZhciBodHRwID0gdGhpcy5odHRwOyAvL3RoYXQgPD0+IHRoaXNcblx0XHRyZXR1cm4gbmV3IFByb21pc2UoZnVuY3Rpb24ocmVzb2x2ZSwgcmVqZWN0KXtcblxuXHRcdFx0LyoqXG5cdFx0XHQgKiBGcmFnaWxlLi4gYXBpIGFsd2F5cyBjaGFuZ2luZ1xuXHRcdFx0ICovXG5cdFx0XHR2YXIgcmVxdWVzdCA9IGh0dHAucG9zdCgnaHR0cHM6Ly93d3cubWluZHMuY29tL29hdXRoMi90b2tlbicsIFxuXHRcdFx0XHRcdFx0XHR7IFxuXHRcdFx0XHRcdFx0XHRcdGdyYW50X3R5cGU6J3Bhc3N3b3JkJyxcblx0XHRcdFx0XHRcdFx0XHR1c2VybmFtZTogdXNlcm5hbWUsIFxuXHRcdFx0XHRcdFx0XHRcdHBhc3N3b3JkOiBwYXNzd29yZFxuXHRcdFx0XHRcdFx0XHR9LFxuXHRcdFx0XHRcdFx0XHR7XG5cdFx0XHRcdFx0XHRcdFx0aGVhZGVyczogeyAnQ29udGVudC1UeXBlJzogJ2FwcGxpY2F0aW9uL3gtd3d3LWZvcm0tdXJsZW5jb2RlZDsgY2hhcnNldD1VVEYtOCcgfVxuXHRcdFx0XHRcdFx0XHR9KVxuXHRcdFx0XHRcdFx0XHQudG9SeCgpXG5cdFx0XHRcdFx0XHRcdC5tYXAocmVzID0+IHJlcy5qc29uKCkpXG5cdFx0XHRcdFx0XHRcdC5zdWJzY3JpYmUoZnVuY3Rpb24ocmVzKXtcblx0XHRcdFx0XHRcdFx0XHRyZXNvbHZlKHRydWUpO1xuXHRcdFx0XHRcdFx0XHRcdGNvbnNvbGUubG9nKHJlcyk7XG5cdFx0XHRcdFx0XHRcdH0pO1xuXG5cdFx0fSk7XG5cdH1cblxufSJdLCJzb3VyY2VSb290IjoiL3NvdXJjZS8ifQ==