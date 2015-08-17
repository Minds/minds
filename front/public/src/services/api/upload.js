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
        __param(0, angular2_1.Inject(http_1.Http)), 
        __metadata('design:paramtypes', [http_1.Http])
    ], Upload);
    return Upload;
})();
exports.Upload = Upload;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9zZXJ2aWNlcy9hcGkvdXBsb2FkLnRzIl0sIm5hbWVzIjpbIlVwbG9hZCIsIlVwbG9hZC5jb25zdHJ1Y3RvciIsIlVwbG9hZC5wb3N0Il0sIm1hcHBpbmdzIjoiOzs7Ozs7Ozs7Ozs7OztBQUFBLHlCQUFxQyxtQkFBbUIsQ0FBQyxDQUFBO0FBQ3pELHFCQUE0QixXQUFXLENBQUMsQ0FBQTtBQUN4Qyx1QkFBcUIscUJBQXFCLENBQUMsQ0FBQTtBQUszQztJQUdDQSxnQkFBaUNBLElBQVdBO1FBQVhDLFNBQUlBLEdBQUpBLElBQUlBLENBQU9BO1FBRjVDQSxTQUFJQSxHQUFZQSxHQUFHQSxDQUFDQTtRQUNwQkEsV0FBTUEsR0FBWUEsSUFBSUEsZUFBTUEsRUFBRUEsQ0FBQ0E7SUFDZ0JBLENBQUNBO0lBS2hERCxxQkFBSUEsR0FBSkEsVUFBS0EsUUFBaUJBLEVBQUVBLEtBQXVCQSxFQUFFQSxJQUFlQSxFQUFFQSxRQUE0QkE7UUFBdEVFLHFCQUF1QkEsR0FBdkJBLFVBQXVCQTtRQUFFQSxvQkFBZUEsR0FBZkEsU0FBZUE7UUFBRUEsd0JBQTRCQSxHQUE1QkEseUJBQTJCQSxDQUFDQTtRQUM3RkEsSUFBSUEsSUFBSUEsR0FBR0EsSUFBSUEsQ0FBQ0E7UUFFaEJBLElBQUlBLFFBQVFBLEdBQUdBLElBQUlBLFFBQVFBLEVBQUVBLENBQUNBO1FBQzlCQSxFQUFFQSxDQUFBQSxDQUFDQSxDQUFDQSxJQUFJQSxDQUFDQSxPQUFPQSxDQUFDQSxDQUFBQSxDQUFDQTtZQUNqQkEsSUFBSUEsQ0FBQ0EsT0FBT0EsR0FBR0EsTUFBTUEsQ0FBQ0E7UUFDdkJBLENBQUNBO1FBRURBLEdBQUdBLENBQUFBLENBQUNBLEdBQUdBLENBQUNBLElBQUlBLElBQUlBLEtBQUtBLENBQUNBO1lBQ3JCQSxRQUFRQSxDQUFDQSxNQUFNQSxDQUFDQSxJQUFJQSxDQUFDQSxPQUFPQSxHQUFHQSxJQUFJQSxFQUFFQSxJQUFJQSxDQUFDQSxDQUFDQTtRQUU1Q0EsR0FBR0EsQ0FBQUEsQ0FBQ0EsR0FBR0EsQ0FBQ0EsR0FBR0EsSUFBSUEsSUFBSUEsQ0FBQ0EsQ0FBQUEsQ0FBQ0E7WUFDcEJBLFFBQVFBLENBQUNBLE1BQU1BLENBQUNBLEdBQUdBLEVBQUVBLElBQUlBLENBQUNBLEdBQUdBLENBQUNBLENBQUNBLENBQUNBO1FBQ2pDQSxDQUFDQTtRQUVEQSxNQUFNQSxDQUFDQSxJQUFJQSxPQUFPQSxDQUFDQSxVQUFDQSxPQUFPQSxFQUFFQSxNQUFNQTtZQUNsQ0EsSUFBSUEsR0FBR0EsR0FBR0EsSUFBSUEsY0FBY0EsRUFBRUEsQ0FBQ0E7WUFDL0JBLEdBQUdBLENBQUNBLElBQUlBLENBQUNBLE1BQU1BLEVBQUVBLElBQUlBLENBQUNBLElBQUlBLEdBQUdBLFFBQVFBLEVBQUVBLElBQUlBLENBQUNBLENBQUNBO1lBQzdDQSxHQUFHQSxDQUFDQSxNQUFNQSxDQUFDQSxVQUFVQSxHQUFHQSxVQUFDQSxDQUFDQTtnQkFDekJBLFFBQVFBLENBQUNBLENBQUNBLENBQUNBLE1BQU1BLEdBQUNBLENBQUNBLENBQUNBLEtBQUtBLENBQUNBLENBQUNBO1lBQzVCQSxDQUFDQSxDQUFBQTtZQUNEQSxHQUFHQSxDQUFDQSxNQUFNQSxHQUFHQTtnQkFDVixFQUFFLENBQUMsQ0FBQyxJQUFJLENBQUMsTUFBTSxJQUFJLEdBQUcsQ0FBQyxDQUFDLENBQUM7b0JBQzFCLE9BQU8sQ0FBQyxJQUFJLENBQUMsUUFBUSxDQUFDLENBQUM7Z0JBQ3hCLENBQUM7Z0JBQUMsSUFBSSxDQUFDLENBQUM7b0JBQ1AsTUFBTSxDQUFDLElBQUksQ0FBQyxRQUFRLENBQUMsQ0FBQztnQkFDdkIsQ0FBQztZQUNGLENBQUMsQ0FBQUE7UUFDRkEsQ0FBQ0EsQ0FBQ0EsQ0FBQ0E7SUFDSkEsQ0FBQ0E7SUFyQ0ZGO1FBR2FBLFdBQUNBLGlCQUFNQSxDQUFDQSxXQUFJQSxDQUFDQSxDQUFBQTs7ZUFvQ3pCQTtJQUFEQSxhQUFDQTtBQUFEQSxDQXZDQSxBQXVDQ0EsSUFBQTtBQXZDWSxjQUFNLFNBdUNsQixDQUFBIiwiZmlsZSI6InNyYy9zZXJ2aWNlcy9hcGkvdXBsb2FkLmpzIiwic291cmNlc0NvbnRlbnQiOlsiaW1wb3J0IHtJbmplY3QsIEluamVjdG9yLCBiaW5kfSBmcm9tICdhbmd1bGFyMi9hbmd1bGFyMic7XG5pbXBvcnQge0h0dHAsIEhlYWRlcnN9IGZyb20gJ2h0dHAvaHR0cCc7XG5pbXBvcnQge0Nvb2tpZX0gZnJvbSAnc3JjL3NlcnZpY2VzL2Nvb2tpZSc7XG5cbi8qKlxuICogQVBJIENsYXNzXG4gKi9cbmV4cG9ydCBjbGFzcyBVcGxvYWQgIHtcblx0YmFzZSA6IHN0cmluZyA9IFwiL1wiO1xuXHRjb29raWUgOiBDb29raWUgPSBuZXcgQ29va2llKCk7XG5cdGNvbnN0cnVjdG9yKEBJbmplY3QoSHR0cCkgcHVibGljIGh0dHAgOiBIdHRwKXsgfVxuXG5cdC8qKlxuXHQgKiBSZXR1cm4gYSBQT1NUIHJlcXVlc3Rcblx0ICovXG5cdHBvc3QoZW5kcG9pbnQgOiBzdHJpbmcsIGZpbGVzIDogQXJyYXk8YW55PiA9IFtdLCBkYXRhIDogYW55ID0ge30sIHByb2dyZXNzIDogRnVuY3Rpb24gPSAoKT0+e30pe1xuXHRcdHZhciBzZWxmID0gdGhpcztcblxuXHRcdHZhciBmb3JtRGF0YSA9IG5ldyBGb3JtRGF0YSgpO1xuXHRcdGlmKCFkYXRhLmZpbGVrZXkpe1xuXHRcdFx0ZGF0YS5maWxla2V5ID0gXCJmaWxlXCI7XG5cdFx0fVxuXG5cdFx0Zm9yKHZhciBmaWxlIGluIGZpbGVzKVxuXHRcdFx0Zm9ybURhdGEuYXBwZW5kKGRhdGEuZmlsZWtleSArIFwiW11cIiwgZmlsZSk7XG5cblx0XHRmb3IodmFyIGtleSBpbiBkYXRhKXtcblx0XHRcdGZvcm1EYXRhLmFwcGVuZChrZXksIGRhdGFba2V5XSk7XG5cdFx0fVxuXG5cdFx0cmV0dXJuIG5ldyBQcm9taXNlKChyZXNvbHZlLCByZWplY3QpID0+IHtcblx0XHRcdHZhciB4aHIgPSBuZXcgWE1MSHR0cFJlcXVlc3QoKTtcblx0XHRcdHhoci5vcGVuKCdQT1NUJywgc2VsZi5iYXNlICsgZW5kcG9pbnQsIHRydWUpO1xuXHRcdFx0eGhyLnVwbG9hZC5vbnByb2dyZXNzID0gKGUpID0+IHtcblx0XHRcdFx0cHJvZ3Jlc3MoZS5sb2FkZWQvZS50b3RhbCk7XG5cdFx0XHR9XG5cdFx0XHR4aHIub25sb2FkID0gZnVuY3Rpb24oKXtcbiAgICBcdFx0aWYgKHRoaXMuc3RhdHVzID09IDIwMCkge1xuXHRcdFx0XHRcdHJlc29sdmUodGhpcy5yZXNwb25zZSk7XG5cdFx0XHRcdH0gZWxzZSB7XG5cdFx0XHRcdFx0cmVqZWN0KHRoaXMucmVzcG9uc2UpO1xuXHRcdFx0XHR9XG5cdFx0XHR9XG5cdFx0fSk7XG5cdH1cblxufVxuIl0sInNvdXJjZVJvb3QiOiIvc291cmNlLyJ9