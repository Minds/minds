if (typeof __decorate !== "function") __decorate = function (decorators, target, key, desc) {
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") return Reflect.decorate(decorators, target, key, desc);
    switch (arguments.length) {
        case 2: return decorators.reduceRight(function(o, d) { return (d && d(o)) || o; }, target);
        case 3: return decorators.reduceRight(function(o, d) { return (d && d(target, key)), void 0; }, void 0);
        case 4: return decorators.reduceRight(function(o, d) { return (d && d(target, key, o)) || o; }, desc);
    }
};
if (typeof __metadata !== "function") __metadata = function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};
var angular2_1 = require('angular2/angular2');
var storage_1 = require('src/services/storage');
var Topbar = (function () {
    function Topbar(storage) {
        this.storage = storage;
    }
    Topbar = __decorate([
        angular2_1.Component({
            selector: 'minds-topbar',
            viewInjector: [storage_1.Storage]
        }),
        angular2_1.View({
            templateUrl: 'templates/components/topbar.html',
            directives: [angular2_1.NgIf]
        }), 
        __metadata('design:paramtypes', [storage_1.Storage])
    ], Topbar);
    return Topbar;
})();
exports.Topbar = Topbar;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9jb21wb25lbnRzL3RvcGJhci50cyJdLCJuYW1lcyI6WyJUb3BiYXIiLCJUb3BiYXIuY29uc3RydWN0b3IiXSwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7O0FBQUEseUJBQW9DLG1CQUFtQixDQUFDLENBQUE7QUFDeEQsd0JBQXNCLHNCQUFzQixDQUFDLENBQUE7QUFHN0M7SUFVQ0EsZ0JBQW1CQSxPQUFnQkE7UUFBaEJDLFlBQU9BLEdBQVBBLE9BQU9BLENBQVNBO0lBQUdBLENBQUNBO0lBVnhDRDtRQUFDQSxvQkFBU0EsQ0FBQ0E7WUFDVEEsUUFBUUEsRUFBRUEsY0FBY0E7WUFDeEJBLFlBQVlBLEVBQUVBLENBQUNBLGlCQUFPQSxDQUFDQTtTQUN4QkEsQ0FBQ0E7UUFDREEsZUFBSUEsQ0FBQ0E7WUFDSkEsV0FBV0EsRUFBRUEsa0NBQWtDQTtZQUMvQ0EsVUFBVUEsRUFBRUEsQ0FBQ0EsZUFBSUEsQ0FBQ0E7U0FDbkJBLENBQUNBOztlQUlEQTtJQUFEQSxhQUFDQTtBQUFEQSxDQVhBLElBV0M7QUFGWSxjQUFNLFNBRWxCLENBQUEiLCJmaWxlIjoic3JjL2NvbXBvbmVudHMvdG9wYmFyLmpzIiwic291cmNlc0NvbnRlbnQiOlsiaW1wb3J0IHtDb21wb25lbnQsIFZpZXcsIE5nSWZ9IGZyb20gJ2FuZ3VsYXIyL2FuZ3VsYXIyJztcbmltcG9ydCB7U3RvcmFnZX0gZnJvbSAnc3JjL3NlcnZpY2VzL3N0b3JhZ2UnO1xuaW1wb3J0IHtMb2dnZWRJbn0gZnJvbSAnc3JjL2RpcmVjdGl2ZXMvbG9nZ2VkaW4nO1xuXG5AQ29tcG9uZW50KHtcbiAgc2VsZWN0b3I6ICdtaW5kcy10b3BiYXInLFxuICB2aWV3SW5qZWN0b3I6IFtTdG9yYWdlXVxufSlcbkBWaWV3KHtcbiAgdGVtcGxhdGVVcmw6ICd0ZW1wbGF0ZXMvY29tcG9uZW50cy90b3BiYXIuaHRtbCcsXG4gIGRpcmVjdGl2ZXM6IFtOZ0lmXVxufSlcblxuZXhwb3J0IGNsYXNzIFRvcGJhciB7IFxuXHRjb25zdHJ1Y3RvcihwdWJsaWMgc3RvcmFnZTogU3RvcmFnZSl7IH1cbn0iXSwic291cmNlUm9vdCI6Ii9zb3VyY2UvIn0=