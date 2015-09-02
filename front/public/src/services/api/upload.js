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
var http_1 = require('angular2/http');
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
        if (files.length > 1) {
            for (var _i = 0; _i < files.length; _i++) {
                var file = files[_i];
                formData.append(data.filekey + "[]", file);
            }
        }
        else {
            formData.append(data.filekey, files[0]);
        }
        delete data.filekey;
        for (var key in data) {
            formData.append(key, data[key]);
        }
        return new Promise(function (resolve, reject) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', self.base + endpoint, true);
            xhr.onprogress = function (e) {
                progress(e.loaded);
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

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9zZXJ2aWNlcy9hcGkvdXBsb2FkLnRzIl0sIm5hbWVzIjpbIlVwbG9hZCIsIlVwbG9hZC5jb25zdHJ1Y3RvciIsIlVwbG9hZC5wb3N0Il0sIm1hcHBpbmdzIjoiOzs7Ozs7Ozs7Ozs7OztBQUFBLHlCQUFxQyxtQkFBbUIsQ0FBQyxDQUFBO0FBQ3pELHFCQUE0QixlQUFlLENBQUMsQ0FBQTtBQUM1Qyx1QkFBcUIscUJBQXFCLENBQUMsQ0FBQTtBQUszQztJQUdDQSxnQkFBaUNBLElBQVdBO1FBQVhDLFNBQUlBLEdBQUpBLElBQUlBLENBQU9BO1FBRjVDQSxTQUFJQSxHQUFZQSxHQUFHQSxDQUFDQTtRQUNwQkEsV0FBTUEsR0FBWUEsSUFBSUEsZUFBTUEsRUFBRUEsQ0FBQ0E7SUFDZ0JBLENBQUNBO0lBS2hERCxxQkFBSUEsR0FBSkEsVUFBS0EsUUFBaUJBLEVBQUVBLEtBQXVCQSxFQUFFQSxJQUFlQSxFQUFFQSxRQUE0QkE7UUFBdEVFLHFCQUF1QkEsR0FBdkJBLFVBQXVCQTtRQUFFQSxvQkFBZUEsR0FBZkEsU0FBZUE7UUFBRUEsd0JBQTRCQSxHQUE1QkEseUJBQTJCQSxDQUFDQTtRQUM3RkEsSUFBSUEsSUFBSUEsR0FBR0EsSUFBSUEsQ0FBQ0E7UUFFaEJBLElBQUlBLFFBQVFBLEdBQUdBLElBQUlBLFFBQVFBLEVBQUVBLENBQUNBO1FBQzlCQSxFQUFFQSxDQUFBQSxDQUFDQSxDQUFDQSxJQUFJQSxDQUFDQSxPQUFPQSxDQUFDQSxDQUFBQSxDQUFDQTtZQUNqQkEsSUFBSUEsQ0FBQ0EsT0FBT0EsR0FBR0EsTUFBTUEsQ0FBQ0E7UUFDdkJBLENBQUNBO1FBRURBLEVBQUVBLENBQUFBLENBQUNBLEtBQUtBLENBQUNBLE1BQU1BLEdBQUdBLENBQUNBLENBQUNBLENBQUFBLENBQUNBO1lBQ3BCQSxHQUFHQSxDQUFBQSxDQUFhQSxVQUFLQSxFQUFqQkEsaUJBQVFBLEVBQVJBLElBQWlCQSxDQUFDQTtnQkFBbEJBLElBQUlBLElBQUlBLEdBQUlBLEtBQUtBLElBQVRBO2dCQUNYQSxRQUFRQSxDQUFDQSxNQUFNQSxDQUFDQSxJQUFJQSxDQUFDQSxPQUFPQSxHQUFHQSxJQUFJQSxFQUFFQSxJQUFJQSxDQUFDQSxDQUFDQTthQUFBQTtRQUM3Q0EsQ0FBQ0E7UUFBQ0EsSUFBSUEsQ0FBQ0EsQ0FBQ0E7WUFDUEEsUUFBUUEsQ0FBQ0EsTUFBTUEsQ0FBQ0EsSUFBSUEsQ0FBQ0EsT0FBT0EsRUFBRUEsS0FBS0EsQ0FBQ0EsQ0FBQ0EsQ0FBQ0EsQ0FBQ0EsQ0FBQ0E7UUFDekNBLENBQUNBO1FBRURBLE9BQU9BLElBQUlBLENBQUNBLE9BQU9BLENBQUNBO1FBRXBCQSxHQUFHQSxDQUFBQSxDQUFDQSxHQUFHQSxDQUFDQSxHQUFHQSxJQUFJQSxJQUFJQSxDQUFDQSxDQUFBQSxDQUFDQTtZQUNwQkEsUUFBUUEsQ0FBQ0EsTUFBTUEsQ0FBQ0EsR0FBR0EsRUFBRUEsSUFBSUEsQ0FBQ0EsR0FBR0EsQ0FBQ0EsQ0FBQ0EsQ0FBQ0E7UUFDakNBLENBQUNBO1FBRURBLE1BQU1BLENBQUNBLElBQUlBLE9BQU9BLENBQUNBLFVBQUNBLE9BQU9BLEVBQUVBLE1BQU1BO1lBQ2xDQSxJQUFJQSxHQUFHQSxHQUFHQSxJQUFJQSxjQUFjQSxFQUFFQSxDQUFDQTtZQUMvQkEsR0FBR0EsQ0FBQ0EsSUFBSUEsQ0FBQ0EsTUFBTUEsRUFBRUEsSUFBSUEsQ0FBQ0EsSUFBSUEsR0FBR0EsUUFBUUEsRUFBRUEsSUFBSUEsQ0FBQ0EsQ0FBQ0E7WUFDN0NBLEdBQUdBLENBQUNBLFVBQVVBLEdBQUdBLFVBQVNBLENBQUNBO2dCQUMxQixRQUFRLENBQUMsQ0FBQyxDQUFDLE1BQU0sQ0FBQyxDQUFDO1lBQ3BCLENBQUMsQ0FBQUE7WUFDREEsR0FBR0EsQ0FBQ0EsTUFBTUEsR0FBR0E7Z0JBQ1YsRUFBRSxDQUFDLENBQUMsSUFBSSxDQUFDLE1BQU0sSUFBSSxHQUFHLENBQUMsQ0FBQyxDQUFDO29CQUMxQixPQUFPLENBQUMsSUFBSSxDQUFDLEtBQUssQ0FBQyxJQUFJLENBQUMsUUFBUSxDQUFDLENBQUMsQ0FBQztnQkFDcEMsQ0FBQztnQkFBQyxJQUFJLENBQUMsQ0FBQztvQkFDUCxNQUFNLENBQUMsSUFBSSxDQUFDLEtBQUssQ0FBQyxJQUFJLENBQUMsUUFBUSxDQUFDLENBQUMsQ0FBQztnQkFDbkMsQ0FBQztZQUNGLENBQUMsQ0FBQUE7WUFDREEsR0FBR0EsQ0FBQ0Esa0JBQWtCQSxHQUFHQTtnQkFDeEIsT0FBTyxDQUFDLEdBQUcsQ0FBQyxJQUFJLENBQUMsQ0FBQztZQUNuQixDQUFDLENBQUFBO1lBQ0RBLEdBQUdBLENBQUNBLElBQUlBLENBQUNBLFFBQVFBLENBQUNBLENBQUNBO1FBQ3BCQSxDQUFDQSxDQUFDQSxDQUFDQTtJQUNKQSxDQUFDQTtJQS9DRkY7UUFHYUEsV0FBQ0EsaUJBQU1BLENBQUNBLFdBQUlBLENBQUNBLENBQUFBOztlQThDekJBO0lBQURBLGFBQUNBO0FBQURBLENBakRBLEFBaURDQSxJQUFBO0FBakRZLGNBQU0sU0FpRGxCLENBQUEiLCJmaWxlIjoic3JjL3NlcnZpY2VzL2FwaS91cGxvYWQuanMiLCJzb3VyY2VzQ29udGVudCI6WyJpbXBvcnQge0luamVjdCwgSW5qZWN0b3IsIGJpbmR9IGZyb20gJ2FuZ3VsYXIyL2FuZ3VsYXIyJztcbmltcG9ydCB7SHR0cCwgSGVhZGVyc30gZnJvbSAnYW5ndWxhcjIvaHR0cCc7XG5pbXBvcnQge0Nvb2tpZX0gZnJvbSAnc3JjL3NlcnZpY2VzL2Nvb2tpZSc7XG5cbi8qKlxuICogQVBJIENsYXNzXG4gKi9cbmV4cG9ydCBjbGFzcyBVcGxvYWQgIHtcblx0YmFzZSA6IHN0cmluZyA9IFwiL1wiO1xuXHRjb29raWUgOiBDb29raWUgPSBuZXcgQ29va2llKCk7XG5cdGNvbnN0cnVjdG9yKEBJbmplY3QoSHR0cCkgcHVibGljIGh0dHAgOiBIdHRwKXsgfVxuXG5cdC8qKlxuXHQgKiBSZXR1cm4gYSBQT1NUIHJlcXVlc3Rcblx0ICovXG5cdHBvc3QoZW5kcG9pbnQgOiBzdHJpbmcsIGZpbGVzIDogQXJyYXk8YW55PiA9IFtdLCBkYXRhIDogYW55ID0ge30sIHByb2dyZXNzIDogRnVuY3Rpb24gPSAoKT0+e30pe1xuXHRcdHZhciBzZWxmID0gdGhpcztcblxuXHRcdHZhciBmb3JtRGF0YSA9IG5ldyBGb3JtRGF0YSgpO1xuXHRcdGlmKCFkYXRhLmZpbGVrZXkpe1xuXHRcdFx0ZGF0YS5maWxla2V5ID0gXCJmaWxlXCI7XG5cdFx0fVxuXG5cdFx0aWYoZmlsZXMubGVuZ3RoID4gMSl7XG5cdFx0XHRmb3IodmFyIGZpbGUgb2YgZmlsZXMpXG5cdFx0XHRcdGZvcm1EYXRhLmFwcGVuZChkYXRhLmZpbGVrZXkgKyBcIltdXCIsIGZpbGUpO1xuXHRcdH0gZWxzZSB7XG5cdFx0XHRmb3JtRGF0YS5hcHBlbmQoZGF0YS5maWxla2V5LCBmaWxlc1swXSk7XG5cdFx0fVxuXG5cdFx0ZGVsZXRlIGRhdGEuZmlsZWtleTtcblxuXHRcdGZvcih2YXIga2V5IGluIGRhdGEpe1xuXHRcdFx0Zm9ybURhdGEuYXBwZW5kKGtleSwgZGF0YVtrZXldKTtcblx0XHR9XG5cblx0XHRyZXR1cm4gbmV3IFByb21pc2UoKHJlc29sdmUsIHJlamVjdCkgPT4ge1xuXHRcdFx0dmFyIHhociA9IG5ldyBYTUxIdHRwUmVxdWVzdCgpO1xuXHRcdFx0eGhyLm9wZW4oJ1BPU1QnLCBzZWxmLmJhc2UgKyBlbmRwb2ludCwgdHJ1ZSk7XG5cdFx0XHR4aHIub25wcm9ncmVzcyA9IGZ1bmN0aW9uKGUpe1xuXHRcdFx0XHRwcm9ncmVzcyhlLmxvYWRlZCk7XG5cdFx0XHR9XG5cdFx0XHR4aHIub25sb2FkID0gZnVuY3Rpb24oKXtcbiAgICBcdFx0aWYgKHRoaXMuc3RhdHVzID09IDIwMCkge1xuXHRcdFx0XHRcdHJlc29sdmUoSlNPTi5wYXJzZSh0aGlzLnJlc3BvbnNlKSk7XG5cdFx0XHRcdH0gZWxzZSB7XG5cdFx0XHRcdFx0cmVqZWN0KEpTT04ucGFyc2UodGhpcy5yZXNwb25zZSkpO1xuXHRcdFx0XHR9XG5cdFx0XHR9XG5cdFx0XHR4aHIub25yZWFkeXN0YXRlY2hhbmdlID0gZnVuY3Rpb24oKXtcblx0XHRcdFx0Y29uc29sZS5sb2codGhpcyk7XG5cdFx0XHR9XG5cdFx0XHR4aHIuc2VuZChmb3JtRGF0YSk7XG5cdFx0fSk7XG5cdH1cblxufVxuIl0sInNvdXJjZVJvb3QiOiIvc291cmNlLyJ9