FROM nginx:1.13-alpine

RUN rm /etc/nginx/conf.d/default.conf
COPY nginx.conf /etc/nginx/nginx.conf
COPY ./dev.conf /etc/nginx/conf.d/dev.conf
