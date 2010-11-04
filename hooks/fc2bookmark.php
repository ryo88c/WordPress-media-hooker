<?php
/**
 * Hooker FC2 bookmark
 * @author HAYASHI Ryo<ryo@spais.co.jp>
 * @version 0.0.1
 * @see http://bookmark.fc2.com/faq
 * @see http://www.delicious.com/help/json
 */
class hooker_fc2bookmark extends hooker{
    var $_name = 'FC2 ブックマーク';
    var $_iconUrl = 'http://bookmark.fc2.com/favicon.ico';
    var $_bookmarkUrl = 'http://bookmark.fc2.com/user/post?title=%s&amp;url=%s';

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