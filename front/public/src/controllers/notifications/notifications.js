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
var material_1 = require('src/directives/material');
var infinite_scroll_1 = require('../../directives/infinite-scroll');
var Notifications = (function () {
    function Notifications(client) {
        this.client = client;
        this.notificatons = [];
        this.moreData = true;
        this.offset = "";
        this.inProgress = false;
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

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9jb250cm9sbGVycy9ub3RpZmljYXRpb25zL25vdGlmaWNhdGlvbnMudHMiXSwibmFtZXMiOlsiTm90aWZpY2F0aW9ucyIsIk5vdGlmaWNhdGlvbnMuY29uc3RydWN0b3IiLCJOb3RpZmljYXRpb25zLmxvYWQiXSwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7O0FBQUEseUJBQXVHLG1CQUFtQixDQUFDLENBQUE7QUFDM0gsdUJBQTJCLGlCQUFpQixDQUFDLENBQUE7QUFDN0Msb0JBQXVCLGtCQUFrQixDQUFDLENBQUE7QUFDMUMseUJBQXlCLHlCQUF5QixDQUFDLENBQUE7QUFDbkQsZ0NBQStCLGtDQUFrQyxDQUFDLENBQUE7QUFFbEU7SUFnQkVBLHVCQUFtQkEsTUFBY0E7UUFBZEMsV0FBTUEsR0FBTkEsTUFBTUEsQ0FBUUE7UUFMakNBLGlCQUFZQSxHQUFtQkEsRUFBRUEsQ0FBQ0E7UUFDbENBLGFBQVFBLEdBQWFBLElBQUlBLENBQUNBO1FBQzFCQSxXQUFNQSxHQUFXQSxFQUFFQSxDQUFDQTtRQUNwQkEsZUFBVUEsR0FBYUEsS0FBS0EsQ0FBQ0E7UUFHM0JBLElBQUlBLENBQUNBLElBQUlBLENBQUNBLElBQUlBLENBQUNBLENBQUNBO0lBQ2xCQSxDQUFDQTtJQUVERCw0QkFBSUEsR0FBSkEsVUFBS0EsT0FBeUJBO1FBQXpCRSx1QkFBeUJBLEdBQXpCQSxlQUF5QkE7UUFDNUJBLElBQUlBLElBQUlBLEdBQUdBLElBQUlBLENBQUNBO1FBRWhCQSxFQUFFQSxDQUFBQSxDQUFDQSxJQUFJQSxDQUFDQSxVQUFVQSxDQUFDQTtZQUFDQSxNQUFNQSxDQUFDQSxLQUFLQSxDQUFDQTtRQUVqQ0EsRUFBRUEsQ0FBQUEsQ0FBQ0EsT0FBT0EsQ0FBQ0E7WUFDVEEsSUFBSUEsQ0FBQ0EsTUFBTUEsR0FBR0EsRUFBRUEsQ0FBQ0E7UUFFbkJBLElBQUlBLENBQUNBLFVBQVVBLEdBQUdBLElBQUlBLENBQUNBO1FBRXZCQSxJQUFJQSxDQUFDQSxNQUFNQSxDQUFDQSxHQUFHQSxDQUFDQSxzQkFBc0JBLEVBQUVBLEVBQUNBLEtBQUtBLEVBQUNBLEVBQUVBLEVBQUVBLE1BQU1BLEVBQUNBLElBQUlBLENBQUNBLE1BQU1BLEVBQUNBLENBQUNBO2FBQ3BFQSxJQUFJQSxDQUFDQSxVQUFDQSxJQUFVQTtZQUVmQSxFQUFFQSxDQUFBQSxDQUFDQSxDQUFDQSxJQUFJQSxDQUFDQSxhQUFhQSxDQUFDQSxDQUFBQSxDQUFDQTtnQkFDdEJBLElBQUlBLENBQUNBLFFBQVFBLEdBQUdBLEtBQUtBLENBQUNBO2dCQUN0QkEsSUFBSUEsQ0FBQ0EsVUFBVUEsR0FBR0EsS0FBS0EsQ0FBQ0E7Z0JBQ3hCQSxNQUFNQSxDQUFDQSxLQUFLQSxDQUFDQTtZQUNmQSxDQUFDQTtZQUVEQSxFQUFFQSxDQUFBQSxDQUFDQSxPQUFPQSxDQUFDQSxDQUFBQSxDQUFDQTtnQkFDVkEsSUFBSUEsQ0FBQ0EsYUFBYUEsR0FBR0EsSUFBSUEsQ0FBQ0EsYUFBYUEsQ0FBQ0E7WUFDMUNBLENBQUNBO1lBQUFBLElBQUlBLENBQUFBLENBQUNBO2dCQUNKQSxFQUFFQSxDQUFBQSxDQUFDQSxJQUFJQSxDQUFDQSxNQUFNQSxDQUFDQTtvQkFDYkEsSUFBSUEsQ0FBQ0EsYUFBYUEsQ0FBQ0EsS0FBS0EsRUFBRUEsQ0FBQ0E7Z0JBQzdCQSxHQUFHQSxDQUFBQSxDQUFlQSxVQUFrQkEsRUFBbEJBLEtBQUFBLElBQUlBLENBQUNBLGFBQWFBLEVBQWhDQSxjQUFVQSxFQUFWQSxJQUFnQ0EsQ0FBQ0E7b0JBQWpDQSxJQUFJQSxNQUFNQSxTQUFBQTtvQkFDWkEsSUFBSUEsQ0FBQ0EsYUFBYUEsQ0FBQ0EsSUFBSUEsQ0FBQ0EsTUFBTUEsQ0FBQ0EsQ0FBQ0E7aUJBQUFBO1lBQ3BDQSxDQUFDQTtZQUVEQSxJQUFJQSxDQUFDQSxNQUFNQSxHQUFHQSxJQUFJQSxDQUFDQSxXQUFXQSxDQUFDQSxDQUFDQTtZQUNoQ0EsSUFBSUEsQ0FBQ0EsVUFBVUEsR0FBR0EsS0FBS0EsQ0FBQ0E7UUFFMUJBLENBQUNBLENBQUNBLENBQUNBO0lBQ1BBLENBQUNBO0lBcERIRjtRQUFDQSxvQkFBU0EsQ0FBQ0E7WUFDVEEsUUFBUUEsRUFBRUEscUJBQXFCQTtZQUMvQkEsWUFBWUEsRUFBRUEsQ0FBRUEsWUFBTUEsQ0FBRUE7U0FDekJBLENBQUNBO1FBQ0RBLGVBQUlBLENBQUNBO1lBQ0pBLFdBQVdBLEVBQUVBLG1DQUFtQ0E7WUFDaERBLFVBQVVBLEVBQUVBLENBQUVBLGdCQUFLQSxFQUFFQSxlQUFJQSxFQUFFQSxtQkFBUUEsRUFBRUEsdUJBQVlBLEVBQUVBLDBCQUFlQSxFQUFFQSxrQkFBT0EsRUFBRUEsbUJBQVVBLEVBQUVBLG1CQUFRQSxFQUFFQSxnQ0FBY0EsQ0FBRUE7U0FDcEhBLENBQUNBOztzQkErQ0RBO0lBQURBLG9CQUFDQTtBQUFEQSxDQXREQSxBQXNEQ0EsSUFBQTtBQTdDWSxxQkFBYSxnQkE2Q3pCLENBQUEiLCJmaWxlIjoic3JjL2NvbnRyb2xsZXJzL25vdGlmaWNhdGlvbnMvbm90aWZpY2F0aW9ucy5qcyIsInNvdXJjZXNDb250ZW50IjpbImltcG9ydCB7IENvbXBvbmVudCwgVmlldywgTmdGb3IsIE5nSWYsIE5nU3dpdGNoLCBOZ1N3aXRjaFdoZW4sIE5nU3dpdGNoRGVmYXVsdCwgSW5qZWN0LCBOZ0NsYXNzIH0gZnJvbSAnYW5ndWxhcjIvYW5ndWxhcjInO1xuaW1wb3J0IHsgUm91dGVyTGluayB9IGZyb20gJ2FuZ3VsYXIyL3JvdXRlcic7XG5pbXBvcnQgeyBDbGllbnQgfSBmcm9tICdzcmMvc2VydmljZXMvYXBpJztcbmltcG9ydCB7IE1hdGVyaWFsIH0gZnJvbSAnc3JjL2RpcmVjdGl2ZXMvbWF0ZXJpYWwnO1xuaW1wb3J0IHsgSW5maW5pdGVTY3JvbGwgfSBmcm9tICcuLi8uLi9kaXJlY3RpdmVzL2luZmluaXRlLXNjcm9sbCc7XG5cbkBDb21wb25lbnQoe1xuICBzZWxlY3RvcjogJ21pbmRzLW5vdGlmaWNhdGlvbnMnLFxuICB2aWV3QmluZGluZ3M6IFsgQ2xpZW50IF1cbn0pXG5AVmlldyh7XG4gIHRlbXBsYXRlVXJsOiAndGVtcGxhdGVzL25vdGlmaWNhdGlvbnMvbGlzdC5odG1sJyxcbiAgZGlyZWN0aXZlczogWyBOZ0ZvciwgTmdJZiwgTmdTd2l0Y2gsIE5nU3dpdGNoV2hlbiwgTmdTd2l0Y2hEZWZhdWx0LCBOZ0NsYXNzLCBSb3V0ZXJMaW5rLCBNYXRlcmlhbCwgSW5maW5pdGVTY3JvbGwgXVxufSlcblxuZXhwb3J0IGNsYXNzIE5vdGlmaWNhdGlvbnMge1xuXG4gIG5vdGlmaWNhdG9ucyA6IEFycmF5PE9iamVjdD4gPSBbXTtcbiAgbW9yZURhdGEgOiBib29sZWFuID0gdHJ1ZTtcbiAgb2Zmc2V0OiBzdHJpbmcgPSBcIlwiO1xuICBpblByb2dyZXNzIDogYm9vbGVhbiA9IGZhbHNlO1xuXG4gIGNvbnN0cnVjdG9yKHB1YmxpYyBjbGllbnQ6IENsaWVudCl7XG4gICAgdGhpcy5sb2FkKHRydWUpO1xuICB9XG5cbiAgbG9hZChyZWZyZXNoIDogYm9vbGVhbiA9IGZhbHNlKXtcbiAgICB2YXIgc2VsZiA9IHRoaXM7XG5cbiAgICBpZih0aGlzLmluUHJvZ3Jlc3MpIHJldHVybiBmYWxzZTtcblxuICAgIGlmKHJlZnJlc2gpXG4gICAgICB0aGlzLm9mZnNldCA9IFwiXCI7XG5cbiAgICB0aGlzLmluUHJvZ3Jlc3MgPSB0cnVlO1xuXG4gICAgdGhpcy5jbGllbnQuZ2V0KCdhcGkvdjEvbm90aWZpY2F0aW9ucycsIHtsaW1pdDoxMiwgb2Zmc2V0OnRoaXMub2Zmc2V0fSlcbiAgICAgIC50aGVuKChkYXRhIDogYW55KSA9PiB7XG5cbiAgICAgICAgaWYoIWRhdGEubm90aWZpY2F0aW9ucyl7XG4gICAgICAgICAgc2VsZi5tb3JlRGF0YSA9IGZhbHNlO1xuICAgICAgICAgIHNlbGYuaW5Qcm9ncmVzcyA9IGZhbHNlO1xuICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgfVxuXG4gICAgICAgIGlmKHJlZnJlc2gpe1xuICAgICAgICAgIHNlbGYubm90aWZpY2F0aW9ucyA9IGRhdGEubm90aWZpY2F0aW9ucztcbiAgICAgICAgfWVsc2V7XG4gICAgICAgICAgaWYoc2VsZi5vZmZzZXQpXG4gICAgICAgICAgICBkYXRhLm5vdGlmaWNhdGlvbnMuc2hpZnQoKTtcbiAgICAgICAgICBmb3IobGV0IGVudGl0eSBvZiBkYXRhLm5vdGlmaWNhdGlvbnMpXG4gICAgICAgICAgICBzZWxmLm5vdGlmaWNhdGlvbnMucHVzaChlbnRpdHkpO1xuICAgICAgICB9XG5cbiAgICAgICAgc2VsZi5vZmZzZXQgPSBkYXRhWydsb2FkLW5leHQnXTtcbiAgICAgICAgc2VsZi5pblByb2dyZXNzID0gZmFsc2U7XG5cbiAgICAgIH0pO1xuICB9XG5cbn1cbiJdLCJzb3VyY2VSb290IjoiL3NvdXJjZS8ifQ==