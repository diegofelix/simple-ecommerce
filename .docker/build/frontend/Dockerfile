FROM node:21.6.1-alpine

RUN apk add bash
RUN npm install -g npm@10.4.0
RUN apk add --no-cache python3 py3-pip
RUN mkdir -p /var/www/
VOLUME [ "/var/www/" ]

USER node
WORKDIR /var/www/
