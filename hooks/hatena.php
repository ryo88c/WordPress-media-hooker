<?php
/**
 * Hooker Hatena
 * @author HAYASHI Ryo<ryo@spais.co.jp>
 * @version 0.0.1
 * @see http://b.hatena.ne.jp/help/button
 * @see http://d.hatena.ne.jp/keyword/%A4%CF%A4%C6%A4%CA%A5%D6%A5%C3%A5%AF%A5%DE%A1%BC%A5%AF%B7%EF%BF%F4%BC%E8%C6%C0API
 */
class hooker_hatena extends hooker{
    var $_name = 'はてなブックマーク';
    var $_iconUrl = 'http://b.hatena.ne.jp/images/append.gif';
    var $_bookmarkUrl = 'http://b.hatena.ne.jp/entry/';

    function let(){
        return sprintf('<a href="%s%s%s" title="%s"><img src="%s" alt="%s"/></a>',
            $this->_bookmarkUrl, getenv('HTTP_HOST'), getenv('REQUEST_URI'),
            sprintf(__('Post to %s', 'media_hooker'), $this->_name),
            $this->_iconUrl, $this->_name);
    }

    function get_count(){
        $host = 'api.b.st-hatena.com';
        $scheme = apache_getenv('HTTPS')? 'https://': 'http://';
        $url = $scheme.getenv('HTTP_HOST').getenv('REQUEST_URI');
        if(!$fp = fsockopen($host, 80)) return 0;
        $out = sprintf("GET /entry.count?url=%s HTTP/1.1\r\n", urlencode($url));
        $out .= sprintf("Host: %s\r\n", $host);
        $out .= "Connection: Close\r\n\r\n";

        fwrite($fp, $out);
        $b = $num = false;
        while (!feof($fp)) {
            $line = trim(fgets($fp, 128));
            if(empty($line)){
                 $b = true;
                 continue;
            }
            if($b !== true) continue;
            $num = $line;
        }
        fclose($fp);
        return (int) $num;
    }
}