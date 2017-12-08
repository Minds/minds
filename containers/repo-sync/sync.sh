#!/bin/sh

echo "SYNCING: $S3_BUCKET" 

aws s3 sync --exact-timestamps $S3_BUCKET $DIR

echo "DONE"

if [ $KEEP_ALIVE ] 
then
    while true; do sleep 1000; done
fi