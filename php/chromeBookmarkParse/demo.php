<?php
/**
 * @author: dengyun
 * @time: 2017/9/19 15:32
 */

require './ChromeBookmarkParse.php';
$info = ChromeBookmarkParse::run('E:\bookmarks_2017_9_18.html');
print_r($info);