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
var AutoGrow = (function () {
    function AutoGrow(viewContainer) {
        var _this = this;
        this.viewContainer = viewContainer;
        var self = this;
        this._listener = function () {
            self.grow();
        };
        this._element = viewContainer.element.nativeElement;
        this._element.addEventListener('keyup', this._listener);
        setTimeout(function () {
            _this.grow();
        });
    }
    AutoGrow.prototype.grow = function () {
        this._element.style.overflow = 'hidden';
        this._element.style.height = 'auto';
        this._element.style.height = this._element.scrollHeight + "px";
    };
    AutoGrow = __decorate([
        angular2_1.Directive({
            selector: '[auto-grow]',
            properties: ['autoGrow', 'for']
        }), 
        __metadata('design:paramtypes', [ViewContainerRef])
    ], AutoGrow);
    return AutoGrow;
})();
exports.AutoGrow = AutoGrow;
//# sourceMappingURL=autogrow.js.map