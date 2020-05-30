# gumshoe
Bug tracker for SoSa

```
docker build . -t sosa/gumshoe
docker run -d -p 4706:3306 -p 4707:80 -p 4708:443 -p 4709:8090 --name "sosa-gumshoe-server" --rm -v "E:/Development/gumeshoe/src/":/var/www/html:Z -t sosa/gumshoe
docker exec -it sosa-gumshoe-server mysql -u root -e "CREATE USER IF NOT EXISTS 'root'@'%'; GRANT ALL PRIVILEGES ON *.* TO 'root'@'%'; GRANT ALL PRIVILEGES ON *.* TO 'root'@'%'; \. /var/www/html/default_schema.sql"
mv src/config/config.example.php src/config/config.php

docker exec -w /var/www/html -it sosa-gumshoe-server composer install

docker exec -it sosa-gumshoe-server bash

```

### Optimization for production
`docker exec -w /var/www/html -it sosa-gumshoe-server composer install --optimize --no-dev --classmap-authoritative`



