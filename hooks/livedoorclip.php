<?php
/**
 * Hooker Livedoor Clip
 * @author HAYASHI Ryo<ryo@spais.co.jp>
 * @version 0.0.1
 * @see http://clip.livedoor.com/guide/blog.html
 * @see http://wiki.livedoor.jp/staff_clip/d/%a5%af%a5%ea%a5%c3%a5%d7%be%f0%ca%f3%bc%e8%c6%c0%20API
 */
class hooker_livedoorclip extends hooker{
    var $_name = 'Livedoor クリップ';
    var $_iconUrl = 'http://parts.blog.livedoor.jp/img/cmn/clip_16_16_w.gif';
    var $_bookmarkUrl = 'http://clip.livedoor.com/redirect?link=';

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