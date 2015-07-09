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
var storage_1 = require('src/services/storage');
var injector = angular2_1.Injector.resolveAndCreate([
    angular2_1.bind(storage_1.Storage).toClass(storage_1.Storage)
]);
var OAuth = (function () {
    function OAuth(http) {
        this.client_id = '421672819009523712';
        this.storage = injector.get(storage_1.Storage);
    }
    OAuth.prototype.buildParams = function (params) {
        return Object.assign(params, {
            'client_id': this.client_id,
            'access_token': this.storage.get('access_token')
        });
    };
    OAuth.prototype.login = function (username, password) {
        var _this = this;
        var that = this;
        var http = this.http;
        return new Promise(function (resolve, reject) {
            var request = http.post('https://www.minds.com/oauth2/token', JSON.stringify({
                grant_type: 'password',
                client_id: that.client_id,
                client_secret: '68a8f432807541549ed3e95ffd22752c',
                username: username,
                password: password
            }))
                .toRx()
                .subscribe(function (res) {
                if (res.status != 200) {
                    return reject("Header: " + status);
                }
                var data = res.json();
                if (!data.access_token) {
                    return reject("No access token");
                }
                _this.storage.set("loggedin", true);
                _this.storage.set("access_token", data.access_token);
                _this.storage.set("user_guid", data.user_id);
                resolve(true);
            });
        });
    };
    OAuth = __decorate([
        __param(0, angular2_1.Inject(angular2_1.Http)), 
        __metadata('design:paramtypes', [Object])
    ], OAuth);
    return OAuth;
})();
exports.OAuth = OAuth;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9zZXJ2aWNlcy9hcGkvb2F1dGgudHMiXSwibmFtZXMiOlsiT0F1dGgiLCJPQXV0aC5jb25zdHJ1Y3RvciIsIk9BdXRoLmJ1aWxkUGFyYW1zIiwiT0F1dGgubG9naW4iXSwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7Ozs7O0FBQUEseUJBQTJDLG1CQUFtQixDQUFDLENBQUE7QUFDL0Qsd0JBQXNCLHNCQUFzQixDQUFDLENBQUE7QUFFN0MsSUFBSSxRQUFRLEdBQUcsbUJBQVEsQ0FBQyxnQkFBZ0IsQ0FBQztJQUN4QyxlQUFJLENBQUMsaUJBQU8sQ0FBQyxDQUFDLE9BQU8sQ0FBQyxpQkFBTyxDQUFDO0NBQzlCLENBQUMsQ0FBQztBQUVIO0lBSUNBLGVBQTBCQSxJQUFJQTtRQUY5QkMsY0FBU0EsR0FBWUEsb0JBQW9CQSxDQUFDQTtRQUd6Q0EsSUFBSUEsQ0FBQ0EsT0FBT0EsR0FBR0EsUUFBUUEsQ0FBQ0EsR0FBR0EsQ0FBQ0EsaUJBQU9BLENBQUNBLENBQUNBO0lBQ3RDQSxDQUFDQTtJQUVERCwyQkFBV0EsR0FBWEEsVUFBWUEsTUFBTUE7UUFDakJFLE1BQU1BLENBQUNBLE1BQU1BLENBQUNBLE1BQU1BLENBQUNBLE1BQU1BLEVBQUVBO1lBQzNCQSxXQUFXQSxFQUFFQSxJQUFJQSxDQUFDQSxTQUFTQTtZQUMzQkEsY0FBY0EsRUFBRUEsSUFBSUEsQ0FBQ0EsT0FBT0EsQ0FBQ0EsR0FBR0EsQ0FBQ0EsY0FBY0EsQ0FBQ0E7U0FDL0NBLENBQUNBLENBQUNBO0lBQ05BLENBQUNBO0lBRURGLHFCQUFLQSxHQUFMQSxVQUFNQSxRQUFRQSxFQUFFQSxRQUFRQTtRQUF4QkcsaUJBaUNDQTtRQWhDQUEsSUFBSUEsSUFBSUEsR0FBR0EsSUFBSUEsQ0FBQ0E7UUFDaEJBLElBQUlBLElBQUlBLEdBQUdBLElBQUlBLENBQUNBLElBQUlBLENBQUNBO1FBQ3JCQSxNQUFNQSxDQUFDQSxJQUFJQSxPQUFPQSxDQUFDQSxVQUFDQSxPQUFPQSxFQUFFQSxNQUFNQTtZQUtsQ0EsSUFBSUEsT0FBT0EsR0FBR0EsSUFBSUEsQ0FBQ0EsSUFBSUEsQ0FBQ0Esb0NBQW9DQSxFQUN4REEsSUFBSUEsQ0FBQ0EsU0FBU0EsQ0FBQ0E7Z0JBQ2RBLFVBQVVBLEVBQUNBLFVBQVVBO2dCQUNyQkEsU0FBU0EsRUFBR0EsSUFBSUEsQ0FBQ0EsU0FBU0E7Z0JBQzFCQSxhQUFhQSxFQUFFQSxrQ0FBa0NBO2dCQUNqREEsUUFBUUEsRUFBRUEsUUFBUUE7Z0JBQ2xCQSxRQUFRQSxFQUFFQSxRQUFRQTthQUNsQkEsQ0FBQ0EsQ0FBQ0E7aUJBQ0ZBLElBQUlBLEVBQUVBO2lCQUVOQSxTQUFTQSxDQUFDQSxVQUFBQSxHQUFHQTtnQkFDWkEsRUFBRUEsQ0FBQUEsQ0FBQ0EsR0FBR0EsQ0FBQ0EsTUFBTUEsSUFBSUEsR0FBR0EsQ0FBQ0EsQ0FBQUEsQ0FBQ0E7b0JBQ3JCQSxNQUFNQSxDQUFDQSxNQUFNQSxDQUFDQSxVQUFVQSxHQUFHQSxNQUFNQSxDQUFDQSxDQUFDQTtnQkFDcENBLENBQUNBO2dCQUNEQSxJQUFJQSxJQUFJQSxHQUFHQSxHQUFHQSxDQUFDQSxJQUFJQSxFQUFFQSxDQUFDQTtnQkFDdEJBLEVBQUVBLENBQUFBLENBQUNBLENBQUNBLElBQUlBLENBQUNBLFlBQVlBLENBQUNBLENBQUFBLENBQUNBO29CQUN0QkEsTUFBTUEsQ0FBQ0EsTUFBTUEsQ0FBQ0EsaUJBQWlCQSxDQUFDQSxDQUFDQTtnQkFDbENBLENBQUNBO2dCQUNEQSxLQUFJQSxDQUFDQSxPQUFPQSxDQUFDQSxHQUFHQSxDQUFDQSxVQUFVQSxFQUFFQSxJQUFJQSxDQUFDQSxDQUFDQTtnQkFDbkNBLEtBQUlBLENBQUNBLE9BQU9BLENBQUNBLEdBQUdBLENBQUNBLGNBQWNBLEVBQUVBLElBQUlBLENBQUNBLFlBQVlBLENBQUNBLENBQUNBO2dCQUNwREEsS0FBSUEsQ0FBQ0EsT0FBT0EsQ0FBQ0EsR0FBR0EsQ0FBQ0EsV0FBV0EsRUFBRUEsSUFBSUEsQ0FBQ0EsT0FBT0EsQ0FBQ0EsQ0FBQ0E7Z0JBQzVDQSxPQUFPQSxDQUFDQSxJQUFJQSxDQUFDQSxDQUFDQTtZQUNoQkEsQ0FBQ0EsQ0FBQ0EsQ0FBQ0E7UUFFUkEsQ0FBQ0EsQ0FBQ0EsQ0FBQ0E7SUFDSkEsQ0FBQ0E7SUFoREZIO1FBSWFBLFdBQUNBLGlCQUFNQSxDQUFDQSxlQUFJQSxDQUFDQSxDQUFBQTs7Y0E4Q3pCQTtJQUFEQSxZQUFDQTtBQUFEQSxDQWxEQSxJQWtEQztBQWxEWSxhQUFLLFFBa0RqQixDQUFBIiwiZmlsZSI6InNyYy9zZXJ2aWNlcy9hcGkvb2F1dGguanMiLCJzb3VyY2VzQ29udGVudCI6WyJpbXBvcnQge0luamVjdCwgSW5qZWN0b3IsIGJpbmQsIEh0dHB9IGZyb20gJ2FuZ3VsYXIyL2FuZ3VsYXIyJztcbmltcG9ydCB7U3RvcmFnZX0gZnJvbSAnc3JjL3NlcnZpY2VzL3N0b3JhZ2UnO1xuXG52YXIgaW5qZWN0b3IgPSBJbmplY3Rvci5yZXNvbHZlQW5kQ3JlYXRlKFtcblx0YmluZChTdG9yYWdlKS50b0NsYXNzKFN0b3JhZ2UpXG5dKTtcblxuZXhwb3J0IGNsYXNzIE9BdXRoIHtcblx0XG5cdGNsaWVudF9pZCA6IFN0cmluZyA9ICc0MjE2NzI4MTkwMDk1MjM3MTInO1xuXG5cdGNvbnN0cnVjdG9yKEBJbmplY3QoSHR0cCkgaHR0cCl7XG5cdFx0dGhpcy5zdG9yYWdlID0gaW5qZWN0b3IuZ2V0KFN0b3JhZ2UpO1xuXHR9XG5cdFxuXHRidWlsZFBhcmFtcyhwYXJhbXMpe1xuXHRcdHJldHVybiBPYmplY3QuYXNzaWduKHBhcmFtcywge1xuXHRcdFx0XHQnY2xpZW50X2lkJzogdGhpcy5jbGllbnRfaWQsXG5cdFx0XHRcdCdhY2Nlc3NfdG9rZW4nOiB0aGlzLnN0b3JhZ2UuZ2V0KCdhY2Nlc3NfdG9rZW4nKVxuXHRcdFx0XHR9KTtcblx0fVxuXG5cdGxvZ2luKHVzZXJuYW1lLCBwYXNzd29yZCl7XG5cdFx0dmFyIHRoYXQgPSB0aGlzO1xuXHRcdHZhciBodHRwID0gdGhpcy5odHRwOyAvL3RoYXQgPD0+IHRoaXNcblx0XHRyZXR1cm4gbmV3IFByb21pc2UoKHJlc29sdmUsIHJlamVjdCkgPT4ge1xuXG5cdFx0XHQvKipcblx0XHRcdCAqIEZyYWdpbGUuLiBhcGkgYWx3YXlzIGNoYW5naW5nXG5cdFx0XHQgKi9cblx0XHRcdHZhciByZXF1ZXN0ID0gaHR0cC5wb3N0KCdodHRwczovL3d3dy5taW5kcy5jb20vb2F1dGgyL3Rva2VuJywgXG5cdFx0XHRcdFx0XHRcdEpTT04uc3RyaW5naWZ5KHsgXG5cdFx0XHRcdFx0XHRcdFx0Z3JhbnRfdHlwZToncGFzc3dvcmQnLFxuXHRcdFx0XHRcdFx0XHRcdGNsaWVudF9pZDogIHRoYXQuY2xpZW50X2lkLFxuXHRcdFx0XHRcdFx0XHRcdGNsaWVudF9zZWNyZXQ6ICc2OGE4ZjQzMjgwNzU0MTU0OWVkM2U5NWZmZDIyNzUyYycsXG5cdFx0XHRcdFx0XHRcdFx0dXNlcm5hbWU6IHVzZXJuYW1lLCBcblx0XHRcdFx0XHRcdFx0XHRwYXNzd29yZDogcGFzc3dvcmRcblx0XHRcdFx0XHRcdFx0fSkpXG5cdFx0XHRcdFx0XHRcdC50b1J4KClcblx0XHRcdFx0XHRcdFx0Ly8ubWFwKHJlcyA9PiByZXMuanNvbigpKVxuXHRcdFx0XHRcdFx0XHQuc3Vic2NyaWJlKHJlcyA9PiB7XG5cdFx0XHRcdFx0XHRcdFx0XHRpZihyZXMuc3RhdHVzICE9IDIwMCl7XG5cdFx0XHRcdFx0XHRcdFx0XHRcdHJldHVybiByZWplY3QoXCJIZWFkZXI6IFwiICsgc3RhdHVzKTtcblx0XHRcdFx0XHRcdFx0XHRcdH1cblx0XHRcdFx0XHRcdFx0XHRcdHZhciBkYXRhID0gcmVzLmpzb24oKTtcblx0XHRcdFx0XHRcdFx0XHRcdGlmKCFkYXRhLmFjY2Vzc190b2tlbil7XG5cdFx0XHRcdFx0XHRcdFx0XHRcdHJldHVybiByZWplY3QoXCJObyBhY2Nlc3MgdG9rZW5cIik7XG5cdFx0XHRcdFx0XHRcdFx0XHR9XG5cdFx0XHRcdFx0XHRcdFx0XHR0aGlzLnN0b3JhZ2Uuc2V0KFwibG9nZ2VkaW5cIiwgdHJ1ZSk7XG5cdFx0XHRcdFx0XHRcdFx0XHR0aGlzLnN0b3JhZ2Uuc2V0KFwiYWNjZXNzX3Rva2VuXCIsIGRhdGEuYWNjZXNzX3Rva2VuKTtcblx0XHRcdFx0XHRcdFx0XHRcdHRoaXMuc3RvcmFnZS5zZXQoXCJ1c2VyX2d1aWRcIiwgZGF0YS51c2VyX2lkKTtcblx0XHRcdFx0XHRcdFx0XHRcdHJlc29sdmUodHJ1ZSk7XG5cdFx0XHRcdFx0XHRcdH0pO1xuXG5cdFx0fSk7XG5cdH1cblxufSJdLCJzb3VyY2VSb290IjoiL3NvdXJjZS8ifQ==