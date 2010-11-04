<?php
/**
 * Nifty clip
 * @author HAYASHI Ryo<ryo@spais.co.jp>
 * @version 0.0.1
 * @see http://clip.nifty.com/
 * 途中
 */
class hooker_niftyclip extends hooker{
    var $_name = 'Buzzurl';
    var $_iconUrl = 'http://buzzurl.jp.eimg.jp/static/image/api/icon/add_icon_mini_01.gif';
    var $_bookmarkUrl = 'http://buzzurl.jp/entry/';

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