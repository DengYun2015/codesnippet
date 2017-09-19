<?php
/**
 * @author: dengyun
 * @time: 2017/9/19 15:13
 */

/**
 * chrome 书签解析
 * Class ChromeBookmarkParse
 * @version 1.0
 */
class ChromeBookmarkParse
{
    /**
     * @param $path string 书签文件路径
     * @return array
     * @throws Exception
     */
    public static function run($path)
    {
        if (!file_exists($path)) {
            throw new Exception('文件不存在：' . $path);
        }
        $fp = fopen($path, 'r+');
        return self::parse($fp);
    }

    /**
     * @param $fp Resource
     * @return array
     */
    private static function parse($fp)
    {
        $parsedData = [];
        $title = '';
        while (!feof($fp)) {
            $row = strtolower(trim(fgets($fp)));
            if (empty($row)) {
                continue;
            }
            if (strpos($row, '<dl><p>') === 0) {
                if (empty($title)) {
                    $parsedData = self::parse($fp);
                } else {
                    $parsedData[] = [
                        'name' => $title,
                        'items' => self::parse($fp)
                    ];
                }
            } elseif (strpos($row, '<dt><h3') === 0) {
                $title = self::parseTitle($row);
            } elseif (strpos($row, '<dt><a') === 0) {
                $parsedData[] = self::parseTagA($row);
            } elseif (strpos($row, '</dl><p>') === 0) {
                break;
            } else {
                //echo 'ignore tag:' . substr($row, 0, 6) . PHP_EOL;
                continue;
            }
        }
        return $parsedData;
    }

    /**
     * @param $row string
     * @return string
     */
    private static function parseTitle($row)
    {
        $rule = '/>[^<]+/';
        preg_match($rule, $row, $matches);
        return empty($matches) ? '' : str_replace('>', '', $matches[0]);
    }

    /**
     * @param $row string
     * @return array
     */
    private static function parseTagA($row)
    {
        $rule = '/href="[^"]+/';
        preg_match($rule, $row, $matches);
        return [
            'name' => self::parseTitle($row),
            'url' => empty($matches) ? '' : str_replace('href="', '', $matches[0])
        ];
    }
}