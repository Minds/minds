#!/usr/bin/env bash

php_version="7.0"
php_conf="/etc/php/$php_version/fpm/php.ini"
fpm_conf="/etc/php/$php_version/fpm/pool.d/www.conf"
cassandra_version="3.0.9"
cassandra_so="/usr/lib/php/20151012/cassandra.so"

add-apt-repository -y ppa:ondrej/php
add-apt-repository -y ppa:openjdk-r/ppa
echo "deb http://debian.datastax.com/community stable main" > /etc/apt/sources.list.d/cassandra.sources.list
curl -sS -L http://debian.datastax.com/debian/repo_key | sudo apt-key add -

apt-get update
apt-get install -y \
  nginx \
  wget \
  openssh-client \
  curl \
  git \
  php$php_version \
  php$php_version-dev \
  php$php_version-bcmath \
  php$php_version-common \
  php$php_version-ctype \
  php$php_version-fpm \
  php$php_version-mbstring \
  php$php_version-mcrypt \
  php$php_version-pdo \
  cassandra=$cassandra_version cassandra-tools=$cassandra_version \
  openjdk-8-jre \
  rabbitmq-server \
  openssl \
  php$php_version-gd \
  php$php_version-xml \
  php$php_version-curl \
  php$php_version-json \
  php$php_version-zip

# Setup nginx configs
rm -rf /etc/nginx/sites-enabled/default
cp -f /var/www/Minds/conf/nginx-site.conf /etc/nginx/sites-enabled/minds.conf
cp -f /var/www/Minds/conf/nginx.conf /etc/nginx/nginx.conf

# Tweak PHP configs

sed -i -e "s/;cgi.fix_pathinfo=1/cgi.fix_pathinfo=0/g" ${php_conf} && \
sed -i -e "s/upload_max_filesize\s*=\s*2M/upload_max_filesize = 100M/g" ${php_conf} && \
sed -i -e "s/post_max_size\s*=\s*8M/post_max_size = 100M/g" ${php_conf} && \
sed -i -e "s/variables_order = \"GPCS\"/variables_order = \"EGPCS\"/g" ${php_conf} && \
sed -i -e "s/;daemonize\s*=\s*yes/daemonize = no/g" ${fpm_conf} && \
sed -i -e "s/;catch_workers_output\s*=\s*yes/catch_workers_output = yes/g" ${fpm_conf} && \
sed -i -e "s/pm.max_children = 4/pm.max_children = 4/g" ${fpm_conf} && \
sed -i -e "s/pm.start_servers = 2/pm.start_servers = 3/g" ${fpm_conf} && \
sed -i -e "s/pm.min_spare_servers = 1/pm.min_spare_servers = 2/g" ${fpm_conf} && \
sed -i -e "s/pm.max_spare_servers = 3/pm.max_spare_servers = 4/g" ${fpm_conf} && \
sed -i -e "s/pm.max_requests = 500/pm.max_requests = 200/g" ${fpm_conf} && \
sed -i -e "s/user = nobody/user = nginx/g" ${fpm_conf} && \
sed -i -e "s/group = nobody/group = nginx/g" ${fpm_conf} && \
sed -i -e "s/;listen.mode = 0660/listen.mode = 0666/g" ${fpm_conf} && \
sed -i -e "s/;listen.owner = nobody/listen.owner = nginx/g" ${fpm_conf} && \
sed -i -e "s/;listen.group = nobody/listen.group = nginx/g" ${fpm_conf} && \
sed -i -e "s/listen = 127.0.0.1:9000/listen = \/var\/run\/php-fpm7.sock/g" ${fpm_conf}

# Cassandra options

sed -i "s/^listen_address:.*/listen_address: 127.0.0.1/" /etc/cassandra/cassandra.yaml
sed -i "s/^\(\s*\)- seeds:.*/\1- seeds: 127.0.0.1/" /etc/cassandra/cassandra.yaml
sed -i "s/^rpc_address:.*/rpc_address: 0.0.0.0/" /etc/cassandra/cassandra.yaml
sed -i "s/^# broadcast_address:.*/broadcast_address: 127.0.0.1/" /etc/cassandra/cassandra.yaml
sed -i "s/^# broadcast_rpc_address:.*/broadcast_rpc_address: 127.0.0.1/" /etc/cassandra/cassandra.yaml
sed -i 's/^start_rpc.*$/start_rpc: true/' /etc/cassandra/cassandra.yaml

# Setup cassandra driver
apt-get install -y php$php_version-dev libgmp-dev libpcre3-dev g++ make cmake libssl-dev openssl
if [ ! -f $cassandra_so ]; then
  wget -nv http://downloads.datastax.com/cpp-driver/ubuntu/14.04/dependencies/libuv/v1.8.0/libuv_1.8.0-1_amd64.deb
  wget -nv http://downloads.datastax.com/cpp-driver/ubuntu/14.04/dependencies/libuv/v1.8.0/libuv-dev_1.8.0-1_amd64.deb
  wget -nv http://downloads.datastax.com/cpp-driver/ubuntu/14.04/cassandra/v2.4.2/cassandra-cpp-driver_2.4.2-1_amd64.deb
  wget -nv http://downloads.datastax.com/cpp-driver/ubuntu/14.04/cassandra/v2.4.2/cassandra-cpp-driver-dev_2.4.2-1_amd64.deb
  dpkg -i libuv_1.8.0-1_amd64.deb
  dpkg -i libuv-dev_1.8.0-1_amd64.deb
  dpkg -i cassandra-cpp-driver_2.4.2-1_amd64.deb
  dpkg -i cassandra-cpp-driver-dev_2.4.2-1_amd64.deb
fi
pecl install cassandra-1.2.2
echo "extension=cassandra.so" > /etc/php/$php_version/mods-available/cassandra.ini
phpenmod cassandra

# Setup Mongo driver
apt-get install -y mongodb php$php_version-mongodb
phpenmod mongodb

# Setup Redis driver
apt-get install -y redis-server php$php_version-redis
phpenmod redis

# start services
service nginx restart
service php$php_version-fpm restart
service cassandra restart
service mongodb restart
service redis-server restart

# Install NodeJS
if ! dpkg --compare-versions `node --version | sed 's/^.//'` ge '6.0'; then
  curl -sL https://deb.nodesource.com/setup_6.x | sudo -E bash -
  sudo apt-get install -y nodejs build-essential
fi
npm install -g npm typescript ts-node

# Install Composer
if [ -f /usr/local/bin/composer ]; then
  composer self-update
else
  wget -nv -O composer-setup.php https://getcomposer.org/installer
  php composer-setup.php --install-dir=/usr/local/bin --filename=composer
  rm composer-setup.php
fi

cd /var/www/Minds/engine
composer install
cd -

# Additional folders
mkdir --parents --mode=0777 /tmp/minds-cache/
mkdir --parents --mode=0777 /data/

cd /var/www/Minds

if [ -f "/var/www/Minds/engine/settings.php" ]; then
  echo "Provisioning Minds…"

  php ./bin/cli.php install \
    --use-existing-settings \
    --graceful-storage-provision \
    --username=minds \
    --password=password \
    --email=minds@dev.minds.io
else
  echo "Installing Minds…"

  php ./bin/regenerateDevKeys.php;

  php ./bin/cli.php install \
    --graceful-storage-provision \
    --domain=dev.minds.io \
    --username=minds \
    --password=password \
    --email=minds@dev.minds.io \
    --private-key=/var/www/Minds/.dev/minds.pem \
    --public-key=/var/www/Minds/.dev/minds.pub
fi
