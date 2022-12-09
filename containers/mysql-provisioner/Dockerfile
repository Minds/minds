FROM mysql:8.0

WORKDIR /provisioner
COPY provision-mysql.sh provision.sql /provisioner/

ENTRYPOINT ["sh", "./provision-mysql.sh"]
