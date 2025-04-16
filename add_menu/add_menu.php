<?php
/*
Plugin Name: Add Menu
Plugin URI: http://wordpress.org/plugins/hello-dolly/
Description: This is not just a plugin, it symbolizes the hope and enthusiasm of an entire generation summed up in two words sung most famously by Louis Armstrong: Hello, Dolly. When activated you will randomly see a lyric from <cite>Hello, Dolly</cite> in the upper right of your admin screen on every page.
Author: Matt Mullenweg
Version: 1.7.2
Author URI: http://ma.tt/
*/
define('public_path',plugin_dir_path(__FILE__));
define('bootstrap_min_css',plugin_dir_url(__FILE__));
define('bootstrap_min_js',plugin_dir_url(__FILE__));
define('dataTables_dataTables_min',plugin_dir_url(__FILE__));
define('dataTables_min',plugin_dir_url(__FILE__));
define('add_custom_javascript1',plugin_dir_url(__FILE__));
define('jquery_validation',plugin_dir_url(__FILE__));

function wpdocs_register_my_custom_menu_page()
{
    add_menu_page('Custom page Title','Custom Menu Title','manage_options','custompage',
                    'Custom_page_Title' ,'dashicons-admin-page');
    add_submenu_page('custompage','Custom Menu Title1','Add Menu Title','manage_options'
                    ,'custompage','Custom_page_Title');
    add_submenu_page('custompage','Custom Menu Title2','edit Menu Title','manage_options'
                    ,'custompage12','Custom_page_Title_edit');
}
function Custom_page_Title()
{
    include_once(public_path.'Template/index.php');
}
function Custom_page_Title_edit()
{
    include_once(public_path.'Template/edit.php');
}
add_action( 'admin_menu', 'wpdocs_register_my_custom_menu_page' );

function database_table()
{
    global $wpdb ;
    $table_name = $wpdb->prefix.'student_data';
    $collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE {$table_name} (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(50) DEFAULT NULL,
        `email` varchar(50) DEFAULT NULL,
        `age` int(11) DEFAULT NULL,
        `gender` varchar(50) DEFAULT NULL,
        PRIMARY KEY (`id`)
        ) {$collate}";
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
    $pageData =[
        "post_title" => "Add Menu page",
        "post_status"=>"publish",
        "post_type"=>"page",
        "post_content"=>"this is the sample contant",
        "post_name"=> "Add-Menu-page"
    ];
    wp_insert_post( $pageData );
}
register_activation_hook(__FILE__,'database_table');
function drop_table()
{
  global $wpdb ;
  $table_name = $wpdb->prefix.'student_data';
  $sql = "Drop table IF EXISTS   {$table_name}";
  $wpdb->query($sql);

}
register_deactivation_hook(__FILE__,'drop_table');
function styles_and_scripts()
{
    wp_enqueue_style('admin_bootstrap_min_css',bootstrap_min_css.'assets/css/bootstrap.min.css',array(),'1.0.0','all');
    wp_enqueue_style('admin_dataTables_min_css',dataTables_dataTables_min.'assets/css/dataTables.dataTables.min.css',array(),'1.0.0','all');

    wp_enqueue_script('admin_javascript_js',bootstrap_min_js.'assets/js/bootstrap.min.js',array("jquery"),"1.0.0","all");
    wp_enqueue_script('admin_datatable_javascript_js',dataTables_min.'assets/js/dataTables.min.js',array("jquery"),"1.0.0","all");
    wp_enqueue_script('admin_custom_javascript_js',add_custom_javascript1.'assets/js/add_custom_javascript.js',array("jquery"),"1.0.0","all");
    wp_enqueue_script('jquery_validation1',jquery_validation.'assets/js/jquery-validation.js',array("jquery"),"1.0.0","all");

}
add_action('admin_enqueue_scripts','styles_and_scripts');
?>