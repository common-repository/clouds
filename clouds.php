<?php 
/*
Plugin Name: Clouds
Plugin URI: http://redyellow.co.uk/plugins/clouds
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=rgubby%40googlemail%2ecom&lc=GB&item_name=Richard%20Gubby%20%2d%20WordPress%20plugins&currency_code=GBP&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted
Description: Movable clouds on your site!
Author: Rich Gubby
Version: 1.2.2
Author URI: http://redyellow.co.uk
*/

/**
 * Function to display clouds on a page
 * @return void
 */
function movableClouds()
{
	// Path to this directory
	$pluginPath = WP_PLUGIN_URL.'/'.basename(dirname(__FILE__));
	
	echo '<div id="clouds"><div id="cloudContainer">';
	
	echo '
	<div id="cloudId0" class="cloud cloudType7" style="top: -11px;display:none;"><img src="'.$pluginPath.'/cloud7.png" alt="Cloud" title="Cloud" width="161" height="90" /></div>
	<div id="cloudId1" class="cloud cloudType4" style="top: 150px;display:none;"><img src="'.$pluginPath.'/cloud4.png" alt="Cloud" title="Cloud" width="174" height="77" /></div>
	<div id="cloudId2" class="cloud cloudType1" style="top: 68px;display:none;"><img src="'.$pluginPath.'/cloud1.png" alt="Cloud" title="Cloud" width="199" height="86" /></div>
	<div id="cloudId3" class="cloud cloudType8" style="top: -20px;display:none;"><img src="'.$pluginPath.'/cloud8.png" alt="Cloud" title="Cloud" width="416" height="43" /></div>
	<div id="cloudId4" class="cloud cloudType5" style="top: 129px;display:none;"><img src="'.$pluginPath.'/cloud5.png" alt="Cloud" title="Cloud" width="199" height="95" /></div>
	<div id="cloudId5" class="cloud cloudType9" style="top: 80px;display:none;"><img src="'.$pluginPath.'/cloud9.png" alt="Cloud" title="Cloud" width="89" height="40" /></div>
	<div id="cloudId6" class="cloud cloudType2" style="top: 335px;display:none;"><img src="'.$pluginPath.'/cloud2.png" alt="Cloud" title="Cloud" width="290" height="72" /></div>
	<div id="cloudId7" class="cloud cloudType6" style="top: 292px;display:none;"><img src="'.$pluginPath.'/cloud6.png" alt="Cloud" title="Cloud" width="117" height="55" /></div>
	<div id="cloudId8" class="cloud cloudType12" style="top: 317px;display:none;"><img src="'.$pluginPath.'/cloud12.png" alt="Cloud" title="Cloud" width="187" height="72" /></div>
	<div id="cloudId9" class="cloud cloudType3" style="top: 317px;display:none;"><img src="'.$pluginPath.'/cloud3.png" alt="Cloud" title="Cloud" width="191" height="48" /></div>
	<div id="cloudId10" class="cloud cloudType11" style="top: 317px;display:none;"><img src="'.$pluginPath.'/cloud11.png" alt="Cloud" title="Cloud" width="178" height="61" /></div>
	<div id="cloudId11" class="cloud cloudType10" style="top: 317px;display:none;"><img src="'.$pluginPath.'/cloud10.png" alt="Cloud" title="Cloud" width="197" height="56" /></div>';
	
	echo '</div></div>';
	
	if(!$clouds = get_option('movable_clouds_move_clouds'))
	{
		$clouds = 0;
	}
	if(!$minspeed = get_option('movable_clouds_min_speed'))
	{
		$minspeed = 100;
	}
	if(!$maxspeed = get_option('movable_clouds_max_speed'))
	{
		$maxspeed = 300;
	}
	echo '<script type="text/javascript">var homepageClouds = new clouds('.$clouds.','.$minspeed.', '.$maxspeed.');</script>
	';
}
/**
 * Load cloud stuff into header
 * @return void
 */
function movableCloudsLoadIntoHead() 
{ 
	// Path to this directory
	$pluginPath = WP_PLUGIN_URL.'/'.basename(dirname(__FILE__));
	
	echo '<link rel="stylesheet" href="'.$pluginPath.'/clouds.css" type="text/css" media="all" />';
	echo '<script type="text/javascript" src="'.$pluginPath.'/prototype.js"></script>';
	echo '<script type="text/javascript" src="'.$pluginPath.'/scriptaculous.js?load=effects,builder"></script>';
	echo '<script type="text/javascript" src="'.$pluginPath.'/clouds.js"></script>';
}
/**
 * jQuery and Prototype don't play well with each other - this fixes it
 * @param unknown_type $js_array
 * @return void
 */
function jquery_no_conflict_follows_jquery( $js_array ) { if ( false === $jquery = array_search( 'jquery', $js_array ) ) return $js_array; if ( false === $jquery_no_conflict = array_search( 'jquery_no_conflict', $js_array ) ) return $js_array; if ( $jquery_no_conflict == $jquery + 1 ) return $js_array; array_splice( $js_array, $jquery + 1, 0, 'jquery_no_conflict' ); unset($js_array[$jquery_no_conflict + ($jquery_no_conflict < $jquery ? 0 : 1)]); return $js_array; } 

// Load jQuery / prototype no conflict code
add_filter('print_scripts_array', 'jquery_no_conflict_follows_jquery' );
// Load header stuff
add_filter('wp_head', 'movableCloudsLoadIntoHead');
// Add the cloud code into the footer
add_action('wp_footer', 'movableClouds');

// Add options to admin menu
add_action('admin_menu', 'add_clouds_admin_options_page');

if(!function_exists('add_clouds_admin_options_page'))
{
	function add_clouds_admin_options_page()
	{
		add_options_page('Cloud Settings', 'Movable Clouds', 'administrator', basename(__FILE__), 'clouds_admin_options_page');
	}
}

if(!function_exists('clouds_admin_options_page'))
{
	function clouds_admin_options_page()
	{
		if (isset($_POST['info_update'])) 
		{
			$updateOption = false;
			
			// Save Cloud Animation
			if(clouds_admin_save_option('movable_clouds_move_clouds')) $updateOption = true;
			// Save Min Cloud Speed
			if(clouds_admin_save_option('movable_clouds_min_speed')) $updateOption = true;
			// Save Max Cloud Speed
			if(clouds_admin_save_option('movable_clouds_max_speed')) $updateOption = true;
		}
		
		if(isset($updateOption) && $updateOption == true)
		{
			echo "<div class='updated fade'><p><strong>Settings saved</strong></p></div>";
		}
			
		echo '<div class="wrap">';
		echo '<form method="post" action="options-general.php?page=clouds.php" enctype="multipart/form-data">';
		echo '<h2>Movable Cloud Settings</h2>';
		
		echo '<table class="form-table" cellspacing="2" cellpadding="5">';

		// Animate the clouds
		echo clouds_admin_option('select', array('label' => 'Animate clouds', 'name' => 'movable_clouds_move_clouds', 'options' => array('1' => 'Yes', '0' => 'No'), 'value' => get_option('movable_clouds_move_clouds'), 'description' => '<br />Animate clouds so they move from right to left'));
		echo clouds_admin_option('text', array('value' => 'The lower a cloud speed the faster it\'ll go!', 'bold' => true));
		
		// Min cloud speed
		if(!$value = get_option('movable_clouds_min_speed'))
		{
			$value = 100;
		}
		echo clouds_admin_option('input', array('label' => 'Min cloud speed (default 100)', 'size' => 5, 'name' => 'movable_clouds_min_speed', 'value' => $value, 'description' => '<br />Minimum speed for moving clouds'));
		// Max cloud speed
		if(!$value = get_option('movable_clouds_max_speed'))
		{
			$value = 300;
		}
		echo clouds_admin_option('input', array('label' => 'Max cloud speed (default 300)', 'size' => 5, 'name' => 'movable_clouds_max_speed', 'value' => $value, 'description' => '<br />Maximum speed for moving clouds'));
		
		
		echo '</table><table class="form-table" cellspacing="2" cellpadding="5" width="100%"><tr><td><p class="submit"><input class="button-primary" type="submit" name="info_update" value="Save Changes" /></p></td></tr></table>';
		echo '</form></div>';
	}
}

if(!function_exists('clouds_admin_save_option'))
{
	function clouds_admin_save_option($option)
	{		
		if($_POST[$option] != get_option($option))
		{
			update_option($option, $_POST[$option]);
			return true;
		}
	}
}

if(!function_exists('clouds_admin_option'))
{
	function clouds_admin_option($type, $options = array())
	{
		$string  = '<tr>';
		if($type != 'text')
		{
			$string .= '<th width="30%" valign="top">';
			
			if(isset($options['name']))
			{
				$string .= '<label for="'.$options['name'].'">'.$options['label'].': </label>';
			}
			$string .= '</th>';
			$string .= '<td>';
		}  else
		{
			$string .= '<td colspan="2">';
		}
		
		switch($type)
		{
			case 'input' : 
				if(!isset($options['size']))
				{
					$options['size'] = 40;
				}
				if(isset($options['before']) AND $options['before'] != '')
				{
					$string .= $options['before'];
				}
				$string .= '<input';
				
				if($options['size'] == 40)
				{
					$string .= ' class="regular-text"';
				}
				$string .= ' size="'.$options['size'].'" type="text" name="'.$options['name'].'" id="'.$options['name'].'" value="'.$options['value'].'" />';
				
				if(isset($options['after']) AND $options['after'] != '')
				{
					$string .= $options['after'];
				}
				break;
			case 'select' :
				$string .= '<select name="'.$options['name'].'">';
				
				foreach($options['options'] as $key => $val)
				{
					$string .= '<option value="'.$key.'"';
				
					if($key == $options['value'])
					{
						$string .= ' selected="selected"';
					}
					
					$string .= '>'.$val.'</option>';
				}
				
				$string .= '</select>';
				break;
			case 'text' :
				$string .= '<p>';
				if(isset($options['bold'])) $string .= '<strong>';
				if(isset($options['italic'])) $string .= '<em>';
				
				$string .= $options['value'];
				
				if(isset($options['italic'])) $string .= '</em>';
				if(isset($options['bold'])) $string .= '</strong>';
				$string .= '</p>';
				break;
		}
		
		if(isset($options['description']) && $type != 'text')
		{
			$string .= '<span class="description">'.$options['description'].'</span>';
		}
		
		$string .= '</td></tr>';
		return $string;
	}
}
