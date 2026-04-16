FROM php:7.4-fpm as base

# Устанавливаем sqlite-клиент и расширение PDO для работы приложения с SQLite.
RUN apt-get update && \
    apt-get install -y sqlite3 libsqlite3-dev && \
    docker-php-ext-install pdo_sqlite

# Отдельный volume для персистентной базы данных.
VOLUME ["/var/www/db"]

COPY sql/schema.sql /var/www/db/schema.sql

# Инициализируем БД из schema.sql на этапе сборки образа.
RUN echo "prepare database" && \
    cat /var/www/db/schema.sql | sqlite3 /var/www/db/db.sqlite && \
    chmod 777 /var/www/db/db.sqlite && \
    rm -rf /var/www/db/schema.sql && \
    echo "database is ready"

COPY site /var/www/html