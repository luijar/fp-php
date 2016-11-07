#!/bin/sh
endpoints=( users stocks accounts )
port=8001
cd src/endpoints
for i in "${endpoints[@]}"
do
  nohup php -S "localhost:$port" "$i.php" > "$i.log" 2>&1 &
  ((port++))
done
echo 'Endpoints started...'
