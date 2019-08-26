<?php
/* Developed by WEBmods
 * Zagorski oglasnik j.d.o.o. za usluge | www.zagorski-oglasnik.com
 *
 * License: GPL-3.0-or-later
 * More info in license.txt
*/

$value = '';
if(isset($url)) { // If on item edit page, set value to previous URL.
    $value = $url;
}
if(!empty(Session::newInstance()->_getForm('videoembed_url'))) { // If error happened, set value to last entered URL.
    $value = Session::newInstance()->_getForm('videoembed_url');
}
?>
<div class="control-group">
    <label class="control-label" for="videoembed"><?php _e('Video URL - Youtube or Vimeo', videoembed_plugin()); ?></label>
    <div class="controls">
        <input type="text" name="videoembed_url" id="videoembed" value="<?php echo $value; ?>" />
    </div>
</div>
