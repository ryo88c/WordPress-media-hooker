<?php
/**
 * tumblr
 * @author HAYASHI Ryo<ryo@spais.co.jp>
 * @version 0.0.1
 * @see http://tumblr.com/
 * 途中
 */
class hooker_tumblr extends hooker{
    var $_name = 'Buzzurl';
    var $_iconUrl = 'http://buzzurl.jp.eimg.jp/static/image/api/icon/add_icon_mini_01.gif';
    var $_bookmarkUrl = 'http://buzzurl.jp/entry/';
    //http://www.tumblr.com/share?v=3&u=http%3A%2F%2Fwww.sanjo-k.net%2F%3Fp%3D14&t=Xrea%E3%81%ABWordPress3%E3%82%92%E3%82%A4%E3%83%B3%E3%82%B9%E3%83%88%E3%83%BC%E3%83%AB%E3%81%97%E3%81%9F%E3%82%89%E7%AE%A1%E7%90%86%E7%94%BB%E9%9D%A2%E3%81%8C%E5%B4%A9%E3%82%8C%E3%82%8B%2C%E7%AE%A1%E7%90%86%E7%94%BB%E9%9D%A2%E3%81%8C%E7%9C%9F%E3%81%A3%E7%99%BD&s=

    function let(){
        $scheme = apache_getenv('HTTPS')? 'https://': 'http://';
        return sprintf('<a href="%s%s%s%s" title="%s"><img src="%s" alt="%s"/></a>',
            $this->_bookmarkUrl, $scheme, getenv('HTTP_HOST'), getenv('REQUEST_URI'),
            sprintf(__('Post to %s', 'media_hooker'), $this->_name),
            $this->_iconUrl, $this->_name);
    }

    function get_count(){
        return 0;
    }
}