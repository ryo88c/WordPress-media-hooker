<?php
/**
 * Hooker Yahoo! bookmark
 * @author HAYASHI Ryo<ryo@spais.co.jp>
 * @version 0.0.1
 * @see http://bookmarks.yahoo.co.jp/settings/tools/savelink
 * @see http://bookmarks.yahoo.co.jp/settings/tools/numlink
 */
class hooker_yahoobookmark extends hooker{
    var $_name = 'Yahoo! ブックマーク';
    var $_iconUrl = 'http://i.yimg.jp/images/sicons/ybm16.gif';
    var $_bookmarkUrl = 'http://bookmarks.yahoo.co.jp/bookmarklet/showpopup?t=%s&amp;u=%s&amp;ei=UTF-8';
    var $_countUrl = 'http://num.bookmarks.yahoo.co.jp/yjnostb.php?urls=';

    function let(){
        $scheme = apache_getenv('HTTPS')? 'https://': 'http://';
        return sprintf('<a href="%s" title="%s"><img src="%s" alt="%s"/></a>',
            sprintf($this->_bookmarkUrl, apply_filters('page_title', get_bloginfo('name')), $scheme.getenv('HTTP_HOST').getenv('REQUEST_URI')),
            sprintf(__('Post to %s', 'media_hooker'), $this->_name),
            $this->_iconUrl, $this->_name);
    }

    function get_count(){
        $host = 'num.bookmarks.yahoo.co.jp';
        $scheme = apache_getenv('HTTPS')? 'https://': 'http://';
        $url = $scheme.getenv('HTTP_HOST').getenv('REQUEST_URI');
        $url = 'http://google.co.jp/';
        if(!$fp = fsockopen($host, 80)) return 0;
        $out = sprintf("GET /yjnostb.php?urls=%s HTTP/1.1\r\n", $url);
        $out .= sprintf("Host: %s\r\n", $host);
        $out .= "Connection: Close\r\n\r\n";

        fwrite($fp, $out);
        $b = $num = false;
        $lines = '';
        while (!feof($fp)) {
            $line = trim(fgets($fp, 128));
            if(empty($line)){
                 $b = true;
                 continue;
            }
            if($b !== true) continue;
            $lines .= $line;
        }
        if(preg_match('/ct="([0-9]+)"/i', $lines, $matches))
            $num = $matches[1];
        fclose($fp);
        return (int) $num;
    }
}