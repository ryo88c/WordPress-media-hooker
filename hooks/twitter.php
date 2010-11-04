<?php
/**
 * Hooker Twitter
 * @author HAYASHI Ryo<ryo@spais.co.jp>
 * @version 0.0.1
 * @see https://twitter.com/goodies/buttons
 * @see http://www.delicious.com/help/json
 */
class hooker_twitter extends hooker{
    var $_name = 'Twitter';
    var $_iconUrl = 'http://twitter-badges.s3.amazonaws.com/t_mini-b.png';
    var $_bookmarkUrl = 'http://twitter.com/share?%s';

    var $_via;
    var $_text;

    function let(){
        $this->_text = apply_filters('page_title', get_bloginfo('name'));
        $scheme = apache_getenv('HTTPS')? 'https://': 'http://';
        $params = array('url='.urlencode($scheme.getenv('HTTP_HOST').getenv('REQUEST_URI')));
        if(!empty($this->_via)) $params[] = 'via='.urlencode($this->_via);
        if(!empty($this->_text)) $params[] = 'text='.urlencode($this->_text);
        return sprintf('<a href="%s" title="%s"><img src="%s" alt="%s"/></a>',
            sprintf($this->_bookmarkUrl, implode('&amp;', $params)),
            sprintf(__('Post to %s', 'media_hooker'), $this->_name),
            $this->_iconUrl, $this->_name);
    }

    function get_count(){
        return 0;
    }
}