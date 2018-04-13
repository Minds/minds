#!/bin/sh

echo "PULLING SECRETS" 

mkdir /var/secure;

aws s3 cp $S3_BUCKET/settings.php /var/www/Minds/engine/settings.php
aws s3 cp $S3_BUCKET/var/secure/email-public.key /var/secure/email-public.key
aws s3 cp $S3_BUCKET/var/secure/email-private.key /var/secure/email-private.key
aws s3 cp $S3_BUCKET/apns.pem /var/secure/apns-production.pem

chmod -xr /var/secure/

echo "PULLED SECRETS";