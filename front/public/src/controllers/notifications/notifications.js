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
var router_1 = require('angular2/router');
var api_1 = require('src/services/api');
var session_1 = require('../../services/session');
var material_1 = require('src/directives/material');
var infinite_scroll_1 = require('../../directives/infinite-scroll');
var Notifications = (function () {
    function Notifications(client) {
        this.client = client;
        this.notificatons = [];
        this.moreData = true;
        this.offset = "";
        this.inProgress = false;
        this.session = session_1.SessionFactory.build();
        this.load(true);
    }
    Notifications.prototype.load = function (refresh) {
        if (refresh === void 0) { refresh = false; }
        var self = this;
        if (this.inProgress)
            return false;
        if (refresh)
            this.offset = "";
        this.inProgress = true;
        this.client.get('api/v1/notifications', { limit: 12, offset: this.offset })
            .then(function (data) {
            if (!data.notifications) {
                self.moreData = false;
                self.inProgress = false;
                return false;
            }
            if (refresh) {
                self.notifications = data.notifications;
            }
            else {
                if (self.offset)
                    data.notifications.shift();
                for (var _i = 0, _a = data.notifications; _i < _a.length; _i++) {
                    var entity = _a[_i];
                    self.notifications.push(entity);
                }
            }
            self.offset = data['load-next'];
            self.inProgress = false;
        });
    };
    Notifications = __decorate([
        angular2_1.Component({
            selector: 'minds-notifications',
            viewBindings: [api_1.Client]
        }),
        angular2_1.View({
            templateUrl: 'templates/notifications/list.html',
            directives: [angular2_1.NgFor, angular2_1.NgIf, angular2_1.NgSwitch, angular2_1.NgSwitchWhen, angular2_1.NgSwitchDefault, angular2_1.NgClass, router_1.RouterLink, material_1.Material, infinite_scroll_1.InfiniteScroll]
        }), 
        __metadata('design:paramtypes', [api_1.Client])
    ], Notifications);
    return Notifications;
})();
exports.Notifications = Notifications;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9jb250cm9sbGVycy9ub3RpZmljYXRpb25zL25vdGlmaWNhdGlvbnMudHMiXSwibmFtZXMiOlsiTm90aWZpY2F0aW9ucyIsIk5vdGlmaWNhdGlvbnMuY29uc3RydWN0b3IiLCJOb3RpZmljYXRpb25zLmxvYWQiXSwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7O0FBQUEseUJBQXVHLG1CQUFtQixDQUFDLENBQUE7QUFDM0gsdUJBQTJCLGlCQUFpQixDQUFDLENBQUE7QUFDN0Msb0JBQXVCLGtCQUFrQixDQUFDLENBQUE7QUFDMUMsd0JBQStCLHdCQUF3QixDQUFDLENBQUE7QUFDeEQseUJBQXlCLHlCQUF5QixDQUFDLENBQUE7QUFDbkQsZ0NBQStCLGtDQUFrQyxDQUFDLENBQUE7QUFFbEU7SUFpQkVBLHVCQUFtQkEsTUFBY0E7UUFBZEMsV0FBTUEsR0FBTkEsTUFBTUEsQ0FBUUE7UUFOakNBLGlCQUFZQSxHQUFtQkEsRUFBRUEsQ0FBQ0E7UUFDbENBLGFBQVFBLEdBQWFBLElBQUlBLENBQUNBO1FBQzFCQSxXQUFNQSxHQUFXQSxFQUFFQSxDQUFDQTtRQUNwQkEsZUFBVUEsR0FBYUEsS0FBS0EsQ0FBQ0E7UUFDN0JBLFlBQU9BLEdBQUdBLHdCQUFjQSxDQUFDQSxLQUFLQSxFQUFFQSxDQUFDQTtRQUcvQkEsSUFBSUEsQ0FBQ0EsSUFBSUEsQ0FBQ0EsSUFBSUEsQ0FBQ0EsQ0FBQ0E7SUFDbEJBLENBQUNBO0lBRURELDRCQUFJQSxHQUFKQSxVQUFLQSxPQUF5QkE7UUFBekJFLHVCQUF5QkEsR0FBekJBLGVBQXlCQTtRQUM1QkEsSUFBSUEsSUFBSUEsR0FBR0EsSUFBSUEsQ0FBQ0E7UUFFaEJBLEVBQUVBLENBQUFBLENBQUNBLElBQUlBLENBQUNBLFVBQVVBLENBQUNBO1lBQUNBLE1BQU1BLENBQUNBLEtBQUtBLENBQUNBO1FBRWpDQSxFQUFFQSxDQUFBQSxDQUFDQSxPQUFPQSxDQUFDQTtZQUNUQSxJQUFJQSxDQUFDQSxNQUFNQSxHQUFHQSxFQUFFQSxDQUFDQTtRQUVuQkEsSUFBSUEsQ0FBQ0EsVUFBVUEsR0FBR0EsSUFBSUEsQ0FBQ0E7UUFFdkJBLElBQUlBLENBQUNBLE1BQU1BLENBQUNBLEdBQUdBLENBQUNBLHNCQUFzQkEsRUFBRUEsRUFBQ0EsS0FBS0EsRUFBQ0EsRUFBRUEsRUFBRUEsTUFBTUEsRUFBQ0EsSUFBSUEsQ0FBQ0EsTUFBTUEsRUFBQ0EsQ0FBQ0E7YUFDcEVBLElBQUlBLENBQUNBLFVBQUNBLElBQVVBO1lBRWZBLEVBQUVBLENBQUFBLENBQUNBLENBQUNBLElBQUlBLENBQUNBLGFBQWFBLENBQUNBLENBQUFBLENBQUNBO2dCQUN0QkEsSUFBSUEsQ0FBQ0EsUUFBUUEsR0FBR0EsS0FBS0EsQ0FBQ0E7Z0JBQ3RCQSxJQUFJQSxDQUFDQSxVQUFVQSxHQUFHQSxLQUFLQSxDQUFDQTtnQkFDeEJBLE1BQU1BLENBQUNBLEtBQUtBLENBQUNBO1lBQ2ZBLENBQUNBO1lBRURBLEVBQUVBLENBQUFBLENBQUNBLE9BQU9BLENBQUNBLENBQUFBLENBQUNBO2dCQUNWQSxJQUFJQSxDQUFDQSxhQUFhQSxHQUFHQSxJQUFJQSxDQUFDQSxhQUFhQSxDQUFDQTtZQUMxQ0EsQ0FBQ0E7WUFBQUEsSUFBSUEsQ0FBQUEsQ0FBQ0E7Z0JBQ0pBLEVBQUVBLENBQUFBLENBQUNBLElBQUlBLENBQUNBLE1BQU1BLENBQUNBO29CQUNiQSxJQUFJQSxDQUFDQSxhQUFhQSxDQUFDQSxLQUFLQSxFQUFFQSxDQUFDQTtnQkFDN0JBLEdBQUdBLENBQUFBLENBQWVBLFVBQWtCQSxFQUFsQkEsS0FBQUEsSUFBSUEsQ0FBQ0EsYUFBYUEsRUFBaENBLGNBQVVBLEVBQVZBLElBQWdDQSxDQUFDQTtvQkFBakNBLElBQUlBLE1BQU1BLFNBQUFBO29CQUNaQSxJQUFJQSxDQUFDQSxhQUFhQSxDQUFDQSxJQUFJQSxDQUFDQSxNQUFNQSxDQUFDQSxDQUFDQTtpQkFBQUE7WUFDcENBLENBQUNBO1lBRURBLElBQUlBLENBQUNBLE1BQU1BLEdBQUdBLElBQUlBLENBQUNBLFdBQVdBLENBQUNBLENBQUNBO1lBQ2hDQSxJQUFJQSxDQUFDQSxVQUFVQSxHQUFHQSxLQUFLQSxDQUFDQTtRQUUxQkEsQ0FBQ0EsQ0FBQ0EsQ0FBQ0E7SUFDUEEsQ0FBQ0E7SUFyREhGO1FBQUNBLG9CQUFTQSxDQUFDQTtZQUNUQSxRQUFRQSxFQUFFQSxxQkFBcUJBO1lBQy9CQSxZQUFZQSxFQUFFQSxDQUFFQSxZQUFNQSxDQUFFQTtTQUN6QkEsQ0FBQ0E7UUFDREEsZUFBSUEsQ0FBQ0E7WUFDSkEsV0FBV0EsRUFBRUEsbUNBQW1DQTtZQUNoREEsVUFBVUEsRUFBRUEsQ0FBRUEsZ0JBQUtBLEVBQUVBLGVBQUlBLEVBQUVBLG1CQUFRQSxFQUFFQSx1QkFBWUEsRUFBRUEsMEJBQWVBLEVBQUVBLGtCQUFPQSxFQUFFQSxtQkFBVUEsRUFBRUEsbUJBQVFBLEVBQUVBLGdDQUFjQSxDQUFFQTtTQUNwSEEsQ0FBQ0E7O3NCQWdEREE7SUFBREEsb0JBQUNBO0FBQURBLENBdkRBLEFBdURDQSxJQUFBO0FBOUNZLHFCQUFhLGdCQThDekIsQ0FBQSIsImZpbGUiOiJzcmMvY29udHJvbGxlcnMvbm90aWZpY2F0aW9ucy9ub3RpZmljYXRpb25zLmpzIiwic291cmNlc0NvbnRlbnQiOlsiaW1wb3J0IHsgQ29tcG9uZW50LCBWaWV3LCBOZ0ZvciwgTmdJZiwgTmdTd2l0Y2gsIE5nU3dpdGNoV2hlbiwgTmdTd2l0Y2hEZWZhdWx0LCBJbmplY3QsIE5nQ2xhc3MgfSBmcm9tICdhbmd1bGFyMi9hbmd1bGFyMic7XG5pbXBvcnQgeyBSb3V0ZXJMaW5rIH0gZnJvbSAnYW5ndWxhcjIvcm91dGVyJztcbmltcG9ydCB7IENsaWVudCB9IGZyb20gJ3NyYy9zZXJ2aWNlcy9hcGknO1xuaW1wb3J0IHsgU2Vzc2lvbkZhY3RvcnkgfSBmcm9tICcuLi8uLi9zZXJ2aWNlcy9zZXNzaW9uJztcbmltcG9ydCB7IE1hdGVyaWFsIH0gZnJvbSAnc3JjL2RpcmVjdGl2ZXMvbWF0ZXJpYWwnO1xuaW1wb3J0IHsgSW5maW5pdGVTY3JvbGwgfSBmcm9tICcuLi8uLi9kaXJlY3RpdmVzL2luZmluaXRlLXNjcm9sbCc7XG5cbkBDb21wb25lbnQoe1xuICBzZWxlY3RvcjogJ21pbmRzLW5vdGlmaWNhdGlvbnMnLFxuICB2aWV3QmluZGluZ3M6IFsgQ2xpZW50IF1cbn0pXG5AVmlldyh7XG4gIHRlbXBsYXRlVXJsOiAndGVtcGxhdGVzL25vdGlmaWNhdGlvbnMvbGlzdC5odG1sJyxcbiAgZGlyZWN0aXZlczogWyBOZ0ZvciwgTmdJZiwgTmdTd2l0Y2gsIE5nU3dpdGNoV2hlbiwgTmdTd2l0Y2hEZWZhdWx0LCBOZ0NsYXNzLCBSb3V0ZXJMaW5rLCBNYXRlcmlhbCwgSW5maW5pdGVTY3JvbGwgXVxufSlcblxuZXhwb3J0IGNsYXNzIE5vdGlmaWNhdGlvbnMge1xuXG4gIG5vdGlmaWNhdG9ucyA6IEFycmF5PE9iamVjdD4gPSBbXTtcbiAgbW9yZURhdGEgOiBib29sZWFuID0gdHJ1ZTtcbiAgb2Zmc2V0OiBzdHJpbmcgPSBcIlwiO1xuICBpblByb2dyZXNzIDogYm9vbGVhbiA9IGZhbHNlO1xuICBzZXNzaW9uID0gU2Vzc2lvbkZhY3RvcnkuYnVpbGQoKTtcblxuICBjb25zdHJ1Y3RvcihwdWJsaWMgY2xpZW50OiBDbGllbnQpe1xuICAgIHRoaXMubG9hZCh0cnVlKTtcbiAgfVxuXG4gIGxvYWQocmVmcmVzaCA6IGJvb2xlYW4gPSBmYWxzZSl7XG4gICAgdmFyIHNlbGYgPSB0aGlzO1xuXG4gICAgaWYodGhpcy5pblByb2dyZXNzKSByZXR1cm4gZmFsc2U7XG5cbiAgICBpZihyZWZyZXNoKVxuICAgICAgdGhpcy5vZmZzZXQgPSBcIlwiO1xuXG4gICAgdGhpcy5pblByb2dyZXNzID0gdHJ1ZTtcblxuICAgIHRoaXMuY2xpZW50LmdldCgnYXBpL3YxL25vdGlmaWNhdGlvbnMnLCB7bGltaXQ6MTIsIG9mZnNldDp0aGlzLm9mZnNldH0pXG4gICAgICAudGhlbigoZGF0YSA6IGFueSkgPT4ge1xuXG4gICAgICAgIGlmKCFkYXRhLm5vdGlmaWNhdGlvbnMpe1xuICAgICAgICAgIHNlbGYubW9yZURhdGEgPSBmYWxzZTtcbiAgICAgICAgICBzZWxmLmluUHJvZ3Jlc3MgPSBmYWxzZTtcbiAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgIH1cblxuICAgICAgICBpZihyZWZyZXNoKXtcbiAgICAgICAgICBzZWxmLm5vdGlmaWNhdGlvbnMgPSBkYXRhLm5vdGlmaWNhdGlvbnM7XG4gICAgICAgIH1lbHNle1xuICAgICAgICAgIGlmKHNlbGYub2Zmc2V0KVxuICAgICAgICAgICAgZGF0YS5ub3RpZmljYXRpb25zLnNoaWZ0KCk7XG4gICAgICAgICAgZm9yKGxldCBlbnRpdHkgb2YgZGF0YS5ub3RpZmljYXRpb25zKVxuICAgICAgICAgICAgc2VsZi5ub3RpZmljYXRpb25zLnB1c2goZW50aXR5KTtcbiAgICAgICAgfVxuXG4gICAgICAgIHNlbGYub2Zmc2V0ID0gZGF0YVsnbG9hZC1uZXh0J107XG4gICAgICAgIHNlbGYuaW5Qcm9ncmVzcyA9IGZhbHNlO1xuXG4gICAgICB9KTtcbiAgfVxuXG59XG4iXSwic291cmNlUm9vdCI6Ii9zb3VyY2UvIn0=