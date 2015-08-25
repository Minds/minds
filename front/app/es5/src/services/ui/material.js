var Material = (function () {
    function Material() {
    }
    Material.rebuild = function () {
        window.componentHandler.upgradeDom();
    };
    Material.updateElement = function (element) {
        window.componentHandler.upgradeDom();
    };
    return Material;
})();
exports.Material = Material;
//# sourceMappingURL=material.js.map