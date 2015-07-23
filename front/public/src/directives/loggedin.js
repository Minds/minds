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
        __metadata('design:paramtypes', [storage_1.Storage])
    ], LoggedIn);
    return LoggedIn;
})();
exports.LoggedIn = LoggedIn;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9kaXJlY3RpdmVzL2xvZ2dlZGluLnRzIl0sIm5hbWVzIjpbIkxvZ2dlZEluIiwiTG9nZ2VkSW4uY29uc3RydWN0b3IiLCJMb2dnZWRJbi5pc0xvZ2dlZEluIl0sIm1hcHBpbmdzIjoiOzs7Ozs7Ozs7OztBQUFBLHlCQUE4QixtQkFBbUIsQ0FBQyxDQUFBO0FBQ2xELHdCQUFzQixzQkFBc0IsQ0FBQyxDQUFBO0FBRTdDO0lBTUNBLGtCQUFtQkEsT0FBZ0JBO1FBQWhCQyxZQUFPQSxHQUFQQSxPQUFPQSxDQUFTQTtJQUVuQ0EsQ0FBQ0E7SUFDREQsNkJBQVVBLEdBQVZBO1FBQ0NFLE9BQU9BLENBQUNBLEdBQUdBLENBQUNBLGdCQUFnQkEsQ0FBQ0EsQ0FBQ0E7UUFDOUJBLEVBQUVBLENBQUFBLENBQUNBLElBQUlBLENBQUNBLE9BQU9BLENBQUNBLEdBQUdBLENBQUNBLFVBQVVBLENBQUNBLENBQUNBO1lBQy9CQSxNQUFNQSxDQUFDQSxJQUFJQSxDQUFDQTtRQUNiQSxNQUFNQSxDQUFDQSxLQUFLQSxDQUFDQTtJQUNkQSxDQUFDQTtJQWRGRjtRQUFDQSxvQkFBU0EsQ0FBQ0E7WUFDVEEsUUFBUUEsRUFBRUEsZ0JBQWdCQTtZQUMxQkEsWUFBWUEsRUFBRUEsQ0FBQ0EsaUJBQU9BLENBQUNBO1NBQ3hCQSxDQUFDQTs7aUJBWURBO0lBQURBLGVBQUNBO0FBQURBLENBZkEsQUFlQ0EsSUFBQTtBQVZZLGdCQUFRLFdBVXBCLENBQUEiLCJmaWxlIjoic3JjL2RpcmVjdGl2ZXMvbG9nZ2VkaW4uanMiLCJzb3VyY2VzQ29udGVudCI6WyJpbXBvcnQge0NvbXBvbmVudCwgTmdJZn0gZnJvbSAnYW5ndWxhcjIvYW5ndWxhcjInO1xuaW1wb3J0IHtTdG9yYWdlfSBmcm9tICdzcmMvc2VydmljZXMvc3RvcmFnZSc7XG5cbkBDb21wb25lbnQoe1xuICBzZWxlY3RvcjogJ21pbmRzLWxvZ2dlZGluJyxcbiAgdmlld0luamVjdG9yOiBbU3RvcmFnZV1cbn0pXG5cbmV4cG9ydCBjbGFzcyBMb2dnZWRJbiB7IFxuXHRjb25zdHJ1Y3RvcihwdWJsaWMgc3RvcmFnZTogU3RvcmFnZSl7XG5cdFx0XG5cdH1cblx0aXNMb2dnZWRJbigpe1xuXHRcdGNvbnNvbGUubG9nKCdjaGVja2luZyBuZy1pZicpO1xuXHRcdGlmKHRoaXMuc3RvcmFnZS5nZXQoJ2xvZ2dlZGluJykpXG5cdFx0XHRyZXR1cm4gdHJ1ZTtcblx0XHRyZXR1cm4gZmFsc2U7XG5cdH1cbn0iXSwic291cmNlUm9vdCI6Ii9zb3VyY2UvIn0=