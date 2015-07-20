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
var cookie_1 = require('src/services/cookie');
var Client = (function () {
    function Client(http) {
        this.http = http;
        this.base = "/";
        this.cookie = new cookie_1.Cookie();
    }
    Client.prototype.buildParams = function (object) {
        return Object.keys(object).map(function (k) {
            return encodeURIComponent(k) + "=" + encodeURIComponent(object[k]);
        }).join('&');
    };
    Client.prototype.buildOptions = function (options) {
        var XSRF_TOKEN = this.cookie.get('XSRF-TOKEN');
        var headers = new angular2_1.Headers();
        headers.append('X-XSRF-TOKEN', XSRF_TOKEN);
        return Object.assign(options, {
            headers: headers,
            cache: true
        });
    };
    Client.prototype.get = function (endpoint, data, options) {
        var _this = this;
        if (data === void 0) { data = {}; }
        if (options === void 0) { options = {}; }
        var self = this;
        endpoint += "?" + this.buildParams(data);
        return new Promise(function (resolve, reject) {
            self.http.get(self.base + endpoint, _this.buildOptions(options))
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
    Client.prototype.post = function (endpoint, data, options) {
        var _this = this;
        if (data === void 0) { data = {}; }
        if (options === void 0) { options = {}; }
        var self = this;
        return new Promise(function (resolve, reject) {
            self.http.post(self.base + endpoint, JSON.stringify(data), _this.buildOptions(options))
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
    Client.prototype.delete = function (endpoint, data, options) {
        var _this = this;
        if (data === void 0) { data = {}; }
        if (options === void 0) { options = {}; }
        var self = this;
        return new Promise(function (resolve, reject) {
            self.http.delete(self.base + endpoint, _this.buildOptions(options))
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
        __metadata('design:paramtypes', [angular2_1.Http])
    ], Client);
    return Client;
})();
exports.Client = Client;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9zZXJ2aWNlcy9hcGkvY2xpZW50LnRzIl0sIm5hbWVzIjpbIkNsaWVudCIsIkNsaWVudC5jb25zdHJ1Y3RvciIsIkNsaWVudC5idWlsZFBhcmFtcyIsIkNsaWVudC5idWlsZE9wdGlvbnMiLCJDbGllbnQuZ2V0IiwiQ2xpZW50LnBvc3QiLCJDbGllbnQuZGVsZXRlIl0sIm1hcHBpbmdzIjoiOzs7Ozs7Ozs7Ozs7OztBQUFBLHlCQUFvRCxtQkFBbUIsQ0FBQyxDQUFBO0FBQ3hFLHVCQUFxQixxQkFBcUIsQ0FBQyxDQUFBO0FBSzNDO0lBR0NBLGdCQUFpQ0EsSUFBV0E7UUFBWEMsU0FBSUEsR0FBSkEsSUFBSUEsQ0FBT0E7UUFGNUNBLFNBQUlBLEdBQVlBLEdBQUdBLENBQUNBO1FBQ3BCQSxXQUFNQSxHQUFZQSxJQUFJQSxlQUFNQSxFQUFFQSxDQUFDQTtJQUNnQkEsQ0FBQ0E7SUFFeENELDRCQUFXQSxHQUFuQkEsVUFBb0JBLE1BQWVBO1FBQ2xDRSxNQUFNQSxDQUFDQSxNQUFNQSxDQUFDQSxJQUFJQSxDQUFDQSxNQUFNQSxDQUFDQSxDQUFDQSxHQUFHQSxDQUFDQSxVQUFDQSxDQUFDQTtZQUNoQ0EsTUFBTUEsQ0FBQ0Esa0JBQWtCQSxDQUFDQSxDQUFDQSxDQUFDQSxHQUFHQSxHQUFHQSxHQUFHQSxrQkFBa0JBLENBQUNBLE1BQU1BLENBQUNBLENBQUNBLENBQUNBLENBQUNBLENBQUNBO1FBQ3BFQSxDQUFDQSxDQUFDQSxDQUFDQSxJQUFJQSxDQUFDQSxHQUFHQSxDQUFDQSxDQUFDQTtJQUNkQSxDQUFDQTtJQUtPRiw2QkFBWUEsR0FBcEJBLFVBQXFCQSxPQUFnQkE7UUFDcENHLElBQUlBLFVBQVVBLEdBQUdBLElBQUlBLENBQUNBLE1BQU1BLENBQUNBLEdBQUdBLENBQUNBLFlBQVlBLENBQUNBLENBQUNBO1FBQy9DQSxJQUFJQSxPQUFPQSxHQUFHQSxJQUFJQSxrQkFBT0EsRUFBRUEsQ0FBQ0E7UUFDNUJBLE9BQU9BLENBQUNBLE1BQU1BLENBQUNB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QWtCQSxHQUFsQkEsU0FBa0JBO1FBQUVBLHVCQUFvQkEsR0FBcEJBLFlBQW9CQTtRQUNqRUEsSUFBSUEsSUFBSUEsR0FBR0EsSUFBSUEsQ0FBQ0E7UUFDaEJBLE1BQU1BLENBQUNBLElBQUlBLE9BQU9BLENBQUNBLFVBQUNBLE9BQU9BLEVBQUVBLE1BQU1BO1lBQ2xDQSxJQUFJQSxDQUFDQSxJQUFJQSxDQUFDQSxNQUFNQSxDQUNkQSxJQUFJQSxDQUFDQSxJQUFJQSxHQUFHQSxRQUFRQSxFQUNwQkEsS0FBSUEsQ0FBQ0EsWUFBWUEsQ0FBQ0EsT0FBT0EsQ0FBQ0EsQ0FDMUJBO2lCQUNBQSxJQUFJQSxFQUFFQTtpQkFDTkEsU0FBU0EsQ0FBQ0EsVUFBQUEsR0FBR0E7Z0JBQ1pBLEVBQUVBLENBQUFBLENBQUNBLEdBQUdBLENBQUNBLE1BQU1BLElBQUlBLEdBQUdBLENBQUNBLENBQUFBLENBQUNBO29CQUNyQkEsTUFBTUEsQ0FBQ0EsTUFBTUEsQ0FBQ0EsVUFBVUEsR0FBR0EsTUFBTUEsQ0FBQ0EsQ0FBQ0E7Z0JBQ3BDQSxDQUFDQTtnQkFDREEsSUFBSUEsSUFBSUEsR0FBR0EsR0FBR0EsQ0FBQ0EsSUFBSUEsRUFBRUEsQ0FBQ0E7Z0JBQ3RCQSxNQUFNQSxDQUFDQSxPQUFPQSxDQUFDQSxJQUFJQSxDQUFDQSxDQUFDQTtZQUN2QkEsQ0FBQ0EsQ0FBQ0EsQ0FBQ0E7UUFDTEEsQ0FBQ0EsQ0FBQ0EsQ0FBQ0E7SUFDSkEsQ0FBQ0E7SUF2RkZOO1FBR2FBLFdBQUNBLGlCQUFNQSxDQUFDQSxlQUFJQSxDQUFDQSxDQUFBQTs7ZUFxRnpCQTtJQUFEQSxhQUFDQTtBQUFEQSxDQXhGQSxBQXdGQ0EsSUFBQTtBQXhGWSxjQUFNLFNBd0ZsQixDQUFBIiwiZmlsZSI6InNyYy9zZXJ2aWNlcy9hcGkvY2xpZW50LmpzIiwic291cmNlc0NvbnRlbnQiOlsiaW1wb3J0IHtJbmplY3QsIEluamVjdG9yLCBiaW5kLCBIdHRwLCBIZWFkZXJzfSBmcm9tICdhbmd1bGFyMi9hbmd1bGFyMic7XG5pbXBvcnQge0Nvb2tpZX0gZnJvbSAnc3JjL3NlcnZpY2VzL2Nvb2tpZSc7XG5cbi8qKlxuICogQVBJIENsYXNzXG4gKi9cbmV4cG9ydCBjbGFzcyBDbGllbnQge1xuXHRiYXNlIDogc3RyaW5nID0gXCIvXCI7XG5cdGNvb2tpZSA6IENvb2tpZSA9IG5ldyBDb29raWUoKTtcblx0Y29uc3RydWN0b3IoQEluamVjdChIdHRwKSBwdWJsaWMgaHR0cCA6IEh0dHApeyB9XG5cblx0cHJpdmF0ZSBidWlsZFBhcmFtcyhvYmplY3QgOiBPYmplY3Qpe1xuXHRcdHJldHVybiBPYmplY3Qua2V5cyhvYmplY3QpLm1hcCgoaykgPT4ge1xuXHRcdFx0cmV0dXJuIGVuY29kZVVSSUNvbXBvbmVudChrKSArIFwiPVwiICsgZW5jb2RlVVJJQ29tcG9uZW50KG9iamVjdFtrXSk7XG5cdFx0fSkuam9pbignJicpO1xuXHR9XG5cblx0LyoqXG5cdCAqIEJ1aWxkIHRoZSBvcHRpb25zXG5cdCAqL1xuXHRwcml2YXRlIGJ1aWxkT3B0aW9ucyhvcHRpb25zIDogT2JqZWN0KXtcblx0XHR2YXIgWFNSRl9UT0tFTiA9IHRoaXMuY29va2llLmdldCgnWFNSRi1UT0tFTicpO1xuXHRcdHZhciBoZWFkZXJzID0gbmV3IEhlYWRlcnMoKTtcblx0XHRoZWFkZXJzLmFwcGVuZCgnWC1YU1JGLVRPS0VOJywgWFNSRl9UT0tFTik7XG5cdFx0cmV0dXJuIE9iamVjdC5hc3NpZ24ob3B0aW9ucywge1xuXHRcdFx0XHRcdGhlYWRlcnM6IGhlYWRlcnMsXG5cdFx0XHRcdFx0Y2FjaGU6IHRydWVcblx0XHRcdFx0fSk7XG5cdH1cblxuXHQvKipcblx0ICogUmV0dXJuIGEgR0VUIHJlcXVlc3Rcblx0ICovXG5cdGdldChlbmRwb2ludCA6IHN0cmluZywgZGF0YSA6IE9iamVjdCA9IHt9LCBvcHRpb25zOiBPYmplY3QgPSB7fSl7XG5cdFx0dmFyIHNlbGYgPSB0aGlzO1xuXHRcdGVuZHBvaW50ICs9IFwiP1wiICsgdGhpcy5idWlsZFBhcmFtcyhkYXRhKTtcblx0XHRyZXR1cm4gbmV3IFByb21pc2UoKHJlc29sdmUsIHJlamVjdCkgPT4ge1xuXHRcdFx0c2VsZi5odHRwLmdldChcblx0XHRcdFx0XHRzZWxmLmJhc2UgKyBlbmRwb2ludCxcblx0XHRcdFx0XHR0aGlzLmJ1aWxkT3B0aW9ucyhvcHRpb25zKVxuXHRcdFx0XHQpXG5cdFx0XHRcdC50b1J4KClcblx0XHRcdFx0LnN1YnNjcmliZShyZXMgPT4ge1xuXHRcdFx0XHRcdFx0aWYocmVzLnN0YXR1cyAhPSAyMDApe1xuXHRcdFx0XHRcdFx0XHRyZXR1cm4gcmVqZWN0KFwiSGVhZGVyOiBcIiArIHN0YXR1cyk7XG5cdFx0XHRcdFx0XHR9XG5cdFx0XHRcdFx0XHR2YXIgZGF0YSA9IHJlcy5qc29uKCk7XG5cdFx0XHRcdFx0XHRyZXR1cm4gcmVzb2x2ZShkYXRhKTtcblx0XHRcdFx0fSk7XG5cdFx0fSk7XG5cdH1cblxuXHQvKipcblx0ICogUmV0dXJuIGEgUE9TVCByZXF1ZXN0XG5cdCAqL1xuXHRwb3N0KGVuZHBvaW50IDogc3RyaW5nLCBkYXRhIDogT2JqZWN0ID0ge30sIG9wdGlvbnM6IE9iamVjdCA9IHt9KXtcblx0XHR2YXIgc2VsZiA9IHRoaXM7XG5cdFx0cmV0dXJuIG5ldyBQcm9taXNlKChyZXNvbHZlLCByZWplY3QpID0+IHtcblx0XHRcdHNlbGYuaHR0cC5wb3N0KFxuXHRcdFx0XHRcdHNlbGYuYmFzZSArIGVuZHBvaW50LFxuXHRcdFx0XHRcdEpTT04uc3RyaW5naWZ5KGRhdGEpLFxuXHRcdFx0XHRcdHRoaXMuYnVpbGRPcHRpb25zKG9wdGlvbnMpXG5cdFx0XHRcdClcblx0XHRcdFx0LnRvUngoKVxuXHRcdFx0XHQuc3Vic2NyaWJlKHJlcyA9PiB7XG5cdFx0XHRcdFx0XHRpZihyZXMuc3RhdHVzICE9IDIwMCl7XG5cdFx0XHRcdFx0XHRcdHJldHVybiByZWplY3QoXCJIZWFkZXI6IFwiICsgc3RhdHVzKTtcblx0XHRcdFx0XHRcdH1cblx0XHRcdFx0XHRcdHZhciBkYXRhID0gcmVzLmpzb24oKTtcblx0XHRcdFx0XHRcdHJldHVybiByZXNvbHZlKGRhdGEpO1xuXHRcdFx0XHR9KTtcblx0XHR9KTtcblx0fVxuXG5cdC8qKlxuXHQgKiBSZXR1cm4gYSBERUxFVEUgcmVxdWVzdFxuXHQgKi9cblx0ZGVsZXRlKGVuZHBvaW50IDogc3RyaW5nLCBkYXRhIDogT2JqZWN0ID0ge30sIG9wdGlvbnM6IE9iamVjdCA9IHt9KXtcblx0XHR2YXIgc2VsZiA9IHRoaXM7XG5cdFx0cmV0dXJuIG5ldyBQcm9taXNlKChyZXNvbHZlLCByZWplY3QpID0+IHtcblx0XHRcdHNlbGYuaHR0cC5kZWxldGUoXG5cdFx0XHRcdFx0c2VsZi5iYXNlICsgZW5kcG9pbnQsXG5cdFx0XHRcdFx0dGhpcy5idWlsZE9wdGlvbnMob3B0aW9ucylcblx0XHRcdFx0KVxuXHRcdFx0XHQudG9SeCgpXG5cdFx0XHRcdC5zdWJzY3JpYmUocmVzID0+IHtcblx0XHRcdFx0XHRcdGlmKHJlcy5zdGF0dXMgIT0gMjAwKXtcblx0XHRcdFx0XHRcdFx0cmV0dXJuIHJlamVjdChcIkhlYWRlcjogXCIgKyBzdGF0dXMpO1xuXHRcdFx0XHRcdFx0fVxuXHRcdFx0XHRcdFx0dmFyIGRhdGEgPSByZXMuanNvbigpO1xuXHRcdFx0XHRcdFx0cmV0dXJuIHJlc29sdmUoZGF0YSk7XG5cdFx0XHRcdH0pO1xuXHRcdH0pO1xuXHR9XG59XG4iXSwic291cmNlUm9vdCI6Ii9zb3VyY2UvIn0=