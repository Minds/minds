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
            { path: '/login', component: login_1.Login, as: 'login' },
            { path: '/logout', component: logout_1.Logout, as: 'logout' },
            { path: '/newsfeed', component: newsfeed_1.Newsfeed, as: 'newsfeed' },
            { path: '/capture', component: capture_1.Capture, as: 'capture' },
            { path: '/discovery/:filter', component: discovery_1.Discovery, as: 'discovery' },
            { path: '/discovery/:filter/:type', component: discovery_1.Discovery, as: 'discovery' },
            { path: '/messenger', component: gatherings_1.Gatherings, as: 'messenger' },
            { path: '/notifications', component: notifications_1.Notifications, as: 'notifications' },
            { path: '/groups', component: comingsoon_1.ComingSoon, as: 'groups' },
            { path: '/:username', component: channel_1.Channel, as: 'channel' }
        ]),
        angular2_1.View({
            templateUrl: './templates/index.html',
            directives: [topbar_1.Topbar, navigation_1.Navigation, router_1.RouterOutlet, router_1.RouterLink]
        }), 
        __metadata('design:paramtypes', [])
    ], Minds);
    return Minds;
})();
angular2_1.bootstrap(Minds, [router_1.routerInjectables, http_1.HTTP_BINDINGS]);

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbImFwcC50cyJdLCJuYW1lcyI6WyJNaW5kcyIsIk1pbmRzLmNvbnN0cnVjdG9yIl0sIm1hcHBpbmdzIjoiOzs7Ozs7Ozs7OztBQUFBLEFBQ0EsNENBRDRDO0FBQzVDLHlCQUF5QyxtQkFBbUIsQ0FBQyxDQUFBO0FBQzdELHVCQUF1RSxpQkFBaUIsQ0FBQyxDQUFBO0FBQ3pGLHFCQUE0QixXQUFXLENBQUMsQ0FBQTtBQUV4Qyx1QkFBcUIseUJBQXlCLENBQUMsQ0FBQTtBQUMvQywyQkFBeUIsNkJBQTZCLENBQUMsQ0FBQTtBQUV2RCxzQkFBb0IseUJBQXlCLENBQUMsQ0FBQTtBQUM5Qyx1QkFBcUIsMEJBQTBCLENBQUMsQ0FBQTtBQUNoRCwyQkFBeUIsOEJBQThCLENBQUMsQ0FBQTtBQUN4RCx5QkFBdUIscUNBQXFDLENBQUMsQ0FBQTtBQUM3RCx3QkFBc0IsbUNBQW1DLENBQUMsQ0FBQTtBQUMxRCwwQkFBd0IsdUNBQXVDLENBQUMsQ0FBQTtBQUNoRSx3QkFBc0Isb0NBQW9DLENBQUMsQ0FBQTtBQUMzRCwyQkFBeUIscUNBQXFDLENBQUMsQ0FBQTtBQUMvRCw4QkFBNEIsK0NBQStDLENBQUMsQ0FBQTtBQUc1RTtJQTJCRUE7UUFDRUMsSUFBSUEsQ0FBQ0EsSUFBSUEsR0FBR0EsT0FBT0EsQ0FBQ0E7SUFDdEJBLENBQUNBO0lBN0JIRDtRQUFDQSxvQkFBU0EsQ0FBQ0E7WUFDVEEsUUFBUUEsRUFBRUEsV0FBV0E7U0FDdEJBLENBQUNBO1FBQ0RBLG9CQUFXQSxDQUFDQTtZQUNYQSxFQUFFQSxJQUFJQSxFQUFFQSxRQUFRQSxFQUFFQSxTQUFTQSxFQUFFQSxhQUFLQSxFQUFFQSxFQUFFQSxFQUFFQSxPQUFPQSxFQUFFQTtZQUNqREEsRUFBRUEsSUFBSUEsRUFBRUEsU0FBU0EsRUFBRUEsU0FBU0EsRUFBRUEsZUFBTUEsRUFBRUEsRUFBRUEsRUFBRUEsUUFBUUEsRUFBRUE7WUFDcERBLEVBQUVBLElBQUlBLEVBQUVBLFdBQVdBLEVBQUVBLFNBQVNBLEVBQUVBLG1CQUFRQSxFQUFFQSxFQUFFQSxFQUFFQSxVQUFVQSxFQUFFQTtZQUMxREEsRUFBRUEsSUFBSUEsRUFBRUEsVUFBVUEsRUFBRUEsU0FBU0EsRUFBRUEsaUJBQU9BLEVBQUVBLEVBQUVBLEVBQUVBLFNBQVNBLEVBQUVBO1lBRXZEQSxFQUFFQSxJQUFJQSxFQUFFQSxvQkFBb0JBLEVBQUVBLFNBQVNBLEVBQUVBLHFCQUFTQSxFQUFFQSxFQUFFQSxFQUFFQSxXQUFXQSxFQUFDQTtZQUNwRUEsRUFBRUEsSUFBSUEsRUFBRUEsMEJBQTBCQSxFQUFFQSxTQUFTQSxFQUFFQSxxQkFBU0EsRUFBRUEsRUFBRUEsRUFBRUEsV0FBV0EsRUFBQ0E7WUFFMUVBLEVBQUVBLElBQUlBLEVBQUVBLFlBQVlBLEVBQUVBLFNBQVNBLEVBQUdBLHVCQUFVQSxFQUFFQSxFQUFFQSxFQUFFQSxXQUFXQSxFQUFDQTtZQUU5REEsRUFBRUEsSUFBSUEsRUFBRUEsZ0JBQWdCQSxFQUFFQSxTQUFTQSxFQUFFQSw2QkFBYUEsRUFBRUEsRUFBRUEsRUFBRUEsZUFBZUEsRUFBQ0E7WUFDeEVBLEVBQUVBLElBQUlBLEVBQUVBLFNBQVNBLEVBQUVBLFNBQVNBLEVBQUVBLHVCQUFVQSxFQUFFQSxFQUFFQSxFQUFFQSxRQUFRQSxFQUFDQTtZQUV2REEsRUFBRUEsSUFBSUEsRUFBRUEsWUFBWUEsRUFBRUEsU0FBU0EsRUFBRUEsaUJBQU9BLEVBQUVBLEVBQUVBLEVBQUVBLFNBQVNBLEVBQUVBO1NBQzFEQSxDQUFDQTtRQUNEQSxlQUFJQSxDQUFDQTtZQUNKQSxXQUFXQSxFQUFFQSx3QkFBd0JBO1lBQ3JDQSxVQUFVQSxFQUFFQSxDQUFDQSxlQUFNQSxFQUFFQSx1QkFBVUEsRUFBRUEscUJBQVlBLEVBQUVBLG1CQUFVQSxDQUFDQTtTQUMzREEsQ0FBQ0E7O2NBUURBO0lBQURBLFlBQUNBO0FBQURBLENBOUJBLEFBOEJDQSxJQUFBO0FBRUQsb0JBQVMsQ0FBQyxLQUFLLEVBQUUsQ0FBQywwQkFBaUIsRUFBRSxvQkFBYSxDQUFDLENBQUMsQ0FBQyIsImZpbGUiOiJhcHAuanMiLCJzb3VyY2VzQ29udGVudCI6WyIvLy8gPHJlZmVyZW5jZSBwYXRoPVwiLi4vdHlwaW5ncy90c2QuZC50c1wiIC8+XG5pbXBvcnQge0NvbXBvbmVudCwgVmlldywgYm9vdHN0cmFwfSBmcm9tICdhbmd1bGFyMi9hbmd1bGFyMic7XG5pbXBvcnQge1JvdXRlQ29uZmlnLCBSb3V0ZXJPdXRsZXQsIFJvdXRlckxpbmssIHJvdXRlckluamVjdGFibGVzfSBmcm9tICdhbmd1bGFyMi9yb3V0ZXInO1xuaW1wb3J0IHtIVFRQX0JJTkRJTkdTfSBmcm9tICdodHRwL2h0dHAnO1xuXG5pbXBvcnQge1RvcGJhcn0gZnJvbSAnLi9zcmMvY29tcG9uZW50cy90b3BiYXInO1xuaW1wb3J0IHtOYXZpZ2F0aW9ufSBmcm9tICcuL3NyYy9jb21wb25lbnRzL25hdmlnYXRpb24nO1xuXG5pbXBvcnQge0xvZ2lufSBmcm9tICcuL3NyYy9jb250cm9sbGVycy9sb2dpbic7XG5pbXBvcnQge0xvZ291dH0gZnJvbSAnLi9zcmMvY29udHJvbGxlcnMvbG9nb3V0JztcbmltcG9ydCB7Q29taW5nU29vbn0gZnJvbSAnLi9zcmMvY29udHJvbGxlcnMvY29taW5nc29vbic7XG5pbXBvcnQge05ld3NmZWVkfSBmcm9tICcuL3NyYy9jb250cm9sbGVycy9uZXdzZmVlZC9uZXdzZmVlZCc7XG5pbXBvcnQge0NhcHR1cmV9IGZyb20gJy4vc3JjL2NvbnRyb2xsZXJzL2NhcHR1cmUvY2FwdHVyZSc7XG5pbXBvcnQge0Rpc2NvdmVyeX0gZnJvbSAnLi9zcmMvY29udHJvbGxlcnMvZGlzY292ZXJ5L2Rpc2NvdmVyeSc7XG5pbXBvcnQge0NoYW5uZWx9IGZyb20gJy4vc3JjL2NvbnRyb2xsZXJzL2NoYW5uZWxzL2NoYW5uZWwnO1xuaW1wb3J0IHtHYXRoZXJpbmdzfSBmcm9tICcuL3NyYy9wbHVnaW5zL2dhdGhlcmluZ3MvZ2F0aGVyaW5ncyc7XG5pbXBvcnQge05vdGlmaWNhdGlvbnN9IGZyb20gJy4vc3JjL2NvbnRyb2xsZXJzL25vdGlmaWNhdGlvbnMvbm90aWZpY2F0aW9ucyc7XG5cblxuQENvbXBvbmVudCh7XG4gIHNlbGVjdG9yOiAnbWluZHMtYXBwJyxcbn0pXG5AUm91dGVDb25maWcoW1xuICB7IHBhdGg6ICcvbG9naW4nLCBjb21wb25lbnQ6IExvZ2luLCBhczogJ2xvZ2luJyB9LFxuICB7IHBhdGg6ICcvbG9nb3V0JywgY29tcG9uZW50OiBMb2dvdXQsIGFzOiAnbG9nb3V0JyB9LFxuICB7IHBhdGg6ICcvbmV3c2ZlZWQnLCBjb21wb25lbnQ6IE5ld3NmZWVkLCBhczogJ25ld3NmZWVkJyB9LFxuICB7IHBhdGg6ICcvY2FwdHVyZScsIGNvbXBvbmVudDogQ2FwdHVyZSwgYXM6ICdjYXB0dXJlJyB9LFxuXG4gIHsgcGF0aDogJy9kaXNjb3ZlcnkvOmZpbHRlcicsIGNvbXBvbmVudDogRGlzY292ZXJ5LCBhczogJ2Rpc2NvdmVyeSd9LFxuICB7IHBhdGg6ICcvZGlzY292ZXJ5LzpmaWx0ZXIvOnR5cGUnLCBjb21wb25lbnQ6IERpc2NvdmVyeSwgYXM6ICdkaXNjb3ZlcnknfSxcblxuICB7IHBhdGg6ICcvbWVzc2VuZ2VyJywgY29tcG9uZW50OiAgR2F0aGVyaW5ncywgYXM6ICdtZXNzZW5nZXInfSxcblxuICB7IHBhdGg6ICcvbm90aWZpY2F0aW9ucycsIGNvbXBvbmVudDogTm90aWZpY2F0aW9ucywgYXM6ICdub3RpZmljYXRpb25zJ30sXG4gIHsgcGF0aDogJy9ncm91cHMnLCBjb21wb25lbnQ6IENvbWluZ1Nvb24sIGFzOiAnZ3JvdXBzJ30sXG5cbiAgeyBwYXRoOiAnLzp1c2VybmFtZScsIGNvbXBvbmVudDogQ2hhbm5lbCwgYXM6ICdjaGFubmVsJyB9XG5dKVxuQFZpZXcoe1xuICB0ZW1wbGF0ZVVybDogJy4vdGVtcGxhdGVzL2luZGV4Lmh0bWwnLFxuICBkaXJlY3RpdmVzOiBbVG9wYmFyLCBOYXZpZ2F0aW9uLCBSb3V0ZXJPdXRsZXQsIFJvdXRlckxpbmtdXG59KVxuXG5jbGFzcyBNaW5kcyB7XG4gIG5hbWU6IHN0cmluZztcblxuICBjb25zdHJ1Y3RvcigpIHtcbiAgICB0aGlzLm5hbWUgPSAnTWluZHMnO1xuICB9XG59XG5cbmJvb3RzdHJhcChNaW5kcywgW3JvdXRlckluamVjdGFibGVzLCBIVFRQX0JJTkRJTkdTXSk7XG4iXSwic291cmNlUm9vdCI6Ii9zb3VyY2UvIn0=