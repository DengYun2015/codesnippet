#!/bin/bash
echo 'starting web server';

services=(
	'/opt/soft/php/sbin/php-fpm'
	'/opt/soft/nginx/sbin/nginx'
	'/etc/init.d/mysqld start'
	)


for((i=0;i<${#services[*]};i++))
do
	path=${services[$i]}
	echo '======================================'
	bin=${path##*/}
	#echo $bin
	bin=${bin% *}
	#echo $bin
	#echo "starting $bin"
	
	countCmd="ps -ef|grep ${bin}|grep -v grep|wc -l"
	# echo $countCmd
	count=`eval "$countCmd"`
	echo "process ${bin} count: ${count}"
	if [ ${count} -gt 0 ]
	then
		echo "${bin} has started"
	else
		echo "starting ${bin}"
		`eval "${path}"`
		if [ `eval "${countCmd}"` == 0 ]
		then
			echo "start ${bin} failed!"
			sleep 1
		else
			echo "start ${bin} success!"
		fi
	fi
	echo
	sleep 2
done

echo
echo 'done!'