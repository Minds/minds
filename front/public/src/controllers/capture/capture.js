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
var http_1 = require('http/http');
var upload_1 = require('src/services/api/upload');
var Capture = (function () {
    function Capture(upload, http) {
        this.upload = upload;
        this.http = http;
        this.postMeta = {};
        console.log("this is the capture");
    }
    Capture.prototype.uploadFile = function () {
        console.log('called');
        console.log(this.postMeta);
        this.upload.post('api/v1/archive', this.postMeta)
            .then(function (response) {
            console.log(response);
        })
            .catch(function (e) {
            console.error(e);
        });
    };
    Capture = __decorate([
        angular2_1.Component({
            selector: 'minds-capture',
            viewBindings: [upload_1.Upload, http_1.Http]
        }),
        angular2_1.View({
            templateUrl: 'templates/capture/capture.html',
            directives: [angular2_1.FORM_DIRECTIVES]
        }), 
        __metadata('design:paramtypes', [upload_1.Upload, http_1.Http])
    ], Capture);
    return Capture;
})();
exports.Capture = Capture;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9jb250cm9sbGVycy9jYXB0dXJlL2NhcHR1cmUudHMiXSwibmFtZXMiOlsiQ2FwdHVyZSIsIkNhcHR1cmUuY29uc3RydWN0b3IiLCJDYXB0dXJlLnVwbG9hZEZpbGUiXSwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7O0FBQUEseUJBQWlELG1CQUFtQixDQUFDLENBQUE7QUFDckUscUJBQTRCLFdBQVcsQ0FBQyxDQUFBO0FBRXhDLHVCQUF1Qix5QkFBeUIsQ0FBQyxDQUFBO0FBR2pEO0lBYUNBLGlCQUFtQkEsTUFBY0EsRUFBU0EsSUFBVUE7UUFBakNDLFdBQU1BLEdBQU5BLE1BQU1BLENBQVFBO1FBQVNBLFNBQUlBLEdBQUpBLElBQUlBLENBQU1BO1FBRm5EQSxhQUFRQSxHQUFZQSxFQUFFQSxDQUFDQTtRQUd2QkEsT0FBT0EsQ0FBQ0EsR0FBR0EsQ0FBQ0EscUJBQXFCQSxDQUFDQSxDQUFDQTtJQUNwQ0EsQ0FBQ0E7SUFFQUQsNEJBQVVBLEdBQVZBO1FBQ0VFLE9BQU9BLENBQUNBLEdBQUdBLENBQUNBLFFBQVFBLENBQUNBLENBQUNBO1FBQ3RCQSxPQUFPQSxDQUFDQSxHQUFHQSxDQUFDQSxJQUFJQSxDQUFDQSxRQUFRQSxDQUFDQSxDQUFDQTtRQUUzQkEsSUFBSUEsQ0FBQ0EsTUFBTUEsQ0FBQ0EsSUFBSUEsQ0FBQ0EsZ0JBQWdCQSxFQUFFQSxJQUFJQSxDQUFDQSxRQUFRQSxDQUFDQTthQUNoREEsSUFBSUEsQ0FBQ0EsVUFBQ0EsUUFBUUE7WUFDZEEsT0FBT0EsQ0FBQ0EsR0FBR0EsQ0FBQ0EsUUFBUUEsQ0FBQ0EsQ0FBQ0E7UUFDdkJBLENBQUNBLENBQUNBO2FBQ0RBLEtBQUtBLENBQUNBLFVBQVNBLENBQUNBO1lBQ2hCLE9BQU8sQ0FBQyxLQUFLLENBQUMsQ0FBQyxDQUFDLENBQUM7UUFDbEIsQ0FBQyxDQUFDQSxDQUFDQTtJQUNMQSxDQUFDQTtJQTVCSEY7UUFBQ0Esb0JBQVNBLENBQUNBO1lBQ1RBLFFBQVFBLEVBQUVBLGVBQWVBO1lBQ3pCQSxZQUFZQSxFQUFFQSxDQUFFQSxlQUFNQSxFQUFFQSxXQUFJQSxDQUFFQTtTQUMvQkEsQ0FBQ0E7UUFDREEsZUFBSUEsQ0FBQ0E7WUFDSkEsV0FBV0EsRUFBRUEsZ0NBQWdDQTtZQUM3Q0EsVUFBVUEsRUFBRUEsQ0FBQ0EsMEJBQWVBLENBQUNBO1NBQzlCQSxDQUFDQTs7Z0JBdUJEQTtJQUFEQSxjQUFDQTtBQUFEQSxDQTlCQSxBQThCQ0EsSUFBQTtBQXJCWSxlQUFPLFVBcUJuQixDQUFBIiwiZmlsZSI6InNyYy9jb250cm9sbGVycy9jYXB0dXJlL2NhcHR1cmUuanMiLCJzb3VyY2VzQ29udGVudCI6WyJpbXBvcnQgeyBDb21wb25lbnQsIFZpZXcsIEZPUk1fRElSRUNUSVZFUyB9IGZyb20gJ2FuZ3VsYXIyL2FuZ3VsYXIyJztcbmltcG9ydCB7SHR0cCwgSGVhZGVyc30gZnJvbSAnaHR0cC9odHRwJztcblxuaW1wb3J0IHsgVXBsb2FkIH0gZnJvbSAnc3JjL3NlcnZpY2VzL2FwaS91cGxvYWQnO1xuXG5cbkBDb21wb25lbnQoe1xuICBzZWxlY3RvcjogJ21pbmRzLWNhcHR1cmUnLFxuICB2aWV3QmluZGluZ3M6IFsgVXBsb2FkLCBIdHRwIF1cbn0pXG5AVmlldyh7XG4gIHRlbXBsYXRlVXJsOiAndGVtcGxhdGVzL2NhcHR1cmUvY2FwdHVyZS5odG1sJyxcbiAgZGlyZWN0aXZlczogW0ZPUk1fRElSRUNUSVZFU11cbn0pXG5cbmV4cG9ydCBjbGFzcyBDYXB0dXJlIHtcblxuICBwb3N0TWV0YSA6IE9iamVjdCA9IHt9O1xuXG5cdGNvbnN0cnVjdG9yKHB1YmxpYyB1cGxvYWQ6IFVwbG9hZCwgcHVibGljIGh0dHA6IEh0dHApe1xuXHRcdGNvbnNvbGUubG9nKFwidGhpcyBpcyB0aGUgY2FwdHVyZVwiKTtcblx0fVxuXG4gIHVwbG9hZEZpbGUoKXtcbiAgICBjb25zb2xlLmxvZygnY2FsbGVkJyk7XG4gICAgY29uc29sZS5sb2codGhpcy5wb3N0TWV0YSk7XG5cbiAgICB0aGlzLnVwbG9hZC5wb3N0KCdhcGkvdjEvYXJjaGl2ZScsIHRoaXMucG9zdE1ldGEpXG5cdFx0XHRcdC50aGVuKChyZXNwb25zZSkgPT4ge1xuXHRcdFx0XHRcdGNvbnNvbGUubG9nKHJlc3BvbnNlKTtcblx0XHRcdFx0fSlcblx0XHRcdFx0LmNhdGNoKGZ1bmN0aW9uKGUpe1xuXHRcdFx0XHRcdGNvbnNvbGUuZXJyb3IoZSk7XG5cdFx0XHRcdH0pO1xuICB9XG5cbn1cbiJdLCJzb3VyY2VSb290IjoiL3NvdXJjZS8ifQ==