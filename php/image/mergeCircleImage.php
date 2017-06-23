<?php
/**
 * @author: dengyun
 * @time: 2017/5/8 15:43
 *
 */

/**
 * 合并圆形图片
 * @param resource $dst_image
 * @param resource $src_image
 * @param int $r
 * @param int $left
 * @param int $top
 */

function mergeCircleImage(&$dst_image, $src_image, $r, $left, $top)
{
    $w = $r * 2;
    for ($x = 0; $x <= $r; $x++) {
        $tw = $r - $x;
        $y = $r + (int)sqrt(($r * $r - $tw * $tw));
        $Ay = $w - $y;
        $Bx = $w - $x;
        $rh = $y - $Ay;
        imagecopy($dst_image, $src_image, $x + $left, $Ay + $top, $x, $Ay, 1, $rh);
        imagecopy($dst_image, $src_image, $Bx + $left, $Ay + $top, $Bx, $Ay, 1, $rh);
    }
}