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
var upload_1 = require('src/services/api/upload');
var Capture = (function () {
    function Capture(upload) {
        this.upload = upload;
        console.log("this is the capture");
    }
    Capture.prototype.uploadFile = function () {
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
            viewBindings: [upload_1.Upload]
        }),
        angular2_1.View({
            template: 'this is capture'
        }), 
        __metadata('design:paramtypes', [upload_1.Upload])
    ], Capture);
    return Capture;
})();
exports.Capture = Capture;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9jb250cm9sbGVycy9jYXB0dXJlL2NhcHR1cmUudHMiXSwibmFtZXMiOlsiQ2FwdHVyZSIsIkNhcHR1cmUuY29uc3RydWN0b3IiLCJDYXB0dXJlLnVwbG9hZEZpbGUiXSwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7O0FBQUEseUJBQWdDLG1CQUFtQixDQUFDLENBQUE7QUFDcEQsdUJBQXVCLHlCQUF5QixDQUFDLENBQUE7QUFHakQ7SUFVQ0EsaUJBQW1CQSxNQUFjQTtRQUFkQyxXQUFNQSxHQUFOQSxNQUFNQSxDQUFRQTtRQUNoQ0EsT0FBT0EsQ0FBQ0EsR0FBR0EsQ0FBQ0EscUJBQXFCQSxDQUFDQSxDQUFDQTtJQUNwQ0EsQ0FBQ0E7SUFFQUQsNEJBQVVBLEdBQVZBO1FBQ0VFLElBQUlBLENBQUNBLE1BQU1BLENBQUNBLElBQUlBLENBQUNBLGdCQUFnQkEsRUFBRUEsSUFBSUEsQ0FBQ0EsUUFBUUEsQ0FBQ0E7YUFDaERBLElBQUlBLENBQUNBLFVBQUNBLFFBQVFBO1lBQ2RBLE9BQU9BLENBQUNBLEdBQUdBLENBQUNBLFFBQVFBLENBQUNBLENBQUNBO1FBQ3ZCQSxDQUFDQSxDQUFDQTthQUNEQSxLQUFLQSxDQUFDQSxVQUFTQSxDQUFDQTtZQUNoQixPQUFPLENBQUMsS0FBSyxDQUFDLENBQUMsQ0FBQyxDQUFDO1FBQ2xCLENBQUMsQ0FBQ0EsQ0FBQ0E7SUFDTEEsQ0FBQ0E7SUF0QkhGO1FBQUNBLG9CQUFTQSxDQUFDQTtZQUNUQSxRQUFRQSxFQUFFQSxlQUFlQTtZQUN6QkEsWUFBWUEsRUFBRUEsQ0FBRUEsZUFBTUEsQ0FBRUE7U0FDekJBLENBQUNBO1FBQ0RBLGVBQUlBLENBQUNBO1lBQ0pBLFFBQVFBLEVBQUVBLGlCQUFpQkE7U0FDNUJBLENBQUNBOztnQkFrQkRBO0lBQURBLGNBQUNBO0FBQURBLENBeEJBLEFBd0JDQSxJQUFBO0FBaEJZLGVBQU8sVUFnQm5CLENBQUEiLCJmaWxlIjoic3JjL2NvbnRyb2xsZXJzL2NhcHR1cmUvY2FwdHVyZS5qcyIsInNvdXJjZXNDb250ZW50IjpbImltcG9ydCB7IENvbXBvbmVudCwgVmlldyB9IGZyb20gJ2FuZ3VsYXIyL2FuZ3VsYXIyJztcbmltcG9ydCB7IFVwbG9hZCB9IGZyb20gJ3NyYy9zZXJ2aWNlcy9hcGkvdXBsb2FkJztcblxuXG5AQ29tcG9uZW50KHtcbiAgc2VsZWN0b3I6ICdtaW5kcy1jYXB0dXJlJyxcbiAgdmlld0JpbmRpbmdzOiBbIFVwbG9hZCBdXG59KVxuQFZpZXcoe1xuICB0ZW1wbGF0ZTogJ3RoaXMgaXMgY2FwdHVyZSdcbn0pXG5cbmV4cG9ydCBjbGFzcyBDYXB0dXJlIHtcblxuXHRjb25zdHJ1Y3RvcihwdWJsaWMgdXBsb2FkOiBVcGxvYWQpe1xuXHRcdGNvbnNvbGUubG9nKFwidGhpcyBpcyB0aGUgY2FwdHVyZVwiKTtcblx0fVxuXG4gIHVwbG9hZEZpbGUoKXtcbiAgICB0aGlzLnVwbG9hZC5wb3N0KCdhcGkvdjEvYXJjaGl2ZScsIHRoaXMucG9zdE1ldGEpXG5cdFx0XHRcdC50aGVuKChyZXNwb25zZSkgPT4ge1xuXHRcdFx0XHRcdGNvbnNvbGUubG9nKHJlc3BvbnNlKTtcblx0XHRcdFx0fSlcblx0XHRcdFx0LmNhdGNoKGZ1bmN0aW9uKGUpe1xuXHRcdFx0XHRcdGNvbnNvbGUuZXJyb3IoZSk7XG5cdFx0XHRcdH0pO1xuICB9XG5cbn1cbiJdLCJzb3VyY2VSb290IjoiL3NvdXJjZS8ifQ==