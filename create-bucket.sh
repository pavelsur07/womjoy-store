#!bin/bash
mc mb mys3/s3/static-bucket
mc mirror mys3/s3/static-bucket /data
mc mirror --remove  mys3/s3/static-bucket /data
