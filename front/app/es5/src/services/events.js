var angular2_1 = require('angular2/angular2');
var LoggedIn = (function () {
    function LoggedIn() {
        this.emitter = new angular2_1.EventEmitter();
    }
    LoggedIn.prototype.listen = function (callback) {
        console.log(this.emitter);
        this.emitter.observer({ next: function (data) {
                callback(data);
            }
        });
    };
    LoggedIn.prototype.emit = function (data) {
        if (data === void 0) { data = ""; }
        this.emitter.next(data);
    };
    return LoggedIn;
})();
exports.LoggedIn = LoggedIn;
var injector = angular2_1.Injector.resolveAndCreate([
    angular2_1.bind(LoggedIn).toFactory(function () {
        return new LoggedIn();
    })
]);
var Factory = (function () {
    function Factory() {
    }
    Factory.build = function (className) {
        console.log(className);
        return injector.get(className);
    };
    return Factory;
})();
exports.Factory = Factory;
//# sourceMappingURL=events.js.map