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
var material_1 = require('src/directives/material');
var ComingSoon = (function () {
    function ComingSoon() {
    }
    ComingSoon = __decorate([
        angular2_1.Component({}),
        angular2_1.View({
            templateUrl: 'templates/comingsoon.html',
            directives: [material_1.Material]
        }), 
        __metadata('design:paramtypes', [])
    ], ComingSoon);
    return ComingSoon;
})();
exports.ComingSoon = ComingSoon;
//# sourceMappingURL=comingsoon.js.map