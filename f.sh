#!/bin/bash
FILESPYT="/home/yutong/ioeweb/*"
FILESAAVEN="/Users/aavenjin/Documents/AAF/wharton_data/*"
FILESTEST="/Users/aavenjin/Documents/AAF/testdata/*"
names=""
i=0
for f in $FILESAAVEN
do
ff=${f##*/}
ff=${ff%.*}
names=$names$ff","
i=$(($i+1))
if (("$i" > "399")); then
sudo curl -s http://141.212.105.103/ioe_loadcsv.php?filename=$names
names=""
i=0
fi
done

if (("$i" < "400")) && (("$i" > "0")); then
sudo curl -s http://141.212.105.103/ioe_loadcsv.php?filename=$names
fi