<?php

/**
 * KK Rating plugin file
 *
 * @phpcs:disable Generic.Files.OneObjectStructurePerFile
 */
namespace TWRP\Plugins\Known_Plugins;

use  TWRP\Plugins\Post_Rating ;
use  TWRP\Utils\Simple_Utils ;
trait KK_Rating_Plugin_Info
{
    public static function get_class_order_among_siblings()
    {
        return 50;
    }
    
    #region -- Plugin Meta
    public function get_plugin_title()
    {
        return 'kk Star Ratings';
    }
    
    public function get_plugin_author()
    {
        return 'Kamal Khan.';
    }
    
    public function get_tested_plugin_versions()
    {
        return '4.0.0 - 4.2.0';
    }
    
    public function get_plugin_file_relative_path()
    {
        return array(
            'kk-star-ratings-premium/index.php',
            'kk-star-ratings-premium/kk-star-ratings.php',
            'kk-star-ratings-premium/kk-star-ratings-premium.php',
            'kk-star-ratings/kk-star-ratings.php',
            'kk-star-ratings/index.php'
        );
    }

}
/**
 * Full class available only in premium plugin.
 */
class KK_Rating_Plugin_Locked extends Post_Rating_Plugin_Locked
{
    use  KK_Rating_Plugin_Info ;
}