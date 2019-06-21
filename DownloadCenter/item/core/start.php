<?php

//区分命令行和web服务器访问
if(IS_CLI){
    require_once CMD_PATH;
}else{
    require_once APP_PATH;
}