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
var http_1 = require('http/http');
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
            xhr.onprogress = function (e) {
                progress(e.loaded / e.total);
            };
            xhr.onload = function () {
                if (this.status == 200) {
                    resolve(JSON.parse(this.response));
                }
                else {
                    reject(JSON.parse(this.response));
                }
            };
            xhr.onreadystatechange = function () {
                console.log(this);
            };
            xhr.send(formData);
        });
    };
    Upload = __decorate([
        __param(0, angular2_1.Inject(http_1.Http)), 
        __metadata('design:paramtypes', [http_1.Http])
    ], Upload);
    return Upload;
})();
exports.Upload = Upload;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9zZXJ2aWNlcy9hcGkvdXBsb2FkLnRzIl0sIm5hbWVzIjpbIlVwbG9hZCIsIlVwbG9hZC5jb25zdHJ1Y3RvciIsIlVwbG9hZC5wb3N0Il0sIm1hcHBpbmdzIjoiOzs7Ozs7Ozs7Ozs7OztBQUFBLHlCQUFxQyxtQkFBbUIsQ0FBQyxDQUFBO0FBQ3pELHFCQUE0QixXQUFXLENBQUMsQ0FBQTtBQUN4Qyx1QkFBcUIscUJBQXFCLENBQUMsQ0FBQTtBQUszQztJQUdDQSxnQkFBaUNBLElBQVdBO1FBQVhDLFNBQUlBLEdBQUpBLElBQUlBLENBQU9BO1FBRjVDQSxTQUFJQSxHQUFZQSxHQUFHQSxDQUFDQTtRQUNwQkEsV0FBTUEsR0FBWUEsSUFBSUEsZUFBTUEsRUFBRUEsQ0FBQ0E7SUFDZ0JBLENBQUNBO0lBS2hERCxxQkFBSUEsR0FBSkEsVUFBS0EsUUFBaUJBLEVBQUVBLEtBQXVCQSxFQUFFQSxJQUFlQSxFQUFFQSxRQUE0QkE7UUFBdEVFLHFCQUF1QkEsR0FBdkJBLFVBQXVCQTtRQUFFQSxvQkFBZUEsR0FBZkEsU0FBZUE7UUFBRUEsd0JBQTRCQSxHQUE1QkEseUJBQTJCQSxDQUFDQTtRQUM3RkEsSUFBSUEsSUFBSUEsR0FBR0EsSUFBSUEsQ0FBQ0E7UUFFaEJBLElBQUlBLFFBQVFBLEdBQUdBLElBQUlBLFFBQVFBLEVBQUVBLENBQUNBO1FBQzlCQSxFQUFFQSxDQUFBQSxDQUFDQSxDQUFDQSxJQUFJQSxDQUFDQSxPQUFPQSxDQUFDQSxDQUFBQSxDQUFDQTtZQUNqQkEsSUFBSUEsQ0FBQ0EsT0FBT0EsR0FBR0EsTUFBTUEsQ0FBQ0E7UUFDdkJBLENBQUNBO1FBRURBLEdBQUdBLENBQUFBLENBQUNBLEdBQUdBLENBQUNBLElBQUlBLElBQUlBLEtBQUtBLENBQUNBO1lBQ3JCQSxRQUFRQSxDQUFDQSxNQUFNQSxDQUFDQSxJQUFJQSxDQUFDQSxPQUFPQSxHQUFHQSxJQUFJQSxFQUFFQSxJQUFJQSxDQUFDQSxDQUFDQTtRQUU1Q0EsR0FBR0EsQ0FBQUEsQ0FBQ0EsR0FBR0EsQ0FBQ0EsR0FBR0EsSUFBSUEsSUFBSUEsQ0FBQ0EsQ0FBQUEsQ0FBQ0E7WUFDcEJBLFFBQVFBLENBQUNBLE1BQU1BLENBQUNBLEdBQUdBLEVBQUVBLElBQUlBLENBQUNBLEdBQUdBLENBQUNBLENBQUNBLENBQUNBO1FBQ2pDQSxDQUFDQTtRQUVEQSxNQUFNQSxDQUFDQSxJQUFJQSxPQUFPQSxDQUFDQSxVQUFDQSxPQUFPQSxFQUFFQSxNQUFNQTtZQUNsQ0EsSUFBSUEsR0FBR0EsR0FBR0EsSUFBSUEsY0FBY0EsRUFBRUEsQ0FBQ0E7WUFDL0JBLEdBQUdBLENBQUNBLElBQUlBLENBQUNBLE1BQU1BLEVBQUVBLElBQUlBLENBQUNBLElBQUlBLEdBQUdBLFFBQVFBLEVBQUVBLElBQUlBLENBQUNBLENBQUNBO1lBQzdDQSxHQUFHQSxDQUFDQSxVQUFVQSxHQUFHQSxVQUFTQSxDQUFDQTtnQkFDMUIsUUFBUSxDQUFDLENBQUMsQ0FBQyxNQUFNLEdBQUMsQ0FBQyxDQUFDLEtBQUssQ0FBQyxDQUFDO1lBQzVCLENBQUMsQ0FBQUE7WUFDREEsR0FBR0EsQ0FBQ0EsTUFBTUEsR0FBR0E7Z0JBQ1YsRUFBRSxDQUFDLENBQUMsSUFBSSxDQUFDLE1BQU0sSUFBSSxHQUFHLENBQUMsQ0FBQyxDQUFDO29CQUMxQixPQUFPLENBQUMsSUFBSSxDQUFDLEtBQUssQ0FBQyxJQUFJLENBQUMsUUFBUSxDQUFDLENBQUMsQ0FBQztnQkFDcEMsQ0FBQztnQkFBQyxJQUFJLENBQUMsQ0FBQztvQkFDUCxNQUFNLENBQUMsSUFBSSxDQUFDLEtBQUssQ0FBQyxJQUFJLENBQUMsUUFBUSxDQUFDLENBQUMsQ0FBQztnQkFDbkMsQ0FBQztZQUNGLENBQUMsQ0FBQUE7WUFDREEsR0FBR0EsQ0FBQ0Esa0JBQWtCQSxHQUFHQTtnQkFDeEIsT0FBTyxDQUFDLEdBQUcsQ0FBQyxJQUFJLENBQUMsQ0FBQztZQUNuQixDQUFDLENBQUFBO1lBQ0RBLEdBQUdBLENBQUNBLElBQUlBLENBQUNBLFFBQVFBLENBQUNBLENBQUNBO1FBQ3BCQSxDQUFDQSxDQUFDQSxDQUFDQTtJQUNKQSxDQUFDQTtJQXpDRkY7UUFHYUEsV0FBQ0EsaUJBQU1BLENBQUNBLFdBQUlBLENBQUNBLENBQUFBOztlQXdDekJBO0lBQURBLGFBQUNBO0FBQURBLENBM0NBLEFBMkNDQSxJQUFBO0FBM0NZLGNBQU0sU0EyQ2xCLENBQUEiLCJmaWxlIjoic3JjL3NlcnZpY2VzL2FwaS91cGxvYWQuanMiLCJzb3VyY2VzQ29udGVudCI6WyJpbXBvcnQge0luamVjdCwgSW5qZWN0b3IsIGJpbmR9IGZyb20gJ2FuZ3VsYXIyL2FuZ3VsYXIyJztcbmltcG9ydCB7SHR0cCwgSGVhZGVyc30gZnJvbSAnaHR0cC9odHRwJztcbmltcG9ydCB7Q29va2llfSBmcm9tICdzcmMvc2VydmljZXMvY29va2llJztcblxuLyoqXG4gKiBBUEkgQ2xhc3NcbiAqL1xuZXhwb3J0IGNsYXNzIFVwbG9hZCAge1xuXHRiYXNlIDogc3RyaW5nID0gXCIvXCI7XG5cdGNvb2tpZSA6IENvb2tpZSA9IG5ldyBDb29raWUoKTtcblx0Y29uc3RydWN0b3IoQEluamVjdChIdHRwKSBwdWJsaWMgaHR0cCA6IEh0dHApeyB9XG5cblx0LyoqXG5cdCAqIFJldHVybiBhIFBPU1QgcmVxdWVzdFxuXHQgKi9cblx0cG9zdChlbmRwb2ludCA6IHN0cmluZywgZmlsZXMgOiBBcnJheTxhbnk+ID0gW10sIGRhdGEgOiBhbnkgPSB7fSwgcHJvZ3Jlc3MgOiBGdW5jdGlvbiA9ICgpPT57fSl7XG5cdFx0dmFyIHNlbGYgPSB0aGlzO1xuXG5cdFx0dmFyIGZvcm1EYXRhID0gbmV3IEZvcm1EYXRhKCk7XG5cdFx0aWYoIWRhdGEuZmlsZWtleSl7XG5cdFx0XHRkYXRhLmZpbGVrZXkgPSBcImZpbGVcIjtcblx0XHR9XG5cblx0XHRmb3IodmFyIGZpbGUgaW4gZmlsZXMpXG5cdFx0XHRmb3JtRGF0YS5hcHBlbmQoZGF0YS5maWxla2V5ICsgXCJbXVwiLCBmaWxlKTtcblxuXHRcdGZvcih2YXIga2V5IGluIGRhdGEpe1xuXHRcdFx0Zm9ybURhdGEuYXBwZW5kKGtleSwgZGF0YVtrZXldKTtcblx0XHR9XG5cblx0XHRyZXR1cm4gbmV3IFByb21pc2UoKHJlc29sdmUsIHJlamVjdCkgPT4ge1xuXHRcdFx0dmFyIHhociA9IG5ldyBYTUxIdHRwUmVxdWVzdCgpO1xuXHRcdFx0eGhyLm9wZW4oJ1BPU1QnLCBzZWxmLmJhc2UgKyBlbmRwb2ludCwgdHJ1ZSk7XG5cdFx0XHR4aHIub25wcm9ncmVzcyA9IGZ1bmN0aW9uKGUpe1xuXHRcdFx0XHRwcm9ncmVzcyhlLmxvYWRlZC9lLnRvdGFsKTtcblx0XHRcdH1cblx0XHRcdHhoci5vbmxvYWQgPSBmdW5jdGlvbigpe1xuICAgIFx0XHRpZiAodGhpcy5zdGF0dXMgPT0gMjAwKSB7XG5cdFx0XHRcdFx0cmVzb2x2ZShKU09OLnBhcnNlKHRoaXMucmVzcG9uc2UpKTtcblx0XHRcdFx0fSBlbHNlIHtcblx0XHRcdFx0XHRyZWplY3QoSlNPTi5wYXJzZSh0aGlzLnJlc3BvbnNlKSk7XG5cdFx0XHRcdH1cblx0XHRcdH1cblx0XHRcdHhoci5vbnJlYWR5c3RhdGVjaGFuZ2UgPSBmdW5jdGlvbigpe1xuXHRcdFx0XHRjb25zb2xlLmxvZyh0aGlzKTtcblx0XHRcdH1cblx0XHRcdHhoci5zZW5kKGZvcm1EYXRhKTtcblx0XHR9KTtcblx0fVxuXG59XG4iXSwic291cmNlUm9vdCI6Ii9zb3VyY2UvIn0=