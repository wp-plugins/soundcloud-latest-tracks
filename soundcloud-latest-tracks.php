<?php
/**
 * @package Soundcloud_Latest_Tracks
 * @version 1.3
 */
/*
Plugin Name: Soundcloud Latest Tracks
Plugin URI: http://wordpress.org/plugins/soundcloud-latest-tracks/
Description: This plugin simply allows you to choose a Soundcloud user and display an x amount of latest tracks from that user using a nice lil' shortcode.
Author: Gerald Campbell
Version: 1.3
Author URI: http://campbell-designs.com
*/
defined('ABSPATH') or die("No script kiddies please!");

function slt_enqueue_scripts() {
	wp_register_script( 'soundcloud-sdk', '//connect.soundcloud.com/sdk.js' );
	wp_register_script( 'slt-main', plugins_url( '/js/slt-main.min.js', __FILE__ ) , array('soundcloud-sdk'), '1.0' );
 
}
add_action('wp_enqueue_scripts', 'slt_enqueue_scripts');

function soundcloud_latest_tracks_shortcode($atts) {	
	extract(
		shortcode_atts(
			array(
				'user' => '',
				'show' => '3',
				'height' => '0',
				'show_comments' => "yes",
				'hear_more' => 'no',
				'visual' => 'yes',
				'color' => 'cc4400'
			),
			$atts
		)
	);
	if(empty($user)){
		return 'You must add a user attribute value to the shortcode <br/>e.g. [soundcloud_latest_tracks user="your_soundcloud_ID"]';
	}
	else if(!is_numeric(intval($show))){
		return 'The tracks attribute must be a number! <br/>e.g. [soundcloud_latest_tracks user="your_soundcloud_ID" show="3"]';
	}
	else{
		
		$output = '<div id="slt-tracks-container"></div>';
		$aText = get_option('slt_button_text');
		$aClass = get_option('slt_button_class');
		// Make all yes or nos lowercase
		$hear_more = strtolower($hear_more);
		$visual = strtolower($visual);
		$show_comments = strtolower($show_comments);
		if(empty($aText))
			$aText = 'Hear more';
		if(empty($aClass))
			$aClass = 'slt-hear-more';
		if($hear_more == 'yes')
			$output .= '<div class="slt-hear-more-container"><a id="slt-hear-more" class="'.$aClass.'" href="#hear_more">'.$aText.'</a></div>';
		// Make sure tracks and height are ints
		$tracks = intval($show);
		$height = intval($height);		
		$javaVariables = array( 
			'userId' => $user,
			'tracks' => $tracks,
			'maxheight' => $height,
			'show_comments' => ($show_comments=="yes" ? 1 : 0),
			'visual' => ($visual=="yes" ? 1 : 0),
			'color' => $color
		);
		wp_enqueue_script('soundcloud-sdk');
		wp_enqueue_script('jquery');
		wp_localize_script( 'slt-main', 'slt', $javaVariables );
		wp_enqueue_script('slt-main');
		return $output;
	} 
}
add_shortcode( 'soundcloud_latest_tracks' , 'soundcloud_latest_tracks_shortcode' );



/****************************************************
* Admin menu items
****************************************************/
function slt_settings_show(){
    ?>
    <div class="wrap">
        <?php screen_icon(); ?>
        <h2>Soundcloud Latest Tracks Settings</h2>       
        <form method="post" action="options.php">
        <?php
            // This prints out all hidden setting fields
            settings_fields( 'slt_options' );   
            ?>
            <div>Below you can customise the "Hear more" button text and class.</div><br/>
            <label for="slt_button_text">Text: </label>
            <input type="text" name="slt_button_text" value="<?php echo get_option('slt_button_text'); ?>" /><br/>
            <label for="slt_button_class">Class (if more than one seperate by a space): </label>
            <input type="text" name="slt_button_class" value="<?php echo get_option('slt_button_class'); ?>" /><br/>
            <?php
            submit_button(); 
        ?>            
        </form>

        <p>If you like the plugin then please <a href="https://wordpress.org/support/view/plugin-reviews/soundcloud-latest-tracks">leave me a review!</a></p>
    </div>
    <?php
}

/****************************************************
* Register menu items
****************************************************/
function add_admin_menu_items() {
    $hook = add_menu_page('SLT settings', 'SLT', 'edit_posts', 'slt-options', 'slt_settings_show', 'dashicons-playlist-audio');    
}
add_action('admin_menu' , 'add_admin_menu_items');



/****************************************************
* Register my settings
****************************************************/
function register_mysettings() {
    // SLT settings
    add_option("slt_button_text", "", "", "yes");
    register_setting( 'slt_options', 'slt_button_text' );
    add_option("slt_button_class", "", "", "yes");
    register_setting( 'slt_options', 'slt_button_class' );

}
add_action( 'admin_init', 'register_mysettings' );

class slt_plugin extends WP_Widget {

	// constructor
	function slt_plugin() {
		parent::WP_Widget(false, $name = __('SoundCloud Latest Tracks', 'wp_widget_plugin') );
	}

	// widget form creation
	function form($instance) {	
		// Check values
		if( $instance) {
			$title = esc_attr($instance['title']);
			$user = esc_attr($instance['user']);
			$show = esc_textarea($instance['show']);
			$show_comments = esc_attr($instance['show_comments']);
			$hear_more = esc_attr($instance['hear_more']);
			$visual = esc_attr($instance['visual']);
			$color = esc_attr($instance['color']);
		} else {
			$title = '';
			$user = '';
			$show = '';
			$show_comments = '';
			$hear_more = '';
			$visual = '';
			$color = '';
		}
		?>

		<p>
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget Title', 'wp_widget_plugin'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>

		<p>
		<label for="<?php echo $this->get_field_id('user'); ?>"><?php _e('SoundCloud User:', 'wp_widget_plugin'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('user'); ?>" name="<?php echo $this->get_field_name('user'); ?>" type="text" value="<?php echo $user; ?>" />
		</p>

		<p>
		<label for="<?php echo $this->get_field_id('show'); ?>"><?php _e('No. of tracks to show:', 'wp_widget_plugin'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('show'); ?>" name="<?php echo $this->get_field_name('show'); ?>" type="text" value="<?php echo $show; ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id('color'); ?>"><?php _e('Player color (leave empty for default orange):', 'wp_widget_plugin'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('color'); ?>" name="<?php echo $this->get_field_name('color'); ?>" type="text" value="<?php echo $color; ?>" />
		</p>
		<p>
		<input id="<?php echo $this->get_field_id('show_comments'); ?>" name="<?php echo $this->get_field_name('show_comments'); ?>" type="checkbox" value="1" <?php checked( '1', $show_comments ); ?> />
		<label for="<?php echo $this->get_field_id('show_comments'); ?>"><?php _e('Show comments', 'wp_widget_plugin'); ?></label>
		</p>
		<p>
		<input id="<?php echo $this->get_field_id('hear_more'); ?>" name="<?php echo $this->get_field_name('hear_more'); ?>" type="checkbox" value="1" <?php checked( '1', $hear_more ); ?> />
		<label for="<?php echo $this->get_field_id('hear_more'); ?>"><?php _e('Hear more button', 'wp_widget_plugin'); ?></label>
		</p>
		<p>
		<input id="<?php echo $this->get_field_id('visual'); ?>" name="<?php echo $this->get_field_name('visual'); ?>" type="checkbox" value="1" <?php checked( '1', $visual ); ?> />
		<label for="<?php echo $this->get_field_id('visual'); ?>"><?php _e('Visual player', 'wp_widget_plugin'); ?></label>
		</p>
		<?php
	}

	// widget update
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		// Fields
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['user'] = strip_tags($new_instance['user']);
		$instance['show'] = strip_tags($new_instance['show']);
		$instance['color'] = strip_tags($new_instance['color']);
		$instance['show_comments'] = strip_tags($new_instance['show_comments']);
		$instance['hear_more'] = strip_tags($new_instance['hear_more']);
		$instance['visual'] = strip_tags($new_instance['visual']);
		return $instance;
	}

	// widget display
	function widget($args, $instance) {
		extract( $args );
		// these are the widget options
		$title = apply_filters('widget_title', $instance['title']);
		$user = $instance['user'];
		$show = $instance['show'];
		$color = $instance['color'];
		$show_comments = $instance['show_comments'];
		$hear_more = $instance['hear_more'];
		$visual = $instance['visual'];
		echo $before_widget;

		// Check if title is set
		if ( $title ) {
		  echo $before_title . $title . $after_title;
		}

		$extraAttribs = '';
		if($show_comments AND $show_comments == '1' )
			$extraAttribs .= 'show_comments="yes" ';
		if($hear_more AND $hear_more == '1' )
			$extraAttribs .= 'hear_more="yes" ';
		if($visual AND $visual == '1' )
			$extraAttribs .= 'visual="yes" ';
		else 
			$extraAttribs .= 'visual="no" ';
		if($color != '')
			$extraAttribs .= ('color="'.$color.'" ');

		echo do_shortcode( sprintf('[soundcloud_latest_tracks user="%s" show="%s" %s]', $user, $show, $extraAttribs) );

		echo $after_widget;
	}
}

// register widget
add_action('widgets_init', create_function('', 'return register_widget("slt_plugin");'));

?>
