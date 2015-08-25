var Storage = (function () {
    function Storage() {
    }
    Storage.prototype.get = function (key) {
        return window.localStorage.getItem(key);
    };
    Storage.prototype.set = function (key, value) {
        return window.localStorage.setItem(key, value);
    };
    Storage.prototype.destroy = function (key) {
        return window.localStorage.removeItem(key);
    };
    return Storage;
})();
exports.Storage = Storage;
//# sourceMappingURL=storage.js.map