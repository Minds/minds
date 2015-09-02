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
var router_1 = require("angular2/router");
var api_1 = require('src/services/api');
var session_1 = require('src/services/session');
var material_1 = require('src/directives/material');
var ImageCard = (function () {
    function ImageCard(client) {
        this.client = client;
        this.session = session_1.SessionFactory.build();
        this.minds = window.Minds;
    }
    Object.defineProperty(ImageCard.prototype, "object", {
        set: function (value) {
            this.entity = value;
        },
        enumerable: true,
        configurable: true
    });
    ImageCard = __decorate([
        angular2_1.Component({
            selector: 'minds-card-image',
            viewBindings: [api_1.Client],
            properties: ['object']
        }),
        angular2_1.View({
            templateUrl: 'templates/cards/object/image.html',
            directives: [angular2_1.NgFor, angular2_1.NgIf, angular2_1.NgClass, angular2_1.NgStyle, material_1.Material, router_1.RouterLink]
        }), 
        __metadata('design:paramtypes', [Client])
    ], ImageCard);
    return ImageCard;
})();
exports.ImageCard = ImageCard;
//# sourceMappingURL=image.js.map