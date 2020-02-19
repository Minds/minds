map $http_upgrade $connection_upgrade {
    default upgrade;
    '' close;
}

server {
    resolver ${DOCKER_RESOLVER} ipv6=off;

    listen 80;
    listen [::]:80 default ipv6only=on;
    listen 8080;
    root /var/www/Minds/front/dist/en;

    index index.php index.html;
    server_name _;

    error_log /dev/stdout warn;
    access_log off;

    if ($host = 'minds.com' ) {
        rewrite  ^/(.*)$  https://www.minds.com/$1  permanent;
    }

    #if ($http_x_forwarded_proto != "https") {
    #    rewrite ^(.*)$ https://$host$REQUEST_URI permanent;
    #}

    sendfile off;

    location / {
        set $upstream http://${UPSTREAM_ENDPOINT};

        port_in_redirect off;

        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header Host localhost:8080;
        proxy_set_header X-NginX-Proxy true;
        proxy_pass $upstream;
        proxy_redirect off;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";
        proxy_redirect off;
        proxy_set_header X-Forwarded-Proto $scheme;
    }

    location ~ ^(/api|/fs|/icon|/carousel) {
        add_header 'Access-Control-Allow-Origin' "$http_origin";
        add_header 'Access-Control-Allow-Credentials' 'true';
        add_header 'Access-Control-Allow-Methods' 'GET, POST, PUT, DELETE, OPTIONS';
        add_header 'Access-Control-Allow-Headers' 'Accept,Authorization,Cache-Control,Content-Type,DNT,If-Modified-Since,Keep-Alive,Origin,User-Agent,X-Mx-ReqToken,X-Requested-With,X-No-Cache';

        rewrite ^(.+)$ /index.php last;
    }

    location ~* \.(png|jpg|jpeg|svg|gif|ico|js|css)$ {
        expires 1y;
        log_not_found off;
    }

    location ~* \.(woff|woff2|ttf|eot) {
        add_header 'Access-Control-Allow-Origin' *;
    }

    location ~ (composer.json|composer.lock|.travis.yml){
        deny all;
    }

    location @rewrite {
        rewrite ^(.+)$ /index.php last;
    }

    # Do not cache by default
    set $no_cache 1;

    # Cache GET requests by default
    if ($request_method = GET){
        set $no_cache 0;
    }

    # Do not cache if we have a cookie set
    if ($http_cookie ~ "(mindsperm)" ){
        set $no_cache 1;
    }

    # Do not cache if we have a logged in cookie
    if ($cookie_minds_sess) {
        set $no_cache 1;
    }

    # pass the PHP scripts to FastCGI server listening on socket
    location ~ \.php$ {
        add_header X-Cache $upstream_cache_status;
        add_header No-Cache $no_cache;
        add_header X-No-Cache $no_cache;

        fastcgi_cache fastcgicache;
        fastcgi_cache_bypass $no_cache;
        fastcgi_no_cache $no_cache;

        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass php-fpm:9000;
        fastcgi_index index.php;

        fastcgi_buffers 64 32k; 
        fastcgi_buffer_size 64k;

        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME /var/www/Minds/engine/index.php;
        fastcgi_param PATH_INFO $fastcgi_path_info;
    }

    location ~* \.(jpg|jpeg|gif|png|css|js|ico|xml|woff|woff2|ttf|eot|svg)$ {
        expires           5d;
    }

    location ~ /\. {
        log_not_found off;
        deny all;
    }

}
