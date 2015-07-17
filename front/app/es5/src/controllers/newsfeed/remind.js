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
        __metadata('design:paramtypes', [Client])
    ], Remind);
    return Remind;
})();
exports.Remind = Remind;
//# sourceMappingURL=remind.js.map