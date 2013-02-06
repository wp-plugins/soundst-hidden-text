<?php
/*
* Plugin Name: Soundst Hidden Text
* Plugin URI: http://soundst.com/
* Description: Allows to add text to the end of post/page, that we can hide/show
* Author URI: http://soundst.com
* Version: 0.0.12
*/


add_action('admin_menu', 'register_add_toggle_text');

add_filter('the_content', 'add_toggle_content', 99);

add_action('init', 'hidden_script');



function hidden_script() {
	wp_register_script( 'jquery-toggle', plugins_url('hidden_text.js', __FILE__), array('jquery'), '1.1', false );
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'jquery-toggle' );
}


/** Add submenu **/

function register_add_toggle_text() {
	add_submenu_page( 'options-general.php', 'Soundst Hidden Text', 'Soundst Hidden Text', 'manage_options', 'soundst_hidden_text_submenu_page', 'toggle_text_submenu_page_callback' );
}

function toggle_text_submenu_page_callback() {

	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
	
	

	$CTOP = get_option('toggle_text_submenu_page');
	if(!$CTOP){
		$CTOP_defaults = array();
		$CTOP_defaults['toggle_text_color'] = '#000';
		$CTOP_defaults['toggle_tag_color'] = '#000';
		$CTOP_defaults['activated_toggle_tag_color'] = '#000';
		$CTOP_defaults['toggle_background_color'] = '#fff';
		$CTOP_defaults['toggle_border'] = '#000';
		$CTOP_defaults['default_tag'] = '';
		$CTOP_defaults['default_activated_tag'] = '';
		
		
		add_option( "toggle_text_submenu_page", $CTOP_defaults );
		$CTOP = get_option('toggle_text_submenu_page');
	}

	if(isset($_POST['submit_postdata'])) {
		$CTOP['toggle_text_color'] =  mysql_real_escape_string($_POST['toggle_text_color']);
		$CTOP['toggle_tag_color'] =  mysql_real_escape_string($_POST['toggle_tag_color']);
		$CTOP['activated_toggle_tag_color'] =  mysql_real_escape_string($_POST['activated_toggle_tag_color']);
		$CTOP['toggle_background_color'] =  mysql_real_escape_string($_POST['toggle_background_color']);
		$CTOP['toggle_border'] =  mysql_real_escape_string($_POST['toggle_border']);
		$CTOP['default_tag'] = $_POST['default_tag'];
		$CTOP['default_activated_tag'] =  mysql_real_escape_string($_POST['default_activated_tag']);
		
		update_option('toggle_text_submenu_page',$CTOP);
		
	}?>
	<div class="wrap">
	<h3>Soundst Hidden Text</h3> 
		<form id="CTOP-form" method="post" style="margin-bottom: 5px;">
			<table>
								
				<tr>
					<td><label for="toggle_tag_color">Tag color</label></td>
					<td><input type="text" id="toggle_tag_color" name="toggle_tag_color" value="<?php echo $CTOP['toggle_tag_color']; ?>"></td>  
				</tr>
				
				<tr>
					<td><label for="activated_toggle_tag_color">Activated tag color</label></td>
					<td><input type="text" id="activated_toggle_tag_color" name="activated_toggle_tag_color" value="<?php echo $CTOP['activated_toggle_tag_color']; ?>"></td>  
				</tr>
				
				<tr>
					<td><label for="toggle_text_color">Text color</label></td>
					<td><input type="text" id="toggle_text_color" name="toggle_text_color" value="<?php echo $CTOP['toggle_text_color']; ?>"></td>  
				</tr>
				
				<tr>
					<td><label for="toggle_background_color">Text background color</label></td>
					<td><input type="text" id="toggle_background_color" name="toggle_background_color" value="<?php echo $CTOP['toggle_background_color']; ?>"></td>  
				</tr>
				
				<tr>
					<td><label for="toggle_border">Text border size/type/color (ie: 1px solid #ccc)</label></td>
					<td><input type="text" id="toggle_border" name="toggle_border" value="<?php echo $CTOP['toggle_border']; ?>"></td>  
				</tr>
				
				<tr>
					<td><label for="default_tag">Default tag</label></td>
					<td><input type="text" id="default_tag" name="default_tag" value="<?php echo $CTOP['default_tag']; ?>"></td>  
				</tr>
				<tr>
					<td><label for="default_activated_tag">Default activated tag</label></td>
					<td><input type="text" id="default_activated_tag" name="default_activated_tag" value="<?php echo $CTOP['default_activated_tag']; ?>"></td>  
				</tr>
				<tr>
					<td><br><input name='submit_postdata' id='submit_postdata' class="button-primary" type='submit' value='Save Settings' /></td>
					<td></td>
				</tr>
			</table>
		</form>
	</div>
<?php 
}

function add_toggle_content($content) {
	global $post;
	$minus_way = plugin_dir_url(__FILE__) . 'img/minus.gif';
	$plus_way = plugin_dir_url(__FILE__) . 'img/plus.gif';
	$cf = get_post_meta($post->ID, 'TextToggle', TRUE);
	
	$hidden_elements = get_post_meta( $post->ID, 'hidden_elements', true );
	$default_custom_tag = $hidden_elements['default_custom_tag'];
	$default_activated_custom_tag = $hidden_elements['default_activated_custom_tag'];
	$hidden_text = $hidden_elements['hidden_text'];

	$CTOP = get_option('toggle_text_submenu_page');
	$def_activated_tag = $CTOP['default_activated_tag'];
	
	if ($default_custom_tag != '') { $def_tag = $default_custom_tag; } else { $def_tag = $CTOP['default_tag']; }
	if ($default_activated_custom_tag != '') { $def_activated_tag = $default_activated_custom_tag; } else { $def_activated_tag = $CTOP['default_activated_tag']; }
	if ($hidden_text != '') {	$display_text = nl2br($hidden_text); } else {	$display_text = nl2br($cf); }
	
	
	if ($display_text != '') {
		return $content . 
		" <div class='toggle_link'>
		<a class='plus_image' href='#'>
			<img src='{$plus_way}' alt='' />
			{$def_tag}
		</a>
		<a class='minus_image' href='#'>
			<img src='{$minus_way}' alt='' />
			{$def_activated_tag}
		</a>
		</div><div class='toggle'>" . $display_text . 
		'</div>';	
	} else {
		return $content;
	}
}


/* Test */
function output_style() {
	$theme_options = get_option('toggle_text_submenu_page');
	
	$toggle_text_color = $theme_options['toggle_text_color'];
	$toggle_tag_color = $theme_options['toggle_tag_color'];
	$activated_toggle_tag_color = $theme_options['activated_toggle_tag_color'];
	$toggle_background_color = $theme_options['toggle_background_color'];
	$toggle_border = $theme_options['toggle_border'];
	
	
	$content = '<style type="text/css">
		.toggle ul,
		.toggle {
			color: ' . $toggle_text_color . ';
			background: ' . $toggle_background_color . ' !important;
			border: ' . $toggle_border . ';
		}
		.toggle ul {
			border: none !important;
			padding-left: 20px;
		}
		.toggle {
			margin-bottom: 10px;
		}
		.minus_image {
			color: ' . $toggle_tag_color . ';
		}
		.plus_image {
			color: ' . $activated_toggle_tag_color . ';
		}
	</style>';


	echo $content;
}
add_action('wp_head', 'output_style');


/** Add metabox **/

add_action('admin_init','add_box', 1);
add_action('save_post','hidden_save');

function add_box() {
	add_meta_box( 
		'hidden_text_section',
		__( 'Soundst Hidden Text', 'hidden_text' ),
		'hidden_text_box',
		'post'
	);
	add_meta_box(
		'hidden_text_section',
		__( 'Soundst Hidden Text', 'hidden_text' ), 
		'hidden_text_box',
		'page'
	);

	
}

function hidden_text_box() {
	//wp_nonce_field( plugin_basename( __FILE__ ), 'myplugin_noncename' );
	
	global $post;

	$hidden_elements = get_post_meta( $post->ID, 'hidden_elements', true );
	?>
	<table>
		<tr>
			<td><label for="default_custom_tag"><b>Tag</b></label></td>
			<td><input type="text" id="default_custom_tag" name="default_custom_tag" value="<?php  echo $hidden_elements['default_custom_tag']; ?>" size="40" /></td>
		</tr>
		
		<tr>
			<td><label for="default_activated_custom_tag"><b>Activated Tag</b></label></td>
			<td><input type="text" id="default_activated_custom_tag" name="default_activated_custom_tag" value="<?php  echo $hidden_elements['default_activated_custom_tag']; ?>" size="40" /></td>
		</tr>
		
		<tr>
			<td><label for="hidden_text"><b>Hidden Text</b></label></td>
			<td><textarea rows="10" cols="160" name="hidden_text" id="hidden_text"><?php echo stripslashes($hidden_elements['hidden_text']); ?></textarea></td>
		</tr>
	</table>
	<?php 
}


function hidden_save() {

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return;
	
	$post_ID = $_POST['post_ID'];
	
	$hidden_elements = array();
	$hidden_elements['default_custom_tag'] = sanitize_text_field( $_POST['default_custom_tag'] );
	$hidden_elements['default_activated_custom_tag'] = sanitize_text_field( $_POST['default_activated_custom_tag'] );
	$hidden_elements['hidden_text'] = addslashes( $_POST['hidden_text'] );
	
	if ($hidden_elements) {
		update_post_meta($post_ID, 'hidden_elements', $hidden_elements);
	} else {
		add_post_meta($post_ID, 'hidden_elements', $hidden_elements, true);
	}
}