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
var api_1 = require('src/services/api');
var session_1 = require('src/services/session');
var material_1 = require('src/directives/material');
var infinite_scroll_1 = require('src/directives/infinite-scroll');
var Wallet = (function () {
    function Wallet(client) {
        this.client = client;
        this.session = session_1.SessionFactory.build();
        this.points = 0;
        this.transactions = [];
        this.offset = "";
        this.inProgress = false;
        this.moreData = true;
        this.getBalance();
        this.loadTransactions();
    }
    Wallet.prototype.getBalance = function () {
        var self = this;
        this.client.get('api/v1/wallet/count', {})
            .then(function (response) {
            self.points = response.count;
        });
    };
    Wallet.prototype.loadTransactions = function (refresh) {
        if (refresh === void 0) { refresh = false; }
        var self = this;
        this.inProgress = true;
        this.client.get('api/v1/wallet/transactions', { limit: 12, offset: this.offset })
            .then(function (response) {
            if (!response.transactions) {
                self.moreData = false;
                self.inProgress = false;
                return false;
            }
            if (refresh) {
                self.transactions = response.transactions;
            }
            else {
                if (self.offset)
                    response.transactions.shift();
                for (var _i = 0, _a = response.transactions; _i < _a.length; _i++) {
                    var transaction = _a[_i];
                    self.transactions.push(transaction);
                }
            }
            self.offset = response['load-next'];
            self.inProgress = false;
        })
            .catch(function (e) {
        });
    };
    Wallet = __decorate([
        angular2_1.Component({
            selector: 'minds-wallet',
            viewBindings: [api_1.Client]
        }),
        angular2_1.View({
            templateUrl: 'templates/plugins/payments/wallet.html',
            directives: [angular2_1.NgFor, angular2_1.NgIf, angular2_1.NgClass, material_1.Material, angular2_1.FORM_DIRECTIVES, infinite_scroll_1.InfiniteScroll]
        }), 
        __metadata('design:paramtypes', [api_1.Client])
    ], Wallet);
    return Wallet;
})();
exports.Wallet = Wallet;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9wbHVnaW5zL3BheW1lbnRzL3dhbGxldC50cyJdLCJuYW1lcyI6WyJXYWxsZXQiLCJXYWxsZXQuY29uc3RydWN0b3IiLCJXYWxsZXQuZ2V0QmFsYW5jZSIsIldhbGxldC5sb2FkVHJhbnNhY3Rpb25zIl0sIm1hcHBpbmdzIjoiOzs7Ozs7Ozs7OztBQUFBLHlCQUFrRixtQkFBbUIsQ0FBQyxDQUFBO0FBRXRHLG9CQUF1QixrQkFBa0IsQ0FBQyxDQUFBO0FBQzFDLHdCQUErQixzQkFBc0IsQ0FBQyxDQUFBO0FBQ3RELHlCQUF5Qix5QkFBeUIsQ0FBQyxDQUFBO0FBQ25ELGdDQUErQixnQ0FBZ0MsQ0FBQyxDQUFBO0FBRWhFO0lBa0JDQSxnQkFBbUJBLE1BQWNBO1FBQWRDLFdBQU1BLEdBQU5BLE1BQU1BLENBQVFBO1FBUGhDQSxZQUFPQSxHQUFHQSx3QkFBY0EsQ0FBQ0EsS0FBS0EsRUFBRUEsQ0FBQ0E7UUFDakNBLFdBQU1BLEdBQVlBLENBQUNBLENBQUNBO1FBQ3BCQSxpQkFBWUEsR0FBZ0JBLEVBQUVBLENBQUNBO1FBQy9CQSxXQUFNQSxHQUFXQSxFQUFFQSxDQUFDQTtRQUNwQkEsZUFBVUEsR0FBYUEsS0FBS0EsQ0FBQ0E7UUFDN0JBLGFBQVFBLEdBQWFBLElBQUlBLENBQUNBO1FBR3hCQSxJQUFJQSxDQUFDQSxVQUFVQSxFQUFFQSxDQUFDQTtRQUNsQkEsSUFBSUEsQ0FBQ0EsZ0JBQWdCQSxFQUFFQSxDQUFDQTtJQUMzQkEsQ0FBQ0E7SUFFQUQsMkJBQVVBLEdBQVZBO1FBQ0VFLElBQUlBLElBQUlBLEdBQUdBLElBQUlBLENBQUNBO1FBQ2hCQSxJQUFJQSxDQUFDQSxNQUFNQSxDQUFDQSxHQUFHQSxDQUFDQSxxQkFBcUJBLEVBQUVBLEVBQUVBLENBQUNBO2FBQ3ZDQSxJQUFJQSxDQUFDQSxVQUFDQSxRQUFRQTtZQUNiQSxJQUFJQSxDQUFDQSxNQUFNQSxHQUFHQSxRQUFRQSxDQUFDQSxLQUFLQSxDQUFBQTtRQUM1QkEsQ0FBQ0EsQ0FBQ0EsQ0FBQ0E7SUFDVEEsQ0FBQ0E7SUFFREYsaUNBQWdCQSxHQUFoQkEsVUFBaUJBLE9BQXlCQTtRQUF6QkcsdUJBQXlCQSxHQUF6QkEsZUFBeUJBO1FBQ3hDQSxJQUFJQSxJQUFJQSxHQUFHQSxJQUFJQSxDQUFDQTtRQUNoQkEsSUFBSUEsQ0FBQ0EsVUFBVUEsR0FBR0EsSUFBSUEsQ0FBQ0E7UUFDdkJBLElBQUlBLENBQUNBLE1BQU1BLENBQUNBLEdBQUdBLENBQUNBLDRCQUE0QkEsRUFBRUEsRUFBRUEsS0FBS0EsRUFBRUEsRUFBRUEsRUFBRUEsTUFBTUEsRUFBRUEsSUFBSUEsQ0FBQ0EsTUFBTUEsRUFBQ0EsQ0FBQ0E7YUFDN0VBLElBQUlBLENBQUNBLFVBQUNBLFFBQVFBO1lBRWJBLEVBQUVBLENBQUFBLENBQUNBLENBQUNBLFFBQVFBLENBQUNBLFlBQVlBLENBQUNBLENBQUFBLENBQUNBO2dCQUN6QkEsSUFBSUEsQ0FBQ0EsUUFBUUEsR0FBR0EsS0FBS0EsQ0FBQ0E7Z0JBQ3RCQSxJQUFJQSxDQUFDQSxVQUFVQSxHQUFHQSxLQUFLQSxDQUFDQTtnQkFDeEJBLE1BQU1BLENBQUNBLEtBQUtBLENBQUNBO1lBQ2ZBLENBQUNBO1lBRURBLEVBQUVBLENBQUFBLENBQUNBLE9BQU9BLENBQUNBLENBQUFBLENBQUNBO2dCQUNWQSxJQUFJQSxDQUFDQSxZQUFZQSxHQUFHQSxRQUFRQSxDQUFDQSxZQUFZQSxDQUFBQTtZQUMzQ0EsQ0FBQ0E7WUFBQ0EsSUFBSUEsQ0FBQ0EsQ0FBQ0E7Z0JBQ05BLEVBQUVBLENBQUFBLENBQUNBLElBQUlBLENBQUNBLE1BQU1BLENBQUNBO29CQUNiQSxRQUFRQSxDQUFDQSxZQUFZQSxDQUFDQSxLQUFLQSxFQUFFQSxDQUFDQTtnQkFDaENBLEdBQUdBLENBQUFBLENBQW9CQSxVQUFxQkEsRUFBckJBLEtBQUFBLFFBQVFBLENBQUNBLFlBQVlBLEVBQXhDQSxjQUFlQSxFQUFmQSxJQUF3Q0EsQ0FBQ0E7b0JBQXpDQSxJQUFJQSxXQUFXQSxTQUFBQTtvQkFDakJBLElBQUlBLENBQUNBLFlBQVlBLENBQUNBLElBQUlBLENBQUNBLFdBQVdBLENBQUNBLENBQUNBO2lCQUFBQTtZQUN4Q0EsQ0FBQ0E7WUFFREEsSUFBSUEsQ0FBQ0EsTUFBTUEsR0FBR0EsUUFBUUEsQ0FBQ0EsV0FBV0EsQ0FBQ0EsQ0FBQ0E7WUFDcENBLElBQUlBLENBQUNBLFVBQVVBLEdBQUdBLEtBQUtBLENBQUNBO1FBQzFCQSxDQUFDQSxDQUFDQTthQUNEQSxLQUFLQSxDQUFDQSxVQUFDQSxDQUFDQTtRQUVUQSxDQUFDQSxDQUFDQSxDQUFDQTtJQUNQQSxDQUFDQTtJQTFESEg7UUFBQ0Esb0JBQVNBLENBQUNBO1lBQ1RBLFFBQVFBLEVBQUVBLGNBQWNBO1lBQ3hCQSxZQUFZQSxFQUFFQSxDQUFFQSxZQUFNQSxDQUFFQTtTQUN6QkEsQ0FBQ0E7UUFDREEsZUFBSUEsQ0FBQ0E7WUFDSkEsV0FBV0EsRUFBRUEsd0NBQXdDQTtZQUNyREEsVUFBVUEsRUFBRUEsQ0FBRUEsZ0JBQUtBLEVBQUVBLGVBQUlBLEVBQUVBLGtCQUFPQSxFQUFFQSxtQkFBUUEsRUFBRUEsMEJBQWVBLEVBQUVBLGdDQUFjQSxDQUFFQTtTQUNoRkEsQ0FBQ0E7O2VBcUREQTtJQUFEQSxhQUFDQTtBQUFEQSxDQTVEQSxBQTREQ0EsSUFBQTtBQW5EWSxjQUFNLFNBbURsQixDQUFBIiwiZmlsZSI6InNyYy9wbHVnaW5zL3BheW1lbnRzL3dhbGxldC5qcyIsInNvdXJjZXNDb250ZW50IjpbImltcG9ydCB7IENvbXBvbmVudCwgVmlldywgTmdGb3IsIE5nSWYsIE5nQ2xhc3MsIE9ic2VydmFibGUsIEZPUk1fRElSRUNUSVZFU30gZnJvbSAnYW5ndWxhcjIvYW5ndWxhcjInO1xuaW1wb3J0IHsgUm91dGVyTGluayB9IGZyb20gXCJhbmd1bGFyMi9yb3V0ZXJcIjtcbmltcG9ydCB7IENsaWVudCB9IGZyb20gJ3NyYy9zZXJ2aWNlcy9hcGknO1xuaW1wb3J0IHsgU2Vzc2lvbkZhY3RvcnkgfSBmcm9tICdzcmMvc2VydmljZXMvc2Vzc2lvbic7XG5pbXBvcnQgeyBNYXRlcmlhbCB9IGZyb20gJ3NyYy9kaXJlY3RpdmVzL21hdGVyaWFsJztcbmltcG9ydCB7IEluZmluaXRlU2Nyb2xsIH0gZnJvbSAnc3JjL2RpcmVjdGl2ZXMvaW5maW5pdGUtc2Nyb2xsJztcblxuQENvbXBvbmVudCh7XG4gIHNlbGVjdG9yOiAnbWluZHMtd2FsbGV0JyxcbiAgdmlld0JpbmRpbmdzOiBbIENsaWVudCBdXG59KVxuQFZpZXcoe1xuICB0ZW1wbGF0ZVVybDogJ3RlbXBsYXRlcy9wbHVnaW5zL3BheW1lbnRzL3dhbGxldC5odG1sJyxcbiAgZGlyZWN0aXZlczogWyBOZ0ZvciwgTmdJZiwgTmdDbGFzcywgTWF0ZXJpYWwsIEZPUk1fRElSRUNUSVZFUywgSW5maW5pdGVTY3JvbGwgXVxufSlcblxuZXhwb3J0IGNsYXNzIFdhbGxldCB7XG5cbiAgc2Vzc2lvbiA9IFNlc3Npb25GYWN0b3J5LmJ1aWxkKCk7XG4gIHBvaW50cyA6IE51bWJlciA9IDA7XG4gIHRyYW5zYWN0aW9ucyA6IEFycmF5PGFueT4gPSBbXTtcbiAgb2Zmc2V0OiBzdHJpbmcgPSBcIlwiO1xuICBpblByb2dyZXNzIDogYm9vbGVhbiA9IGZhbHNlO1xuICBtb3JlRGF0YSA6IGJvb2xlYW4gPSB0cnVlO1xuXG5cdGNvbnN0cnVjdG9yKHB1YmxpYyBjbGllbnQ6IENsaWVudCl7XG4gICAgdGhpcy5nZXRCYWxhbmNlKCk7XG4gICAgdGhpcy5sb2FkVHJhbnNhY3Rpb25zKCk7XG5cdH1cblxuICBnZXRCYWxhbmNlKCl7XG4gICAgdmFyIHNlbGYgPSB0aGlzO1xuICAgIHRoaXMuY2xpZW50LmdldCgnYXBpL3YxL3dhbGxldC9jb3VudCcsIHt9KVxuICAgICAgLnRoZW4oKHJlc3BvbnNlKSA9PiB7XG4gICAgICAgIHNlbGYucG9pbnRzID0gcmVzcG9uc2UuY291bnRcbiAgICAgICAgfSk7XG4gIH1cblxuICBsb2FkVHJhbnNhY3Rpb25zKHJlZnJlc2ggOiBib29sZWFuID0gZmFsc2Upe1xuICAgIHZhciBzZWxmID0gdGhpcztcbiAgICB0aGlzLmluUHJvZ3Jlc3MgPSB0cnVlO1xuICAgIHRoaXMuY2xpZW50LmdldCgnYXBpL3YxL3dhbGxldC90cmFuc2FjdGlvbnMnLCB7IGxpbWl0OiAxMiwgb2Zmc2V0OiB0aGlzLm9mZnNldH0pXG4gICAgICAudGhlbigocmVzcG9uc2UpID0+IHtcblxuICAgICAgICBpZighcmVzcG9uc2UudHJhbnNhY3Rpb25zKXtcbiAgICAgICAgICBzZWxmLm1vcmVEYXRhID0gZmFsc2U7XG4gICAgICAgICAgc2VsZi5pblByb2dyZXNzID0gZmFsc2U7XG4gICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICB9XG5cbiAgICAgICAgaWYocmVmcmVzaCl7XG4gICAgICAgICAgc2VsZi50cmFuc2FjdGlvbnMgPSByZXNwb25zZS50cmFuc2FjdGlvbnNcbiAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICBpZihzZWxmLm9mZnNldClcbiAgICAgICAgICAgIHJlc3BvbnNlLnRyYW5zYWN0aW9ucy5zaGlmdCgpO1xuICAgICAgICAgIGZvcihsZXQgdHJhbnNhY3Rpb24gb2YgcmVzcG9uc2UudHJhbnNhY3Rpb25zKVxuICAgICAgICAgICAgc2VsZi50cmFuc2FjdGlvbnMucHVzaCh0cmFuc2FjdGlvbik7XG4gICAgICAgIH1cblxuICAgICAgICBzZWxmLm9mZnNldCA9IHJlc3BvbnNlWydsb2FkLW5leHQnXTtcbiAgICAgICAgc2VsZi5pblByb2dyZXNzID0gZmFsc2U7XG4gICAgICB9KVxuICAgICAgLmNhdGNoKChlKT0+e1xuXG4gICAgICB9KTtcbiAgfVxuXG59XG4iXSwic291cmNlUm9vdCI6Ii9zb3VyY2UvIn0=