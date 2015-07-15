var Sidebar = (function () {
    function Sidebar() {
    }
    Sidebar.prototype.open = function () {
        var drawer = document.getElementsByClassName('mdl-layout__drawer')[0];
        drawer.style['transform'] = "translateX(0)";
        drawer.style['-webkit-transform'] = "translateX(0)";
        drawer.style['-moz-transform'] = "translateX(0)";
        var self = this;
        setTimeout(function () {
            var listener = function (e) {
                self.close();
                document.removeEventListener('click', listener);
            };
            document.addEventListener("click", listener);
        }, 300);
    };
    Sidebar.prototype.close = function () {
        var drawer = document.getElementsByClassName('mdl-layout__drawer')[0];
        drawer.style['transform'] = null;
        drawer.style['-webkit-transform'] = null;
        drawer.style['-moz-transform'] = null;
    };
    return Sidebar;
})();
exports.Sidebar = Sidebar;
//# sourceMappingURL=sidebar.js.map