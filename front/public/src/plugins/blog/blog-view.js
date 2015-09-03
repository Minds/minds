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
var router_1 = require("angular2/router");
var messenger_conversation_1 = require("./messenger-conversation");
var messenger_setup_1 = require("./messenger-setup");
var api_1 = require('src/services/api');
var session_1 = require('src/services/session');
var material_1 = require('src/directives/material');
var Gatherings = (function () {
    function Gatherings(client) {
        this.client = client;
        this.session = session_1.SessionFactory.build();
        this.setup = false;
    }
    Gatherings = __decorate([
        angular2_1.Component({
            selector: 'minds-blog-view',
            viewBindings: [api_1.Client]
        }),
        angular2_1.View({
            templateUrl: 'templates/plugins/blog/view.html',
            directives: [angular2_1.NgFor, angular2_1.NgIf, angular2_1.NgClass, material_1.Material, router_1.RouterLink, messenger_conversation_1.MessengerConversation, messenger_setup_1.MessengerSetup]
        }), 
        __metadata('design:paramtypes', [api_1.Client])
    ], Gatherings);
    return Gatherings;
})();
exports.Gatherings = Gatherings;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9wbHVnaW5zL2Jsb2cvYmxvZy12aWV3LnRzIl0sIm5hbWVzIjpbIkdhdGhlcmluZ3MiLCJHYXRoZXJpbmdzLmNvbnN0cnVjdG9yIl0sIm1hcHBpbmdzIjoiOzs7Ozs7Ozs7OztBQUFBLHlCQUFrRixtQkFBbUIsQ0FBQyxDQUFBO0FBQ3RHLHVCQUEyQixpQkFBaUIsQ0FBQyxDQUFBO0FBQzdDLHVDQUFzQywwQkFBMEIsQ0FBQyxDQUFBO0FBQ2pFLGdDQUErQixtQkFBbUIsQ0FBQyxDQUFBO0FBRW5ELG9CQUF1QixrQkFBa0IsQ0FBQyxDQUFBO0FBQzFDLHdCQUErQixzQkFBc0IsQ0FBQyxDQUFBO0FBQ3RELHlCQUF5Qix5QkFBeUIsQ0FBQyxDQUFBO0FBRW5EO0lBY0NBLG9CQUFtQkEsTUFBY0E7UUFBZEMsV0FBTUEsR0FBTkEsTUFBTUEsQ0FBUUE7UUFIaENBLFlBQU9BLEdBQUdBLHdCQUFjQSxDQUFDQSxLQUFLQSxFQUFFQSxDQUFDQTtRQUNqQ0EsVUFBS0EsR0FBYUEsS0FBS0EsQ0FBQ0E7SUFHekJBLENBQUNBO0lBZkZEO1FBQUNBLG9CQUFTQSxDQUFDQTtZQUNUQSxRQUFRQSxFQUFFQSxpQkFBaUJBO1lBQzNCQSxZQUFZQSxFQUFFQSxDQUFFQSxZQUFNQSxDQUFFQTtTQUN6QkEsQ0FBQ0E7UUFDREEsZUFBSUEsQ0FBQ0E7WUFDSkEsV0FBV0EsRUFBRUEsa0NBQWtDQTtZQUMvQ0EsVUFBVUEsRUFBRUEsQ0FBRUEsZ0JBQUtBLEVBQUVBLGVBQUlBLEVBQUVBLGtCQUFPQSxFQUFFQSxtQkFBUUEsRUFBRUEsbUJBQVVBLEVBQUVBLDhDQUFxQkEsRUFBRUEsZ0NBQWNBLENBQUNBO1NBQ2pHQSxDQUFDQTs7bUJBVURBO0lBQURBLGlCQUFDQTtBQUFEQSxDQWpCQSxBQWlCQ0EsSUFBQTtBQVJZLGtCQUFVLGFBUXRCLENBQUEiLCJmaWxlIjoic3JjL3BsdWdpbnMvYmxvZy9ibG9nLXZpZXcuanMiLCJzb3VyY2VzQ29udGVudCI6WyJpbXBvcnQgeyBDb21wb25lbnQsIFZpZXcsIE5nRm9yLCBOZ0lmLCBOZ0NsYXNzLCBPYnNlcnZhYmxlLCBGT1JNX0RJUkVDVElWRVN9IGZyb20gJ2FuZ3VsYXIyL2FuZ3VsYXIyJztcbmltcG9ydCB7IFJvdXRlckxpbmsgfSBmcm9tIFwiYW5ndWxhcjIvcm91dGVyXCI7XG5pbXBvcnQgeyBNZXNzZW5nZXJDb252ZXJzYXRpb24gfSBmcm9tIFwiLi9tZXNzZW5nZXItY29udmVyc2F0aW9uXCI7XG5pbXBvcnQgeyBNZXNzZW5nZXJTZXR1cCB9IGZyb20gXCIuL21lc3Nlbmdlci1zZXR1cFwiO1xuXG5pbXBvcnQgeyBDbGllbnQgfSBmcm9tICdzcmMvc2VydmljZXMvYXBpJztcbmltcG9ydCB7IFNlc3Npb25GYWN0b3J5IH0gZnJvbSAnc3JjL3NlcnZpY2VzL3Nlc3Npb24nO1xuaW1wb3J0IHsgTWF0ZXJpYWwgfSBmcm9tICdzcmMvZGlyZWN0aXZlcy9tYXRlcmlhbCc7XG5cbkBDb21wb25lbnQoe1xuICBzZWxlY3RvcjogJ21pbmRzLWJsb2ctdmlldycsXG4gIHZpZXdCaW5kaW5nczogWyBDbGllbnQgXVxufSlcbkBWaWV3KHtcbiAgdGVtcGxhdGVVcmw6ICd0ZW1wbGF0ZXMvcGx1Z2lucy9ibG9nL3ZpZXcuaHRtbCcsXG4gIGRpcmVjdGl2ZXM6IFsgTmdGb3IsIE5nSWYsIE5nQ2xhc3MsIE1hdGVyaWFsLCBSb3V0ZXJMaW5rLCBNZXNzZW5nZXJDb252ZXJzYXRpb24sIE1lc3NlbmdlclNldHVwXVxufSlcblxuZXhwb3J0IGNsYXNzIEdhdGhlcmluZ3Mge1xuICBhY3Rpdml0eSA6IGFueTtcbiAgc2Vzc2lvbiA9IFNlc3Npb25GYWN0b3J5LmJ1aWxkKCk7XG4gIHNldHVwIDogYm9vbGVhbiA9IGZhbHNlO1xuXG5cdGNvbnN0cnVjdG9yKHB1YmxpYyBjbGllbnQ6IENsaWVudCl7XG5cdH1cblxufVxuIl0sInNvdXJjZVJvb3QiOiIvc291cmNlLyJ9