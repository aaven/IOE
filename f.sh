#!/bin/bash
FILES="/home/yutong/ioeweb/*"
for f in $FILES
do
 sudo curl -s http://localhost/insertcsv.php?filename=$f
done
