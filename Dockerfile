FROM wordpress:6.1.1

# Install system dependencies
RUN apt-get update && apt-get install -y \
    sudo \
    unzip \
    zip \
    wget

# Install WP-CLI
RUN wget https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar -O /usr/local/bin/wp && \
    chmod +x /usr/local/bin/wp

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install bash
RUN apt-get update && apt-get install -y bash

# Set bash as the default shell
SHELL ["/bin/bash", "-c"]

# Set the working directory
WORKDIR /var/www/html