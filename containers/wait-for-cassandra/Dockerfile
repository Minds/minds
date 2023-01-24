FROM cassandra:4.0.7

WORKDIR /opt
COPY wait-for-cassandra.sh /opt/

ENTRYPOINT ["sh", "./wait-for-cassandra.sh"]
