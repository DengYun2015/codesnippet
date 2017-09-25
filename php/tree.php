<?php
$dir = dirname(__DIR__);
DirTree($dir);
function DirTree($dir, $level = 0)
{
    $ignoe = ['.', '..','node_modules','.git','.idea'];
    if (!is_dir($dir)) {
        return '';
    }
    if($level===0){
        echo '┌─'.pathinfo($dir,PATHINFO_FILENAME).PHP_EOL;
    }else{
        echo str_repeat('│  ',$level).'└─'.pathinfo($dir,PATHINFO_FILENAME).PHP_EOL;
    }
    $dirs = scandir($dir);
    $level++;
    foreach ($dirs as $path){
        if(in_array($path,$ignoe)){
            continue;
        }
        $fullPath = $dir.DIRECTORY_SEPARATOR.$path;
        if(is_dir($fullPath)){
            DirTree($fullPath,$level);
        }else{
            continue;
        }
    }
}