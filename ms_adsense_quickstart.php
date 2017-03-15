<?php
/*
Plugin Name: Google Adsense Quickstart
Plugin URI: http://michaelseiler.net
Description: Adds a Google adsense page_ad code head of your theme, by hooking to wp_head.
Author: Michael Seiler
Version: 1.0
 */

add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'add_adsense_links' );

function add_adsense_links ( $links ) {
	 $mylinks = array(
	 '<a href="' . admin_url( 'options-general.php?page=ms_adsense_quickstart/ms_adsense_quickstart.php' ) . '">Settings</a>',
	 );
	return array_merge( $links, $mylinks );
}

function ms_adsense_quickstart() { 
$msaq_code = get_option('MSAQ_ACCOUNT');
echo $msaq_code;
}
add_action( 'wp_head', 'ms_adsense_quickstart', 10 );

# CHECK TO SEE IF WE ARE GETTING AN UPDATE FROM OUR ADMIN SETTINGS PAGE
if( ($_POST) && ($_POST['updateMSAQ'] == "YES") )
{
	# UPDATE WP_OPTIONS TABLE
	update_option('MSAQ_ACCOUNT',$_POST['msaq_code']);
		
	# NOW CALL THE ADMIN SETTINGS MENU AGAIN
	add_action('admin_menu', 'msaq_admin_menu');
}
else
{
	# CALL THE ADMIN SETTINGS MENU
	add_action('admin_menu', 'msaq_admin_menu');
}

function msaq_admin_menu() {
	add_options_page("Google Analytics Settings","Google Analytics Settings","activate_plugins",__FILE__,"msaq_options_page");
}

function msaq_options_page() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	else
	{
		$msaq_code = get_option('MSAQ_ACCOUNT');
?>
<div class="wrap">
<h2><?php _e('Settings for Google Adsense Quickstart','msaq');?></h2>
<?php if($_POST['updateMSAQ'] == "YES") { ?>
<div id='setting-error-settings_updated' class='updated settings-error'><p><strong><?php echo _e('Tracking ID Saved','msaq');?></strong></p></div>
<?php } ?>
<h3>Google Adsense Quickstart Code</h3>
<p>Enter the full code snippet from Google Adsense Quickstart below:</p>
<form method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
<input type="hidden" name="updateMSAQ" value="YES">
<table border="0" width="50%" cellpadding="2" cellspacing="0">
    <tr>
        <td>
		<textarea name="msaq_code" id="msaq_code" cols="60" rows="10"><?php echo $msaq_code;?></textarea>
		</td>
    </tr>
	<tr>
        <td><input type="submit" name="Submit" value="<?php _e('Save Quickstart Code Snippet','msaq');?>"></td>
    </tr>
</table>
</form>
</div>
<?php
	}
}
