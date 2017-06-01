<?php
defined( 'ABSPATH' ) or die( 'Idiot, this is a unauthorize access. Do it again and you get banned.' );
/**
 *
 * @link              http://lostwebdesigns.com
 * @since             1.0.0
 * @package           Wp_Cbf
 *
 * @wordpress-plugin
 * Plugin Name:       Xensor Maintenance
 * Plugin URI:        http://www.pixelmonmemories.ml
 * Description:       Ability to put website into maintenance and redirect banned members to a page
 * Version:           1.0.1
 * Author:            Xensor, ChuChuYokai
 * Author URI:        http://www.pixelmonmemories.ml
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       xensor-maintenance
 * Domain Path:       /languages
 */

global $jal_db_version;
$jal_db_version = '1.0';

function jal_install()
{
  global $wpdb;
  global $jal_db_version;

  $table_name      = $wpdb->prefix . 'maint';

  $charset_collate = $wpdb->get_charset_collate();

  $sql             = "CREATE TABLE IF NOT EXISTS $table_name (
  `maint_id` int(11) NOT NULL AUTO_INCREMENT,
  `maint` text  NOT NULL,
  `bid` int(11) NOT NULL,
  `sid` int(11) NOT NULL,
  `gid` int(11) NOT NULL,
  `mid` text  NOT NULL,
  `ban_url` text  NOT NULL,
  `ban_page` text  NOT NULL,
  `maint_page` text  NOT NULL,
  PRIMARY KEY (`maint_id`)
  ) $charset_collate;";

  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
  dbDelta( $sql );

}

function jal_install_data()
{
  global $wpdb;

  $dbhost          = '29743';

  $dbname          = '29564';

  $dbuser          = '29578';

  $dbpwd           = '/maintenance';

  $prod_img_folder = '/banned';
  $maint_status    = 'yes';
  $banned_page     = '29578';
  $maint_pages     = '29578';

  $table_name      = $wpdb->prefix . 'maint';

  $wpdb->insert(
    $table_name,
    array(
      'bid'       => $dbhost,
      'sid'       => $dbname,
      'gid'       => $dbuser,
      'mid'       =>$dbpwd,
      'ban_url'   => $prod_img_folder,
      'maint'     => $maint_status,
      'ban_page'  =>$banned_page,
      'maint_page'=> $maint_pages,
    )
  );
}
function maint_admin()
{
  include('maint_admin.php');
}
function maint_admin_actions()
{
  add_options_page("Maintenance", "maintenance", 1, "Maintenance", "maint_admin");
}


function redirect_members($page)
{
$user_id = get_current_user_id();
$member  = MS_Factory::load( 'MS_Model_Member', $user_id );

global $wpdb;
$maint = $wpdb->get_results( 'SELECT * FROM wprh_maint WHERE maint_id = 1', OBJECT );
//Normal page display
$bid        = $maint[0]->bid;
$sid        = $maint[0]->sid;
$gid        = $maint[0]->gid;
$mid        = $maint[0]->mid;
$ban_url    = $maint[0]->ban_url;

$maints     = $maint[0]->maint;
$ban_page   = $maint[0]->ban_page;
$maint_page = $maint[0]->maint_page;
foreach ( $member->subscriptions as $subscription ) {
$membership = MS_Factory::load( 'MS_Model_Membership', $subscription->membership_id );
// membership level matches the banned one
if ( $membership->id == $bid  ) {
if (is_page($ban_page)):
wp_redirect( $ban_url );
exit;
endif;
}
elseif ( $membership->id == $sid || $membership->id == $gid ) {
if (get_the_ID() != $maint_page) {
if ($maints == 'yes') {
if (get_the_ID() != 29761) {
wp_redirect( $mid );
exit;
}
}
}

}
}


}
// And here goes the uninstallation function:
function maint_uninstall()
{

// drop a custom database table
global $wpdb;
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}maint");
}

//register_activation_hook( __FILE__, 'jal_install' );
register_activation_hook( __FILE__, 'jal_install_data' );
register_uninstall_hook(__FILE__, 'maint_uninstall');
add_action('admin_menu', 'maint_admin_actions');
add_action('wp', 'redirect_members');
?>
