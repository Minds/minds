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
var http_1 = require('http/http');
var topbar_1 = require('./src/components/topbar');
var navigation_1 = require('./src/components/navigation');
var login_1 = require('./src/controllers/login');
var logout_1 = require('./src/controllers/logout');
var newsfeed_1 = require('./src/controllers/newsfeed/newsfeed');
var capture_1 = require('./src/controllers/capture/capture');
var discovery_1 = require('./src/controllers/discovery/discovery');
var channel_1 = require('./src/controllers/channels/channel');
var notifications_1 = require('./src/controllers/notifications/notifications');
var gatherings_1 = require('./src/plugins/gatherings/gatherings');
var groups_1 = require('./src/plugins/groups/groups');
var payments_1 = require('./src/plugins/payments/payments');
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
            { path: '/groups/:filter', component: groups_1.Groups, as: 'groups' },
            { path: '/groups/create', component: groups_1.GroupsCreator, as: 'groups-create' },
            { path: '/groups/profile/:guid', component: groups_1.GroupsProfile, as: 'groups-profile' },
            { path: '/wallet', component: payments_1.Wallet, as: 'wallet' },
            { path: '/:username', component: channel_1.Channel, as: 'channel' },
            { path: '/:username/:filter', component: channel_1.Channel, as: 'channel-filter' },
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

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbImFwcC50cyJdLCJuYW1lcyI6WyJNaW5kcyIsIk1pbmRzLmNvbnN0cnVjdG9yIl0sIm1hcHBpbmdzIjoiOzs7Ozs7Ozs7OztBQUFBLHlCQUF5QyxtQkFBbUIsQ0FBQyxDQUFBO0FBQzdELHVCQUE0RSxpQkFBaUIsQ0FBQyxDQUFBO0FBQzlGLHFCQUE0QixXQUFXLENBQUMsQ0FBQTtBQUV4Qyx1QkFBcUIseUJBQXlCLENBQUMsQ0FBQTtBQUMvQywyQkFBeUIsNkJBQTZCLENBQUMsQ0FBQTtBQUV2RCxzQkFBb0IseUJBQXlCLENBQUMsQ0FBQTtBQUM5Qyx1QkFBcUIsMEJBQTBCLENBQUMsQ0FBQTtBQUVoRCx5QkFBdUIscUNBQXFDLENBQUMsQ0FBQTtBQUM3RCx3QkFBc0IsbUNBQW1DLENBQUMsQ0FBQTtBQUMxRCwwQkFBd0IsdUNBQXVDLENBQUMsQ0FBQTtBQUNoRSx3QkFBZ0Usb0NBQW9DLENBQUMsQ0FBQTtBQUNyRyw4QkFBNEIsK0NBQStDLENBQUMsQ0FBQTtBQUs1RSwyQkFBeUIscUNBQXFDLENBQUMsQ0FBQTtBQUMvRCx1QkFBbUQsNkJBQTZCLENBQUMsQ0FBQTtBQUNqRix5QkFBcUIsaUNBQWlDLENBQUMsQ0FBQTtBQUV2RDtJQW9DRUE7UUFDRUMsSUFBSUEsQ0FBQ0EsSUFBSUEsR0FBR0EsT0FBT0EsQ0FBQ0E7SUFDdEJBLENBQUNBO0lBdENIRDtRQUFDQSxvQkFBU0EsQ0FBQ0E7WUFDVEEsUUFBUUEsRUFBRUEsV0FBV0E7U0FDdEJBLENBQUNBO1FBQ0RBLG9CQUFXQSxDQUFDQTtZQUNYQSxJQUFJQSxjQUFLQSxDQUFDQSxFQUFFQSxJQUFJQSxFQUFFQSxRQUFRQSxFQUFFQSxTQUFTQSxFQUFFQSxhQUFLQSxFQUFFQSxFQUFFQSxFQUFFQSxPQUFPQSxFQUFFQSxDQUFDQTtZQUM1REEsRUFBRUEsSUFBSUEsRUFBRUEsU0FBU0EsRUFBRUEsU0FBU0EsRUFBRUEsZUFBTUEsRUFBRUEsRUFBRUEsRUFBRUEsUUFBUUEsRUFBRUE7WUFDcERBLEVBQUVBLElBQUlBLEVBQUVBLFdBQVdBLEVBQUVBLFNBQVNBLEVBQUVBLG1CQUFRQSxFQUFFQSxFQUFFQSxFQUFFQSxVQUFVQSxFQUFFQTtZQUMxREEsRUFBRUEsSUFBSUEsRUFBRUEsVUFBVUEsRUFBRUEsU0FBU0EsRUFBRUEsaUJBQU9BLEVBQUVBLEVBQUVBLEVBQUVBLFNBQVNBLEVBQUVBO1lBRXZEQSxFQUFFQSxJQUFJQSxFQUFFQSxvQkFBb0JBLEVBQUVBLFNBQVNBLEVBQUVBLHFCQUFTQSxFQUFFQSxFQUFFQSxFQUFFQSxXQUFXQSxFQUFDQTtZQUNwRUEsRUFBRUEsSUFBSUEsRUFBRUEsMEJBQTBCQSxFQUFFQSxTQUFTQSxFQUFFQSxxQkFBU0EsRUFBRUEsRUFBRUEsRUFBRUEsV0FBV0EsRUFBQ0E7WUFFMUVBLEVBQUVBLElBQUlBLEVBQUVBLFlBQVlBLEVBQUVBLFNBQVNBLEVBQUdBLHVCQUFVQSxFQUFFQSxFQUFFQSxFQUFFQSxXQUFXQSxFQUFDQTtZQUU5REEsRUFBRUEsSUFBSUEsRUFBRUEsZ0JBQWdCQSxFQUFFQSxTQUFTQSxFQUFFQSw2QkFBYUEsRUFBRUEsRUFBRUEsRUFBRUEsZUFBZUEsRUFBQ0E7WUFFeEVBLEVBQUVBLElBQUlBLEVBQUVBLGlCQUFpQkEsRUFBRUEsU0FBU0EsRUFBRUEsZUFBTUEsRUFBRUEsRUFBRUEsRUFBRUEsUUFBUUEsRUFBQ0E7WUFDM0RBLEVBQUVBLElBQUlBLEVBQUVBLGdCQUFnQkEsRUFBRUEsU0FBU0EsRUFBRUEsc0JBQWFBLEVBQUVBLEVBQUVBLEVBQUVBLGVBQWVBLEVBQUNBO1lBQ3hFQSxFQUFFQSxJQUFJQSxFQUFFQSx1QkFBdUJBLEVBQUVBLFNBQVNBLEVBQUVBLHNCQUFhQSxFQUFFQSxFQUFFQSxFQUFFQSxnQkFBZ0JBLEVBQUNBO1lBRWhGQSxFQUFFQSxJQUFJQSxFQUFFQSxTQUFTQSxFQUFFQSxTQUFTQSxFQUFFQSxpQkFBTUEsRUFBRUEsRUFBRUEsRUFBRUEsUUFBUUEsRUFBQ0E7WUFHbkRBLEVBQUVBLElBQUlBLEVBQUVBLFlBQVlBLEVBQUVBLFNBQVNBLEVBQUVBLGlCQUFPQSxFQUFFQSxFQUFFQSxFQUFFQSxTQUFTQSxFQUFFQTtZQUN6REEsRUFBRUEsSUFBSUEsRUFBRUEsb0JBQW9CQSxFQUFFQSxTQUFTQSxFQUFFQSxpQkFBT0EsRUFBRUEsRUFBRUEsRUFBRUEsZ0JBQWdCQSxFQUFFQTtZQUV4RUEsRUFBRUEsSUFBSUEsRUFBRUEsR0FBR0EsRUFBRUEsVUFBVUEsRUFBRUEsV0FBV0EsRUFBRUE7U0FDdkNBLENBQUNBO1FBQ0RBLGVBQUlBLENBQUNBO1lBQ0pBLFdBQVdBLEVBQUVBLHdCQUF3QkE7WUFDckNBLFVBQVVBLEVBQUVBLENBQUNBLGVBQU1BLEVBQUVBLHVCQUFVQSxFQUFFQSxxQkFBWUEsRUFBRUEsbUJBQVVBLENBQUNBO1NBQzNEQSxDQUFDQTs7Y0FRREE7SUFBREEsWUFBQ0E7QUFBREEsQ0F2Q0EsQUF1Q0NBLElBQUE7QUFFRCxvQkFBUyxDQUFDLEtBQUssRUFBRSxDQUFDLHdCQUFlLEVBQUUsb0JBQWEsQ0FBQyxDQUFDLENBQUMiLCJmaWxlIjoiYXBwLmpzIiwic291cmNlc0NvbnRlbnQiOlsiaW1wb3J0IHtDb21wb25lbnQsIFZpZXcsIGJvb3RzdHJhcH0gZnJvbSAnYW5ndWxhcjIvYW5ndWxhcjInO1xuaW1wb3J0IHtSb3V0ZUNvbmZpZywgUm91dGVyT3V0bGV0LCBSb3V0ZXJMaW5rLCBSb3V0ZSwgUk9VVEVSX0JJTkRJTkdTfSBmcm9tICdhbmd1bGFyMi9yb3V0ZXInO1xuaW1wb3J0IHtIVFRQX0JJTkRJTkdTfSBmcm9tICdodHRwL2h0dHAnO1xuXG5pbXBvcnQge1RvcGJhcn0gZnJvbSAnLi9zcmMvY29tcG9uZW50cy90b3BiYXInO1xuaW1wb3J0IHtOYXZpZ2F0aW9ufSBmcm9tICcuL3NyYy9jb21wb25lbnRzL25hdmlnYXRpb24nO1xuXG5pbXBvcnQge0xvZ2lufSBmcm9tICcuL3NyYy9jb250cm9sbGVycy9sb2dpbic7XG5pbXBvcnQge0xvZ291dH0gZnJvbSAnLi9zcmMvY29udHJvbGxlcnMvbG9nb3V0JztcbmltcG9ydCB7Q29taW5nU29vbn0gZnJvbSAnLi9zcmMvY29udHJvbGxlcnMvY29taW5nc29vbic7XG5pbXBvcnQge05ld3NmZWVkfSBmcm9tICcuL3NyYy9jb250cm9sbGVycy9uZXdzZmVlZC9uZXdzZmVlZCc7XG5pbXBvcnQge0NhcHR1cmV9IGZyb20gJy4vc3JjL2NvbnRyb2xsZXJzL2NhcHR1cmUvY2FwdHVyZSc7XG5pbXBvcnQge0Rpc2NvdmVyeX0gZnJvbSAnLi9zcmMvY29udHJvbGxlcnMvZGlzY292ZXJ5L2Rpc2NvdmVyeSc7XG5pbXBvcnQge0NoYW5uZWwsIENoYW5uZWxTdWJzY3JpYmVycywgQ2hhbm5lbFN1YnNjcmlwdGlvbnN9IGZyb20gJy4vc3JjL2NvbnRyb2xsZXJzL2NoYW5uZWxzL2NoYW5uZWwnO1xuaW1wb3J0IHtOb3RpZmljYXRpb25zfSBmcm9tICcuL3NyYy9jb250cm9sbGVycy9ub3RpZmljYXRpb25zL25vdGlmaWNhdGlvbnMnO1xuXG4vKipcbiAqIFRPRE86IExvYWQgdGhlc2UgYXV0b21hZ2ljYWxseSBmcm9tIGd1bHBcbiAqL1xuaW1wb3J0IHtHYXRoZXJpbmdzfSBmcm9tICcuL3NyYy9wbHVnaW5zL2dhdGhlcmluZ3MvZ2F0aGVyaW5ncyc7XG5pbXBvcnQge0dyb3VwcywgR3JvdXBzUHJvZmlsZSwgR3JvdXBzQ3JlYXRvcn0gZnJvbSAnLi9zcmMvcGx1Z2lucy9ncm91cHMvZ3JvdXBzJztcbmltcG9ydCB7V2FsbGV0fSBmcm9tICcuL3NyYy9wbHVnaW5zL3BheW1lbnRzL3BheW1lbnRzJztcblxuQENvbXBvbmVudCh7XG4gIHNlbGVjdG9yOiAnbWluZHMtYXBwJyxcbn0pXG5AUm91dGVDb25maWcoW1xuICBuZXcgUm91dGUoeyBwYXRoOiAnL2xvZ2luJywgY29tcG9uZW50OiBMb2dpbiwgYXM6ICdsb2dpbicgfSksXG4gIHsgcGF0aDogJy9sb2dvdXQnLCBjb21wb25lbnQ6IExvZ291dCwgYXM6ICdsb2dvdXQnIH0sXG4gIHsgcGF0aDogJy9uZXdzZmVlZCcsIGNvbXBvbmVudDogTmV3c2ZlZWQsIGFzOiAnbmV3c2ZlZWQnIH0sXG4gIHsgcGF0aDogJy9jYXB0dXJlJywgY29tcG9uZW50OiBDYXB0dXJlLCBhczogJ2NhcHR1cmUnIH0sXG5cbiAgeyBwYXRoOiAnL2Rpc2NvdmVyeS86ZmlsdGVyJywgY29tcG9uZW50OiBEaXNjb3ZlcnksIGFzOiAnZGlzY292ZXJ5J30sXG4gIHsgcGF0aDogJy9kaXNjb3ZlcnkvOmZpbHRlci86dHlwZScsIGNvbXBvbmVudDogRGlzY292ZXJ5LCBhczogJ2Rpc2NvdmVyeSd9LFxuXG4gIHsgcGF0aDogJy9tZXNzZW5nZXInLCBjb21wb25lbnQ6ICBHYXRoZXJpbmdzLCBhczogJ21lc3Nlbmdlcid9LFxuXG4gIHsgcGF0aDogJy9ub3RpZmljYXRpb25zJywgY29tcG9uZW50OiBOb3RpZmljYXRpb25zLCBhczogJ25vdGlmaWNhdGlvbnMnfSxcblxuICB7IHBhdGg6ICcvZ3JvdXBzLzpmaWx0ZXInLCBjb21wb25lbnQ6IEdyb3VwcywgYXM6ICdncm91cHMnfSxcbiAgeyBwYXRoOiAnL2dyb3Vwcy9jcmVhdGUnLCBjb21wb25lbnQ6IEdyb3Vwc0NyZWF0b3IsIGFzOiAnZ3JvdXBzLWNyZWF0ZSd9LFxuICB7IHBhdGg6ICcvZ3JvdXBzL3Byb2ZpbGUvOmd1aWQnLCBjb21wb25lbnQ6IEdyb3Vwc1Byb2ZpbGUsIGFzOiAnZ3JvdXBzLXByb2ZpbGUnfSxcblxuICB7IHBhdGg6ICcvd2FsbGV0JywgY29tcG9uZW50OiBXYWxsZXQsIGFzOiAnd2FsbGV0J30sXG5cblxuICB7IHBhdGg6ICcvOnVzZXJuYW1lJywgY29tcG9uZW50OiBDaGFubmVsLCBhczogJ2NoYW5uZWwnIH0sXG4gIHsgcGF0aDogJy86dXNlcm5hbWUvOmZpbHRlcicsIGNvbXBvbmVudDogQ2hhbm5lbCwgYXM6ICdjaGFubmVsLWZpbHRlcicgfSxcblxuICB7IHBhdGg6ICcvJywgcmVkaXJlY3RUbzogJy9uZXdzZmVlZCcgfVxuXSlcbkBWaWV3KHtcbiAgdGVtcGxhdGVVcmw6ICcuL3RlbXBsYXRlcy9pbmRleC5odG1sJyxcbiAgZGlyZWN0aXZlczogW1RvcGJhciwgTmF2aWdhdGlvbiwgUm91dGVyT3V0bGV0LCBSb3V0ZXJMaW5rXVxufSlcblxuY2xhc3MgTWluZHMge1xuICBuYW1lOiBzdHJpbmc7XG5cbiAgY29uc3RydWN0b3IoKSB7XG4gICAgdGhpcy5uYW1lID0gJ01pbmRzJztcbiAgfVxufVxuXG5ib290c3RyYXAoTWluZHMsIFtST1VURVJfQklORElOR1MsIEhUVFBfQklORElOR1NdKTtcbiJdLCJzb3VyY2VSb290IjoiL3NvdXJjZS8ifQ==