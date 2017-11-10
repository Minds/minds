#!/bin/sh

echo "SYNCING: $S3_BUCKET" 

aws s3 sync $S3_BUCKET $DIR

echo "DONE"