<?php
/* Developed by WEBmods
 * Zagorski oglasnik j.d.o.o. za usluge | www.zagorski-oglasnik.com
 *
 * License: GPL-3.0-or-later
 * More info in license.txt
*/

if(!defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

class VideoEmbed {
    public $model;

    function __construct() {
        osc_add_hook('init', array(&$this,'includes'));

        osc_add_hook('pre_item_post', array(&$this,'itemPrePost'));
        osc_add_filter('pre_item_add_error', array(&$this,'itemPrePostError'));
        osc_add_filter('pre_item_edit_error', array(&$this,'itemPrePostError'));
        osc_add_hook('posted_item', array(&$this,'itemPost'));
        osc_add_hook('edited_item', array(&$this,'itemPost'));
        osc_add_hook('item_form', array(&$this,'itemForm'));
        osc_add_hook('item_edit', array(&$this,'itemForm'));
        osc_add_hook('delete_item', array(&$this,'itemDelete'));
        osc_add_hook('item_detail', array(&$this,'itemDetail'));

        $this->model = VideoEmbedModel::newInstance();
    }

    // Save video URL in Session to use when saving in DB and in case errors appear.
    function itemPrePost() {
        // Save video URL in Session.
        $url = trim(Params::getParam('videoembed_url'));
        Session::newInstance()->_setForm('videoembed_url', $url);
        Session::newInstance()->_keepForm('videoembed_url');
    }

    // Show error if video URL is not valid or if it's required  empty.
    function itemPrePostError($errors, $item = null) {
        $url = trim(Params::getParam('videoembed_url'));
        if(!empty($url)) {
            if (strpos($url, 'youtu') === false && strpos($url, 'vimeo') === false) {
                $errors .= __('Video URL is not a valid Youtube or Vimeo URL', videoembed_plugin()) . PHP_EOL;
            }
        } else {
            if($item['fk_i_category_id'] != null) {
                if(osc_is_this_category('zo_videoembed', $item['fk_i_category_id'])) {
                    if(VIDEOEMBED_REQUIRED) {
                        $errors .= __('Video URL is required', videoembed_plugin()) . PHP_EOL;
                    }
                }
            }
        }

        return $errors;
    }

    // Save video URL in DB when saving item.
    function itemPost($item) {
        if($item['fk_i_category_id'] != null) {
            if(osc_is_this_category('zo_videoembed', $item['fk_i_category_id'])) {
                if(empty(Session::newInstance()->_getForm('videoembed_url'))) {
                    return false;
                }

                $id = $item['pk_i_id'];
                $url = Session::newInstance()->_getForm('videoembed_url');
                $data = array('fk_i_item_id' => $id, 's_url' => $url);

                if($this->model->findByPrimaryKey($id) != false) { // If a video URl record already exists, update it.
                    return $this->model->updateByPrimaryKey($data, $id);
                } else { // Otherwise create a new record.
                    return $this->model->insert($data);
                }
            }
        }
    }

    // Show video URL input on item post/edit page.
    function itemForm($category = null, $item = null) {
        if($category != null) {
            if(osc_is_this_category('zo_videoembed', $category)) {
                if($item != null) {
                    $result = $this->model->findByPrimaryKey($item);
                    if($result && count($result) && array_key_exists('s_url', $result)) {
                        $url = $result['s_url'];
                    }
                }

                include VIDEOEMBED_PATH.'views/web/item_form.php';
            }
        }
    }

    // Delete video URl record when item is deleted.
    function itemDelete($item) {
        return $this->model->deleteByPrimaryKey($item);
    }

    // Show video on item detail page.
    function itemDetail($item) {
        $id = $item['pk_i_id'];
        $result = $this->model->findByPrimaryKey($id);
        if($result && count($result) && array_key_exists('s_url', $result)) {
            $url = $result['s_url'];
        } else {
            return false;
        }

        if(strpos($url, 'youtu') !== false) {
            $type = 'youtube';
        } else if(strpos($url, 'vimeo') !== false) {
            $type = 'vimeo';
        } else {
            return false;
        }

        include VIDEOEMBED_PATH.'views/web/item_detail.php';
    }

    function includes() {
        // Add frontend and backend JS and CSS.
        osc_enqueue_style('plyr-css', osc_plugin_url('zo_videoembed/assets/web/plyr.css').'plyr.css');
        osc_register_script('plyr-js', osc_plugin_url('zo_videoembed/assets/web/plyr.js').'plyr.js');
        osc_enqueue_script('plyr-js');
    }
}
$VideoEmbed = new VideoEmbed();
