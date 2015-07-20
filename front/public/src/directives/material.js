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
var ui_1 = require("src/services/ui");
var Material = (function () {
    function Material(viewContainer) {
        ui_1.Material.updateElement(viewContainer.element.nativeElement);
    }
    Material = __decorate([
        angular2_1.Directive({
            selector: '[mdl]',
            properties: ['mdl']
        }), 
        __metadata('design:paramtypes', [angular2_1.ViewContainerRef])
    ], Material);
    return Material;
})();
exports.Material = Material;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9kaXJlY3RpdmVzL21hdGVyaWFsLnRzIl0sIm5hbWVzIjpbIk1hdGVyaWFsIiwiTWF0ZXJpYWwuY29uc3RydWN0b3IiXSwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7O0FBQUEseUJBQTBELG1CQUFtQixDQUFDLENBQUE7QUFDOUUsbUJBQTRDLGlCQUFpQixDQUFDLENBQUE7QUFFOUQ7SUFNRUEsa0JBQVlBLGFBQStCQTtRQUV6Q0MsYUFBZUEsQ0FBQ0EsYUFBYUEsQ0FBQ0EsYUFBYUEsQ0FBQ0EsT0FBT0EsQ0FBQ0EsYUFBYUEsQ0FBQ0EsQ0FBQ0E7SUFDckVBLENBQUNBO0lBVEhEO1FBQUNBLG9CQUFTQSxDQUFDQTtZQUNUQSxRQUFRQSxFQUFFQSxPQUFPQTtZQUNqQkEsVUFBVUEsRUFBRUEsQ0FBQ0EsS0FBS0EsQ0FBQ0E7U0FDcEJBLENBQUNBOztpQkFPREE7SUFBREEsZUFBQ0E7QUFBREEsQ0FWQSxBQVVDQSxJQUFBO0FBTFksZ0JBQVEsV0FLcEIsQ0FBQSIsImZpbGUiOiJzcmMvZGlyZWN0aXZlcy9tYXRlcmlhbC5qcyIsInNvdXJjZXNDb250ZW50IjpbImltcG9ydCB7IERpcmVjdGl2ZSwgVmlld0NvbnRhaW5lclJlZiwgUHJvdG9WaWV3UmVmIH0gZnJvbSAnYW5ndWxhcjIvYW5ndWxhcjInO1xuaW1wb3J0IHsgTWF0ZXJpYWwgYXMgTWF0ZXJpYWxTZXJ2aWNlIH0gZnJvbSBcInNyYy9zZXJ2aWNlcy91aVwiO1xuXG5ARGlyZWN0aXZlKHtcbiAgc2VsZWN0b3I6ICdbbWRsXScsXG4gIHByb3BlcnRpZXM6IFsnbWRsJ11cbn0pXG5cbmV4cG9ydCBjbGFzcyBNYXRlcmlhbHtcbiAgY29uc3RydWN0b3Iodmlld0NvbnRhaW5lcjogVmlld0NvbnRhaW5lclJlZikge1xuICAgIC8vTWF0ZXJpYWxTZXJ2aWNlLnJlYnVpbGQoKTtcbiAgICBNYXRlcmlhbFNlcnZpY2UudXBkYXRlRWxlbWVudCh2aWV3Q29udGFpbmVyLmVsZW1lbnQubmF0aXZlRWxlbWVudCk7XG4gIH1cbn1cbiJdLCJzb3VyY2VSb290IjoiL3NvdXJjZS8ifQ==