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
var storage_1 = require('src/services/storage');
var LoggedIn = (function () {
    function LoggedIn(storage) {
        this.storage = storage;
    }
    LoggedIn.prototype.isLoggedIn = function () {
        console.log('checking ng-if');
        if (this.storage.get('loggedin'))
            return true;
        return false;
    };
    LoggedIn = __decorate([
        angular2_1.Component({
            selector: 'minds-loggedin',
            viewInjector: [storage_1.Storage]
        }), 
        __metadata('design:paramtypes', [Storage])
    ], LoggedIn);
    return LoggedIn;
})();
exports.LoggedIn = LoggedIn;
//# sourceMappingURL=loggedin.js.map