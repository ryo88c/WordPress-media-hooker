<?php
/**
 * Hooker Google bookmark
 * @author HAYASHI Ryo<ryo@spais.co.jp>
 * @version 0.0.1
 * @see http://www.google.com/support/toolbar/bin/answer.py?hl=jp&answer=43305
 */
class hooker_googlebookmark extends hooker{
    var $_name = 'Google ブックマーク';
    var $_iconUrl = 'http://www.google.co.jp/favicon.ico';
    var $_bookmarkUrl = 'https://www.google.com/bookmarks/mark?op=edit&amp;title=%s&amp;bkmk=%s';

    function let(){
        $scheme = apache_getenv('HTTPS')? 'https://': 'http://';
        return sprintf('<a href="%s" title="%s"><img src="%s" alt="%s"/></a>',
            sprintf($this->_bookmarkUrl, apply_filters('page_title', get_bloginfo('name')), $scheme.getenv('HTTP_HOST').getenv('REQUEST_URI')),
            sprintf(__('Post to %s', 'media_hooker'), $this->_name),
            $this->_iconUrl, $this->_name);
    }

    function get_count(){
        return 0;
    }
}