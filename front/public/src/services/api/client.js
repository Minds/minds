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

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9zZXJ2aWNlcy9hcGkvY2xpZW50LnRzIl0sIm5hbWVzIjpbIkNsaWVudCIsIkNsaWVudC5jb25zdHJ1Y3RvciIsIkNsaWVudC5idWlsZFBhcmFtcyIsIkNsaWVudC5idWlsZE9wdGlvbnMiLCJDbGllbnQuZ2V0IiwiQ2xpZW50LnBvc3QiLCJDbGllbnQuZGVsZXRlIl0sIm1hcHBpbmdzIjoiOzs7Ozs7Ozs7Ozs7OztBQUFBLHlCQUFvRCxtQkFBbUIsQ0FBQyxDQUFBO0FBQ3hFLHVCQUFxQixxQkFBcUIsQ0FBQyxDQUFBO0FBSzNDO0lBR0NBLGdCQUFpQ0EsSUFBV0E7UUFBWEMsU0FBSUEsR0FBSkEsSUFBSUEsQ0FBT0E7UUFGNUNBLFNBQUlBLEdBQVlBLEdBQUdBLENBQUNBO1FBQ3BCQSxXQUFNQSxHQUFZQSxJQUFJQSxlQUFNQSxFQUFFQSxDQUFDQTtJQUNnQkEsQ0FBQ0E7SUFFeENELDRCQUFXQSxHQUFuQkEsVUFBb0JBLE1BQWVBO1FBQ2xDRSxNQUFNQSxDQUFDQSxNQUFNQSxDQUFDQSxJQUFJQSxDQUFDQSxNQUFNQSxDQUFDQSxDQUFDQSxHQUFHQSxDQUFDQSxVQUFDQSxDQUFDQTtZQUNoQ0EsTUFBTUEsQ0FBQ0Esa0JBQWtCQSxDQUFDQSxDQUFDQSxDQUFDQSxHQUFHQSxHQUFHQSxHQUFHQSxrQkFBa0JBLENBQUNBLE1BQU1BLENBQUNBLENBQUNBLENBQUNBLENBQUNBLENBQUNBO1FBQ3BFQSxDQUFDQSxDQUFDQSxDQUFDQSxJQUFJQSxDQUFDQSxHQUFHQSxDQUFDQSxDQUFDQTtJQUNkQSxDQUFDQTtJQUtPRiw2QkFBWUEsR0FBcEJBLFVBQXFCQSxPQUFnQkE7UUFDcENHLElBQUlBLFVBQVVBLEdBQUdBLElBQUlBLENBQUNBLE1BQU1BLENBQUNBLEdBQUdBLENBQUNBLFlBQVlBLENBQUNBLENBQUNBO1FBQy9DQSxJQUFJQSxPQUFPQSxHQUFHQSxJQUFJQSxrQkFBT0EsRUFBRUEsQ0FBQ0E7UUFDNUJBLE9BQU9BLENBQUNBLE1BQU1BLENBQUNB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QWtCQSxHQUFsQkEsU0FBa0JBO1FBQUVBLHVCQUFvQkEsR0FBcEJBLFlBQW9CQTtRQUNqRUEsSUFBSUEsSUFBSUEsR0FBR0EsSUFBSUEsQ0FBQ0E7UUFDaEJBLE1BQU1BLENBQUNBLElBQUlBLE9BQU9BLENBQUNBLFVBQUNBLE9BQU9BLEVBQUVBLE1BQU1BO1lBQ2xDQSxJQUFJQSxDQUFDQSxJQUFJQSxDQUFDQSxNQUFNQSxDQUNkQSxJQUFJQSxDQUFDQSxJQUFJQSxHQUFHQSxRQUFRQSxFQUNwQkEsS0FBSUEsQ0FBQ0EsWUFBWUEsQ0FBQ0EsT0FBT0EsQ0FBQ0EsQ0FDMUJBO2lCQUNBQSxJQUFJQSxFQUFFQTtpQkFDTkEsU0FBU0EsQ0FBQ0EsVUFBQUEsR0FBR0E7Z0JBQ1pBLEVBQUVBLENBQUFBLENBQUNBLEdBQUdBLENBQUNBLE1BQU1BLElBQUlBLEdBQUdBLENBQUNBLENBQUFBLENBQUNBO29CQUNyQkEsTUFBTUEsQ0FBQ0EsTUFBTUEsQ0FBQ0EsVUFBVUEsR0FBR0EsTUFBTUEsQ0FBQ0EsQ0FBQ0E7Z0JBQ3BDQSxDQUFDQTtnQkFDREEsSUFBSUEsSUFBSUEsR0FBR0EsR0FBR0EsQ0FBQ0EsSUFBSUEsRUFBRUEsQ0FBQ0E7Z0JBQ3RCQSxNQUFNQSxDQUFDQSxPQUFPQSxDQUFDQSxJQUFJQSxDQUFDQSxDQUFDQTtZQUN2QkEsQ0FBQ0EsQ0FBQ0EsQ0FBQ0E7UUFDTEEsQ0FBQ0EsQ0FBQ0EsQ0FBQ0E7SUFDSkEsQ0FBQ0E7SUF2RkZOO1FBR2FBLFdBQUNBLGlCQUFNQSxDQUFDQSxlQUFJQSxDQUFDQSxDQUFBQTs7ZUFxRnpCQTtJQUFEQSxhQUFDQTtBQUFEQSxDQXhGQSxJQXdGQztBQXhGWSxjQUFNLFNBd0ZsQixDQUFBIiwiZmlsZSI6InNyYy9zZXJ2aWNlcy9hcGkvY2xpZW50LmpzIiwic291cmNlc0NvbnRlbnQiOlsiaW1wb3J0IHtJbmplY3QsIEluamVjdG9yLCBiaW5kLCBIdHRwLCBIZWFkZXJzfSBmcm9tICdhbmd1bGFyMi9hbmd1bGFyMic7XG5pbXBvcnQge0Nvb2tpZX0gZnJvbSAnc3JjL3NlcnZpY2VzL2Nvb2tpZSc7XG5cbi8qKlxuICogQVBJIENsYXNzXG4gKi9cbmV4cG9ydCBjbGFzcyBDbGllbnQge1xuXHRiYXNlIDogc3RyaW5nID0gXCIvXCI7XG5cdGNvb2tpZSA6IENvb2tpZSA9IG5ldyBDb29raWUoKTtcblx0Y29uc3RydWN0b3IoQEluamVjdChIdHRwKSBwdWJsaWMgaHR0cCA6IEh0dHApeyB9XG5cdFxuXHRwcml2YXRlIGJ1aWxkUGFyYW1zKG9iamVjdCA6IE9iamVjdCl7XG5cdFx0cmV0dXJuIE9iamVjdC5rZXlzKG9iamVjdCkubWFwKChrKSA9PiB7XG5cdFx0XHRyZXR1cm4gZW5jb2RlVVJJQ29tcG9uZW50KGspICsgXCI9XCIgKyBlbmNvZGVVUklDb21wb25lbnQob2JqZWN0W2tdKTtcblx0XHR9KS5qb2luKCcmJyk7XG5cdH1cblx0XG5cdC8qKlxuXHQgKiBCdWlsZCB0aGUgb3B0aW9uc1xuXHQgKi9cblx0cHJpdmF0ZSBidWlsZE9wdGlvbnMob3B0aW9ucyA6IE9iamVjdCl7XG5cdFx0dmFyIFhTUkZfVE9LRU4gPSB0aGlzLmNvb2tpZS5nZXQoJ1hTUkYtVE9LRU4nKTtcblx0XHR2YXIgaGVhZGVycyA9IG5ldyBIZWFkZXJzKCk7XG5cdFx0aGVhZGVycy5hcHBlbmQoJ1gtWFNSRi1UT0tFTicsIFhTUkZfVE9LRU4pO1xuXHRcdHJldHVybiBPYmplY3QuYXNzaWduKG9wdGlvbnMsIHtcblx0XHRcdFx0XHRoZWFkZXJzOiBoZWFkZXJzLFxuXHRcdFx0XHRcdGNhY2hlOiB0cnVlXG5cdFx0XHRcdH0pO1xuXHR9XG5cblx0LyoqXG5cdCAqIFJldHVybiBhIEdFVCByZXF1ZXN0XG5cdCAqL1xuXHRnZXQoZW5kcG9pbnQgOiBzdHJpbmcsIGRhdGEgOiBPYmplY3QgPSB7fSwgb3B0aW9uczogT2JqZWN0ID0ge30pe1xuXHRcdHZhciBzZWxmID0gdGhpcztcblx0XHRlbmRwb2ludCArPSBcIj9cIiArIHRoaXMuYnVpbGRQYXJhbXMoZGF0YSk7XG5cdFx0cmV0dXJuIG5ldyBQcm9taXNlKChyZXNvbHZlLCByZWplY3QpID0+IHtcblx0XHRcdHNlbGYuaHR0cC5nZXQoXG5cdFx0XHRcdFx0c2VsZi5iYXNlICsgZW5kcG9pbnQsIFxuXHRcdFx0XHRcdHRoaXMuYnVpbGRPcHRpb25zKG9wdGlvbnMpXG5cdFx0XHRcdClcblx0XHRcdFx0LnRvUngoKVxuXHRcdFx0XHQuc3Vic2NyaWJlKHJlcyA9PiB7XG5cdFx0XHRcdFx0XHRpZihyZXMuc3RhdHVzICE9IDIwMCl7XG5cdFx0XHRcdFx0XHRcdHJldHVybiByZWplY3QoXCJIZWFkZXI6IFwiICsgc3RhdHVzKTtcblx0XHRcdFx0XHRcdH1cblx0XHRcdFx0XHRcdHZhciBkYXRhID0gcmVzLmpzb24oKTtcblx0XHRcdFx0XHRcdHJldHVybiByZXNvbHZlKGRhdGEpO1xuXHRcdFx0XHR9KTtcblx0XHR9KTtcblx0fVxuXHRcblx0LyoqXG5cdCAqIFJldHVybiBhIFBPU1QgcmVxdWVzdFxuXHQgKi9cblx0cG9zdChlbmRwb2ludCA6IHN0cmluZywgZGF0YSA6IE9iamVjdCA9IHt9LCBvcHRpb25zOiBPYmplY3QgPSB7fSl7XG5cdFx0dmFyIHNlbGYgPSB0aGlzO1xuXHRcdHJldHVybiBuZXcgUHJvbWlzZSgocmVzb2x2ZSwgcmVqZWN0KSA9PiB7XG5cdFx0XHRzZWxmLmh0dHAucG9zdChcblx0XHRcdFx0XHRzZWxmLmJhc2UgKyBlbmRwb2ludCxcblx0XHRcdFx0XHRKU09OLnN0cmluZ2lmeShkYXRhKSxcblx0XHRcdFx0XHR0aGlzLmJ1aWxkT3B0aW9ucyhvcHRpb25zKVxuXHRcdFx0XHQpXG5cdFx0XHRcdC50b1J4KClcblx0XHRcdFx0LnN1YnNjcmliZShyZXMgPT4ge1xuXHRcdFx0XHRcdFx0aWYocmVzLnN0YXR1cyAhPSAyMDApe1xuXHRcdFx0XHRcdFx0XHRyZXR1cm4gcmVqZWN0KFwiSGVhZGVyOiBcIiArIHN0YXR1cyk7XG5cdFx0XHRcdFx0XHR9XG5cdFx0XHRcdFx0XHR2YXIgZGF0YSA9IHJlcy5qc29uKCk7XG5cdFx0XHRcdFx0XHRyZXR1cm4gcmVzb2x2ZShkYXRhKTtcblx0XHRcdFx0fSk7XG5cdFx0fSk7XG5cdH1cblxuXHQvKipcblx0ICogUmV0dXJuIGEgREVMRVRFIHJlcXVlc3Rcblx0ICovXG5cdGRlbGV0ZShlbmRwb2ludCA6IHN0cmluZywgZGF0YSA6IE9iamVjdCA9IHt9LCBvcHRpb25zOiBPYmplY3QgPSB7fSl7XG5cdFx0dmFyIHNlbGYgPSB0aGlzO1xuXHRcdHJldHVybiBuZXcgUHJvbWlzZSgocmVzb2x2ZSwgcmVqZWN0KSA9PiB7XG5cdFx0XHRzZWxmLmh0dHAuZGVsZXRlKFxuXHRcdFx0XHRcdHNlbGYuYmFzZSArIGVuZHBvaW50LFxuXHRcdFx0XHRcdHRoaXMuYnVpbGRPcHRpb25zKG9wdGlvbnMpXG5cdFx0XHRcdClcblx0XHRcdFx0LnRvUngoKVxuXHRcdFx0XHQuc3Vic2NyaWJlKHJlcyA9PiB7XG5cdFx0XHRcdFx0XHRpZihyZXMuc3RhdHVzICE9IDIwMCl7XG5cdFx0XHRcdFx0XHRcdHJldHVybiByZWplY3QoXCJIZWFkZXI6IFwiICsgc3RhdHVzKTtcblx0XHRcdFx0XHRcdH1cblx0XHRcdFx0XHRcdHZhciBkYXRhID0gcmVzLmpzb24oKTtcblx0XHRcdFx0XHRcdHJldHVybiByZXNvbHZlKGRhdGEpO1xuXHRcdFx0XHR9KTtcblx0XHR9KTtcblx0fVxufSJdLCJzb3VyY2VSb290IjoiL3NvdXJjZS8ifQ==