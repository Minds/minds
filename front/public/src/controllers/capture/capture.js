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
var Capture = (function () {
    function Capture() {
        console.log("this is the capture");
    }
    Capture = __decorate([
        angular2_1.Component({
            selector: 'minds-capture'
        }),
        angular2_1.View({
            template: 'this is capture'
        }), 
        __metadata('design:paramtypes', [])
    ], Capture);
    return Capture;
})();
exports.Capture = Capture;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9jb250cm9sbGVycy9jYXB0dXJlL2NhcHR1cmUudHMiXSwibmFtZXMiOlsiQ2FwdHVyZSIsIkNhcHR1cmUuY29uc3RydWN0b3IiXSwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7O0FBQUEseUJBQThCLG1CQUFtQixDQUFDLENBQUE7QUFHbEQ7SUFRQ0E7UUFDQ0MsT0FBT0EsQ0FBQ0EsR0FBR0EsQ0FBQ0EscUJBQXFCQSxDQUFDQSxDQUFDQTtJQUNwQ0EsQ0FBQ0E7SUFWRkQ7UUFBQ0Esb0JBQVNBLENBQUNBO1lBQ1RBLFFBQVFBLEVBQUVBLGVBQWVBO1NBQzFCQSxDQUFDQTtRQUNEQSxlQUFJQSxDQUFDQTtZQUNKQSxRQUFRQSxFQUFFQSxpQkFBaUJBO1NBQzVCQSxDQUFDQTs7Z0JBTURBO0lBQURBLGNBQUNBO0FBQURBLENBWEEsQUFXQ0EsSUFBQTtBQUpZLGVBQU8sVUFJbkIsQ0FBQSIsImZpbGUiOiJzcmMvY29udHJvbGxlcnMvY2FwdHVyZS9jYXB0dXJlLmpzIiwic291cmNlc0NvbnRlbnQiOlsiaW1wb3J0IHtDb21wb25lbnQsIFZpZXd9IGZyb20gJ2FuZ3VsYXIyL2FuZ3VsYXIyJztcblxuXG5AQ29tcG9uZW50KHtcbiAgc2VsZWN0b3I6ICdtaW5kcy1jYXB0dXJlJ1xufSlcbkBWaWV3KHtcbiAgdGVtcGxhdGU6ICd0aGlzIGlzIGNhcHR1cmUnXG59KVxuXG5leHBvcnQgY2xhc3MgQ2FwdHVyZSB7XG5cdGNvbnN0cnVjdG9yKCl7XG5cdFx0Y29uc29sZS5sb2coXCJ0aGlzIGlzIHRoZSBjYXB0dXJlXCIpO1xuXHR9XG59Il0sInNvdXJjZVJvb3QiOiIvc291cmNlLyJ9