1. docker-compose up -d
2. get into php container
3. composer install 
4. php bin/console d:m:m
5. get into mariadb container 
6. mysql -u root -proot -e "USE forum" -e "GRANT ALL PRIVILEGES ON forum to 'username'@'%'"
