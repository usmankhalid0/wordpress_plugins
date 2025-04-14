<?php
/*
*
Plugin Name: CSV UPloader
Plugin URI: http://wordpress.org/plugins/hello-dolly/
Description: This is not just a plugin, it symbolizes the hope and enthusiasm of an entire generation summed up in two words sung most famously by Louis Armstrong: Hello, Dolly. When activated you will randomly see a lyric from <cite>Hello, Dolly</cite> in the upper right of your admin screen on every page.
Author: Matt Mullenweg
Version: 1.7.2
Author URI: http://example.com
*/
define('dir_path',plugin_dir_path( __FILE__));
// echo dir_path ;
// die;
function csv_display_uploader_form()
{
    ob_start();
    include_once dir_path.'/template/form.php';
    $template = ob_get_contents();
    ob_end_clean();
    return $template;
}
add_shortcode( 'csv-data-uploader', 'csv_display_uploader_form' );
function db_table_create() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'students_data';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE {$table_name} (
        id int(11) NOT NULL AUTO_INCREMENT,
        name varchar(50) DEFAULT NULL,
        email varchar(50) DEFAULT NULL,
        age int(11) DEFAULT NULL,
        phone varchar(50) DEFAULT NULL,
        image varchar(255) DEFAULT NULL,
        address varchar(255) DEFAULT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
register_activation_hook(__FILE__, 'db_table_create');
function enScript() {
    wp_enqueue_script(
        'enscript1',
        plugin_dir_url(__FILE__) . 'assets/script.js',
        array('jquery'),
        null,
        true
    );
    wp_localize_script('enscript1', 'cdu_object', array(
        'ajax_url' => admin_url('admin-ajax.php')
    ));
}
add_action('wp_enqueue_scripts', 'enScript');

add_action('wp_ajax_cdu_submit_form_data', 'cud_handle_ajax');
add_action('wp_ajax_nopriv_cdu_submit_form_data', 'cud_handle_ajax');

function cud_handle_ajax() {
    if (!empty($_FILES['csv_file_data']['tmp_name'])) {

        $csvfile = $_FILES['csv_file_data']['tmp_name'];
        $handle = fopen($csvfile, "r");

        global $wpdb;
        $table_name = $wpdb->prefix . 'students_data';
        // print_r($handle);
        // die;
        if ($handle) {
            $row = 0;
            $inserted = 0;

            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if ($row === 0) {
                    $row++;
                    continue;
                }
                if (count($data) >= 7) {
                    // Merge address if it's split by a comma
                    $address = isset($data[6]) ? trim($data[6]) : '';
                    if (isset($data[7])) {
                        $address .= ', ' . trim($data[7]);
                    }

                    $wpdb->insert($table_name, array(
                        "name"    => trim($data[1]),
                        "email"   => trim($data[2]),
                        "age"     => trim($data[3]),
                        "phone"   => trim($data[4]),
                        "image"   => trim($data[5]),
                        "address" => $address,
                    ));
                    $inserted++;
                }
                $row++;
            }

            fclose($handle);

            echo json_encode([
                "status" => 1,
                "message" => "Data uploaded successfully. Rows inserted: $inserted"
            ]);
        } else {
            echo json_encode([
                "status" => 0,
                "message" => "Unable to open the CSV file."
            ]);
        }
    } else {
        echo json_encode([
            'status' => 0,
            'message' => "CSV file not uploaded."
        ]);
    }

    wp_die(); // End AJAX
}

?>