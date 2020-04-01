<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
/**
 * @package Gallrie
 */
/*
Plugin Name: Gallrie
Plugin URI: https://lynck.net/
Description: If you’re searching for a user friendly and have made plugin to feature responsive galleries and albums to your web site, this plugin may be the simplest possibility for you. It’s straightforward in use nevertheless full of powerful practicality, permitting you to form something from straightforward image galleries to commercialism digital content right from your web site. image Gallery comes full of beautiful layout choices, gallery and album views, multiple widgets and variety of extensions that take its practicality even more. This may be a nice alternative for photography websites and blogs, similarly as sites that need to possess strong image galleries with simple navigation.
Check the intensive feature list of the plugin bellow, have a glance at this fast and lightweight plugin.
Version: 2.1
Author: Lynck.net
Author URI: https://lynck.net/
Text Domain: gallrie
*/

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	die( 'No script kiddies please!' );
}
function custom_gallery()

{

  $name = 'Gallrie';

  $slug = 'gallrie';

  $labels = array(

    'name' => _x($name, 'post type general name'),

    'singular_name' => _x($name, 'post type singular name'),

    'add_new' => _x('Add New', 'post type singular name'),

    'add_new_item' => __('Add New '.$name),

    'edit_item' => __('Edit '.$name),

    'new_item' => __('New '.$name),

    'view_item' => __('View '.$name),

    'search_items' => __('Search '.$name),

    'not_found' =>  __('No '.$name.' found'),

    'not_found_in_trash' => __('No '.$name.' found in Trash'),

    'parent_item_colon' => ''

   );

  $args = array(

    'labels' => $labels,

    //'public' => true,

    //'publicly_queryable' => false,

	//'query_var' => true,

    'show_ui' => true,

	'rewrite' => false,

	'_builtin' => false,

    'capability_type' => 'post',

    'hierarchical' => false,

    'menu_position' => null,

	'menu_icon' => 'dashicons-admin-media',

    'supports' => array('title')

  );

   register_post_type($slug,$args);

}

add_action('init', 'custom_gallery');


// -----------------------------------------------------------

add_action( 'admin_init', 'add_post_gallery_so_14445904' );
add_action( 'admin_head-post.php', 'print_scripts_so_14445904' );
add_action( 'admin_head-post-new.php', 'print_scripts_so_14445904' );
add_action( 'save_post', 'update_post_gallery_so_14445904', 10, 2 );

function load_admin_things() {
    wp_enqueue_script('media-upload');
    wp_enqueue_script('thickbox');
    wp_enqueue_style('thickbox');
}

add_action( 'admin_enqueue_scripts', 'load_admin_things' );

function add_post_gallery_so_14445904()
{
    add_meta_box(
        'post_gallery',
        'Image Uploader For Gallery',
        'post_gallery_options_so_14445904',
        'gallrie',
        'normal',
        'core'
    );
}

function post_gallery_options_so_14445904()
{
    global $post;
    $gallery_data = get_post_meta( $post->ID, 'gallery_data', true );
?>
<style>
  .field_right.image_wrap img {
      display: block;
      height: auto;
      max-width: 100%;
      margin: 10px auto;
  }
	.field_row{
		padding: 15px;
    margin: 15px;
    border: 1px dashed #d2d2d2;
    border-radius: 10px;
	}
  .meta_image_desc,.meta_image_url {
      margin: 10px auto;
  }
</style>
<div id="dynamic_form">
    <div id="field_wrap">
    <?php
    if ( isset( $gallery_data['image_url'] ) )
    {
        for( $i = 0; $i < count( $gallery_data['image_url'] ); $i++ )
        {
        ?>
        <div class="field_row">
          <div class="field_left">
            <div class="form_field">
              <input type="text"
                     size="66"
										 placeholder="Image URL"
                     class="meta_image_url"
                     name="gallery[image_url][]"
                     value="<?php esc_html_e( $gallery_data['image_url'][$i] ); ?>"
              />
            </div>
            <div class="form_field">
              <input type="text"
                     size="66"
										 placeholder="Image Link / Description"
                     class="meta_image_desc"
                     name="gallery[image_desc][]"
                     value="<?php esc_html_e( $gallery_data['image_desc'][$i] ); ?>"
              />
            </div>
          </div>
          <div class="field_right image_wrap" style="width: 100%; float: left; clear: both;">
            <img style="width: 25%; float: left; clear: both;" src="<?php esc_html_e( $gallery_data['image_url'][$i] ); ?>" />
          </div>
          <div class="field_right">
            <p>
                <input class="button button-primary button-large" type="button" value="Choose File" onclick="add_image(this)" />
                <input class="button button-primary button-large" type="button" value="Remove File" onclick="remove_field(this)" />
            </p>
          </div>
          <div class="clear" /></div>
        </div>
        <?php
        } // endif
    } // endforeach
    ?>
    </div>
    <div style="display:none" id="master-row">
    <div class="field_row">
        <div class="field_left">
            <div class="form_field">
                <input size="66" placeholder="Image URL" class="meta_image_url" value="" type="text" name="gallery[image_url][]" />
            </div>
            <div class="form_field">
                <input size="66" placeholder="Image Link / Description" class="meta_image_desc" value="" type="text" name="gallery[image_desc][]" />
            </div>
        </div>
        <div class="field_right image_wrap" style="width: 100%; float: left; clear: both;">
        </div>
        <div class="field_right">
            <p>
                <input type="button" class="button button-primary button-large" value="Choose File" onclick="add_image(this)" />
                <input class="button button-primary button-large" type="button" value="Remove File" onclick="remove_field(this)" />
            </p>
        </div>
        <div class="clear"></div>
    </div>
    </div>
    <div id="add_field_row">
      <input class="button" type="button" value="Add Another Field" onclick="add_field_row();" />
    </div>
</div>
  <?php
}

function print_scripts_so_14445904()
{
    global $post;
    ?>
    <script type="text/javascript">
        function add_image(obj) {
            var parent=jQuery(obj).parent().parent().parent('div.field_row');
            var inputField = jQuery(parent).find("input.meta_image_url");

            tb_show('', 'media-upload.php?TB_iframe=true');

            window.send_to_editor = function(html) {
                var url = jQuery(html).attr('src');
								console.log(html);
								console.log(url);
                inputField.val(url);
                jQuery(parent).find("div.image_wrap").html('<img style="width: 25%; float: left; clear: both;" src="'+url+'" />');
                tb_remove();
            };

            return false;
        }

        function remove_field(obj) {
            var parent=jQuery(obj).parent().parent().parent();
            parent.remove();
        }

        function add_field_row() {
            var row = jQuery('#master-row').html();
            jQuery(row).appendTo('#field_wrap');
        }
    </script>
    <?php
}

/**
 * Save post action, process fields
 */
function update_post_gallery_so_14445904( $post_id, $post_object )
{
    // Doing revision, exit earlier **can be removed**
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
        return;
    // Doing revision, exit earlier
    if ( 'revision' == $post_object->post_type )
        return;
    if ( isset( $_POST['gallery'] ) )
    {
        // Build array for saving post meta
        $gallery_data = array();
        for ($i = 0; $i < count( $_POST['gallery']['image_url'] ); $i++ )
        {
            if ( '' != $_POST['gallery']['image_url'][ $i ] )
            {
                $gallery_data['image_url'][]  = $_POST['gallery']['image_url'][ $i ];
                $gallery_data['image_desc'][] = $_POST['gallery']['image_desc'][ $i ];
            }
        }

        if ( $gallery_data )
            update_post_meta( $post_id, 'gallery_data', $gallery_data );
        else
            delete_post_meta( $post_id, 'gallery_data' );
    }
    // Nothing received, all fields are empty, delete option
    else
    {
        delete_post_meta( $post_id, 'gallery_data' );
    }
}

// add shortcode to post/page

function custom_gallery_view( $atts ) {
  $atts = shortcode_atts( array(
    'id' => null,
    'mode' => 'slider',
    'height' => '',
    'width' => '',
    'sliderSpeed' => 500,
    'easing' => 'easeOutElastic',
    'movement' => 'horizontal',
  ), $atts );

  $get_current_atts = null;
  if ( ( $atts['id'] ) == null ) {
    # code...
    $args = array(
      'post_status' => 'publish',
      'showposts' => '1',
      'post_type' => 'gallrie'
    );

    $query = new WP_Query( $args );
    if ( $query->have_posts() ) {
      while ( $query->have_posts() ) {
        $query->the_post();
        $get_current_atts = get_the_ID();
      }
    }
  }

  else {
    	$get_current_atts = $atts['id'];
  }

  $gallery_data = get_post_meta( $get_current_atts, 'gallery_data', true );
  if ( $gallery_data ) {
    # code...
    $max_value = sizeof( $gallery_data['image_url'] );
    for ( $count = 0; $count < $max_value ; $count++ ) {

				if($atts['mode'] == 'popup'){
						echo '<a class="gallrie-popup-mode" title="'. $gallery_data['image_desc'][$count] .'" href="'.$gallery_data['image_url'][$count].'">
											<img src="'. $gallery_data['image_url'][$count] .'" height="'.$atts['height'].'" width="'.$atts['width'].'">
									</a>';
				}
				else if($atts['mode'] == 'slider'){

						if($count==0){
								echo '<div class="gallrie-slider">';
						}

						echo '<div>
											<img title="'. $gallery_data['image_desc'][$count] .'" src="'. $gallery_data['image_url'][$count] .'" height="'.$atts['height'].'" width="'.$atts['width'].'">
									</div>';

						if($count==$max_value-1){
								echo '</div>';
								echo "<script>jQuery(document).ready(function($){
													$('.gallrie-slider').bxSlider({
															easing: '".$atts['easing']."',
															speed: '".$atts['sliderSpeed']."',
															mode: '".$atts['movement']."',
															useCSS: false,
													});
											});</script>";
						}

				} else {
						echo '<img height="'.$atts['height'].'" width="'.$atts['width'].'" src="'. $gallery_data['image_url'][$count] .'" title="'. $gallery_data['image_desc'][$count] .'">';
				}
      	# code...

    }
  }
  else {
    echo '<h3>please create a gallery first </h3>';
  }
}
add_shortcode( 'custom-gallery','custom_gallery_view' );


add_action( 'wp_enqueue_scripts', 'my_custom_script_load' );
function my_custom_script_load(){
  	wp_enqueue_script( 'gallrie-magnific-script', plugin_dir_url( __FILE__ ) . 'js/jquery.magnific-popup.min.js', array( 'jquery' ) );
  	wp_enqueue_script( 'gallrie-easing-script', plugin_dir_url( __FILE__ ) . 'js/jquery.easing.1.3.js', array( 'jquery' ) );
  	wp_enqueue_script( 'gallrie-bxslider-script', plugin_dir_url( __FILE__ ) . 'js/jquery.bxslider.min.js', array( 'jquery' ) );
  	wp_enqueue_script( 'gallrie-custom-script', plugin_dir_url( __FILE__ ) . 'js/gallrie-custom-script.js', array( 'jquery' ) );
		wp_enqueue_style( 'gallrie-magnific-css', plugin_dir_url( __FILE__ ) . 'css/magnific-popup.css' );
		wp_enqueue_style( 'gallrie-bxslider-css', plugin_dir_url( __FILE__ ) . 'css/jquery.bxslider.css' );
		wp_enqueue_style( 'gallrie-custom-css', plugin_dir_url( __FILE__ ) . 'css/gallrie-custom-css.css' );
}
