<?php

/**
 * Blaz Rating plugin file
 *
 * @phpcs:disable Generic.Files.OneObjectStructurePerFile
 */
namespace TWRP\Plugins\Known_Plugins;

use  TWRP\Plugins\Post_Rating ;
use  TWRP\Utils\Helper_Trait\After_Setup_Theme_Init_Trait ;
use  TWRP\Utils\Simple_Utils ;
use  WP_Query ;
trait Blaz_Rating_Plugin_Info
{
    public static function get_class_order_among_siblings()
    {
        return 20;
    }
    
    #region -- Plugin Meta
    public function get_plugin_title()
    {
        return 'Rate my Post';
    }
    
    public function get_plugin_author()
    {
        return 'Blaz K.';
    }
    
    public function get_tested_plugin_versions()
    {
        return '2.5.0 - 3.3.2';
    }
    
    public function get_plugin_file_relative_path()
    {
        return array( 'rate-my-post-premium/rate-my-post.php', 'rate-my-post-premium/rate-my-post-premium.php', 'rate-my-post/rate-my-post.php' );
    }

}
/**
 * Full class available only in premium plugin.
 */
class Blaz_Rating_Plugin_Locked extends Post_Rating_Plugin_Locked
{
    use  Blaz_Rating_Plugin_Info ;
}