<?php
/**
 * Hooker Delicious
 * @author HAYASHI Ryo<ryo@spais.co.jp>
 * @version 0.0.1
 * @see http://www.delicious.com/help/savebuttons
 * @see http://www.delicious.com/help/json
 */
class hooker_delicious extends hooker{
    var $_name = 'delicious';
    var $_iconUrl = 'http://static.delicious.com/img/delicious.gif';
    var $_bookmarkUrl = 'http://www.delicious.com/save?title=%s&amp;url=%s';

    function let(){
        $scheme = apache_getenv('HTTPS')? 'https://': 'http://';
        return sprintf('<a href="%s" title="%s"><img src="%s" alt="%s"/></a>',
            sprintf($this->_bookmarkUrl, apply_filters('page_title', get_bloginfo('name')), $scheme.getenv('HTTP_HOST').getenv('REQUEST_URI')),
            sprintf(__('Post to %s', 'media_hooker'), $this->_name),
            $this->_iconUrl, $this->_name);
    }

    function get_count(){
        //http://feeds.delicious.com/v2/json/urlinfo/data?url=http://google.co.jp/
        return 0;
    }
}