#!/bin/bash
#当天时间
curData=`date +%Y%m%d`
#循环体类主要执行的函数，今天之前的所有日志全部压缩
function tarBefore()
{
    #文件最后更新时间戳
    fileTime=`stat -c %Y $1`;
    #文件最后更新时间
    formatFileTime=`date -d @$fileTime +"%Y%m%d"`
    #时间判断
    if [ $formatFileTime -lt $2 ]
        then
            tar -zcvf $i.tar.gz $i;
            rm -rf $i;

    fi
}
#进入目录
cd /home/wwwroot/default/DH_TuoLing/DownloadCenter/item/storage/;
AllLog=`ls *.log`;
for i in $AllLog
  do
      tarBefore $i $curData;
  done
