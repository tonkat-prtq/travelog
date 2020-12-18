FROM nginx:1.18-alpine
SHELL ["/bin/ash", "-oeux", "pipefail", "-c"]

RUN apk update
COPY ./docker/nginx/default.conf /etc/nginx/conf.d/default.conf
WORKDIR /work
