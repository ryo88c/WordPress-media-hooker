<?php
/*
Plugin Name: Media hooker
Plugin URI: http://spais.co.jp/%e3%83%97%e3%83%ad%e3%83%80%e3%82%af%e3%83%88-product/media-hooker/
Description: ソーシャルメディアへのインターフェースを提供
Author: HAYASHI Ryo
Version: 0.0.1
Author URI: http://spais.co.jp/
Copyright: 2010 (c) SPaiS Inc.
*/
/**
 * 新たに追加したい場合は hooker クラスを継承する
 */
/**
 * メイン
 * @author HAYASHI Ryo<ryo@spais.co.jp>
 * @version 0.0.1
 */
class media_hooker{

    var $_hookerHTML = array('parentBegin' => '<ul class="socialMedia">', 'parentEnd' => '</ul>', 'childBegin' => '<li>', 'childEnd' => '</li>');

    function action_display_media_response_count($classes = array()){
        $hookers = $this->get_hookers();
        foreach($hookers as $hookerId => $hookerClass){
            if(!empty($classes) && !in_array(get_class($hookerClass), $classes)) continue;
            //var_dump($hookerClass->get_count());
        }
    }

    function action_display_media_hooker(){
        $hookers = $this->get_hookers();
        $li = array();
        foreach($hookers as $hookerId => $hookerClass){
            $li[] = sprintf('%s%s%s', $this->_hookerHTML['childBegin'], $hookerClass->let(), $this->_hookerHTML['childEnd']);
        }
        if(empty($li)) return;
        printf('%s%s%s', $this->_hookerHTML['parentBegin'], implode("\n", $li), $this->_hookerHTML['parentEnd']);
    }

    function get_hookers(){
        static $_hookers = array();
        if(empty($_hookers)){
            $hookers = get_option('media_hookers', array());
            $dir = dirname(__FILE__);
            foreach($hookers as $hooker){
                include("{$dir}/hooks/{$hooker}");
                $class = 'hooker_'.basename($hooker, '.php');
                $_hookers[$hooker] = new $class;
            }
        }
        return $_hookers;
    }

    function load_textdomain(){
        load_plugin_textdomain('media_hooker', false, 'media-hooker/languages');
    }

    function media_hooker(){
        add_action('display_media_hooker', array(&$this, 'action_display_media_hooker'));
        //add_action('display_media_response_count', array(&$this, 'action_display_media_response_count'));
        add_action('plugins_loaded', array(&$this, 'load_textdomain'));
    }
}
new media_hooker;

/**
 * 管理画面用
 * @author HAYASHI Ryo<ryo@spais.co.jp>
 * @version 0.0.1
 */
class media_hooker_admin{

    var $_hookers = array();

    function display_option(){
        $file = basename(__FILE__);
        add_settings_section('general', __('General'), array(&$this, 'display_section'), $file);
        add_settings_field('services', __('Services', 'media_hooker'), array(&$this, 'display_element'), $file, 'general');

        ?><div class="wrap">
            <h2><?php _e('Media hooker options', 'media_hooker')?></h2>
            <form method="post" action="<?php echo getenv('REQUEST_URI')?>">
                <?php settings_fields('media_hooker')?>
                <input type="hidden" name="order" id="order" value="<?php echo implode(',', get_option('media_hookers', array()))?>" />
                <?php do_settings_sections($file)?>
                <p class="submit">
                    <input type="submit" class="button-primary" value="<?php _e('Save')?>" />
                </p>
            </form>
        </div><?php

    }

    function display_section(){}

    function display_element($a){
        $options = get_option('media_hookers', array());
        $li = array();
        foreach($this->_hookers as $hookerId => $hookerClass){
            $checked = in_array($hookerId, $options)? ' checked="checked"': null;
            $_li = sprintf('<li><span class="handle">⇅</span><label><input type="checkbox" name="hookers[]" value="%1$s"%4$s /><img src="%5$s" />%3$s[%2$s]</label></li>',
                $hookerId, get_class($hookerClass), $hookerClass->_name, $checked, $hookerClass->_iconUrl);

            $order = array_search($hookerId, $options);
            if($order === false)
                $li[] = $_li;
            else
                $li[$order] = $_li;
        }
        ksort($li);
        printf('<ul>%s</ul>', implode("\n", $li));
    }

    function action_admin_init(){
        if(!isset($_GET['page']) || $_GET['page'] !== 'media-hooker/media-hooker.php') return;
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script('media-hooker-admin', plugin_dir_url(__FILE__) . 'admin.js', array('jquery-ui-sortable'), null, true);
        wp_enqueue_style('media-hooker-admin', plugin_dir_url(__FILE__) . 'admin.css');
        $dir = dir(dirname(__FILE__).'/hooks');
        while (false !== ($entry = $dir->read())){
            if(!preg_match('/\.php$/i', $entry)) continue;
            include($dir->path.'/'.$entry);
            $class = 'hooker_'.basename($entry, '.php');
            $this->_hookers[$entry] = new $class;
        }
        $dir->close();

        if(getenv('REQUEST_METHOD') !== 'POST' || !isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'media_hooker-options')) return;
        if(!isset($_POST['hookers'])) $_POST['hookers'] = array();
        if(isset($_POST['order'])) $_POST['order'] = explode(',', $_POST['order']);
        $newHookers = array();
        foreach((array) $_POST['hookers'] as $hooker){
            if(!array_key_exists($hooker, $this->_hookers)) continue;
            $order = array_search($hooker, $_POST['order']);
            if($order === false)
                $newHookers[] = $hooker;
            else
                $newHookers[$order] = $hooker;
        }
        ksort($newHookers);
        update_option('media_hookers', $newHookers);
        $this->notice(__('Options updated.', 'media_hooker'));
    }

    function action_admin_menu(){
        add_options_page(__('Media hooker', 'media_hooker'), __('Media hooker', 'media_hooker'), 'administrator', __FILE__, array(&$this, 'display_option'));
    }

    /**
     * notice メッセージ
     * @param string $message OPTIONAL メッセージ
     * @param string $class OPTIONAL メッセージの種類
     */
    function notice($message = null, $class = 'updated')
    {
        static $notices = array('error' => array(), 'updated' => array());
        if (empty($message)) {
            foreach($notices as $class => $notice) if (!empty($notice))
                printf('<div class="%s fade"><ul><li>%s</li></ul></div>', $class, implode("</li><li>", $notice));
        } else $notices[$class][] = $message;
    }

    function media_hooker_admin(){
        add_action('admin_init', array(&$this, 'action_admin_init'));
        add_action('admin_menu', array(&$this, 'action_admin_menu'));
        add_action('admin_notices', array(&$this, 'notice'));
    }
}
new media_hooker_admin;

/**
 * hooker 基底クラス
 * @author HAYASHI Ryo<ryo@spais.co.jp>
 * @version 0.0.1
 */
class hooker{
    var $_name;
    var $_iconUrl;

    function let(){
        wp_die(__('Please do not call this class directly.', 'media_hooker'));
    }

    function get_count(){
        wp_die(__('Please do not call this class directly.', 'media_hooker'));
    }
}