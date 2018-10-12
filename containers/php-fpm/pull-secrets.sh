#!/bin/sh

echo "PULLING SECRETS" 

mkdir /var/secure;

aws s3 cp $S3_BUCKET/settings.php /var/www/Minds/engine/settings.php
aws s3 cp $S3_BUCKET/var/secure/email-public.key /var/secure/email-public.key
aws s3 cp $S3_BUCKET/var/secure/email-private.key /var/secure/email-private.key

# OAuth
aws s3 cp $S3_BUCKET/var/secure/oauth-priv.key /var/secure/oauth-priv.key
aws s3 cp $S3_BUCKET/var/secure/oauth-pub.key /var/secure/oauth-pub.key

# Sessions
aws s3 cp $S3_BUCKET/var/secure/sessions-priv.key /var/secure/sessions-priv.key
aws s3 cp $S3_BUCKET/var/secure/sessions-pub.key /var/secure/sessions-pub.key

aws s3 cp $S3_BUCKET/var/secure/google.sheets.key.json /var/secure/google.sheets.key.json

chmod -xr /var/secure/

echo "PULLED SECRETS";
