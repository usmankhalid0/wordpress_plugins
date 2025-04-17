<?php
/*
 *  Plugin Name: Ajax-crud
 * Plugin URI: http://wordpress.org/plugins/Ajax-crud/
 * Description: A simple plugin to demonstrate WP Ajax-crud  functionality.
 * Author: Usman Khalid
 * Version: 1.7.0
 * Author URI: http://maaa.tt/1
*/
function dbtable_ajaxcrud()
{
    global $wpdb ;
    $table_name = $wpdb->prefix."ajax_crud";
    $collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE {$table_name} (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(50) DEFAULT NULL,
        `email` varchar(50) DEFAULT NULL,
        `age` int(11) DEFAULT NULL,
        `address` varchar(50) DEFAULT NULL,
        `father_name` varchar(50) DEFAULT NULL,
        PRIMARY KEY (`id`)
        ) {$collate}";
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);
}
register_activation_hook( __FILE__, "dbtable_ajaxcrud" );
function dbtable_dropajaxcrud()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'ajax_crud';
    $sql = "DROP TABLE IF EXISTS {$table_name}";
    $wpdb->query($sql);  
}
register_deactivation_hook( __FILE__, "dbtable_dropajaxcrud" );
// Define constant for template path
define("PAGES_PATH", plugin_dir_path(__FILE__));
define("URL_PATH", plugin_dir_url(__FILE__));
// Register admin menu
add_action("admin_menu", "addmenupage");

function addmenupage() {
    add_menu_page(
        'Admin Menu',            // Page title
        'Admin Menu',            // Menu title
        'manage_options',        // Capability
        'main_plugin_slug',         // Menu slug
        'pagesofplugin'          // Callback function to display content
    );
    add_submenu_page(
        'main_plugin_slug',       // Parent slug (must match top-level)
        'Submenu 1 Title',        // Page title
        'Submenu 1',              // Menu title
        'manage_options',         // Capability
        'submenu_slug_1',         // Menu slug
        'render_submenu_page_1'   // Callback
    );

    // Submenu 2
    add_submenu_page(
        'main_plugin_slug',
        'Submenu 2 Title',
        'Submenu 2',
        'manage_options',
        'submenu_slug_2',
        'render_submenu_page_2'
    );
}

// Callback function to display the plugin page
function pagesofplugin() {
    require_once PAGES_PATH . 'Template/index.php';
}
function render_submenu_page_1()
{
    require_once PAGES_PATH . 'Template/edit.php';
}
function render_submenu_page_2()
{
    require_once PAGES_PATH . 'Template/create.php';
}
function scriptandstyle() {
    wp_enqueue_style('bootstrap-css',  URL_PATH . 'Template/assets/css/bootstrap.min.css', array(),  '1.0.0', 'all' );
    // wp_enqueue_style('custom-css',  URL_PATH . 'Template/assets/css/style.css', array(),  '1.0.0', 'all' );
    wp_enqueue_script( "bootstrap-min-js", URL_PATH . 'Template/assets/js/bootstrap.bundle.min.js', array("jquery"),"1.0.0", "all" );
    wp_enqueue_script( "popper-min-js", URL_PATH . 'Template/assets/js/popper.min.js', array("jquery"),"1.0.0", "all" );
    wp_enqueue_script( "custom-js", URL_PATH . 'Template/assets/js/custom.js', array("jquery"),"1.0.0", "all" );

    $data = "var ajax_url = '".admin_url('admin-ajax.php')."'";// ajax url

    wp_add_inline_script( "custom-js", $data);
}
add_action('admin_enqueue_scripts', 'scriptandstyle');
function handle_ajax_request()
{
    $name = $_POST['name'];
    $email = $_POST['email'];
    $age = $_POST['age'];
    $address = $_POST['address'];
    $fname = $_POST['fname'];
    echo("this is ok:  ".$name."   ".$fname."   "."    ".$email."   ".$age);
}
add_action("wp_ajax_my_action","handle_ajax_request");
?>