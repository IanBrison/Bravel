if [ ! -d "LatestBravel" ]; then
    git clone https://github.com/IanBrison/Bravel.git LatestBravel
else
    cd LatestBravel && git pull origin master && cd ..
fi

cp ./LatestBravel/bootstrap.php ../../
cp -r ./LatestBravel/server ../../
cp ./LatestBravel/web/index.php ../../web/
cp -r ./LatestBravel/core ../../
