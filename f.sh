#!/bin/bash
FILESPYT="/home/yutong/ioeweb/*"
FILESAAVEN="/Users/aavenjin/Documents/AAF/wharton_data/*"
FILESTEST="/Users/aavenjin/Documents/AAF/testdata/*"
for f in $FILESAAVEN
do
	sudo curl -s http://localhost/ioe_loadcsv.php?filename=$f
done
