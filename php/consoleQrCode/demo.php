<?php
/**
 * 在命令行生产二维码，只支持linux
 */
require_once('./qrcode.php');
$qr = QRCode::getMinimumQRCode("Hello PHP", QR_ERROR_CORRECT_LEVEL_L);
echo PHP_EOL;
$w = $qr->getModuleCount();
$h = $w;
$white = '\033[47;37;1m  \033[0m';
$black = '\033[40;37;1m  \033[0m';
$qrStr = '';
$head = str_repeat($white,$w+2);
$qrStr .= $head.'\r\n';
for ($r = 0; $r < $h; $r++) {
    $line = $white;
    for ($c = 0; $c < $w; $c++) {
        if($qr->isDark($r, $c)){
            $line .=$black;
        }else{
            $line .=$white;
        }
    }
    $line .=$white;
    $line .='\n';
    $qrStr .=$line;
}
$qrStr .= $head.'\n\n';
$cmd = 'echo -ne "'.$qrStr.'"';
//echo $cmd;
print shell_exec($cmd);