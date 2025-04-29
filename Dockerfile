FROM php:8.2-cli

# Instala dependências do Laravel e cliente MySQL
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    git \
    npm \
    libzip-dev \
    default-mysql-client \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl

# Instala o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Instala Node.js (opcional, se você quiser ter dentro do container Laravel também)
RUN curl -sL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs

# Define o diretório de trabalho
WORKDIR /var/www

# Copia arquivos da aplicação
COPY . .

# Instala dependências PHP
RUN composer install

# Instala dependências frontend (opcional se seu vite estiver separado)
RUN npm install

# Inicia o servidor Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
