<?php
/* Developed by WEBmods
 * Zagorski oglasnik j.d.o.o. za usluge | www.zagorski-oglasnik.com
 *
 * License: GPL-3.0-or-later
 * More info in license.txt
*/

if(!defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

class VideoEmbedModel extends DAO {
    private static $instance;

    public static function newInstance() {
        if(!self::$instance instanceof self) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    function __construct() {
        parent::__construct();
        $this->setTableName('t_item_videoembed');
        $this->setPrimaryKey('fk_i_item_id');
        $this->setFields(array('fk_i_item_id', 's_url'));
    }

    public function getSQL($file) {
        $path = VIDEOEMBED_PATH.'assets/model/'.$file;
        $sql = file_get_contents($path);

        return $sql;
    }

    public function install() {
        $sql = $this->getSQL('install.sql');
        if(!$this->dao->importSQL($sql)) {
            throw new Exception('Installation error: VideoEmbedModel:'.$file);
        }
    }

    public function uninstall() {
        $sql = $this->getSQL('uninstall.sql');
        if(!$this->dao->importSQL($sql)) {
            throw new Exception('Uninstallation error: VideoEmbedModel:'.$file);
        }
    }
}
?>
