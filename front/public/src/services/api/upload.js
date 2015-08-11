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
var __param = (this && this.__param) || function (paramIndex, decorator) {
    return function (target, key) { decorator(target, key, paramIndex); }
};
var angular2_1 = require('angular2/angular2');
var cookie_1 = require('src/services/cookie');
var Upload = (function () {
    function Upload(http) {
        this.http = http;
        this.base = "/";
        this.cookie = new cookie_1.Cookie();
    }
    Upload.prototype.post = function (endpoint, files, data, progress) {
        if (files === void 0) { files = []; }
        if (data === void 0) { data = {}; }
        if (progress === void 0) { progress = function () { }; }
        var self = this;
        var formData = new FormData();
        if (!data.filekey) {
            data.filekey = "file";
        }
        for (var file in files)
            formData.append(data.filekey + "[]", file);
        for (var key in data) {
            formData.append(key, data[key]);
        }
        return new Promise(function (resolve, reject) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', self.base + endpoint, true);
            xhr.upload.onprogress = function (e) {
                progress(e.loaded / e.total);
            };
            xhr.onload = function () {
                if (this.status == 200) {
                    resolve(this.response);
                }
                else {
                    reject(this.response);
                }
            };
        });
    };
    Upload = __decorate([
        __param(0, angular2_1.Inject(angular2_1.Http)), 
        __metadata('design:paramtypes', [angular2_1.Http])
    ], Upload);
    return Upload;
})();
exports.Upload = Upload;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9zZXJ2aWNlcy9hcGkvdXBsb2FkLnRzIl0sIm5hbWVzIjpbIlVwbG9hZCIsIlVwbG9hZC5jb25zdHJ1Y3RvciIsIlVwbG9hZC5wb3N0Il0sIm1hcHBpbmdzIjoiOzs7Ozs7Ozs7Ozs7OztBQUFBLHlCQUFvRCxtQkFBbUIsQ0FBQyxDQUFBO0FBQ3hFLHVCQUFxQixxQkFBcUIsQ0FBQyxDQUFBO0FBSzNDO0lBR0NBLGdCQUFpQ0EsSUFBV0E7UUFBWEMsU0FBSUEsR0FBSkEsSUFBSUEsQ0FBT0E7UUFGNUNBLFNBQUlBLEdBQVlBLEdBQUdBLENBQUNBO1FBQ3BCQSxXQUFNQSxHQUFZQSxJQUFJQSxlQUFNQSxFQUFFQSxDQUFDQTtJQUNnQkEsQ0FBQ0E7SUFLaERELHFCQUFJQSxHQUFKQSxVQUFLQSxRQUFpQkEsRUFBRUEsS0FBdUJBLEVBQUVBLElBQWVBLEVBQUVBLFFBQTRCQTtRQUF0RUUscUJBQXVCQSxHQUF2QkEsVUFBdUJBO1FBQUVBLG9CQUFlQSxHQUFmQSxTQUFlQTtRQUFFQSx3QkFBNEJBLEdBQTVCQSx5QkFBMkJBLENBQUNBO1FBQzdGQSxJQUFJQSxJQUFJQSxHQUFHQSxJQUFJQSxDQUFDQTtRQUVoQkEsSUFBSUEsUUFBUUEsR0FBR0EsSUFBSUEsUUFBUUEsRUFBRUEsQ0FBQ0E7UUFDOUJBLEVBQUVBLENBQUFBLENBQUNBLENBQUNBLElBQUlBLENBQUNBLE9BQU9BLENBQUNBLENBQUFBLENBQUNBO1lBQ2pCQSxJQUFJQSxDQUFDQSxPQUFPQSxHQUFHQSxNQUFNQSxDQUFDQTtRQUN2QkEsQ0FBQ0E7UUFFREEsR0FBR0EsQ0FBQUEsQ0FBQ0EsR0FBR0EsQ0FBQ0EsSUFBSUEsSUFBSUEsS0FBS0EsQ0FBQ0E7WUFDckJBLFFBQVFBLENBQUNBLE1BQU1BLENBQUNBLElBQUlBLENBQUNBLE9BQU9BLEdBQUdBLElBQUlBLEVBQUVBLElBQUlBLENBQUNBLENBQUNBO1FBRTVDQSxHQUFHQSxDQUFBQSxDQUFDQSxHQUFHQSxDQUFDQSxHQUFHQSxJQUFJQSxJQUFJQSxDQUFDQSxDQUFBQSxDQUFDQTtZQUNwQkEsUUFBUUEsQ0FBQ0EsTUFBTUEsQ0FBQ0EsR0FBR0EsRUFBRUEsSUFBSUEsQ0FBQ0EsR0FBR0EsQ0FBQ0EsQ0FBQ0EsQ0FBQ0E7UUFDakNBLENBQUNBO1FBRURBLE1BQU1BLENBQUNBLElBQUlBLE9BQU9BLENBQUNBLFVBQUNBLE9BQU9BLEVBQUVBLE1BQU1BO1lBQ2xDQSxJQUFJQSxHQUFHQSxHQUFHQSxJQUFJQSxjQUFjQSxFQUFFQSxDQUFDQTtZQUMvQkEsR0FBR0EsQ0FBQ0EsSUFBSUEsQ0FBQ0EsTUFBTUEsRUFBRUEsSUFBSUEsQ0FBQ0EsSUFBSUEsR0FBR0EsUUFBUUEsRUFBRUEsSUFBSUEsQ0FBQ0EsQ0FBQ0E7WUFDN0NBLEdBQUdBLENBQUNBLE1BQU1BLENBQUNBLFVBQVVBLEdBQUdBLFVBQUNBLENBQUNBO2dCQUN6QkEsUUFBUUEsQ0FBQ0EsQ0FBQ0EsQ0FBQ0EsTUFBTUEsR0FBQ0EsQ0FBQ0EsQ0FBQ0EsS0FBS0EsQ0FBQ0EsQ0FBQ0E7WUFDNUJBLENBQUNBLENBQUFBO1lBQ0RBLEdBQUdBLENBQUNBLE1BQU1BLEdBQUdBO2dCQUNWLEVBQUUsQ0FBQyxDQUFDLElBQUksQ0FBQyxNQUFNLElBQUksR0FBRyxDQUFDLENBQUMsQ0FBQztvQkFDMUIsT0FBTyxDQUFDLElBQUksQ0FBQyxRQUFRLENBQUMsQ0FBQztnQkFDeEIsQ0FBQztnQkFBQyxJQUFJLENBQUMsQ0FBQztvQkFDUCxNQUFNLENBQUMsSUFBSSxDQUFDLFFBQVEsQ0FBQyxDQUFDO2dCQUN2QixDQUFDO1lBQ0YsQ0FBQyxDQUFBQTtRQUNGQSxDQUFDQSxDQUFDQSxDQUFDQTtJQUNKQSxDQUFDQTtJQXJDRkY7UUFHYUEsV0FBQ0EsaUJBQU1BLENBQUNBLGVBQUlBLENBQUNBLENBQUFBOztlQW9DekJBO0lBQURBLGFBQUNBO0FBQURBLENBdkNBLEFBdUNDQSxJQUFBO0FBdkNZLGNBQU0sU0F1Q2xCLENBQUEiLCJmaWxlIjoic3JjL3NlcnZpY2VzL2FwaS91cGxvYWQuanMiLCJzb3VyY2VzQ29udGVudCI6WyJpbXBvcnQge0luamVjdCwgSW5qZWN0b3IsIGJpbmQsIEh0dHAsIEhlYWRlcnN9IGZyb20gJ2FuZ3VsYXIyL2FuZ3VsYXIyJztcbmltcG9ydCB7Q29va2llfSBmcm9tICdzcmMvc2VydmljZXMvY29va2llJztcblxuLyoqXG4gKiBBUEkgQ2xhc3NcbiAqL1xuZXhwb3J0IGNsYXNzIFVwbG9hZCAge1xuXHRiYXNlIDogc3RyaW5nID0gXCIvXCI7XG5cdGNvb2tpZSA6IENvb2tpZSA9IG5ldyBDb29raWUoKTtcblx0Y29uc3RydWN0b3IoQEluamVjdChIdHRwKSBwdWJsaWMgaHR0cCA6IEh0dHApeyB9XG5cblx0LyoqXG5cdCAqIFJldHVybiBhIFBPU1QgcmVxdWVzdFxuXHQgKi9cblx0cG9zdChlbmRwb2ludCA6IHN0cmluZywgZmlsZXMgOiBBcnJheTxhbnk+ID0gW10sIGRhdGEgOiBhbnkgPSB7fSwgcHJvZ3Jlc3MgOiBGdW5jdGlvbiA9ICgpPT57fSl7XG5cdFx0dmFyIHNlbGYgPSB0aGlzO1xuXG5cdFx0dmFyIGZvcm1EYXRhID0gbmV3IEZvcm1EYXRhKCk7XG5cdFx0aWYoIWRhdGEuZmlsZWtleSl7XG5cdFx0XHRkYXRhLmZpbGVrZXkgPSBcImZpbGVcIjtcblx0XHR9XG5cblx0XHRmb3IodmFyIGZpbGUgaW4gZmlsZXMpXG5cdFx0XHRmb3JtRGF0YS5hcHBlbmQoZGF0YS5maWxla2V5ICsgXCJbXVwiLCBmaWxlKTtcblxuXHRcdGZvcih2YXIga2V5IGluIGRhdGEpe1xuXHRcdFx0Zm9ybURhdGEuYXBwZW5kKGtleSwgZGF0YVtrZXldKTtcblx0XHR9XG5cblx0XHRyZXR1cm4gbmV3IFByb21pc2UoKHJlc29sdmUsIHJlamVjdCkgPT4ge1xuXHRcdFx0dmFyIHhociA9IG5ldyBYTUxIdHRwUmVxdWVzdCgpO1xuXHRcdFx0eGhyLm9wZW4oJ1BPU1QnLCBzZWxmLmJhc2UgKyBlbmRwb2ludCwgdHJ1ZSk7XG5cdFx0XHR4aHIudXBsb2FkLm9ucHJvZ3Jlc3MgPSAoZSkgPT4ge1xuXHRcdFx0XHRwcm9ncmVzcyhlLmxvYWRlZC9lLnRvdGFsKTtcblx0XHRcdH1cblx0XHRcdHhoci5vbmxvYWQgPSBmdW5jdGlvbigpe1xuICAgIFx0XHRpZiAodGhpcy5zdGF0dXMgPT0gMjAwKSB7XG5cdFx0XHRcdFx0cmVzb2x2ZSh0aGlzLnJlc3BvbnNlKTtcblx0XHRcdFx0fSBlbHNlIHtcblx0XHRcdFx0XHRyZWplY3QodGhpcy5yZXNwb25zZSk7XG5cdFx0XHRcdH1cblx0XHRcdH1cblx0XHR9KTtcblx0fVxuXG59XG4iXSwic291cmNlUm9vdCI6Ii9zb3VyY2UvIn0=