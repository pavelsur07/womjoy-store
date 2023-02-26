FROM node:16-alpine AS node-builder

WORKDIR /app

COPY ./package.json ./yarn.lock ./

RUN yarn install && npm rebuild node-sass

COPY ./ ./

RUN npm run build

#############################################################

FROM nginx:1.23-alpine

RUN apk add --no-cache curl

COPY ./docker/common/nginx/conf.d /etc/nginx/conf.d

WORKDIR /app

COPY ./public ./public
COPY --from=node-builder /app/public/build ./public/build

HEALTHCHECK --interval=5s --timeout=3s --start-period=1s CMD curl --fail http://127.0.0.1/health || exit 1
