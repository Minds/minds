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
        __metadata('design:paramtypes', [angular2_1.ViewContainerRef])
    ], AutoGrow);
    return AutoGrow;
})();
exports.AutoGrow = AutoGrow;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9kaXJlY3RpdmVzL2F1dG9ncm93LnRzIl0sIm5hbWVzIjpbIkF1dG9Hcm93IiwiQXV0b0dyb3cuY29uc3RydWN0b3IiLCJBdXRvR3Jvdy5ncm93Il0sIm1hcHBpbmdzIjoiOzs7Ozs7Ozs7OztBQUFBLHlCQUFzRixtQkFBbUIsQ0FBQyxDQUFBO0FBRzFHO0lBWUVBLGtCQUFZQSxhQUErQkE7UUFaN0NDLGlCQWdDQ0E7UUFuQkdBLElBQUlBLENBQUNBLGFBQWFBLEdBQUdBLGFBQWFBLENBQUNBO1FBQ25DQSxJQUFJQSxJQUFJQSxHQUFHQSxJQUFJQSxDQUFDQTtRQUNoQkEsSUFBSUEsQ0FBQ0EsU0FBU0EsR0FBR0E7WUFDZkEsSUFBSUEsQ0FBQ0EsSUFBSUEsRUFBRUEsQ0FBQ0E7UUFDZEEsQ0FBQ0EsQ0FBQ0E7UUFDRkEsSUFBSUEsQ0FBQ0EsUUFBUUEsR0FBSUEsYUFBYUEsQ0FBQ0EsT0FBT0EsQ0FBQ0EsYUFBYUEsQ0FBQ0E7UUFDckRBLElBQUlBLENBQUNBLFFBQVFBLENBQUNBLGdCQUFnQkEsQ0FBQ0EsT0FBT0EsRUFBRUEsSUFBSUEsQ0FBQ0EsU0FBU0EsQ0FBQ0EsQ0FBQ0E7UUFDeERBLFVBQVVBLENBQUNBO1lBQ1RBLEtBQUlBLENBQUNBLElBQUlBLEVBQUVBLENBQUNBO1FBQ2RBLENBQUNBLENBQUNBLENBQUNBO0lBQ0xBLENBQUNBO0lBRURELHVCQUFJQSxHQUFKQTtRQUNFRSxJQUFJQSxDQUFDQSxRQUFRQSxDQUFDQSxLQUFLQSxDQUFDQSxRQUFRQSxHQUFHQSxRQUFRQSxDQUFDQTtRQUN4Q0EsSUFBSUEsQ0FBQ0EsUUFBUUEsQ0FBQ0EsS0FBS0EsQ0FBQ0EsTUFBTUEsR0FBR0EsTUFBTUEsQ0FBQ0E7UUFDcENBLElBQUlBLENBQUNBLFFBQVFBLENBQUNBLEtBQUtBLENBQUNBLE1BQU1BLEdBQUdBLElBQUlBLENBQUNBLFFBQVFBLENBQUNBLFlBQVlBLEdBQUdBLElBQUlBLENBQUNBO0lBQ2pFQSxDQUFDQTtJQTdCSEY7UUFBQ0Esb0JBQVNBLENBQUNBO1lBQ1RBLFFBQVFBLEVBQUVBLGFBQWFBO1lBQ3ZCQSxVQUFVQSxFQUFFQSxDQUFDQSxVQUFVQSxFQUFFQSxLQUFLQSxDQUFDQTtTQUNoQ0EsQ0FBQ0E7O2lCQTZCREE7SUFBREEsZUFBQ0E7QUFBREEsQ0FoQ0EsQUFnQ0NBLElBQUE7QUExQlksZ0JBQVEsV0EwQnBCLENBQUEiLCJmaWxlIjoic3JjL2RpcmVjdGl2ZXMvYXV0b2dyb3cuanMiLCJzb3VyY2VzQ29udGVudCI6WyJpbXBvcnQgeyBEaXJlY3RpdmUsICBFdmVudEVtaXR0ZXIsIFZpZXdDb250YWluZXJSZWYsIFByb3RvVmlld1JlZiwgRG9tUmVuZGVyZXIgfSBmcm9tICdhbmd1bGFyMi9hbmd1bGFyMic7XG5pbXBvcnQgeyBNYXRlcmlhbCBhcyBNYXRlcmlhbFNlcnZpY2UgfSBmcm9tIFwic3JjL3NlcnZpY2VzL3VpXCI7XG5cbkBEaXJlY3RpdmUoe1xuICBzZWxlY3RvcjogJ1thdXRvLWdyb3ddJyxcbiAgcHJvcGVydGllczogWydhdXRvR3JvdycsICdmb3InXVxufSlcblxuXG5leHBvcnQgY2xhc3MgQXV0b0dyb3d7XG4gIHZpZXdDb250YWluZXI6IFZpZXdDb250YWluZXJSZWY7XG4gIF9saXN0ZW5lciA6IEZ1bmN0aW9uO1xuICBfZWxlbWVudCA6IGFueTtcbi8vICBncm93SGFuZGxlcjogRXZlbnRFbWl0dGVyID0gbmV3IEV2ZW50RW1pdHRlcigpO1xuXG4gIGNvbnN0cnVjdG9yKHZpZXdDb250YWluZXI6IFZpZXdDb250YWluZXJSZWYpIHtcbiAgICB0aGlzLnZpZXdDb250YWluZXIgPSB2aWV3Q29udGFpbmVyO1xuICAgIHZhciBzZWxmID0gdGhpcztcbiAgICB0aGlzLl9saXN0ZW5lciA9ICgpID0+IHtcbiAgICAgIHNlbGYuZ3JvdygpO1xuICAgIH07XG4gICAgdGhpcy5fZWxlbWVudCA9ICB2aWV3Q29udGFpbmVyLmVsZW1lbnQubmF0aXZlRWxlbWVudDtcbiAgICB0aGlzLl9lbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoJ2tleXVwJywgdGhpcy5fbGlzdGVuZXIpO1xuICAgIHNldFRpbWVvdXQoKCk9PntcbiAgICAgIHRoaXMuZ3JvdygpO1xuICAgIH0pO1xuICB9XG5cbiAgZ3Jvdygpe1xuICAgIHRoaXMuX2VsZW1lbnQuc3R5bGUub3ZlcmZsb3cgPSAnaGlkZGVuJztcbiAgICB0aGlzLl9lbGVtZW50LnN0eWxlLmhlaWdodCA9ICdhdXRvJztcbiAgICB0aGlzLl9lbGVtZW50LnN0eWxlLmhlaWdodCA9IHRoaXMuX2VsZW1lbnQuc2Nyb2xsSGVpZ2h0ICsgXCJweFwiO1xuICB9XG5cblxufVxuIl0sInNvdXJjZVJvb3QiOiIvc291cmNlLyJ9