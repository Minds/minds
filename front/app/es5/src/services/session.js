var angular2_1 = require('angular2/angular2');
var Session = (function () {
    function Session() {
        this.loggedinEmitter = new angular2_1.EventEmitter();
        this.userEmitter = new angular2_1.EventEmitter();
    }
    Session.prototype.isLoggedIn = function (observe) {
        if (observe === void 0) { observe = null; }
        if (observe) {
            this.loggedinEmitter.observer({ next: function (is) {
                    if (is)
                        observe(true);
                    else
                        observe(false);
                }
            });
        }
        if (window.Minds.LoggedIn)
            return true;
        return false;
    };
    Session.prototype.getLoggedInUser = function (observe) {
        if (observe === void 0) { observe = null; }
        if (observe) {
            this.userEmitter.observer({ next: function (user) {
                    observe(user);
                } });
        }
        if (window.Minds.user)
            return window.Minds.user;
        return false;
    };
    Session.prototype.login = function (user) {
        if (user === void 0) { user = null; }
        this.userEmitter.next(user);
        window.Minds.user = user;
        this.loggedinEmitter.next(true);
    };
    Session.prototype.logout = function () {
        this.loggedinEmitter.next(false);
        this.userEmitter.next(null);
    };
    return Session;
})();
exports.Session = Session;
var injector = angular2_1.Injector.resolveAndCreate([
    angular2_1.bind(Session).toFactory(function () {
        return new Session();
    })
]);
var SessionFactory = (function () {
    function SessionFactory() {
    }
    SessionFactory.build = function () {
        return injector.get(Session);
    };
    return SessionFactory;
})();
exports.SessionFactory = SessionFactory;
//# sourceMappingURL=session.js.map