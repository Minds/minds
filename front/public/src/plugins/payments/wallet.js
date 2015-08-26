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
            console.log(response);
            return;
            if (!response.groups) {
                self.moreData = false;
                self.inProgress = false;
                return false;
            }
            if (refresh) {
                self.groups = response.groups;
            }
            else {
                if (self.offset)
                    response.groups.shift();
                for (var _i = 0, _a = response.groups; _i < _a.length; _i++) {
                    var group = _a[_i];
                    self.groups.push(group);
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
            directives: [angular2_1.NgFor, angular2_1.NgIf, angular2_1.NgClass, material_1.Material, angular2_1.FORM_DIRECTIVES]
        }), 
        __metadata('design:paramtypes', [api_1.Client])
    ], Wallet);
    return Wallet;
})();
exports.Wallet = Wallet;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9wbHVnaW5zL3BheW1lbnRzL3dhbGxldC50cyJdLCJuYW1lcyI6WyJXYWxsZXQiLCJXYWxsZXQuY29uc3RydWN0b3IiLCJXYWxsZXQuZ2V0QmFsYW5jZSIsIldhbGxldC5sb2FkVHJhbnNhY3Rpb25zIl0sIm1hcHBpbmdzIjoiOzs7Ozs7Ozs7OztBQUFBLHlCQUFrRixtQkFBbUIsQ0FBQyxDQUFBO0FBRXRHLG9CQUF1QixrQkFBa0IsQ0FBQyxDQUFBO0FBQzFDLHdCQUErQixzQkFBc0IsQ0FBQyxDQUFBO0FBQ3RELHlCQUF5Qix5QkFBeUIsQ0FBQyxDQUFBO0FBRW5EO0lBa0JDQSxnQkFBbUJBLE1BQWNBO1FBQWRDLFdBQU1BLEdBQU5BLE1BQU1BLENBQVFBO1FBUGhDQSxZQUFPQSxHQUFHQSx3QkFBY0EsQ0FBQ0EsS0FBS0EsRUFBRUEsQ0FBQ0E7UUFDakNBLFdBQU1BLEdBQVlBLENBQUNBLENBQUNBO1FBQ3BCQSxpQkFBWUEsR0FBZ0JBLEVBQUVBLENBQUNBO1FBQy9CQSxXQUFNQSxHQUFXQSxFQUFFQSxDQUFDQTtRQUNwQkEsZUFBVUEsR0FBYUEsS0FBS0EsQ0FBQ0E7UUFDN0JBLGFBQVFBLEdBQWFBLElBQUlBLENBQUNBO1FBR3hCQSxJQUFJQSxDQUFDQSxVQUFVQSxFQUFFQSxDQUFDQTtRQUNsQkEsSUFBSUEsQ0FBQ0EsZ0JBQWdCQSxFQUFFQSxDQUFDQTtJQUMzQkEsQ0FBQ0E7SUFFQUQsMkJBQVVBLEdBQVZBO1FBQ0VFLElBQUlBLElBQUlBLEdBQUdBLElBQUlBLENBQUNBO1FBQ2hCQSxJQUFJQSxDQUFDQSxNQUFNQSxDQUFDQSxHQUFHQSxDQUFDQSxxQkFBcUJBLEVBQUVBLEVBQUVBLENBQUNBO2FBQ3ZDQSxJQUFJQSxDQUFDQSxVQUFDQSxRQUFRQTtZQUNiQSxJQUFJQSxDQUFDQSxNQUFNQSxHQUFHQSxRQUFRQSxDQUFDQSxLQUFLQSxDQUFBQTtRQUM1QkEsQ0FBQ0EsQ0FBQ0EsQ0FBQ0E7SUFDVEEsQ0FBQ0E7SUFFREYsaUNBQWdCQSxHQUFoQkEsVUFBaUJBLE9BQXlCQTtRQUF6QkcsdUJBQXlCQSxHQUF6QkEsZUFBeUJBO1FBQ3hDQSxJQUFJQSxJQUFJQSxHQUFHQSxJQUFJQSxDQUFDQTtRQUNoQkEsSUFBSUEsQ0FBQ0EsVUFBVUEsR0FBR0EsSUFBSUEsQ0FBQ0E7UUFDdkJBLElBQUlBLENBQUNBLE1BQU1BLENBQUNBLEdBQUdBLENBQUNBLDRCQUE0QkEsRUFBRUEsRUFBRUEsS0FBS0EsRUFBRUEsRUFBRUEsRUFBRUEsTUFBTUEsRUFBRUEsSUFBSUEsQ0FBQ0EsTUFBTUEsRUFBQ0EsQ0FBQ0E7YUFDN0VBLElBQUlBLENBQUNBLFVBQUNBLFFBQVFBO1lBQ1hBLE9BQU9BLENBQUNBLEdBQUdBLENBQUNBLFFBQVFBLENBQUNBLENBQUNBO1lBQ3RCQSxNQUFNQSxDQUFDQTtZQUNUQSxFQUFFQSxDQUFBQSxDQUFDQSxDQUFDQSxRQUFRQSxDQUFDQSxNQUFNQSxDQUFDQSxDQUFBQSxDQUFDQTtnQkFDbkJBLElBQUlBLENBQUNBLFFBQVFBLEdBQUdBLEtBQUtBLENBQUNBO2dCQUN0QkEsSUFBSUEsQ0FBQ0EsVUFBVUEsR0FBR0EsS0FBS0EsQ0FBQ0E7Z0JBQ3hCQSxNQUFNQSxDQUFDQSxLQUFLQSxDQUFDQTtZQUNmQSxDQUFDQTtZQUVEQSxFQUFFQSxDQUFBQSxDQUFDQSxPQUFPQSxDQUFDQSxDQUFBQSxDQUFDQTtnQkFDVkEsSUFBSUEsQ0FBQ0EsTUFBTUEsR0FBR0EsUUFBUUEsQ0FBQ0EsTUFBTUEsQ0FBQ0E7WUFDaENBLENBQUNBO1lBQUNBLElBQUlBLENBQUNBLENBQUNBO2dCQUNOQSxFQUFFQSxDQUFBQSxDQUFDQSxJQUFJQSxDQUFDQSxNQUFNQSxDQUFDQTtvQkFDYkEsUUFBUUEsQ0FBQ0EsTUFBTUEsQ0FBQ0EsS0FBS0EsRUFBRUEsQ0FBQ0E7Z0JBQzFCQSxHQUFHQSxDQUFBQSxDQUFjQSxVQUFlQSxFQUFmQSxLQUFBQSxRQUFRQSxDQUFDQSxNQUFNQSxFQUE1QkEsY0FBU0EsRUFBVEEsSUFBNEJBLENBQUNBO29CQUE3QkEsSUFBSUEsS0FBS0EsU0FBQUE7b0JBQ1hBLElBQUlBLENBQUNBLE1BQU1BLENBQUNBLElBQUlBLENBQUNBLEtBQUtBLENBQUNBLENBQUNBO2lCQUFBQTtZQUM1QkEsQ0FBQ0E7WUFFREEsSUFBSUEsQ0FBQ0EsTUFBTUEsR0FBR0EsUUFBUUEsQ0FBQ0EsV0FBV0EsQ0FBQ0EsQ0FBQ0E7WUFDcENBLElBQUlBLENBQUNBLFVBQVVBLEdBQUdBLEtBQUtBLENBQUNBO1FBQzFCQSxDQUFDQSxDQUFDQTthQUNEQSxLQUFLQSxDQUFDQSxVQUFDQSxDQUFDQTtRQUVUQSxDQUFDQSxDQUFDQSxDQUFDQTtJQUNQQSxDQUFDQTtJQTNESEg7UUFBQ0Esb0JBQVNBLENBQUNBO1lBQ1RBLFFBQVFBLEVBQUVBLGNBQWNBO1lBQ3hCQSxZQUFZQSxFQUFFQSxDQUFFQSxZQUFNQSxDQUFFQTtTQUN6QkEsQ0FBQ0E7UUFDREEsZUFBSUEsQ0FBQ0E7WUFDSkEsV0FBV0EsRUFBRUEsd0NBQXdDQTtZQUNyREEsVUFBVUEsRUFBRUEsQ0FBRUEsZ0JBQUtBLEVBQUVBLGVBQUlBLEVBQUVBLGtCQUFPQSxFQUFFQSxtQkFBUUEsRUFBRUEsMEJBQWVBLENBQUVBO1NBQ2hFQSxDQUFDQTs7ZUFzRERBO0lBQURBLGFBQUNBO0FBQURBLENBN0RBLEFBNkRDQSxJQUFBO0FBcERZLGNBQU0sU0FvRGxCLENBQUEiLCJmaWxlIjoic3JjL3BsdWdpbnMvcGF5bWVudHMvd2FsbGV0LmpzIiwic291cmNlc0NvbnRlbnQiOlsiaW1wb3J0IHsgQ29tcG9uZW50LCBWaWV3LCBOZ0ZvciwgTmdJZiwgTmdDbGFzcywgT2JzZXJ2YWJsZSwgRk9STV9ESVJFQ1RJVkVTfSBmcm9tICdhbmd1bGFyMi9hbmd1bGFyMic7XG5pbXBvcnQgeyBSb3V0ZXJMaW5rIH0gZnJvbSBcImFuZ3VsYXIyL3JvdXRlclwiO1xuaW1wb3J0IHsgQ2xpZW50IH0gZnJvbSAnc3JjL3NlcnZpY2VzL2FwaSc7XG5pbXBvcnQgeyBTZXNzaW9uRmFjdG9yeSB9IGZyb20gJ3NyYy9zZXJ2aWNlcy9zZXNzaW9uJztcbmltcG9ydCB7IE1hdGVyaWFsIH0gZnJvbSAnc3JjL2RpcmVjdGl2ZXMvbWF0ZXJpYWwnO1xuXG5AQ29tcG9uZW50KHtcbiAgc2VsZWN0b3I6ICdtaW5kcy13YWxsZXQnLFxuICB2aWV3QmluZGluZ3M6IFsgQ2xpZW50IF1cbn0pXG5AVmlldyh7XG4gIHRlbXBsYXRlVXJsOiAndGVtcGxhdGVzL3BsdWdpbnMvcGF5bWVudHMvd2FsbGV0Lmh0bWwnLFxuICBkaXJlY3RpdmVzOiBbIE5nRm9yLCBOZ0lmLCBOZ0NsYXNzLCBNYXRlcmlhbCwgRk9STV9ESVJFQ1RJVkVTIF1cbn0pXG5cbmV4cG9ydCBjbGFzcyBXYWxsZXQge1xuXG4gIHNlc3Npb24gPSBTZXNzaW9uRmFjdG9yeS5idWlsZCgpO1xuICBwb2ludHMgOiBOdW1iZXIgPSAwO1xuICB0cmFuc2FjdGlvbnMgOiBBcnJheTxhbnk+ID0gW107XG4gIG9mZnNldDogc3RyaW5nID0gXCJcIjtcbiAgaW5Qcm9ncmVzcyA6IGJvb2xlYW4gPSBmYWxzZTtcbiAgbW9yZURhdGEgOiBib29sZWFuID0gdHJ1ZTtcblxuXHRjb25zdHJ1Y3RvcihwdWJsaWMgY2xpZW50OiBDbGllbnQpe1xuICAgIHRoaXMuZ2V0QmFsYW5jZSgpO1xuICAgIHRoaXMubG9hZFRyYW5zYWN0aW9ucygpO1xuXHR9XG5cbiAgZ2V0QmFsYW5jZSgpe1xuICAgIHZhciBzZWxmID0gdGhpcztcbiAgICB0aGlzLmNsaWVudC5nZXQoJ2FwaS92MS93YWxsZXQvY291bnQnLCB7fSlcbiAgICAgIC50aGVuKChyZXNwb25zZSkgPT4ge1xuICAgICAgICBzZWxmLnBvaW50cyA9IHJlc3BvbnNlLmNvdW50XG4gICAgICAgIH0pO1xuICB9XG5cbiAgbG9hZFRyYW5zYWN0aW9ucyhyZWZyZXNoIDogYm9vbGVhbiA9IGZhbHNlKXtcbiAgICB2YXIgc2VsZiA9IHRoaXM7XG4gICAgdGhpcy5pblByb2dyZXNzID0gdHJ1ZTtcbiAgICB0aGlzLmNsaWVudC5nZXQoJ2FwaS92MS93YWxsZXQvdHJhbnNhY3Rpb25zJywgeyBsaW1pdDogMTIsIG9mZnNldDogdGhpcy5vZmZzZXR9KVxuICAgICAgLnRoZW4oKHJlc3BvbnNlKSA9PiB7XG4gICAgICAgICAgY29uc29sZS5sb2cocmVzcG9uc2UpO1xuICAgICAgICAgIHJldHVybjtcbiAgICAgICAgaWYoIXJlc3BvbnNlLmdyb3Vwcyl7XG4gICAgICAgICAgc2VsZi5tb3JlRGF0YSA9IGZhbHNlO1xuICAgICAgICAgIHNlbGYuaW5Qcm9ncmVzcyA9IGZhbHNlO1xuICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgfVxuXG4gICAgICAgIGlmKHJlZnJlc2gpe1xuICAgICAgICAgIHNlbGYuZ3JvdXBzID0gcmVzcG9uc2UuZ3JvdXBzO1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgIGlmKHNlbGYub2Zmc2V0KVxuICAgICAgICAgICAgcmVzcG9uc2UuZ3JvdXBzLnNoaWZ0KCk7XG4gICAgICAgICAgZm9yKGxldCBncm91cCBvZiByZXNwb25zZS5ncm91cHMpXG4gICAgICAgICAgICBzZWxmLmdyb3Vwcy5wdXNoKGdyb3VwKTtcbiAgICAgICAgfVxuXG4gICAgICAgIHNlbGYub2Zmc2V0ID0gcmVzcG9uc2VbJ2xvYWQtbmV4dCddO1xuICAgICAgICBzZWxmLmluUHJvZ3Jlc3MgPSBmYWxzZTtcbiAgICAgIH0pXG4gICAgICAuY2F0Y2goKGUpPT57XG5cbiAgICAgIH0pO1xuICB9XG5cbn1cbiJdLCJzb3VyY2VSb290IjoiL3NvdXJjZS8ifQ==