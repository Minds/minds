if (typeof __decorate !== "function") __decorate = function (decorators, target, key, desc) {
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") return Reflect.decorate(decorators, target, key, desc);
    switch (arguments.length) {
        case 2: return decorators.reduceRight(function(o, d) { return (d && d(o)) || o; }, target);
        case 3: return decorators.reduceRight(function(o, d) { return (d && d(target, key)), void 0; }, void 0);
        case 4: return decorators.reduceRight(function(o, d) { return (d && d(target, key, o)) || o; }, desc);
    }
};
if (typeof __metadata !== "function") __metadata = function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};
var angular2_1 = require('angular2/angular2');
var InfiniteScroll = (function () {
    function InfiniteScroll(viewContainer) {
        this.loadHandler = new angular2_1.EventEmitter();
        this._inprogress = false;
        this.scroll();
    }
    Object.defineProperty(InfiniteScroll.prototype, "distance", {
        set: function (value) {
            this._distance = parseInt(value);
        },
        enumerable: true,
        configurable: true
    });
    InfiniteScroll.prototype.scroll = function () {
        var content = document.getElementsByClassName('mdl-layout__content')[0];
        var self = this;
        content.addEventListener('scroll', function () {
            var height = content.scrollHeight, top = content.scrollTop, bottom = height - top, distance = (bottom / height) * 100;
            if (distance <= self._distance) {
                self.loadHandler.next(true);
            }
        });
    };
    InfiniteScroll = __decorate([
        angular2_1.Directive({
            selector: 'infinite-scroll',
            properties: ['distance', 'on'],
            events: ['loadHandler: load']
        }),
        angular2_1.View({
            template: '<loading-icon>loading more..</loading-icon>',
            directives: []
        }), 
        __metadata('design:paramtypes', [angular2_1.ViewContainerRef])
    ], InfiniteScroll);
    return InfiniteScroll;
})();
exports.InfiniteScroll = InfiniteScroll;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9kaXJlY3RpdmVzL2luZmluaXRlLXNjcm9sbC50cyJdLCJuYW1lcyI6WyJJbmZpbml0ZVNjcm9sbCIsIkluZmluaXRlU2Nyb2xsLmNvbnN0cnVjdG9yIiwiSW5maW5pdGVTY3JvbGwuZGlzdGFuY2UiLCJJbmZpbml0ZVNjcm9sbC5zY3JvbGwiXSwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7O0FBQUEseUJBQTJGLG1CQUFtQixDQUFDLENBQUE7QUFHL0c7SUFnQkVBLHdCQUFZQSxhQUErQkE7UUFKM0NDLGdCQUFXQSxHQUFpQkEsSUFBSUEsdUJBQVlBLEVBQUVBLENBQUNBO1FBRS9DQSxnQkFBV0EsR0FBYUEsS0FBS0EsQ0FBQ0E7UUFHNUJBLElBQUlBLENBQUNBLE1BQU1BLEVBQUVBLENBQUNBO0lBQ2hCQSxDQUFDQTtJQUVERCxzQkFBSUEsb0NBQVFBO2FBQVpBLFVBQWFBLEtBQVdBO1lBQ3RCRSxJQUFJQSxDQUFDQSxTQUFTQSxHQUFHQSxRQUFRQSxDQUFDQSxLQUFLQSxDQUFDQSxDQUFDQTtRQUNuQ0EsQ0FBQ0E7OztPQUFBRjtJQUVEQSwrQkFBTUEsR0FBTkE7UUFDRUcsSUFBSUEsT0FBT0EsR0FBU0EsUUFBUUEsQ0FBQ0Esc0JBQXNCQSxDQUFDQSxxQkFBcUJBLENBQUNBLENBQUNBLENBQUNBLENBQUNBLENBQUNBO1FBQzlFQSxJQUFJQSxJQUFJQSxHQUFHQSxJQUFJQSxDQUFDQTtRQUNoQkEsT0FBT0EsQ0FBQ0EsZ0JBQWdCQSxDQUFDQSxRQUFRQSxFQUFFQTtZQUUvQkEsSUFBSUEsTUFBTUEsR0FBR0EsT0FBT0EsQ0FBQ0EsWUFBWUEsRUFDN0JBLEdBQUdBLEdBQUdBLE9BQU9BLENBQUNBLFNBQVNBLEVBQ3ZCQSxNQUFNQSxHQUFHQSxNQUFNQSxHQUFHQSxHQUFHQSxFQUNyQkEsUUFBUUEsR0FBR0EsQ0FBQ0EsTUFBTUEsR0FBR0EsTUFBTUEsQ0FBQ0EsR0FBR0EsR0FBR0EsQ0FBQ0E7WUFFdkNBLEVBQUVBLENBQUFBLENBQUNBLFFBQVFBLElBQUlBLElBQUlBLENBQUNBLFNBQVNBLENBQUNBLENBQUFBLENBQUNBO2dCQUM3QkEsSUFBSUEsQ0FBQ0EsV0FBV0EsQ0FBQ0EsSUFBSUEsQ0FBQ0EsSUFBSUEsQ0FBQ0EsQ0FBQ0E7WUFDOUJBLENBQUNBO1FBQ0hBLENBQUNBLENBQUNBLENBQUNBO0lBQ1BBLENBQUNBO0lBdENISDtRQUFDQSxvQkFBU0EsQ0FBQ0E7WUFDVEEsUUFBUUEsRUFBRUEsaUJBQWlCQTtZQUMzQkEsVUFBVUEsRUFBRUEsQ0FBQ0EsVUFBVUEsRUFBRUEsSUFBSUEsQ0FBQ0E7WUFDOUJBLE1BQU1BLEVBQUVBLENBQUNBLG1CQUFtQkEsQ0FBQ0E7U0FDOUJBLENBQUNBO1FBQ0RBLGVBQUlBLENBQUNBO1lBQ0pBLFFBQVFBLEVBQUVBLDZDQUE2Q0E7WUFDdkRBLFVBQVVBLEVBQUVBLEVBQUVBO1NBQ2ZBLENBQUNBOzt1QkFnQ0RBO0lBQURBLHFCQUFDQTtBQUFEQSxDQXhDQSxJQXdDQztBQTlCWSxzQkFBYyxpQkE4QjFCLENBQUEiLCJmaWxlIjoic3JjL2RpcmVjdGl2ZXMvaW5maW5pdGUtc2Nyb2xsLmpzIiwic291cmNlc0NvbnRlbnQiOlsiaW1wb3J0IHsgRGlyZWN0aXZlLCBWaWV3LCBFdmVudEVtaXR0ZXIsIFZpZXdDb250YWluZXJSZWYsIFByb3RvVmlld1JlZiwgRG9tUmVuZGVyZXIgfSBmcm9tICdhbmd1bGFyMi9hbmd1bGFyMic7XG5pbXBvcnQgeyBNYXRlcmlhbCBhcyBNYXRlcmlhbFNlcnZpY2UgfSBmcm9tIFwic3JjL3NlcnZpY2VzL3VpXCI7XG5cbkBEaXJlY3RpdmUoe1xuICBzZWxlY3RvcjogJ2luZmluaXRlLXNjcm9sbCcsXG4gIHByb3BlcnRpZXM6IFsnZGlzdGFuY2UnLCAnb24nXSxcbiAgZXZlbnRzOiBbJ2xvYWRIYW5kbGVyOiBsb2FkJ11cbn0pXG5AVmlldyh7XG4gIHRlbXBsYXRlOiAnPGxvYWRpbmctaWNvbj5sb2FkaW5nIG1vcmUuLjwvbG9hZGluZy1pY29uPicsXG4gIGRpcmVjdGl2ZXM6IFtdXG59KVxuXG5leHBvcnQgY2xhc3MgSW5maW5pdGVTY3JvbGx7XG4gIHZpZXdDb250YWluZXI6IFZpZXdDb250YWluZXJSZWY7XG4gIGxvYWRIYW5kbGVyOiBFdmVudEVtaXR0ZXIgPSBuZXcgRXZlbnRFbWl0dGVyKCk7XG4gIF9kaXN0YW5jZSA6IGFueTtcbiAgX2lucHJvZ3Jlc3MgOiBib29sZWFuID0gZmFsc2U7XG5cbiAgY29uc3RydWN0b3Iodmlld0NvbnRhaW5lcjogVmlld0NvbnRhaW5lclJlZikge1xuICAgIHRoaXMuc2Nyb2xsKCk7XG4gIH1cblxuICBzZXQgZGlzdGFuY2UodmFsdWUgOiBhbnkpe1xuICAgIHRoaXMuX2Rpc3RhbmNlID0gcGFyc2VJbnQodmFsdWUpO1xuICB9XG5cbiAgc2Nyb2xsKCl7XG4gICAgdmFyIGNvbnRlbnQgOiBhbnkgPSBkb2N1bWVudC5nZXRFbGVtZW50c0J5Q2xhc3NOYW1lKCdtZGwtbGF5b3V0X19jb250ZW50JylbMF07XG4gICAgdmFyIHNlbGYgPSB0aGlzO1xuICAgIGNvbnRlbnQuYWRkRXZlbnRMaXN0ZW5lcignc2Nyb2xsJywgKCkgPT4ge1xuXG4gICAgICAgIHZhciBoZWlnaHQgPSBjb250ZW50LnNjcm9sbEhlaWdodCxcbiAgICAgICAgICAgIHRvcCA9IGNvbnRlbnQuc2Nyb2xsVG9wLFxuICAgICAgICAgICAgYm90dG9tID0gaGVpZ2h0IC0gdG9wLFxuICAgICAgICAgICAgZGlzdGFuY2UgPSAoYm90dG9tIC8gaGVpZ2h0KSAqIDEwMDtcblxuICAgICAgICBpZihkaXN0YW5jZSA8PSBzZWxmLl9kaXN0YW5jZSl7XG4gICAgICAgICAgc2VsZi5sb2FkSGFuZGxlci5uZXh0KHRydWUpO1xuICAgICAgICB9XG4gICAgICB9KTtcbiAgfVxuXG59XG4iXSwic291cmNlUm9vdCI6Ii9zb3VyY2UvIn0=