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
var router_1 = require("angular2/router");
var api_1 = require('src/services/api');
var session_1 = require('src/services/session');
var material_1 = require('src/directives/material');
var Blog = (function () {
    function Blog(client, router, params) {
        this.client = client;
        this.router = router;
        this.params = params;
        this.offset = "";
        this.moreDate = true;
        this.inProgress = false;
        this.blogs = [];
        this.session = session_1.SessionFactory.build();
        this._filter = "featured";
        this._filter = params.params['filter'];
        this.minds = window.Minds;
        this.load();
    }
    Blog.prototype.load = function (refresh) {
        if (refresh === void 0) { refresh = false; }
        return;
        var self = this;
        this.inProgress = true;
        this.client.get('api/v1/groups/' + this._filter, { limit: 12, offset: this.offset })
            .then(function (response) {
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
    Blog = __decorate([
        angular2_1.Component({
            selector: 'minds-blog',
            viewBindings: [api_1.Client]
        }),
        angular2_1.View({
            templateUrl: 'templates/plugins/blog/list.html',
            directives: [angular2_1.NgFor, angular2_1.NgIf, angular2_1.NgClass, material_1.Material, router_1.RouterLink]
        }),
        __param(1, angular2_1.Inject(router_1.Router)),
        __param(2, angular2_1.Inject(router_1.RouteParams)), 
        __metadata('design:paramtypes', [api_1.Client, router_1.Router, router_1.RouteParams])
    ], Blog);
    return Blog;
})();
exports.Blog = Blog;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9wbHVnaW5zL2Jsb2cvYmxvZy50cyJdLCJuYW1lcyI6WyJCbG9nIiwiQmxvZy5jb25zdHJ1Y3RvciIsIkJsb2cubG9hZCJdLCJtYXBwaW5ncyI6Ijs7Ozs7Ozs7Ozs7Ozs7QUFBQSx5QkFBOEUsbUJBQW1CLENBQUMsQ0FBQTtBQUNsRyx1QkFBZ0QsaUJBQWlCLENBQUMsQ0FBQTtBQUVsRSxvQkFBdUIsa0JBQWtCLENBQUMsQ0FBQTtBQUMxQyx3QkFBK0Isc0JBQXNCLENBQUMsQ0FBQTtBQUN0RCx5QkFBeUIseUJBQXlCLENBQUMsQ0FBQTtBQUVuRDtJQWtCRUEsY0FBbUJBLE1BQWNBLEVBQ1JBLE1BQWNBLEVBQ1RBLE1BQW1CQTtRQUY5QkMsV0FBTUEsR0FBTkEsTUFBTUEsQ0FBUUE7UUFDUkEsV0FBTUEsR0FBTkEsTUFBTUEsQ0FBUUE7UUFDVEEsV0FBTUEsR0FBTkEsTUFBTUEsQ0FBYUE7UUFUakRBLFdBQU1BLEdBQVlBLEVBQUVBLENBQUNBO1FBQ3JCQSxhQUFRQSxHQUFhQSxJQUFJQSxDQUFDQTtRQUMxQkEsZUFBVUEsR0FBYUEsS0FBS0EsQ0FBQ0E7UUFDN0JBLFVBQUtBLEdBQWdCQSxFQUFFQSxDQUFDQTtRQUN4QkEsWUFBT0EsR0FBR0Esd0JBQWNBLENBQUNBLEtBQUtBLEVBQUVBLENBQUNBO1FBQ2pDQSxZQUFPQSxHQUFZQSxVQUFVQSxDQUFDQTtRQU0xQkEsSUFBSUEsQ0FBQ0EsT0FBT0EsR0FBR0EsTUFBTUEsQ0FBQ0EsTUFBTUEsQ0FBQ0EsUUFBUUEsQ0FBQ0EsQ0FBQ0E7UUFDdkNBLElBQUlBLENBQUNBLEtBQUtBLEdBQUdBLE1BQU1BLENBQUNBLEtBQUtBLENBQUNBO1FBQzFCQSxJQUFJQSxDQUFDQSxJQUFJQSxFQUFFQSxDQUFDQTtJQUNoQkEsQ0FBQ0E7SUFFREQsbUJBQUlBLEdBQUpBLFVBQUtBLE9BQXlCQTtRQUF6QkUsdUJBQXlCQSxHQUF6QkEsZUFBeUJBO1FBQzVCQSxNQUFNQSxDQUFDQTtRQUNQQSxJQUFJQSxJQUFJQSxHQUFHQSxJQUFJQSxDQUFDQTtRQUNoQkEsSUFBSUEsQ0FBQ0EsVUFBVUEsR0FBR0EsSUFBSUEsQ0FBQ0E7UUFDdkJBLElBQUlBLENBQUNBLE1BQU1BLENBQUNBLEdBQUdBLENBQUNBLGdCQUFnQkEsR0FBR0EsSUFBSUEsQ0FBQ0EsT0FBT0EsRUFBRUEsRUFBRUEsS0FBS0EsRUFBRUEsRUFBRUEsRUFBRUEsTUFBTUEsRUFBRUEsSUFBSUEsQ0FBQ0EsTUFBTUEsRUFBQ0EsQ0FBQ0E7YUFDaEZBLElBQUlBLENBQUNBLFVBQUNBLFFBQVFBO1lBRWJBLEVBQUVBLENBQUFBLENBQUNBLENBQUNBLFFBQVFBLENBQUNBLE1BQU1BLENBQUNBLENBQUFBLENBQUNBO2dCQUNuQkEsSUFBSUEsQ0FBQ0EsUUFBUUEsR0FBR0EsS0FBS0EsQ0FBQ0E7Z0JBQ3RCQSxJQUFJQSxDQUFDQSxVQUFVQSxHQUFHQSxLQUFLQSxDQUFDQTtnQkFDeEJBLE1BQU1BLENBQUNBLEtBQUtBLENBQUNBO1lBQ2ZBLENBQUNBO1lBRURBLEVBQUVBLENBQUFBLENBQUNBLE9BQU9BLENBQUNBLENBQUFBLENBQUNBO2dCQUNWQSxJQUFJQSxDQUFDQSxNQUFNQSxHQUFHQSxRQUFRQSxDQUFDQSxNQUFNQSxDQUFDQTtZQUNoQ0EsQ0FBQ0E7WUFBQ0EsSUFBSUEsQ0FBQ0EsQ0FBQ0E7Z0JBQ05BLEVBQUVBLENBQUFBLENBQUNBLElBQUlBLENBQUNBLE1BQU1BLENBQUNBO29CQUNiQSxRQUFRQSxDQUFDQSxNQUFNQSxDQUFDQSxLQUFLQSxFQUFFQSxDQUFDQTtnQkFDMUJBLEdBQUdBLENBQUFBLENBQWNBLFVBQWVBLEVBQWZBLEtBQUFBLFFBQVFBLENBQUNBLE1BQU1BLEVBQTVCQSxjQUFTQSxFQUFUQSxJQUE0QkEsQ0FBQ0E7b0JBQTdCQSxJQUFJQSxLQUFLQSxTQUFBQTtvQkFDWEEsSUFBSUEsQ0FBQ0EsTUFBTUEsQ0FBQ0EsSUFBSUEsQ0FBQ0EsS0FBS0EsQ0FBQ0EsQ0FBQ0E7aUJBQUFBO1lBQzVCQSxDQUFDQTtZQUVEQSxJQUFJQSxDQUFDQSxNQUFNQSxHQUFHQSxRQUFRQSxDQUFDQSxXQUFXQSxDQUFDQSxDQUFDQTtZQUNwQ0EsSUFBSUEsQ0FBQ0EsVUFBVUEsR0FBR0EsS0FBS0EsQ0FBQ0E7UUFDMUJBLENBQUNBLENBQUNBO2FBQ0RBLEtBQUtBLENBQUNBLFVBQUNBLENBQUNBO1FBRVRBLENBQUNBLENBQUNBLENBQUNBO0lBQ1BBLENBQUNBO0lBdkRIRjtRQUFDQSxvQkFBU0EsQ0FBQ0E7WUFDVEEsUUFBUUEsRUFBRUEsWUFBWUE7WUFDdEJBLFlBQVlBLEVBQUVBLENBQUVBLFlBQU1BLENBQUVBO1NBQ3pCQSxDQUFDQTtRQUNEQSxlQUFJQSxDQUFDQTtZQUNKQSxXQUFXQSxFQUFFQSxrQ0FBa0NBO1lBQy9DQSxVQUFVQSxFQUFFQSxDQUFFQSxnQkFBS0EsRUFBRUEsZUFBSUEsRUFBRUEsa0JBQU9BLEVBQUVBLG1CQUFRQSxFQUFFQSxtQkFBVUEsQ0FBRUE7U0FDM0RBLENBQUNBO1FBWUVBLFdBQUNBLGlCQUFNQSxDQUFDQSxlQUFNQSxDQUFDQSxDQUFBQTtRQUNmQSxXQUFDQSxpQkFBTUEsQ0FBQ0Esb0JBQVdBLENBQUNBLENBQUFBOzthQW9DdkJBO0lBQURBLFdBQUNBO0FBQURBLENBeERBLEFBd0RDQSxJQUFBO0FBL0NZLFlBQUksT0ErQ2hCLENBQUEiLCJmaWxlIjoic3JjL3BsdWdpbnMvYmxvZy9ibG9nLmpzIiwic291cmNlc0NvbnRlbnQiOlsiaW1wb3J0IHsgQ29tcG9uZW50LCBWaWV3LCBOZ0ZvciwgTmdJZiwgTmdDbGFzcywgSW5qZWN0LCBGT1JNX0RJUkVDVElWRVN9IGZyb20gJ2FuZ3VsYXIyL2FuZ3VsYXIyJztcbmltcG9ydCB7IFJvdXRlckxpbmssIFJvdXRlciwgUm91dGVQYXJhbXMgfSBmcm9tIFwiYW5ndWxhcjIvcm91dGVyXCI7XG5cbmltcG9ydCB7IENsaWVudCB9IGZyb20gJ3NyYy9zZXJ2aWNlcy9hcGknO1xuaW1wb3J0IHsgU2Vzc2lvbkZhY3RvcnkgfSBmcm9tICdzcmMvc2VydmljZXMvc2Vzc2lvbic7XG5pbXBvcnQgeyBNYXRlcmlhbCB9IGZyb20gJ3NyYy9kaXJlY3RpdmVzL21hdGVyaWFsJztcblxuQENvbXBvbmVudCh7XG4gIHNlbGVjdG9yOiAnbWluZHMtYmxvZycsXG4gIHZpZXdCaW5kaW5nczogWyBDbGllbnQgXVxufSlcbkBWaWV3KHtcbiAgdGVtcGxhdGVVcmw6ICd0ZW1wbGF0ZXMvcGx1Z2lucy9ibG9nL2xpc3QuaHRtbCcsXG4gIGRpcmVjdGl2ZXM6IFsgTmdGb3IsIE5nSWYsIE5nQ2xhc3MsIE1hdGVyaWFsLCBSb3V0ZXJMaW5rIF1cbn0pXG5cbmV4cG9ydCBjbGFzcyBCbG9nIHtcblxuICBvZmZzZXQgOiBzdHJpbmcgPSBcIlwiO1xuICBtb3JlRGF0ZSA6IGJvb2xlYW4gPSB0cnVlO1xuICBpblByb2dyZXNzIDogYm9vbGVhbiA9IGZhbHNlO1xuICBibG9ncyA6IEFycmF5PGFueT4gPSBbXTtcbiAgc2Vzc2lvbiA9IFNlc3Npb25GYWN0b3J5LmJ1aWxkKCk7XG4gIF9maWx0ZXIgOiBzdHJpbmcgPSBcImZlYXR1cmVkXCI7XG5cbiAgY29uc3RydWN0b3IocHVibGljIGNsaWVudDogQ2xpZW50LFxuICAgIEBJbmplY3QoUm91dGVyKSBwdWJsaWMgcm91dGVyOiBSb3V0ZXIsXG4gICAgQEluamVjdChSb3V0ZVBhcmFtcykgcHVibGljIHBhcmFtczogUm91dGVQYXJhbXNcbiAgICApe1xuICAgICAgdGhpcy5fZmlsdGVyID0gcGFyYW1zLnBhcmFtc1snZmlsdGVyJ107XG4gICAgICB0aGlzLm1pbmRzID0gd2luZG93Lk1pbmRzO1xuICAgICAgdGhpcy5sb2FkKCk7XG4gIH1cblxuICBsb2FkKHJlZnJlc2ggOiBib29sZWFuID0gZmFsc2Upe1xuICAgIHJldHVybjtcbiAgICB2YXIgc2VsZiA9IHRoaXM7XG4gICAgdGhpcy5pblByb2dyZXNzID0gdHJ1ZTtcbiAgICB0aGlzLmNsaWVudC5nZXQoJ2FwaS92MS9ncm91cHMvJyArIHRoaXMuX2ZpbHRlciwgeyBsaW1pdDogMTIsIG9mZnNldDogdGhpcy5vZmZzZXR9KVxuICAgICAgLnRoZW4oKHJlc3BvbnNlKSA9PiB7XG5cbiAgICAgICAgaWYoIXJlc3BvbnNlLmdyb3Vwcyl7XG4gICAgICAgICAgc2VsZi5tb3JlRGF0YSA9IGZhbHNlO1xuICAgICAgICAgIHNlbGYuaW5Qcm9ncmVzcyA9IGZhbHNlO1xuICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgfVxuXG4gICAgICAgIGlmKHJlZnJlc2gpe1xuICAgICAgICAgIHNlbGYuZ3JvdXBzID0gcmVzcG9uc2UuZ3JvdXBzO1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgIGlmKHNlbGYub2Zmc2V0KVxuICAgICAgICAgICAgcmVzcG9uc2UuZ3JvdXBzLnNoaWZ0KCk7XG4gICAgICAgICAgZm9yKGxldCBncm91cCBvZiByZXNwb25zZS5ncm91cHMpXG4gICAgICAgICAgICBzZWxmLmdyb3Vwcy5wdXNoKGdyb3VwKTtcbiAgICAgICAgfVxuXG4gICAgICAgIHNlbGYub2Zmc2V0ID0gcmVzcG9uc2VbJ2xvYWQtbmV4dCddO1xuICAgICAgICBzZWxmLmluUHJvZ3Jlc3MgPSBmYWxzZTtcbiAgICAgIH0pXG4gICAgICAuY2F0Y2goKGUpPT57XG5cbiAgICAgIH0pO1xuICB9XG59XG5cblxuLy9leHBvcnQgeyBCbG9nVmlldyB9IGZyb20gJy4vYmxvZy12aWV3JztcbiJdLCJzb3VyY2VSb290IjoiL3NvdXJjZS8ifQ==