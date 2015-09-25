

wget https://download.elastic.co/elasticsearch/elasticsearch/elasticsearch-1.7.2.tar.gz

tar -xvf elasticsearch-1.7.2.tar.gz
rm -rf elasticsearch
mv elasticsearch-1.7.2 elasticsearch

echo "network.bind_host: 127.0.0.1" >> elasticsearch/config/elasticsearch.yml

elasticsearch/bin/elasticsearch -d

echo "Installed. Run with \`./elasticsearch/bin/elasticsearch -d\`"

php /var/www/Minds/misc/plugins.php

rm elasticsearch-1.7.2.tar.gz
