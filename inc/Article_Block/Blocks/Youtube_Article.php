<?php

namespace TWRP\Article_Block\Blocks;

use  TWRP\Article_Block\Article_Block ;
use  TWRP\Article_Block\Article_Block_Locked ;
use  TWRP\Article_Block\Component\Artblock_Component ;
use  TWRP\Article_Block\Settings\Display_Meta ;
// phpcs:disable Generic.Files.OneObjectStructurePerFile.MultipleFound
/**
 * Freemius class, that shows to the users that this style is available in
 * premium mode.
 */
class Youtube_Article_Locked extends Article_Block_Locked
{
    public static function get_class_order_among_siblings()
    {
        return 50;
    }
    
    public static function get_id()
    {
        return 'youtube_style';
    }
    
    public static function get_name()
    {
        return _x( 'Youtube Style (PRO Only)', 'backend', 'tabs-with-posts' );
    }
    
    public static function get_file_name()
    {
        return '';
    }

}