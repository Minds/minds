#!/usr/bin/env bash

php_version="7.1"
php_conf="/etc/php/$php_version/fpm/php.ini"
fpm_conf="/etc/php/$php_version/fpm/pool.d/www.conf"

add-apt-repository ppa:ondrej/php
add-apt-repository ppa:openjdk-r/ppa
echo "deb http://debian.datastax.com/community stable main" > /etc/apt/sources.list.d/cassandra.sources.list
curl -L http://debian.datastax.com/debian/repo_key | sudo apt-key add -

apt-get update
apt-get install -y \
  nginx \
  wget \
  openssh-client \
  curl \
  git \
  php$php_version \
  php$php_version-bcmath \
  php$php_version-common \
  php$php_version-ctype \
  php$php_version-fpm \
  php$php_version-mbstring \
  php$php_version-mysql \
  php$php_version-mysqlnd \
  php$php_version-mysqli \
  php$php_version-mcrypt \
  php$php_version-pdo \
  dsc22 cassandra=2.2.7 cassandra-tools=2.2.7 \
  openjdk-8-jre \
  rabbitmq-server \
  openssl
  #php$php_version-zlib \
  #php$php_version-gd \
  #php$php_version-intl \
  #php$php_version-memcached \
  #php$php_version-sqlite3 \
  #4php$php_version-pgsql \
  #php$php_version-xml \
  #php$php_version-xsl \
  #php$php_version-curl \
  #php$php_version-openssl \
  #php$php_version-iconv \
  #php$php_version-json \
  #php$php_version-phar \
  #php$php_version-soap \
  #php$php_version-dom && \

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

# start services
service php$php_version-fpm restart
service nginx restart
service cassandra restart


if [ -f "/var/www/Minds/engine/settings.php" ]
then
	echo "[Success]: Minds is already installed";
else
  sleep 10s
  php /var/www/Minds/bin/cli.php install \
    --domain=dev.minds.io \
    --username=mark \
    --password=temp123 \
    --email=mark@minds.com
fi
