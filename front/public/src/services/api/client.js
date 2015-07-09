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
var oauth_1 = require('src/services/api/oauth');
var Client = (function () {
    function Client(http) {
        this.http = http;
        this.base = "https://www.minds.com/";
        this.oauth = new oauth_1.OAuth(http);
    }
    Client.prototype.params = function (object) {
        return Object.keys(object).map(function (k) {
            return encodeURIComponent(k) + "=" + encodeURIComponent(object[k]);
        }).join('&');
    };
    Client.prototype.get = function (endpoint, data, options) {
        var self = this;
        var data = this.oauth.buildParams(data);
        console.log(this.params(data));
        endpoint += "?" + this.params(data);
        return new Promise(function (resolve, reject) {
            self.http.get(self.base + endpoint, options)
                .toRx()
                .subscribe(function (res) {
                if (res.status != 200) {
                    return reject("Header: " + status);
                }
                var data = res.json();
                return resolve(data);
            });
        });
    };
    Client = __decorate([
        __param(0, angular2_1.Inject(angular2_1.Http)), 
        __metadata('design:paramtypes', [(typeof Http !== 'undefined' && Http) || Object])
    ], Client);
    return Client;
})();
exports.Client = Client;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9zZXJ2aWNlcy9hcGkvY2xpZW50LnRzIl0sIm5hbWVzIjpbIkNsaWVudCIsIkNsaWVudC5jb25zdHJ1Y3RvciIsIkNsaWVudC5wYXJhbXMiLCJDbGllbnQuZ2V0Il0sIm1hcHBpbmdzIjoiOzs7Ozs7Ozs7Ozs7OztBQUFBLHlCQUEyQyxtQkFBbUIsQ0FBQyxDQUFBO0FBQy9ELHNCQUFvQix3QkFBd0IsQ0FBQyxDQUFBO0FBSzdDO0lBR0NBLGdCQUFpQ0EsSUFBV0E7UUFBWEMsU0FBSUEsR0FBSkEsSUFBSUEsQ0FBT0E7UUFGNUNBLFNBQUlBLEdBQVlBLHdCQUF3QkEsQ0FBQ0E7UUFHeENBLElBQUlBLENBQUNBLEtBQUtBLEdBQUdBLElBQUlBLGFBQUtBLENBQUNBLElBQUlBLENBQUNBLENBQUNBO0lBQzlCQSxDQUFDQTtJQUVERCx1QkFBTUEsR0FBTkEsVUFBT0EsTUFBZUE7UUFDckJFLE1BQU1BLENBQUNBLE1BQU1BLENBQUNBLElBQUlBLENBQUNBLE1BQU1BLENBQUNBLENBQUNBLEdBQUdBLENBQUNBLFVBQUNBLENBQUNBO1lBQ2hDQSxNQUFNQSxDQUFDQSxrQkFBa0JBLENBQUNBLENBQUNBLENBQUNBLEdBQUdBLEdBQUdBLEdBQUdBLGtCQUFrQkEsQ0FBQ0EsTUFBTUEsQ0FBQ0EsQ0FBQ0EsQ0FBQ0EsQ0FBQ0EsQ0FBQ0E7UUFDcEVBLENBQUNBLENBQUNBLENBQUNBLElBQUlBLENBQUNBLEdBQUdBLENBQUNBLENBQUNBO0lBQ2RBLENBQUNBO0lBS0RGLG9CQUFHQSxHQUFIQSxVQUFJQSxRQUFpQkEsRUFBRUEsSUFBYUEsRUFBRUEsT0FBZUE7UUFDcERHLElBQUlBLElBQUlBLEdBQUdBLElBQUlBLENBQUNBO1FBQ2hCQSxJQUFJQSxJQUFJQSxHQUFHQSxJQUFJQSxDQUFDQSxLQUFLQSxDQUFDQSxXQUFXQSxDQUFDQSxJQUFJQSxDQUFDQSxDQUFDQTtRQUN4Q0EsT0FBT0EsQ0FBQ0EsR0FBR0EsQ0FBQ0EsSUFBSUEsQ0FBQ0EsTUFBTUEsQ0FBQ0EsSUFBSUEsQ0FBQ0EsQ0FBQ0EsQ0FBQ0E7UUFDL0JBLFFBQVFBLElBQUlBLEdBQUdBLEdBQUdBLElBQUlBLENBQUNBLE1BQU1BLENBQUNBLElBQUlBLENBQUNBLENBQUNBO1FBQ3BDQSxNQUFNQSxDQUFDQSxJQUFJQSxPQUFPQSxDQUFDQSxVQUFDQSxPQUFPQSxFQUFFQSxNQUFNQTtZQUNsQ0EsSUFBSUEsQ0FBQ0EsSUFBSUEsQ0FBQ0EsR0FBR0EsQ0FDWEEsSUFBSUEsQ0FBQ0EsSUFBSUEsR0FBR0EsUUFBUUEsRUFDcEJBLE9BQU9BLENBQ1BBO2lCQUNBQSxJQUFJQSxFQUFFQTtpQkFDTkEsU0FBU0EsQ0FBQ0EsVUFBQUEsR0FBR0E7Z0JBQ1pBLEVBQUVBLENBQUFBLENBQUNBLEdBQUdBLENBQUNBLE1BQU1BLElBQUlBLEdBQUdBLENBQUNBLENBQUFBLENBQUNBO29CQUNyQkEsTUFBTUEsQ0FBQ0EsTUFBTUEsQ0FBQ0EsVUFBVUEsR0FBR0EsTUFBTUEsQ0FBQ0EsQ0FBQ0E7Z0JBQ3BDQSxDQUFDQTtnQkFDREEsSUFBSUEsSUFBSUEsR0FBR0EsR0FBR0EsQ0FBQ0EsSUFBSUEsRUFBRUEsQ0FBQ0E7Z0JBQ3RCQSxNQUFNQSxDQUFDQSxPQUFPQSxDQUFDQSxJQUFJQSxDQUFDQSxDQUFDQTtZQUN2QkEsQ0FBQ0EsQ0FBQ0EsQ0FBQ0E7UUFDTEEsQ0FBQ0EsQ0FBQ0EsQ0FBQ0E7SUFDSkEsQ0FBQ0E7SUFuQ0ZIO1FBR2FBLFdBQUNBLGlCQUFNQSxDQUFDQSxlQUFJQSxDQUFDQSxDQUFBQTs7ZUFpQ3pCQTtJQUFEQSxhQUFDQTtBQUFEQSxDQXBDQSxJQW9DQztBQXBDWSxjQUFNLFNBb0NsQixDQUFBIiwiZmlsZSI6InNyYy9zZXJ2aWNlcy9hcGkvY2xpZW50LmpzIiwic291cmNlc0NvbnRlbnQiOlsiaW1wb3J0IHtJbmplY3QsIEluamVjdG9yLCBiaW5kLCBIdHRwfSBmcm9tICdhbmd1bGFyMi9hbmd1bGFyMic7XG5pbXBvcnQge09BdXRofSBmcm9tICdzcmMvc2VydmljZXMvYXBpL29hdXRoJztcblxuLyoqXG4gKiBBUEkgQ2xhc3NcbiAqL1xuZXhwb3J0IGNsYXNzIENsaWVudCB7XG5cdGJhc2UgOiBTdHJpbmcgPSBcImh0dHBzOi8vd3d3Lm1pbmRzLmNvbS9cIjtcblxuXHRjb25zdHJ1Y3RvcihASW5qZWN0KEh0dHApIHB1YmxpYyBodHRwIDogSHR0cCl7IFxuXHRcdHRoaXMub2F1dGggPSBuZXcgT0F1dGgoaHR0cCk7XG5cdH1cblx0XG5cdHBhcmFtcyhvYmplY3QgOiBPYmplY3Qpe1xuXHRcdHJldHVybiBPYmplY3Qua2V5cyhvYmplY3QpLm1hcCgoaykgPT4ge1xuXHRcdFx0cmV0dXJuIGVuY29kZVVSSUNvbXBvbmVudChrKSArIFwiPVwiICsgZW5jb2RlVVJJQ29tcG9uZW50KG9iamVjdFtrXSk7XG5cdFx0fSkuam9pbignJicpO1xuXHR9XG5cblx0LyoqXG5cdCAqIFJldHVybiBhIEdFVCByZXF1ZXN0XG5cdCAqL1xuXHRnZXQoZW5kcG9pbnQgOiBTdHJpbmcsIGRhdGEgOiBPYmplY3QsIG9wdGlvbnM6IE9iamVjdCl7XG5cdFx0dmFyIHNlbGYgPSB0aGlzO1xuXHRcdHZhciBkYXRhID0gdGhpcy5vYXV0aC5idWlsZFBhcmFtcyhkYXRhKTtcblx0XHRjb25zb2xlLmxvZyh0aGlzLnBhcmFtcyhkYXRhKSk7XG5cdFx0ZW5kcG9pbnQgKz0gXCI/XCIgKyB0aGlzLnBhcmFtcyhkYXRhKTtcblx0XHRyZXR1cm4gbmV3IFByb21pc2UoKHJlc29sdmUsIHJlamVjdCkgPT4ge1xuXHRcdFx0c2VsZi5odHRwLmdldChcblx0XHRcdFx0XHRzZWxmLmJhc2UgKyBlbmRwb2ludCwgXG5cdFx0XHRcdFx0b3B0aW9uc1xuXHRcdFx0XHQpXG5cdFx0XHRcdC50b1J4KClcblx0XHRcdFx0LnN1YnNjcmliZShyZXMgPT4ge1xuXHRcdFx0XHRcdFx0aWYocmVzLnN0YXR1cyAhPSAyMDApe1xuXHRcdFx0XHRcdFx0XHRyZXR1cm4gcmVqZWN0KFwiSGVhZGVyOiBcIiArIHN0YXR1cyk7XG5cdFx0XHRcdFx0XHR9XG5cdFx0XHRcdFx0XHR2YXIgZGF0YSA9IHJlcy5qc29uKCk7XG5cdFx0XHRcdFx0XHRyZXR1cm4gcmVzb2x2ZShkYXRhKTtcblx0XHRcdFx0fSk7XG5cdFx0fSk7XG5cdH1cbn0iXSwic291cmNlUm9vdCI6Ii9zb3VyY2UvIn0=