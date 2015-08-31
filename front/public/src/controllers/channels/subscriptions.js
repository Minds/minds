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
var ChannelSubscriptions = (function () {
    function ChannelSubscriptions(client) {
        this.client = client;
        this.session = session_1.SessionFactory.build();
        this.users = [];
        this.offset = "";
        this.moreData = true;
        this.inProgress = false;
    }
    Object.defineProperty(ChannelSubscriptions.prototype, "channel", {
        set: function (value) {
            this.guid = value.guid;
            this.load();
        },
        enumerable: true,
        configurable: true
    });
    ChannelSubscriptions.prototype.load = function () {
        var self = this;
        this.inProgress = true;
        this.client.get('api/v1/subscribe/subscriptions/' + this.guid, {})
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
    ChannelSubscriptions = __decorate([
        angular2_1.Component({
            selector: 'minds-channel-subscriptions',
            viewBindings: [api_1.Client],
            properties: ['channel']
        }),
        angular2_1.View({
            templateUrl: 'templates/channels/subscriptions.html',
            directives: [angular2_1.NgFor, angular2_1.NgIf, material_1.Material, infinite_scroll_1.InfiniteScroll]
        }), 
        __metadata('design:paramtypes', [api_1.Client])
    ], ChannelSubscriptions);
    return ChannelSubscriptions;
})();
exports.ChannelSubscriptions = ChannelSubscriptions;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9jb250cm9sbGVycy9jaGFubmVscy9zdWJzY3JpcHRpb25zLnRzIl0sIm5hbWVzIjpbIkNoYW5uZWxTdWJzY3JpcHRpb25zIiwiQ2hhbm5lbFN1YnNjcmlwdGlvbnMuY29uc3RydWN0b3IiLCJDaGFubmVsU3Vic2NyaXB0aW9ucy5jaGFubmVsIiwiQ2hhbm5lbFN1YnNjcmlwdGlvbnMubG9hZCJdLCJtYXBwaW5ncyI6Ijs7Ozs7Ozs7Ozs7QUFBQSx5QkFBcUUsbUJBQW1CLENBQUMsQ0FBQTtBQUV6RixvQkFBdUIsa0JBQWtCLENBQUMsQ0FBQTtBQUMxQyx5QkFBeUIseUJBQXlCLENBQUMsQ0FBQTtBQUNuRCx3QkFBK0Isd0JBQXdCLENBQUMsQ0FBQTtBQUN4RCxnQ0FBK0Isa0NBQWtDLENBQUMsQ0FBQTtBQUVsRTtJQW9CRUEsOEJBQW1CQSxNQUFjQTtRQUFkQyxXQUFNQSxHQUFOQSxNQUFNQSxDQUFRQTtRQVRqQ0EsWUFBT0EsR0FBR0Esd0JBQWNBLENBQUNBLEtBQUtBLEVBQUVBLENBQUNBO1FBR2pDQSxVQUFLQSxHQUFnQkEsRUFBRUEsQ0FBQ0E7UUFFeEJBLFdBQU1BLEdBQVlBLEVBQUVBLENBQUNBO1FBQ3JCQSxhQUFRQSxHQUFhQSxJQUFJQSxDQUFDQTtRQUMxQkEsZUFBVUEsR0FBYUEsS0FBS0EsQ0FBQ0E7SUFHN0JBLENBQUNBO0lBR0RELHNCQUFJQSx5Q0FBT0E7YUFBWEEsVUFBWUEsS0FBVUE7WUFDcEJFLElBQUlBLENBQUNBLElBQUlBLEdBQUdBLEtBQUtBLENBQUNBLElBQUlBLENBQUNBO1lBQ3ZCQSxJQUFJQSxDQUFDQSxJQUFJQSxFQUFFQSxDQUFDQTtRQUNkQSxDQUFDQTs7O09BQUFGO0lBRURBLG1DQUFJQSxHQUFKQTtRQUNFRyxJQUFJQSxJQUFJQSxHQUFHQSxJQUFJQSxDQUFDQTtRQUNoQkEsSUFBSUEsQ0FBQ0EsVUFBVUEsR0FBR0EsSUFBSUEsQ0FBQ0E7UUFDdkJBLElBQUlBLENBQUNBLE1BQU1BLENBQUNBLEdBQUdBLENBQUNBLGlDQUFpQ0EsR0FBR0EsSUFBSUEsQ0FBQ0EsSUFBSUEsRUFBRUEsRUFBRUEsQ0FBQ0E7YUFDL0RBLElBQUlBLENBQUNBLFVBQUNBLFFBQWNBO1lBRW5CQSxFQUFFQSxDQUFBQSxDQUFDQSxRQUFRQSxDQUFDQSxNQUFNQSxJQUFJQSxTQUFTQSxDQUFDQSxDQUFBQSxDQUFDQTtnQkFDL0JBLE1BQU1BLENBQUNBLEtBQUtBLENBQUNBO1lBQ2ZBLENBQUNBO1lBRURBLEVBQUVBLENBQUFBLENBQUNBLElBQUlBLENBQUNBLE1BQU1BLENBQUNBLENBQUFBLENBQUNBLENBQUFBLENBQUNBO1lBQ2pCQSxHQUFHQSxDQUFBQSxDQUFhQSxVQUFjQSxFQUFkQSxLQUFBQSxRQUFRQSxDQUFDQSxLQUFLQSxFQUExQkEsY0FBUUEsRUFBUkEsSUFBMEJBLENBQUNBO2dCQUEzQkEsSUFBSUEsSUFBSUEsU0FBQUE7Z0JBQ1ZBLElBQUlBLENBQUNBLEtBQUtBLENBQUNBLElBQUlBLENBQUNBLElBQUlBLENBQUNBLENBQUNBO2FBQ3ZCQTtZQUVEQSxJQUFJQSxDQUFDQSxNQUFNQSxHQUFHQSxRQUFRQSxDQUFDQSxXQUFXQSxDQUFDQSxDQUFDQTtZQUNwQ0EsSUFBSUEsQ0FBQ0EsVUFBVUEsR0FBR0EsS0FBS0EsQ0FBQ0E7UUFDMUJBLENBQUNBLENBQUNBO2FBQ0RBLEtBQUtBLENBQUNBLFVBQUNBLENBQUNBO1lBQ1BBLE9BQU9BLENBQUNBLEdBQUdBLENBQUNBLHNCQUFzQkEsRUFBRUEsQ0FBQ0EsQ0FBQ0EsQ0FBQ0E7UUFDekNBLENBQUNBLENBQUNBLENBQUNBO0lBQ1BBLENBQUNBO0lBbERISDtRQUFDQSxvQkFBU0EsQ0FBQ0E7WUFDVEEsUUFBUUEsRUFBRUEsNkJBQTZCQTtZQUN2Q0EsWUFBWUEsRUFBRUEsQ0FBRUEsWUFBTUEsQ0FBRUE7WUFDeEJBLFVBQVVBLEVBQUVBLENBQUNBLFNBQVNBLENBQUNBO1NBQ3hCQSxDQUFDQTtRQUNEQSxlQUFJQSxDQUFDQTtZQUNKQSxXQUFXQSxFQUFFQSx1Q0FBdUNBO1lBQ3BEQSxVQUFVQSxFQUFFQSxDQUFFQSxnQkFBS0EsRUFBRUEsZUFBSUEsRUFBRUEsbUJBQVFBLEVBQUVBLGdDQUFjQSxDQUFFQTtTQUN0REEsQ0FBQ0E7OzZCQTRDREE7SUFBREEsMkJBQUNBO0FBQURBLENBcERBLEFBb0RDQSxJQUFBO0FBMUNZLDRCQUFvQix1QkEwQ2hDLENBQUEiLCJmaWxlIjoic3JjL2NvbnRyb2xsZXJzL2NoYW5uZWxzL3N1YnNjcmlwdGlvbnMuanMiLCJzb3VyY2VzQ29udGVudCI6WyJpbXBvcnQgeyBDb21wb25lbnQsIFZpZXcsIE5nRm9yLCBOZ0lmLCBJbmplY3QsIEZPUk1fRElSRUNUSVZFU30gZnJvbSAnYW5ndWxhcjIvYW5ndWxhcjInO1xuaW1wb3J0IHsgUm91dGVyLCBSb3V0ZVBhcmFtcyB9IGZyb20gJ2FuZ3VsYXIyL3JvdXRlcic7XG5pbXBvcnQgeyBDbGllbnQgfSBmcm9tICdzcmMvc2VydmljZXMvYXBpJztcbmltcG9ydCB7IE1hdGVyaWFsIH0gZnJvbSAnc3JjL2RpcmVjdGl2ZXMvbWF0ZXJpYWwnO1xuaW1wb3J0IHsgU2Vzc2lvbkZhY3RvcnkgfSBmcm9tICcuLi8uLi9zZXJ2aWNlcy9zZXNzaW9uJztcbmltcG9ydCB7IEluZmluaXRlU2Nyb2xsIH0gZnJvbSAnLi4vLi4vZGlyZWN0aXZlcy9pbmZpbml0ZS1zY3JvbGwnO1xuXG5AQ29tcG9uZW50KHtcbiAgc2VsZWN0b3I6ICdtaW5kcy1jaGFubmVsLXN1YnNjcmlwdGlvbnMnLFxuICB2aWV3QmluZGluZ3M6IFsgQ2xpZW50IF0sXG4gIHByb3BlcnRpZXM6IFsnY2hhbm5lbCddXG59KVxuQFZpZXcoe1xuICB0ZW1wbGF0ZVVybDogJ3RlbXBsYXRlcy9jaGFubmVscy9zdWJzY3JpcHRpb25zLmh0bWwnLFxuICBkaXJlY3RpdmVzOiBbIE5nRm9yLCBOZ0lmLCBNYXRlcmlhbCwgSW5maW5pdGVTY3JvbGwgXVxufSlcblxuZXhwb3J0IGNsYXNzIENoYW5uZWxTdWJzY3JpcHRpb25zIHtcbiAgc2Vzc2lvbiA9IFNlc3Npb25GYWN0b3J5LmJ1aWxkKCk7XG5cbiAgZ3VpZCA6IHN0cmluZztcbiAgdXNlcnMgOiBBcnJheTxhbnk+ID0gW107XG5cbiAgb2Zmc2V0IDogc3RyaW5nID0gXCJcIjtcbiAgbW9yZURhdGEgOiBib29sZWFuID0gdHJ1ZTtcbiAgaW5Qcm9ncmVzcyA6IGJvb2xlYW4gPSBmYWxzZTtcblxuICBjb25zdHJ1Y3RvcihwdWJsaWMgY2xpZW50OiBDbGllbnQpeyAgICBcbiAgfVxuXG5cbiAgc2V0IGNoYW5uZWwodmFsdWU6IGFueSkge1xuICAgIHRoaXMuZ3VpZCA9IHZhbHVlLmd1aWQ7XG4gICAgdGhpcy5sb2FkKCk7XG4gIH1cblxuICBsb2FkKCl7XG4gICAgdmFyIHNlbGYgPSB0aGlzO1xuICAgIHRoaXMuaW5Qcm9ncmVzcyA9IHRydWU7XG4gICAgdGhpcy5jbGllbnQuZ2V0KCdhcGkvdjEvc3Vic2NyaWJlL3N1YnNjcmlwdGlvbnMvJyArIHRoaXMuZ3VpZCwge30pXG4gICAgICAudGhlbigocmVzcG9uc2UgOiBhbnkpID0+IHtcblxuICAgICAgICBpZihyZXNwb25zZS5zdGF0dXMgIT0gXCJzdWNjZXNzXCIpe1xuICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgfVxuXG4gICAgICAgIGlmKHNlbGYub2Zmc2V0KXt9XG4gICAgICAgIGZvcihsZXQgdXNlciBvZiByZXNwb25zZS51c2Vycyl7XG4gICAgICAgICAgc2VsZi51c2Vycy5wdXNoKHVzZXIpO1xuICAgICAgICB9XG5cbiAgICAgICAgc2VsZi5vZmZzZXQgPSByZXNwb25zZVsnbG9hZC1uZXh0J107XG4gICAgICAgIHNlbGYuaW5Qcm9ncmVzcyA9IGZhbHNlO1xuICAgICAgfSlcbiAgICAgIC5jYXRjaCgoZSkgPT4ge1xuICAgICAgICBjb25zb2xlLmxvZygnY291bGRudCBsb2FkIGNoYW5uZWwnLCBlKTtcbiAgICAgIH0pO1xuICB9XG5cbn1cbiJdLCJzb3VyY2VSb290IjoiL3NvdXJjZS8ifQ==