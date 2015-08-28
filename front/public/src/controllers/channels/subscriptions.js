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
var __param = (this && this.__param) || function (paramIndex, decorator) {
    return function (target, key) { decorator(target, key, paramIndex); }
};
var angular2_1 = require('angular2/angular2');
var router_1 = require('angular2/router');
var api_1 = require('src/services/api');
var material_1 = require('src/directives/material');
var session_1 = require('../../services/session');
var infinite_scroll_1 = require('../../directives/infinite-scroll');
var ChannelSubscriptions = (function () {
    function ChannelSubscriptions(client, router, params) {
        this.client = client;
        this.router = router;
        this.params = params;
        this.session = session_1.SessionFactory.build();
        this.users = [];
        this.offset = "";
        this.moreData = true;
        this.inProgress = false;
        this.guid = params.params['guid'];
        this.load();
    }
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
            viewBindings: [api_1.Client]
        }),
        angular2_1.View({
            templateUrl: 'templates/channels/subscriptions.html',
            directives: [angular2_1.NgFor, angular2_1.NgIf, material_1.Material, infinite_scroll_1.InfiniteScroll]
        }),
        __param(1, angular2_1.Inject(router_1.Router)),
        __param(2, angular2_1.Inject(router_1.RouteParams)), 
        __metadata('design:paramtypes', [api_1.Client, router_1.Router, router_1.RouteParams])
    ], ChannelSubscriptions);
    return ChannelSubscriptions;
})();
exports.ChannelSubscriptions = ChannelSubscriptions;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9jb250cm9sbGVycy9jaGFubmVscy9zdWJzY3JpcHRpb25zLnRzIl0sIm5hbWVzIjpbIkNoYW5uZWxTdWJzY3JpcHRpb25zIiwiQ2hhbm5lbFN1YnNjcmlwdGlvbnMuY29uc3RydWN0b3IiLCJDaGFubmVsU3Vic2NyaXB0aW9ucy5sb2FkIl0sIm1hcHBpbmdzIjoiOzs7Ozs7Ozs7Ozs7OztBQUFBLHlCQUFxRSxtQkFBbUIsQ0FBQyxDQUFBO0FBQ3pGLHVCQUFvQyxpQkFBaUIsQ0FBQyxDQUFBO0FBQ3RELG9CQUF1QixrQkFBa0IsQ0FBQyxDQUFBO0FBQzFDLHlCQUF5Qix5QkFBeUIsQ0FBQyxDQUFBO0FBQ25ELHdCQUErQix3QkFBd0IsQ0FBQyxDQUFBO0FBQ3hELGdDQUErQixrQ0FBa0MsQ0FBQyxDQUFBO0FBRWxFO0lBa0JFQSw4QkFBbUJBLE1BQWNBLEVBQ1JBLE1BQWNBLEVBQ1RBLE1BQW1CQTtRQUY5QkMsV0FBTUEsR0FBTkEsTUFBTUEsQ0FBUUE7UUFDUkEsV0FBTUEsR0FBTkEsTUFBTUEsQ0FBUUE7UUFDVEEsV0FBTUEsR0FBTkEsTUFBTUEsQ0FBYUE7UUFWakRBLFlBQU9BLEdBQUdBLHdCQUFjQSxDQUFDQSxLQUFLQSxFQUFFQSxDQUFDQTtRQUVqQ0EsVUFBS0EsR0FBZ0JBLEVBQUVBLENBQUNBO1FBRXhCQSxXQUFNQSxHQUFZQSxFQUFFQSxDQUFDQTtRQUNyQkEsYUFBUUEsR0FBYUEsSUFBSUEsQ0FBQ0E7UUFDMUJBLGVBQVVBLEdBQWFBLEtBQUtBLENBQUNBO1FBTXpCQSxJQUFJQSxDQUFDQSxJQUFJQSxHQUFHQSxNQUFNQSxDQUFDQSxNQUFNQSxDQUFDQSxNQUFNQSxDQUFDQSxDQUFDQTtRQUNsQ0EsSUFBSUEsQ0FBQ0EsSUFBSUEsRUFBRUEsQ0FBQ0E7SUFDaEJBLENBQUNBO0lBRURELG1DQUFJQSxHQUFKQTtRQUNFRSxJQUFJQSxJQUFJQSxHQUFHQSxJQUFJQSxDQUFDQTtRQUNoQkEsSUFBSUEsQ0FBQ0EsVUFBVUEsR0FBR0EsSUFBSUEsQ0FBQ0E7UUFDdkJBLElBQUlBLENBQUNBLE1BQU1BLENBQUNBLEdBQUdBLENBQUNBLGlDQUFpQ0EsR0FBR0EsSUFBSUEsQ0FBQ0EsSUFBSUEsRUFBRUEsRUFBRUEsQ0FBQ0E7YUFDL0RBLElBQUlBLENBQUNBLFVBQUNBLFFBQWNBO1lBRW5CQSxFQUFFQSxDQUFBQSxDQUFDQSxRQUFRQSxDQUFDQSxNQUFNQSxJQUFJQSxTQUFTQSxDQUFDQSxDQUFBQSxDQUFDQTtnQkFDL0JBLE1BQU1BLENBQUNBLEtBQUtBLENBQUNBO1lBQ2ZBLENBQUNBO1lBRURBLEVBQUVBLENBQUFBLENBQUNBLElBQUlBLENBQUNBLE1BQU1BLENBQUNBLENBQUFBLENBQUNBLENBQUFBLENBQUNBO1lBQ2pCQSxHQUFHQSxDQUFBQSxDQUFhQSxVQUFjQSxFQUFkQSxLQUFBQSxRQUFRQSxDQUFDQSxLQUFLQSxFQUExQkEsY0FBUUEsRUFBUkEsSUFBMEJBLENBQUNBO2dCQUEzQkEsSUFBSUEsSUFBSUEsU0FBQUE7Z0JBQ1ZBLElBQUlBLENBQUNBLEtBQUtBLENBQUNBLElBQUlBLENBQUNBLElBQUlBLENBQUNBLENBQUNBO2FBQ3ZCQTtZQUVEQSxJQUFJQSxDQUFDQSxNQUFNQSxHQUFHQSxRQUFRQSxDQUFDQSxXQUFXQSxDQUFDQSxDQUFDQTtZQUNwQ0EsSUFBSUEsQ0FBQ0EsVUFBVUEsR0FBR0EsS0FBS0EsQ0FBQ0E7UUFDMUJBLENBQUNBLENBQUNBO2FBQ0RBLEtBQUtBLENBQUNBLFVBQUNBLENBQUNBO1lBQ1BBLE9BQU9BLENBQUNBLEdBQUdBLENBQUNBLHNCQUFzQkEsRUFBRUEsQ0FBQ0EsQ0FBQ0EsQ0FBQ0E7UUFDekNBLENBQUNBLENBQUNBLENBQUNBO0lBQ1BBLENBQUNBO0lBL0NIRjtRQUFDQSxvQkFBU0EsQ0FBQ0E7WUFDVEEsUUFBUUEsRUFBRUEsNkJBQTZCQTtZQUN2Q0EsWUFBWUEsRUFBRUEsQ0FBRUEsWUFBTUEsQ0FBRUE7U0FDekJBLENBQUNBO1FBQ0RBLGVBQUlBLENBQUNBO1lBQ0pBLFdBQVdBLEVBQUVBLHVDQUF1Q0E7WUFDcERBLFVBQVVBLEVBQUVBLENBQUVBLGdCQUFLQSxFQUFFQSxlQUFJQSxFQUFFQSxtQkFBUUEsRUFBRUEsZ0NBQWNBLENBQUVBO1NBQ3REQSxDQUFDQTtRQVlFQSxXQUFDQSxpQkFBTUEsQ0FBQ0EsZUFBTUEsQ0FBQ0EsQ0FBQUE7UUFDZkEsV0FBQ0EsaUJBQU1BLENBQUNBLG9CQUFXQSxDQUFDQSxDQUFBQTs7NkJBNkJ2QkE7SUFBREEsMkJBQUNBO0FBQURBLENBakRBLEFBaURDQSxJQUFBO0FBeENZLDRCQUFvQix1QkF3Q2hDLENBQUEiLCJmaWxlIjoic3JjL2NvbnRyb2xsZXJzL2NoYW5uZWxzL3N1YnNjcmlwdGlvbnMuanMiLCJzb3VyY2VzQ29udGVudCI6WyJpbXBvcnQgeyBDb21wb25lbnQsIFZpZXcsIE5nRm9yLCBOZ0lmLCBJbmplY3QsIEZPUk1fRElSRUNUSVZFU30gZnJvbSAnYW5ndWxhcjIvYW5ndWxhcjInO1xuaW1wb3J0IHsgUm91dGVyLCBSb3V0ZVBhcmFtcyB9IGZyb20gJ2FuZ3VsYXIyL3JvdXRlcic7XG5pbXBvcnQgeyBDbGllbnQgfSBmcm9tICdzcmMvc2VydmljZXMvYXBpJztcbmltcG9ydCB7IE1hdGVyaWFsIH0gZnJvbSAnc3JjL2RpcmVjdGl2ZXMvbWF0ZXJpYWwnO1xuaW1wb3J0IHsgU2Vzc2lvbkZhY3RvcnkgfSBmcm9tICcuLi8uLi9zZXJ2aWNlcy9zZXNzaW9uJztcbmltcG9ydCB7IEluZmluaXRlU2Nyb2xsIH0gZnJvbSAnLi4vLi4vZGlyZWN0aXZlcy9pbmZpbml0ZS1zY3JvbGwnO1xuXG5AQ29tcG9uZW50KHtcbiAgc2VsZWN0b3I6ICdtaW5kcy1jaGFubmVsLXN1YnNjcmlwdGlvbnMnLFxuICB2aWV3QmluZGluZ3M6IFsgQ2xpZW50IF1cbn0pXG5AVmlldyh7XG4gIHRlbXBsYXRlVXJsOiAndGVtcGxhdGVzL2NoYW5uZWxzL3N1YnNjcmlwdGlvbnMuaHRtbCcsXG4gIGRpcmVjdGl2ZXM6IFsgTmdGb3IsIE5nSWYsIE1hdGVyaWFsLCBJbmZpbml0ZVNjcm9sbCBdXG59KVxuXG5leHBvcnQgY2xhc3MgQ2hhbm5lbFN1YnNjcmlwdGlvbnMge1xuICBzZXNzaW9uID0gU2Vzc2lvbkZhY3RvcnkuYnVpbGQoKTtcbiAgZ3VpZCA6IHN0cmluZztcbiAgdXNlcnMgOiBBcnJheTxhbnk+ID0gW107XG5cbiAgb2Zmc2V0IDogc3RyaW5nID0gXCJcIjtcbiAgbW9yZURhdGEgOiBib29sZWFuID0gdHJ1ZTtcbiAgaW5Qcm9ncmVzcyA6IGJvb2xlYW4gPSBmYWxzZTtcblxuICBjb25zdHJ1Y3RvcihwdWJsaWMgY2xpZW50OiBDbGllbnQsXG4gICAgQEluamVjdChSb3V0ZXIpIHB1YmxpYyByb3V0ZXI6IFJvdXRlcixcbiAgICBASW5qZWN0KFJvdXRlUGFyYW1zKSBwdWJsaWMgcGFyYW1zOiBSb3V0ZVBhcmFtc1xuICAgICl7XG4gICAgICB0aGlzLmd1aWQgPSBwYXJhbXMucGFyYW1zWydndWlkJ107XG4gICAgICB0aGlzLmxvYWQoKTtcbiAgfVxuXG4gIGxvYWQoKXtcbiAgICB2YXIgc2VsZiA9IHRoaXM7XG4gICAgdGhpcy5pblByb2dyZXNzID0gdHJ1ZTtcbiAgICB0aGlzLmNsaWVudC5nZXQoJ2FwaS92MS9zdWJzY3JpYmUvc3Vic2NyaXB0aW9ucy8nICsgdGhpcy5ndWlkLCB7fSlcbiAgICAgIC50aGVuKChyZXNwb25zZSA6IGFueSkgPT4ge1xuXG4gICAgICAgIGlmKHJlc3BvbnNlLnN0YXR1cyAhPSBcInN1Y2Nlc3NcIil7XG4gICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICB9XG5cbiAgICAgICAgaWYoc2VsZi5vZmZzZXQpe31cbiAgICAgICAgZm9yKGxldCB1c2VyIG9mIHJlc3BvbnNlLnVzZXJzKXtcbiAgICAgICAgICBzZWxmLnVzZXJzLnB1c2godXNlcik7XG4gICAgICAgIH1cblxuICAgICAgICBzZWxmLm9mZnNldCA9IHJlc3BvbnNlWydsb2FkLW5leHQnXTtcbiAgICAgICAgc2VsZi5pblByb2dyZXNzID0gZmFsc2U7XG4gICAgICB9KVxuICAgICAgLmNhdGNoKChlKSA9PiB7XG4gICAgICAgIGNvbnNvbGUubG9nKCdjb3VsZG50IGxvYWQgY2hhbm5lbCcsIGUpO1xuICAgICAgfSk7XG4gIH1cblxufVxuIl0sInNvdXJjZVJvb3QiOiIvc291cmNlLyJ9