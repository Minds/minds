var Cookie = (function () {
    function Cookie() {
    }
    Cookie.prototype.get = function (key) {
        var cookies = document.cookie ? document.cookie.split('; ') : [];
        if (!cookies)
            return;
        for (var _i = 0; _i < cookies.length; _i++) {
            var cookie = cookies[_i];
            var name_1 = void 0, value = void 0;
            _a = cookie.split('='), name_1 = _a[0], value = _a[1];
            if (name_1 == key)
                return value;
        }
        return;
        var _a;
    };
    return Cookie;
})();
exports.Cookie = Cookie;
//# sourceMappingURL=cookie.js.map