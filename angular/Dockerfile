# Pirmais posms - ar node būvē angular aplikāciju:
FROM node:lts-alpine as npmbuild
WORKDIR /usr/src/angular
COPY package*.json ./
RUN npm install

COPY . ./
RUN npm run build --prod

# Otrais posms - nginx servē uzbūvēto aplikāciju, (kā arī konfigurācijā
# servē PHP requestus uz symfony(php-fpm) konteineru)
FROM nginx:alpine

WORKDIR /var/www
COPY --from=npmbuild /usr/src/angular/dist/angular/ .
COPY ./docker/nginx/default.conf /etc/nginx/conf.d/default.conf
EXPOSE 80
