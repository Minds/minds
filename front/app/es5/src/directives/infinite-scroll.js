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
        __metadata('design:paramtypes', [ViewContainerRef])
    ], InfiniteScroll);
    return InfiniteScroll;
})();
exports.InfiniteScroll = InfiniteScroll;
//# sourceMappingURL=infinite-scroll.js.map