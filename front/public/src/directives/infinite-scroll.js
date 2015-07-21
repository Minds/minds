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
        this._content = document.getElementsByClassName('mdl-layout__content')[0];
        var self = this;
        this._listener = function () {
            var height = self._content.scrollHeight, maxHeight = height - self._content.clientHeight, top = self._content.scrollTop, bottom = maxHeight - top, distance = (bottom / maxHeight) * 100;
            if (distance <= self._distance) {
                self.loadHandler.next(true);
            }
        };
        this._content.addEventListener('scroll', this._listener);
    };
    InfiniteScroll.prototype.onDestroy = function () {
        this._content.removeEventListener('scroll', this._listener);
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

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9kaXJlY3RpdmVzL2luZmluaXRlLXNjcm9sbC50cyJdLCJuYW1lcyI6WyJJbmZpbml0ZVNjcm9sbCIsIkluZmluaXRlU2Nyb2xsLmNvbnN0cnVjdG9yIiwiSW5maW5pdGVTY3JvbGwuZGlzdGFuY2UiLCJJbmZpbml0ZVNjcm9sbC5zY3JvbGwiLCJJbmZpbml0ZVNjcm9sbC5vbkRlc3Ryb3kiXSwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7O0FBQUEseUJBQTJGLG1CQUFtQixDQUFDLENBQUE7QUFHL0c7SUFrQkVBLHdCQUFZQSxhQUErQkE7UUFOM0NDLGdCQUFXQSxHQUFpQkEsSUFBSUEsdUJBQVlBLEVBQUVBLENBQUNBO1FBRS9DQSxnQkFBV0EsR0FBYUEsS0FBS0EsQ0FBQ0E7UUFLNUJBLElBQUlBLENBQUNBLE1BQU1BLEVBQUVBLENBQUNBO0lBQ2hCQSxDQUFDQTtJQUVERCxzQkFBSUEsb0NBQVFBO2FBQVpBLFVBQWFBLEtBQVdBO1lBQ3RCRSxJQUFJQSxDQUFDQSxTQUFTQSxHQUFHQSxRQUFRQSxDQUFDQSxLQUFLQSxDQUFDQSxDQUFDQTtRQUNuQ0EsQ0FBQ0E7OztPQUFBRjtJQUVEQSwrQkFBTUEsR0FBTkE7UUFDRUcsSUFBSUEsQ0FBQ0EsUUFBUUEsR0FBR0EsUUFBUUEsQ0FBQ0Esc0JBQXNCQSxDQUFDQSxxQkFBcUJBLENBQUNBLENBQUNBLENBQUNBLENBQUNBLENBQUNBO1FBQzFFQSxJQUFJQSxJQUFJQSxHQUFHQSxJQUFJQSxDQUFDQTtRQUNoQkEsSUFBSUEsQ0FBQ0EsU0FBU0EsR0FBR0E7WUFDZkEsSUFBSUEsTUFBTUEsR0FBR0EsSUFBSUEsQ0FBQ0EsUUFBUUEsQ0FBQ0EsWUFBWUEsRUFDbkNBLFNBQVNBLEdBQUdBLE1BQU1BLEdBQUdBLElBQUlBLENBQUNBLFFBQVFBLENBQUNBLFlBQVlBLEVBQy9DQSxHQUFHQSxHQUFHQSxJQUFJQSxDQUFDQSxRQUFRQSxDQUFDQSxTQUFTQSxFQUM3QkEsTUFBTUEsR0FBR0EsU0FBU0EsR0FBR0EsR0FBR0EsRUFDeEJBLFFBQVFBLEdBQUdBLENBQUNBLE1BQU1BLEdBQUdBLFNBQVNBLENBQUNBLEdBQUdBLEdBQUdBLENBQUNBO1lBSTFDQSxFQUFFQSxDQUFBQSxDQUFDQSxRQUFRQSxJQUFJQSxJQUFJQSxDQUFDQSxTQUFTQSxDQUFDQSxDQUFBQSxDQUFDQTtnQkFDN0JBLElBQUlBLENBQUNBLFdBQVdBLENBQUNBLElBQUlBLENBQUNBLElBQUlBLENBQUNBLENBQUNBO1lBQzlCQSxDQUFDQTtRQUNIQSxDQUFDQSxDQUFDQTtRQUNGQSxJQUFJQSxDQUFDQSxRQUFRQSxDQUFDQSxnQkFBZ0JBLENBQUNBLFFBQVFBLEVBQUVBLElBQUlBLENBQUNBLFNBQVNBLENBQUNBLENBQUNBO0lBQzNEQSxDQUFDQTtJQUVESCxrQ0FBU0EsR0FBVEE7UUFDRUksSUFBSUEsQ0FBQ0EsUUFBUUEsQ0FBQ0EsbUJBQW1CQSxDQUFDQSxRQUFRQSxFQUFFQSxJQUFJQSxDQUFDQSxTQUFTQSxDQUFDQSxDQUFBQTtJQUM3REEsQ0FBQ0E7SUEvQ0hKO1FBQUNBLG9CQUFTQSxDQUFDQTtZQUNUQSxRQUFRQSxFQUFFQSxpQkFBaUJBO1lBQzNCQSxVQUFVQSxFQUFFQSxDQUFDQSxVQUFVQSxFQUFFQSxJQUFJQSxDQUFDQTtZQUM5QkEsTUFBTUEsRUFBRUEsQ0FBQ0EsbUJBQW1CQSxDQUFDQTtTQUM5QkEsQ0FBQ0E7UUFDREEsZUFBSUEsQ0FBQ0E7WUFDSkEsUUFBUUEsRUFBRUEsNkNBQTZDQTtZQUN2REEsVUFBVUEsRUFBRUEsRUFBRUE7U0FDZkEsQ0FBQ0E7O3VCQXlDREE7SUFBREEscUJBQUNBO0FBQURBLENBakRBLElBaURDO0FBdkNZLHNCQUFjLGlCQXVDMUIsQ0FBQSIsImZpbGUiOiJzcmMvZGlyZWN0aXZlcy9pbmZpbml0ZS1zY3JvbGwuanMiLCJzb3VyY2VzQ29udGVudCI6WyJpbXBvcnQgeyBEaXJlY3RpdmUsIFZpZXcsIEV2ZW50RW1pdHRlciwgVmlld0NvbnRhaW5lclJlZiwgUHJvdG9WaWV3UmVmLCBEb21SZW5kZXJlciB9IGZyb20gJ2FuZ3VsYXIyL2FuZ3VsYXIyJztcbmltcG9ydCB7IE1hdGVyaWFsIGFzIE1hdGVyaWFsU2VydmljZSB9IGZyb20gXCJzcmMvc2VydmljZXMvdWlcIjtcblxuQERpcmVjdGl2ZSh7XG4gIHNlbGVjdG9yOiAnaW5maW5pdGUtc2Nyb2xsJyxcbiAgcHJvcGVydGllczogWydkaXN0YW5jZScsICdvbiddLFxuICBldmVudHM6IFsnbG9hZEhhbmRsZXI6IGxvYWQnXVxufSlcbkBWaWV3KHtcbiAgdGVtcGxhdGU6ICc8bG9hZGluZy1pY29uPmxvYWRpbmcgbW9yZS4uPC9sb2FkaW5nLWljb24+JyxcbiAgZGlyZWN0aXZlczogW11cbn0pXG5cbmV4cG9ydCBjbGFzcyBJbmZpbml0ZVNjcm9sbHtcbiAgdmlld0NvbnRhaW5lcjogVmlld0NvbnRhaW5lclJlZjtcbiAgbG9hZEhhbmRsZXI6IEV2ZW50RW1pdHRlciA9IG5ldyBFdmVudEVtaXR0ZXIoKTtcbiAgX2Rpc3RhbmNlIDogYW55O1xuICBfaW5wcm9ncmVzcyA6IGJvb2xlYW4gPSBmYWxzZTtcbiAgX2NvbnRlbnQgOiBhbnk7XG4gIF9saXN0ZW5lciA6IEZ1bmN0aW9uO1xuXG4gIGNvbnN0cnVjdG9yKHZpZXdDb250YWluZXI6IFZpZXdDb250YWluZXJSZWYpIHtcbiAgICB0aGlzLnNjcm9sbCgpO1xuICB9XG5cbiAgc2V0IGRpc3RhbmNlKHZhbHVlIDogYW55KXtcbiAgICB0aGlzLl9kaXN0YW5jZSA9IHBhcnNlSW50KHZhbHVlKTtcbiAgfVxuXG4gIHNjcm9sbCgpe1xuICAgIHRoaXMuX2NvbnRlbnQgPSBkb2N1bWVudC5nZXRFbGVtZW50c0J5Q2xhc3NOYW1lKCdtZGwtbGF5b3V0X19jb250ZW50JylbMF07XG4gICAgdmFyIHNlbGYgPSB0aGlzO1xuICAgIHRoaXMuX2xpc3RlbmVyID0gKCkgPT4ge1xuICAgICAgdmFyIGhlaWdodCA9IHNlbGYuX2NvbnRlbnQuc2Nyb2xsSGVpZ2h0LFxuICAgICAgICAgIG1heEhlaWdodCA9IGhlaWdodCAtIHNlbGYuX2NvbnRlbnQuY2xpZW50SGVpZ2h0LFxuICAgICAgICAgIHRvcCA9IHNlbGYuX2NvbnRlbnQuc2Nyb2xsVG9wLFxuICAgICAgICAgIGJvdHRvbSA9IG1heEhlaWdodCAtIHRvcCxcbiAgICAgICAgICBkaXN0YW5jZSA9IChib3R0b20gLyBtYXhIZWlnaHQpICogMTAwO1xuXG4gICAgICAvL2NvbnNvbGUubG9nKFwiSGVpZ2h0IFwiICsgaGVpZ2h0LCBcIk1heCBcIiArIG1heEhlaWdodCwgXCJUb3AgXCIgKyB0b3AsIFwiQm90dG9tIFwiICsgYm90dG9tLCBcIkRpc3RhbmNlIFwiICsgZGlzdGFuY2UpO1xuXG4gICAgICBpZihkaXN0YW5jZSA8PSBzZWxmLl9kaXN0YW5jZSl7XG4gICAgICAgIHNlbGYubG9hZEhhbmRsZXIubmV4dCh0cnVlKTtcbiAgICAgIH1cbiAgICB9O1xuICAgIHRoaXMuX2NvbnRlbnQuYWRkRXZlbnRMaXN0ZW5lcignc2Nyb2xsJywgdGhpcy5fbGlzdGVuZXIpO1xuICB9XG5cbiAgb25EZXN0cm95KCl7XG4gICAgdGhpcy5fY29udGVudC5yZW1vdmVFdmVudExpc3RlbmVyKCdzY3JvbGwnLCB0aGlzLl9saXN0ZW5lcilcbiAgfVxuXG59XG4iXSwic291cmNlUm9vdCI6Ii9zb3VyY2UvIn0=