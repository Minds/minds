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
var api_1 = require('src/services/api');
var material_1 = require('src/directives/material');
var Remind = (function () {
    function Remind(client) {
        this.client = client;
        this.hideTabs = true;
    }
    Object.defineProperty(Remind.prototype, "object", {
        set: function (value) {
            this.activity = value;
        },
        enumerable: true,
        configurable: true
    });
    Remind.prototype.toDate = function (timestamp) {
        return new Date(timestamp * 1000);
    };
    Remind = __decorate([
        angular2_1.Component({
            selector: 'minds-remind',
            viewInjector: [api_1.Client],
            properties: ['object']
        }),
        angular2_1.View({
            templateUrl: 'templates/entities/activity.html',
            directives: [angular2_1.NgFor, angular2_1.NgIf, material_1.Material]
        }), 
        __metadata('design:paramtypes', [api_1.Client])
    ], Remind);
    return Remind;
})();
exports.Remind = Remind;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9jb250cm9sbGVycy9uZXdzZmVlZC9yZW1pbmQudHMiXSwibmFtZXMiOlsiUmVtaW5kIiwiUmVtaW5kLmNvbnN0cnVjdG9yIiwiUmVtaW5kLm9iamVjdCIsIlJlbWluZC50b0RhdGUiXSwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7O0FBQUEseUJBQXdFLG1CQUFtQixDQUFDLENBQUE7QUFDNUYsb0JBQXVCLGtCQUFrQixDQUFDLENBQUE7QUFDMUMseUJBQXlCLHlCQUF5QixDQUFDLENBQUE7QUFHbkQ7SUFjQ0EsZ0JBQW1CQSxNQUFjQTtRQUFkQyxXQUFNQSxHQUFOQSxNQUFNQSxDQUFRQTtRQUM5QkEsSUFBSUEsQ0FBQ0EsUUFBUUEsR0FBR0EsSUFBSUEsQ0FBQ0E7SUFDeEJBLENBQUNBO0lBRUFELHNCQUFJQSwwQkFBTUE7YUFBVkEsVUFBV0EsS0FBVUE7WUFDbkJFLElBQUlBLENBQUNBLFFBQVFBLEdBQUdBLEtBQUtBLENBQUNBO1FBQ3hCQSxDQUFDQTs7O09BQUFGO0lBRURBLHVCQUFNQSxHQUFOQSxVQUFPQSxTQUFTQTtRQUNkRyxNQUFNQSxDQUFDQSxJQUFJQSxJQUFJQSxDQUFDQSxTQUFTQSxHQUFDQSxJQUFJQSxDQUFDQSxDQUFDQTtJQUNsQ0EsQ0FBQ0E7SUF4QkhIO1FBQUNBLG9CQUFTQSxDQUFDQTtZQUNUQSxRQUFRQSxFQUFFQSxjQUFjQTtZQUN4QkEsWUFBWUEsRUFBRUEsQ0FBRUEsWUFBTUEsQ0FBRUE7WUFDeEJBLFVBQVVBLEVBQUVBLENBQUNBLFFBQVFBLENBQUNBO1NBQ3ZCQSxDQUFDQTtRQUNEQSxlQUFJQSxDQUFDQTtZQUNKQSxXQUFXQSxFQUFFQSxrQ0FBa0NBO1lBQy9DQSxVQUFVQSxFQUFFQSxDQUFFQSxnQkFBS0EsRUFBRUEsZUFBSUEsRUFBRUEsbUJBQVFBLENBQUNBO1NBQ3JDQSxDQUFDQTs7ZUFpQkRBO0lBQURBLGFBQUNBO0FBQURBLENBekJBLEFBeUJDQSxJQUFBO0FBZlksY0FBTSxTQWVsQixDQUFBIiwiZmlsZSI6InNyYy9jb250cm9sbGVycy9uZXdzZmVlZC9yZW1pbmQuanMiLCJzb3VyY2VzQ29udGVudCI6WyJpbXBvcnQgeyBDb21wb25lbnQsIFZpZXcsIE5nRm9yLCBOZ0lmLCBPYnNlcnZhYmxlLCBmb3JtRGlyZWN0aXZlc30gZnJvbSAnYW5ndWxhcjIvYW5ndWxhcjInO1xuaW1wb3J0IHsgQ2xpZW50IH0gZnJvbSAnc3JjL3NlcnZpY2VzL2FwaSc7XG5pbXBvcnQgeyBNYXRlcmlhbCB9IGZyb20gJ3NyYy9kaXJlY3RpdmVzL21hdGVyaWFsJztcbmltcG9ydCB7IEFjdGl2aXR5IH0gZnJvbSAnLi9hY3Rpdml0eSc7XG5cbkBDb21wb25lbnQoe1xuICBzZWxlY3RvcjogJ21pbmRzLXJlbWluZCcsXG4gIHZpZXdJbmplY3RvcjogWyBDbGllbnQgXSxcbiAgcHJvcGVydGllczogWydvYmplY3QnXVxufSlcbkBWaWV3KHtcbiAgdGVtcGxhdGVVcmw6ICd0ZW1wbGF0ZXMvZW50aXRpZXMvYWN0aXZpdHkuaHRtbCcsXG4gIGRpcmVjdGl2ZXM6IFsgTmdGb3IsIE5nSWYsIE1hdGVyaWFsXVxufSlcblxuZXhwb3J0IGNsYXNzIFJlbWluZCB7XG4gIGFjdGl2aXR5IDogYW55O1xuICBoaWRlVGFicyA6IGJvb2xlYW47XG5cblx0Y29uc3RydWN0b3IocHVibGljIGNsaWVudDogQ2xpZW50KXtcbiAgICB0aGlzLmhpZGVUYWJzID0gdHJ1ZTtcblx0fVxuXG4gIHNldCBvYmplY3QodmFsdWU6IGFueSkge1xuICAgIHRoaXMuYWN0aXZpdHkgPSB2YWx1ZTtcbiAgfVxuXG4gIHRvRGF0ZSh0aW1lc3RhbXApe1xuICAgIHJldHVybiBuZXcgRGF0ZSh0aW1lc3RhbXAqMTAwMCk7XG4gIH1cbn1cbiJdLCJzb3VyY2VSb290IjoiL3NvdXJjZS8ifQ==