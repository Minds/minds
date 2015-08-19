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
/// <reference path="../typings/tsd.d.ts" />
var angular2_1 = require('angular2/angular2');
var router_1 = require('angular2/router');
var http_1 = require('http/http');
var topbar_1 = require('./src/components/topbar');
var navigation_1 = require('./src/components/navigation');
var login_1 = require('./src/controllers/login');
var logout_1 = require('./src/controllers/logout');
var comingsoon_1 = require('./src/controllers/comingsoon');
var newsfeed_1 = require('./src/controllers/newsfeed/newsfeed');
var capture_1 = require('./src/controllers/capture/capture');
var discovery_1 = require('./src/controllers/discovery/discovery');
var channel_1 = require('./src/controllers/channels/channel');
var gatherings_1 = require('./src/plugins/gatherings/gatherings');
var notifications_1 = require('./src/controllers/notifications/notifications');
var Minds = (function () {
    function Minds() {
        this.name = 'Minds';
    }
    Minds = __decorate([
        angular2_1.Component({
            selector: 'minds-app',
        }),
        router_1.RouteConfig([
            new router_1.Route({ path: '/login', component: login_1.Login, as: 'login' }),
            { path: '/logout', component: logout_1.Logout, as: 'logout' },
            { path: '/newsfeed', component: newsfeed_1.Newsfeed, as: 'newsfeed' },
            { path: '/capture', component: capture_1.Capture, as: 'capture' },
            { path: '/discovery/:filter', component: discovery_1.Discovery, as: 'discovery' },
            { path: '/discovery/:filter/:type', component: discovery_1.Discovery, as: 'discovery' },
            { path: '/messenger', component: gatherings_1.Gatherings, as: 'messenger' },
            { path: '/notifications', component: notifications_1.Notifications, as: 'notifications' },
            { path: '/groups', component: comingsoon_1.ComingSoon, as: 'groups' },
            { path: '/:username', component: channel_1.Channel, as: 'channel' },
            { path: '/', redirectTo: '/newsfeed' }
        ]),
        angular2_1.View({
            templateUrl: './templates/index.html',
            directives: [topbar_1.Topbar, navigation_1.Navigation, router_1.RouterOutlet, router_1.RouterLink]
        }), 
        __metadata('design:paramtypes', [])
    ], Minds);
    return Minds;
})();
angular2_1.bootstrap(Minds, [router_1.ROUTER_BINDINGS, http_1.HTTP_BINDINGS]);

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbImFwcC50cyJdLCJuYW1lcyI6WyJNaW5kcyIsIk1pbmRzLmNvbnN0cnVjdG9yIl0sIm1hcHBpbmdzIjoiOzs7Ozs7Ozs7OztBQUFBLEFBQ0EsNENBRDRDO0FBQzVDLHlCQUF5QyxtQkFBbUIsQ0FBQyxDQUFBO0FBQzdELHVCQUE0RSxpQkFBaUIsQ0FBQyxDQUFBO0FBQzlGLHFCQUE0QixXQUFXLENBQUMsQ0FBQTtBQUV4Qyx1QkFBcUIseUJBQXlCLENBQUMsQ0FBQTtBQUMvQywyQkFBeUIsNkJBQTZCLENBQUMsQ0FBQTtBQUV2RCxzQkFBb0IseUJBQXlCLENBQUMsQ0FBQTtBQUM5Qyx1QkFBcUIsMEJBQTBCLENBQUMsQ0FBQTtBQUNoRCwyQkFBeUIsOEJBQThCLENBQUMsQ0FBQTtBQUN4RCx5QkFBdUIscUNBQXFDLENBQUMsQ0FBQTtBQUM3RCx3QkFBc0IsbUNBQW1DLENBQUMsQ0FBQTtBQUMxRCwwQkFBd0IsdUNBQXVDLENBQUMsQ0FBQTtBQUNoRSx3QkFBc0Isb0NBQW9DLENBQUMsQ0FBQTtBQUMzRCwyQkFBeUIscUNBQXFDLENBQUMsQ0FBQTtBQUMvRCw4QkFBNEIsK0NBQStDLENBQUMsQ0FBQTtBQUc1RTtJQTZCRUE7UUFDRUMsSUFBSUEsQ0FBQ0EsSUFBSUEsR0FBR0EsT0FBT0EsQ0FBQ0E7SUFDdEJBLENBQUNBO0lBL0JIRDtRQUFDQSxvQkFBU0EsQ0FBQ0E7WUFDVEEsUUFBUUEsRUFBRUEsV0FBV0E7U0FDdEJBLENBQUNBO1FBQ0RBLG9CQUFXQSxDQUFDQTtZQUNYQSxJQUFJQSxjQUFLQSxDQUFDQSxFQUFFQSxJQUFJQSxFQUFFQSxRQUFRQSxFQUFFQSxTQUFTQSxFQUFFQSxhQUFLQSxFQUFFQSxFQUFFQSxFQUFFQSxPQUFPQSxFQUFFQSxDQUFDQTtZQUM1REEsRUFBRUEsSUFBSUEsRUFBRUEsU0FBU0EsRUFBRUEsU0FBU0EsRUFBRUEsZUFBTUEsRUFBRUEsRUFBRUEsRUFBRUEsUUFBUUEsRUFBRUE7WUFDcERBLEVBQUVBLElBQUlBLEVBQUVBLFdBQVdBLEVBQUVBLFNBQVNBLEVBQUVBLG1CQUFRQSxFQUFFQSxFQUFFQSxFQUFFQSxVQUFVQSxFQUFFQTtZQUMxREEsRUFBRUEsSUFBSUEsRUFBRUEsVUFBVUEsRUFBRUEsU0FBU0EsRUFBRUEsaUJBQU9BLEVBQUVBLEVBQUVBLEVBQUVBLFNBQVNBLEVBQUVBO1lBRXZEQSxFQUFFQSxJQUFJQSxFQUFFQSxvQkFBb0JBLEVBQUVBLFNBQVNBLEVBQUVBLHFCQUFTQSxFQUFFQSxFQUFFQSxFQUFFQSxXQUFXQSxFQUFDQTtZQUNwRUEsRUFBRUEsSUFBSUEsRUFBRUEsMEJBQTBCQSxFQUFFQSxTQUFTQSxFQUFFQSxxQkFBU0EsRUFBRUEsRUFBRUEsRUFBRUEsV0FBV0EsRUFBQ0E7WUFFMUVBLEVBQUVBLElBQUlBLEVBQUVBLFlBQVlBLEVBQUVBLFNBQVNBLEVBQUdBLHVCQUFVQSxFQUFFQSxFQUFFQSxFQUFFQSxXQUFXQSxFQUFDQTtZQUU5REEsRUFBRUEsSUFBSUEsRUFBRUEsZ0JBQWdCQSxFQUFFQSxTQUFTQSxFQUFFQSw2QkFBYUEsRUFBRUEsRUFBRUEsRUFBRUEsZUFBZUEsRUFBQ0E7WUFDeEVBLEVBQUVBLElBQUlBLEVBQUVBLFNBQVNBLEVBQUVBLFNBQVNBLEVBQUVBLHVCQUFVQSxFQUFFQSxFQUFFQSxFQUFFQSxRQUFRQSxFQUFDQTtZQUV2REEsRUFBRUEsSUFBSUEsRUFBRUEsWUFBWUEsRUFBRUEsU0FBU0EsRUFBRUEsaUJBQU9BLEVBQUVBLEVBQUVBLEVBQUVBLFNBQVNBLEVBQUVBO1lBRXpEQSxFQUFFQSxJQUFJQSxFQUFFQSxHQUFHQSxFQUFFQSxVQUFVQSxFQUFFQSxXQUFXQSxFQUFFQTtTQUN2Q0EsQ0FBQ0E7UUFDREEsZUFBSUEsQ0FBQ0E7WUFDSkEsV0FBV0EsRUFBRUEsd0JBQXdCQTtZQUNyQ0EsVUFBVUEsRUFBRUEsQ0FBQ0EsZUFBTUEsRUFBRUEsdUJBQVVBLEVBQUVBLHFCQUFZQSxFQUFFQSxtQkFBVUEsQ0FBQ0E7U0FDM0RBLENBQUNBOztjQVFEQTtJQUFEQSxZQUFDQTtBQUFEQSxDQWhDQSxBQWdDQ0EsSUFBQTtBQUVELG9CQUFTLENBQUMsS0FBSyxFQUFFLENBQUMsd0JBQWUsRUFBRSxvQkFBYSxDQUFDLENBQUMsQ0FBQyIsImZpbGUiOiJhcHAuanMiLCJzb3VyY2VzQ29udGVudCI6WyIvLy8gPHJlZmVyZW5jZSBwYXRoPVwiLi4vdHlwaW5ncy90c2QuZC50c1wiIC8+XG5pbXBvcnQge0NvbXBvbmVudCwgVmlldywgYm9vdHN0cmFwfSBmcm9tICdhbmd1bGFyMi9hbmd1bGFyMic7XG5pbXBvcnQge1JvdXRlQ29uZmlnLCBSb3V0ZXJPdXRsZXQsIFJvdXRlckxpbmssIFJvdXRlLCBST1VURVJfQklORElOR1N9IGZyb20gJ2FuZ3VsYXIyL3JvdXRlcic7XG5pbXBvcnQge0hUVFBfQklORElOR1N9IGZyb20gJ2h0dHAvaHR0cCc7XG5cbmltcG9ydCB7VG9wYmFyfSBmcm9tICcuL3NyYy9jb21wb25lbnRzL3RvcGJhcic7XG5pbXBvcnQge05hdmlnYXRpb259IGZyb20gJy4vc3JjL2NvbXBvbmVudHMvbmF2aWdhdGlvbic7XG5cbmltcG9ydCB7TG9naW59IGZyb20gJy4vc3JjL2NvbnRyb2xsZXJzL2xvZ2luJztcbmltcG9ydCB7TG9nb3V0fSBmcm9tICcuL3NyYy9jb250cm9sbGVycy9sb2dvdXQnO1xuaW1wb3J0IHtDb21pbmdTb29ufSBmcm9tICcuL3NyYy9jb250cm9sbGVycy9jb21pbmdzb29uJztcbmltcG9ydCB7TmV3c2ZlZWR9IGZyb20gJy4vc3JjL2NvbnRyb2xsZXJzL25ld3NmZWVkL25ld3NmZWVkJztcbmltcG9ydCB7Q2FwdHVyZX0gZnJvbSAnLi9zcmMvY29udHJvbGxlcnMvY2FwdHVyZS9jYXB0dXJlJztcbmltcG9ydCB7RGlzY292ZXJ5fSBmcm9tICcuL3NyYy9jb250cm9sbGVycy9kaXNjb3ZlcnkvZGlzY292ZXJ5JztcbmltcG9ydCB7Q2hhbm5lbH0gZnJvbSAnLi9zcmMvY29udHJvbGxlcnMvY2hhbm5lbHMvY2hhbm5lbCc7XG5pbXBvcnQge0dhdGhlcmluZ3N9IGZyb20gJy4vc3JjL3BsdWdpbnMvZ2F0aGVyaW5ncy9nYXRoZXJpbmdzJztcbmltcG9ydCB7Tm90aWZpY2F0aW9uc30gZnJvbSAnLi9zcmMvY29udHJvbGxlcnMvbm90aWZpY2F0aW9ucy9ub3RpZmljYXRpb25zJztcblxuXG5AQ29tcG9uZW50KHtcbiAgc2VsZWN0b3I6ICdtaW5kcy1hcHAnLFxufSlcbkBSb3V0ZUNvbmZpZyhbXG4gIG5ldyBSb3V0ZSh7IHBhdGg6ICcvbG9naW4nLCBjb21wb25lbnQ6IExvZ2luLCBhczogJ2xvZ2luJyB9KSxcbiAgeyBwYXRoOiAnL2xvZ291dCcsIGNvbXBvbmVudDogTG9nb3V0LCBhczogJ2xvZ291dCcgfSxcbiAgeyBwYXRoOiAnL25ld3NmZWVkJywgY29tcG9uZW50OiBOZXdzZmVlZCwgYXM6ICduZXdzZmVlZCcgfSxcbiAgeyBwYXRoOiAnL2NhcHR1cmUnLCBjb21wb25lbnQ6IENhcHR1cmUsIGFzOiAnY2FwdHVyZScgfSxcblxuICB7IHBhdGg6ICcvZGlzY292ZXJ5LzpmaWx0ZXInLCBjb21wb25lbnQ6IERpc2NvdmVyeSwgYXM6ICdkaXNjb3ZlcnknfSxcbiAgeyBwYXRoOiAnL2Rpc2NvdmVyeS86ZmlsdGVyLzp0eXBlJywgY29tcG9uZW50OiBEaXNjb3ZlcnksIGFzOiAnZGlzY292ZXJ5J30sXG5cbiAgeyBwYXRoOiAnL21lc3NlbmdlcicsIGNvbXBvbmVudDogIEdhdGhlcmluZ3MsIGFzOiAnbWVzc2VuZ2VyJ30sXG5cbiAgeyBwYXRoOiAnL25vdGlmaWNhdGlvbnMnLCBjb21wb25lbnQ6IE5vdGlmaWNhdGlvbnMsIGFzOiAnbm90aWZpY2F0aW9ucyd9LFxuICB7IHBhdGg6ICcvZ3JvdXBzJywgY29tcG9uZW50OiBDb21pbmdTb29uLCBhczogJ2dyb3Vwcyd9LFxuXG4gIHsgcGF0aDogJy86dXNlcm5hbWUnLCBjb21wb25lbnQ6IENoYW5uZWwsIGFzOiAnY2hhbm5lbCcgfSxcblxuICB7IHBhdGg6ICcvJywgcmVkaXJlY3RUbzogJy9uZXdzZmVlZCcgfVxuXSlcbkBWaWV3KHtcbiAgdGVtcGxhdGVVcmw6ICcuL3RlbXBsYXRlcy9pbmRleC5odG1sJyxcbiAgZGlyZWN0aXZlczogW1RvcGJhciwgTmF2aWdhdGlvbiwgUm91dGVyT3V0bGV0LCBSb3V0ZXJMaW5rXVxufSlcblxuY2xhc3MgTWluZHMge1xuICBuYW1lOiBzdHJpbmc7XG5cbiAgY29uc3RydWN0b3IoKSB7XG4gICAgdGhpcy5uYW1lID0gJ01pbmRzJztcbiAgfVxufVxuXG5ib290c3RyYXAoTWluZHMsIFtST1VURVJfQklORElOR1MsIEhUVFBfQklORElOR1NdKTtcbiJdLCJzb3VyY2VSb290IjoiL3NvdXJjZS8ifQ==