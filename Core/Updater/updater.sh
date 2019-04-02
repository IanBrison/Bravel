#!/bin/bash
git clone https://github.com/IanBrison/Bravel.git LatestBravel

cp -r ./LatestBravel/server ../../
cp ./LatestBravel/web/index.php ../../web/
cp -r ./LatestBravel/core ../../

rm -rf ./LatestBravel
