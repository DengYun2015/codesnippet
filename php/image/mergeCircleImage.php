<?php

/**
 * 使用GD库合并圆形图片
 * @param resource $dst_image
 * @param resource $src_image
 * @param int $r
 * @param int $left
 * @param int $top
 * @author: dengyun
 * @time: 2017/5/8 15:43
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


/**
 * 使用Imagick裁剪
 * @link https://segmentfault.com/q/1010000007501202
 */
$avatar = __DIR__ . DIRECTORY_SEPARATOR . 'avatar.jpg';
$circleAvatar = __DIR__ . DIRECTORY_SEPARATOR . 'avatar.png';
$r = 50;
getCircleAvatar($avatar, $circleAvatar, $r);

function getCircleAvatar($avatar, $circleAvatar, $r) {
    /**
     * @des     画一个正方形
     * @size    两个半径
     */
    $size = 2 * $r;
    $circle = new Imagick();
    $circle->newImage($size, $size, 'none');
    $circle->setimageformat('png');
    $circle->setimagematte(true);

    /**
     * @des     在矩形上画一个白色圆
     */
    $draw = new ImagickDraw();
    $draw->setfillcolor('#fff');
    $draw->circle($r, $r, $r, $size);
    $circle->drawimage($draw);

    /**
     * @des     裁剪头像成圆形
     */
    $imagick = new Imagick();
    $imagick->readImage($avatar);
    $imagick->setImageFormat('png');
    $imagick->setimagematte(true);
    $imagick->cropimage($size, $size, 30, 0); // 修改裁剪属性
    $imagick->compositeimage($circle, Imagick::COMPOSITE_COPYOPACITY , 0, 0);
    $imagick->writeImage($circleAvatar);
    $imagick->destroy();
}