#!/bin/sh

docker run \
  -d \
  -it \
  -p 3080:80 \
  -p 3022:22 \
  --name kanshi \
  -v /data/docker-share/kanshi:/var/share:rw \
  localhost:5000/kanshi:v1.0 

