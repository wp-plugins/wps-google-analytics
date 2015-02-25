<?php
/*
Plugin Name: WPS Google Analytics
Plugin URI: http://www.vinojcardoza.com/blog/wps-google-analytics/
Description: WPS Google Analytics allows you to easilly add your Google Analytics code through out the whole site.  
Version: 1.1
Author: Vinoj Cardoza
Author URI: http://www.vinojcardoza.com
License: GPL2
*/

add_action("plugins_loaded", "cawpsga_init");
add_action("admin_menu", "cawpsga_menu");
add_action("wp_enqueue_scripts", "put_ga_code");

function put_ga_code()
{
	$ga_track_id = get_option('cawps_ga_track_id');
	$sub_domain = get_option('cawps_ga_sub_domain');
	$sub_domain_url = get_option('cawps_ga_sub_domain_url');
	$allow_multiple_domain = get_option('cawps_ga_multiple_domain');
	
	print "<script type='text/javascript'>";
	print "var _gaq = _gaq || [];";
	print "_gaq.push(['_setAccount', '".$ga_track_id."']);";
	if($sub_domain == 'yes')
	{
		if(!empty($sub_domain_url))
		{
			print "_gaq.push(['_setDomainName', '".$sub_domain_url."']);";
			if($allow_multiple_domain == 'yes')
				print "_gaq.push(['_setAllowLinker', true]);";
		}		
	}
	print "_gaq.push(['_trackPageview']);";
	print "(function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	})();
</script>";		
}

function cawpsga_init(){
	load_plugin_textdomain('cawpsgoogleanalytics', false, dirname( plugin_basename(__FILE__)).'/languages');
}

function cawpsga_menu(){
	
	add_menu_page(
		__('WPS Google Analytics'), 
		__('Google Analytics'), 
		'manage_options', 
		'cawps-google-analytics', 
		'cawps_google_analytics_options_page',
		plugin_dir_url(__FILE__).'google-analytics-icon.png');    
    /*add_submenu_page(
            'cawps-google-analytics',
            __('Stats'),
            __('Stats'),
            'manage_options',
            'cawps_stats',
			'cawps_stats');*/
}

function cawps_google_analytics_options_page(){
	
	if(isset($_POST['frm_submit'])){
			
		if(!empty($_POST['frm_track_id'])) 
			update_option('cawps_ga_track_id', $_POST['frm_track_id']);
		
		if(!empty($_POST['frm_sub_domain'])) 
			update_option('cawps_ga_sub_domain', $_POST['frm_sub_domain']);
		
		if(!empty($_POST['frm_sub_domain_url'])) 
			update_option('cawps_ga_sub_domain_url', $_POST['frm_sub_domain_url']);
		
		if(!empty($_POST['frm_multiple_top_level'])) 
			update_option('cawps_ga_multiple_domain', $_POST['frm_multiple_top_level']);
?>
		<div id="message" class="updated fade"><p><strong><?php _e('Options saved.', 'cawpsgoogleanalytics' ); ?></strong></p></div>
<?php	
	}
	$track_id = get_option('cawps_ga_track_id');
	$sub_domain = get_option('cawps_ga_sub_domain');
	$sub_domain_url = get_option('cawps_ga_sub_domain_url');
	$allow_multiple_domain = get_option('cawps_ga_multiple_domain');
?>
	<div class="wrap">
		<table>
			<tr>
				<td><img src="<?php print plugin_dir_url(__FILE__).'google-analytics-icon-32.png';?>" /></td>
				<td><h1><?php echo __("WPS Google Analytics", "cawpsgoogleanalytics");?></h1></td>
			</tr>	
		</table>
		
		<!-- Administration panel form -->
		<form method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
		<table>
			<tr>
				<td><h2><?php _e('Settings', 'cawpsgoogleanalytics');?></h2></td>
				<td></td>
			</tr>
			<tr height="35">
				<td>
					<h3><?php _e('Google Analytics Tracking ID', 'cawpsgoogleanalytics');?>:</h3>
				</td>
				<td>
					<input type="text" name="frm_track_id" size="50" value="<?php echo get_option('cawps_ga_track_id');?>"/>
				</td>
			</tr>
			<tr height="35">
				<td>
					<h3><?php _e('Is this site installed in sub domain?', 'cawpsgoogleanalytics');?>:</h3>
				</td>
				<td>
					<select name="frm_sub_domain" style="width:75px;">
						<option value="yes"<?php if($sub_domain == 'yes') print ' selected="selected"';?>><?php _e('Yes', 'cawpsgoogleanalytics');?></option>
						<option value="no"<?php if($sub_domain == 'no') print ' selected="selected"';?>><?php _e('No', 'cawpsgoogleanalytics');?></option> 
					</select>
				</td>
			</tr>
			<tr height="35">
				<td>
					<h3><?php _e('Sub domain URL?', 'cawpsgoogleanalytics');?>:</h3>
				</td>
				<td>
					<input type="text" name="frm_sub_domain_url" size="50" value="<?php echo get_option('cawps_ga_sub_domain_url');?>"/>
				</td>
			</tr>
			<tr height="35">
				<td>
					<h3><?php _e('Allow multiple top-level domains?', 'cawpsgoogleanalytics');?>:</h3>
					<p><?php _e('Examples: www.yoursite.com -and- www.yoursite.co.uk', 'cawpsgoogleanalytics');?></p>
				</td>
				<td>
					<select name="frm_multiple_top_level" style="width:75px;">
						<option value="yes"<?php if($allow_multiple_domain == 'yes') print ' selected';?>><?php _e('Yes', 'cawpsgoogleanalytics');?></option>
						<option value="no"<?php if($allow_multiple_domain == 'no') print ' selected';?>><?php _e('No', 'cawpsgoogleanalytics');?></option> 
					</select>
				</td>
			</tr>
		</table>
		<input type="submit" name="frm_submit" class="button button-primary" value="<?php _e('Save', 'cawpsgoogleanalytics');?>"/>
		</form>
	</div>
<?php
}
?>
