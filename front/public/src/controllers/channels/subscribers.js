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
var material_1 = require('src/directives/material');
var session_1 = require('../../services/session');
var infinite_scroll_1 = require('../../directives/infinite-scroll');
var ChannelSubscribers = (function () {
    function ChannelSubscribers(client) {
        this.client = client;
        this.session = session_1.SessionFactory.build();
        this.users = [];
        this.offset = "";
        this.moreData = true;
        this.inProgress = false;
    }
    Object.defineProperty(ChannelSubscribers.prototype, "channel", {
        set: function (value) {
            this.guid = value.guid;
            this.load();
        },
        enumerable: true,
        configurable: true
    });
    ChannelSubscribers.prototype.load = function () {
        var self = this;
        this.inProgress = true;
        this.client.get('api/v1/subscribe/subscribers/' + this.guid, {})
            .then(function (response) {
            if (response.status != "success") {
                return false;
            }
            if (self.offset) { }
            for (var _i = 0, _a = response.users; _i < _a.length; _i++) {
                var user = _a[_i];
                self.users.push(user);
            }
            self.offset = response['load-next'];
            self.inProgress = false;
        })
            .catch(function (e) {
            console.log('couldnt load channel', e);
        });
    };
    ChannelSubscribers = __decorate([
        angular2_1.Component({
            selector: 'minds-channel-subscribers',
            viewBindings: [api_1.Client],
            properties: ['channel']
        }),
        angular2_1.View({
            templateUrl: 'templates/channels/subscribers.html',
            directives: [angular2_1.NgFor, angular2_1.NgIf, material_1.Material, infinite_scroll_1.InfiniteScroll]
        }), 
        __metadata('design:paramtypes', [api_1.Client])
    ], ChannelSubscribers);
    return ChannelSubscribers;
})();
exports.ChannelSubscribers = ChannelSubscribers;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9jb250cm9sbGVycy9jaGFubmVscy9zdWJzY3JpYmVycy50cyJdLCJuYW1lcyI6WyJDaGFubmVsU3Vic2NyaWJlcnMiLCJDaGFubmVsU3Vic2NyaWJlcnMuY29uc3RydWN0b3IiLCJDaGFubmVsU3Vic2NyaWJlcnMuY2hhbm5lbCIsIkNoYW5uZWxTdWJzY3JpYmVycy5sb2FkIl0sIm1hcHBpbmdzIjoiOzs7Ozs7Ozs7OztBQUFBLHlCQUFxRSxtQkFBbUIsQ0FBQyxDQUFBO0FBRXpGLG9CQUF1QixrQkFBa0IsQ0FBQyxDQUFBO0FBQzFDLHlCQUF5Qix5QkFBeUIsQ0FBQyxDQUFBO0FBQ25ELHdCQUErQix3QkFBd0IsQ0FBQyxDQUFBO0FBQ3hELGdDQUErQixrQ0FBa0MsQ0FBQyxDQUFBO0FBRWxFO0lBb0JFQSw0QkFBbUJBLE1BQWNBO1FBQWRDLFdBQU1BLEdBQU5BLE1BQU1BLENBQVFBO1FBVGpDQSxZQUFPQSxHQUFHQSx3QkFBY0EsQ0FBQ0EsS0FBS0EsRUFBRUEsQ0FBQ0E7UUFHakNBLFVBQUtBLEdBQWdCQSxFQUFFQSxDQUFDQTtRQUV4QkEsV0FBTUEsR0FBWUEsRUFBRUEsQ0FBQ0E7UUFDckJBLGFBQVFBLEdBQWFBLElBQUlBLENBQUNBO1FBQzFCQSxlQUFVQSxHQUFhQSxLQUFLQSxDQUFDQTtJQUc3QkEsQ0FBQ0E7SUFFREQsc0JBQUlBLHVDQUFPQTthQUFYQSxVQUFZQSxLQUFVQTtZQUNwQkUsSUFBSUEsQ0FBQ0EsSUFBSUEsR0FBR0EsS0FBS0EsQ0FBQ0EsSUFBSUEsQ0FBQ0E7WUFDdkJBLElBQUlBLENBQUNBLElBQUlBLEVBQUVBLENBQUNBO1FBQ2RBLENBQUNBOzs7T0FBQUY7SUFFREEsaUNBQUlBLEdBQUpBO1FBRUVHLElBQUlBLElBQUlBLEdBQUdBLElBQUlBLENBQUNBO1FBQ2hCQSxJQUFJQSxDQUFDQSxVQUFVQSxHQUFHQSxJQUFJQSxDQUFDQTtRQUN2QkEsSUFBSUEsQ0FBQ0EsTUFBTUEsQ0FBQ0EsR0FBR0EsQ0FBQ0EsK0JBQStCQSxHQUFHQSxJQUFJQSxDQUFDQSxJQUFJQSxFQUFFQSxFQUFFQSxDQUFDQTthQUM3REEsSUFBSUEsQ0FBQ0EsVUFBQ0EsUUFBY0E7WUFFbkJBLEVBQUVBLENBQUFBLENBQUNBLFFBQVFBLENBQUNBLE1BQU1BLElBQUlBLFNBQVNBLENBQUNBLENBQUFBLENBQUNBO2dCQUMvQkEsTUFBTUEsQ0FBQ0EsS0FBS0EsQ0FBQ0E7WUFDZkEsQ0FBQ0E7WUFFREEsRUFBRUEsQ0FBQUEsQ0FBQ0EsSUFBSUEsQ0FBQ0EsTUFBTUEsQ0FBQ0EsQ0FBQUEsQ0FBQ0EsQ0FBQUEsQ0FBQ0E7WUFDakJBLEdBQUdBLENBQUFBLENBQWFBLFVBQWNBLEVBQWRBLEtBQUFBLFFBQVFBLENBQUNBLEtBQUtBLEVBQTFCQSxjQUFRQSxFQUFSQSxJQUEwQkEsQ0FBQ0E7Z0JBQTNCQSxJQUFJQSxJQUFJQSxTQUFBQTtnQkFDVkEsSUFBSUEsQ0FBQ0EsS0FBS0EsQ0FBQ0EsSUFBSUEsQ0FBQ0EsSUFBSUEsQ0FBQ0EsQ0FBQ0E7YUFDdkJBO1lBRURBLElBQUlBLENBQUNBLE1BQU1BLEdBQUdBLFFBQVFBLENBQUNBLFdBQVdBLENBQUNBLENBQUNBO1lBQ3BDQSxJQUFJQSxDQUFDQSxVQUFVQSxHQUFHQSxLQUFLQSxDQUFDQTtRQUMxQkEsQ0FBQ0EsQ0FBQ0E7YUFDREEsS0FBS0EsQ0FBQ0EsVUFBQ0EsQ0FBQ0E7WUFDUEEsT0FBT0EsQ0FBQ0EsR0FBR0EsQ0FBQ0Esc0JBQXNCQSxFQUFFQSxDQUFDQSxDQUFDQSxDQUFDQTtRQUN6Q0EsQ0FBQ0EsQ0FBQ0EsQ0FBQ0E7SUFDUEEsQ0FBQ0E7SUFsREhIO1FBQUNBLG9CQUFTQSxDQUFDQTtZQUNUQSxRQUFRQSxFQUFFQSwyQkFBMkJBO1lBQ3JDQSxZQUFZQSxFQUFFQSxDQUFFQSxZQUFNQSxDQUFFQTtZQUN4QkEsVUFBVUEsRUFBRUEsQ0FBQ0EsU0FBU0EsQ0FBQ0E7U0FDeEJBLENBQUNBO1FBQ0RBLGVBQUlBLENBQUNBO1lBQ0pBLFdBQVdBLEVBQUVBLHFDQUFxQ0E7WUFDbERBLFVBQVVBLEVBQUVBLENBQUVBLGdCQUFLQSxFQUFFQSxlQUFJQSxFQUFFQSxtQkFBUUEsRUFBRUEsZ0NBQWNBLENBQUVBO1NBQ3REQSxDQUFDQTs7MkJBNENEQTtJQUFEQSx5QkFBQ0E7QUFBREEsQ0FwREEsQUFvRENBLElBQUE7QUExQ1ksMEJBQWtCLHFCQTBDOUIsQ0FBQSIsImZpbGUiOiJzcmMvY29udHJvbGxlcnMvY2hhbm5lbHMvc3Vic2NyaWJlcnMuanMiLCJzb3VyY2VzQ29udGVudCI6WyJpbXBvcnQgeyBDb21wb25lbnQsIFZpZXcsIE5nRm9yLCBOZ0lmLCBJbmplY3QsIEZPUk1fRElSRUNUSVZFU30gZnJvbSAnYW5ndWxhcjIvYW5ndWxhcjInO1xuaW1wb3J0IHsgUm91dGVyLCBSb3V0ZVBhcmFtcyB9IGZyb20gJ2FuZ3VsYXIyL3JvdXRlcic7XG5pbXBvcnQgeyBDbGllbnQgfSBmcm9tICdzcmMvc2VydmljZXMvYXBpJztcbmltcG9ydCB7IE1hdGVyaWFsIH0gZnJvbSAnc3JjL2RpcmVjdGl2ZXMvbWF0ZXJpYWwnO1xuaW1wb3J0IHsgU2Vzc2lvbkZhY3RvcnkgfSBmcm9tICcuLi8uLi9zZXJ2aWNlcy9zZXNzaW9uJztcbmltcG9ydCB7IEluZmluaXRlU2Nyb2xsIH0gZnJvbSAnLi4vLi4vZGlyZWN0aXZlcy9pbmZpbml0ZS1zY3JvbGwnO1xuXG5AQ29tcG9uZW50KHtcbiAgc2VsZWN0b3I6ICdtaW5kcy1jaGFubmVsLXN1YnNjcmliZXJzJyxcbiAgdmlld0JpbmRpbmdzOiBbIENsaWVudCBdLFxuICBwcm9wZXJ0aWVzOiBbJ2NoYW5uZWwnXVxufSlcbkBWaWV3KHtcbiAgdGVtcGxhdGVVcmw6ICd0ZW1wbGF0ZXMvY2hhbm5lbHMvc3Vic2NyaWJlcnMuaHRtbCcsXG4gIGRpcmVjdGl2ZXM6IFsgTmdGb3IsIE5nSWYsIE1hdGVyaWFsLCBJbmZpbml0ZVNjcm9sbCBdXG59KVxuXG5leHBvcnQgY2xhc3MgQ2hhbm5lbFN1YnNjcmliZXJzIHtcbiAgc2Vzc2lvbiA9IFNlc3Npb25GYWN0b3J5LmJ1aWxkKCk7XG5cbiAgZ3VpZCA6IHN0cmluZztcbiAgdXNlcnMgOiBBcnJheTxhbnk+ID0gW107XG5cbiAgb2Zmc2V0IDogc3RyaW5nID0gXCJcIjtcbiAgbW9yZURhdGEgOiBib29sZWFuID0gdHJ1ZTtcbiAgaW5Qcm9ncmVzcyA6IGJvb2xlYW4gPSBmYWxzZTtcblxuICBjb25zdHJ1Y3RvcihwdWJsaWMgY2xpZW50OiBDbGllbnQpe1xuICB9XG5cbiAgc2V0IGNoYW5uZWwodmFsdWU6IGFueSkge1xuICAgIHRoaXMuZ3VpZCA9IHZhbHVlLmd1aWQ7XG4gICAgdGhpcy5sb2FkKCk7XG4gIH1cblxuICBsb2FkKCl7XG5cbiAgICB2YXIgc2VsZiA9IHRoaXM7XG4gICAgdGhpcy5pblByb2dyZXNzID0gdHJ1ZTtcbiAgICB0aGlzLmNsaWVudC5nZXQoJ2FwaS92MS9zdWJzY3JpYmUvc3Vic2NyaWJlcnMvJyArIHRoaXMuZ3VpZCwge30pXG4gICAgICAudGhlbigocmVzcG9uc2UgOiBhbnkpID0+IHtcblxuICAgICAgICBpZihyZXNwb25zZS5zdGF0dXMgIT0gXCJzdWNjZXNzXCIpe1xuICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgfVxuXG4gICAgICAgIGlmKHNlbGYub2Zmc2V0KXt9XG4gICAgICAgIGZvcihsZXQgdXNlciBvZiByZXNwb25zZS51c2Vycyl7XG4gICAgICAgICAgc2VsZi51c2Vycy5wdXNoKHVzZXIpO1xuICAgICAgICB9XG5cbiAgICAgICAgc2VsZi5vZmZzZXQgPSByZXNwb25zZVsnbG9hZC1uZXh0J107XG4gICAgICAgIHNlbGYuaW5Qcm9ncmVzcyA9IGZhbHNlO1xuICAgICAgfSlcbiAgICAgIC5jYXRjaCgoZSkgPT4ge1xuICAgICAgICBjb25zb2xlLmxvZygnY291bGRudCBsb2FkIGNoYW5uZWwnLCBlKTtcbiAgICAgIH0pO1xuICB9XG5cbn1cbiJdLCJzb3VyY2VSb290IjoiL3NvdXJjZS8ifQ==