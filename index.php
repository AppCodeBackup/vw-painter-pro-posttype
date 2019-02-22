<?php 
/*
 Plugin Name: VW Painter Pro Posttype
 lugin URI: https://www.vwthemes.com/
 Description: Creating new post type for VW Painter Pro Theme.
 Author: VW Themes
 Version: 1.0
 Author URI: https://www.vwthemes.com/
*/

define( 'VW_PAINTER_PRO_POSTTYPE_VERSION', '1.0' );
add_action( 'init', 'createcategory');
add_action( 'init', 'vw_painter_pro_posttype_create_post_type' );

function vw_painter_pro_posttype_create_post_type() {
  register_post_type( 'project',
    array(
      'labels' => array(
        'name' => __( 'Project','vw-painter-pro-posttype' ),
        'singular_name' => __( 'Project','vw-painter-pro-posttype' )
      ),
      'capability_type' => 'post',
      'menu_icon'  => 'dashicons-portfolio',
      'public' => true,
      'supports' => array(
        'title',
        'editor',
        'thumbnail'
      )
    )
  );
  register_post_type( 'services',
    array(
      'labels' => array(
        'name' => __( 'Services','vw-painter-pro-posttype' ),
        'singular_name' => __( 'Services','vw-painter-pro-posttype' )
      ),
      'capability_type' => 'post',
      'menu_icon'  => 'dashicons-portfolio',
      'public' => true,
      'supports' => array(
        'title',
        'editor',
        'thumbnail'
      )
    )
  );

  register_post_type( 'faq',
    array(
      'labels' => array(
        'name' => __( 'Faq','vw-painter-pro-posttype' ),
        'singular_name' => __( 'Faq','vw-painter-pro-posttype' )
      ),
      'capability_type' => 'post',
      'menu_icon'  => 'dashicons-editor-help',
      'public' => true,
      'supports' => array(
        'title',
        'editor'
      )
    )
  );

  register_post_type( 'testimonials',
    array(
  		'labels' => array(
  			'name' => __( 'Testimonials','vw-painter-pro-posttype' ),
  			'singular_name' => __( 'Testimonials','vw-painter-pro-posttype' )
  		),
  		'capability_type' => 'post',
  		'menu_icon'  => 'dashicons-businessman',
  		'public' => true,
  		'supports' => array(
  			'title',
  			'editor',
  			'thumbnail'
  		)
		)
	);
}

/*--------------- Project section ----------------*/
function createcategory() {
  // Add new taxonomy, make it hierarchical (like categories)
  $labels = array(
    'name'              => __( 'Project Category', 'vw-painter-pro-posttype' ),
    'singular_name'     => __( 'Project Category', 'vw-painter-pro-posttype' ),
    'search_items'      => __( 'Search Ccats', 'vw-painter-pro-posttype' ),
    'all_items'         => __( 'All Project Category', 'vw-painter-pro-posttype' ),
    'parent_item'       => __( 'Parent Project Category', 'vw-painter-pro-posttype' ),
    'parent_item_colon' => __( 'Parent Project Category:', 'vw-painter-pro-posttype' ),
    'edit_item'         => __( 'Edit Project Category', 'vw-painter-pro-posttype' ),
    'update_item'       => __( 'Update Project Category', 'vw-painter-pro-posttype' ),
    'add_new_item'      => __( 'Add New Project Category', 'vw-painter-pro-posttype' ),
    'new_item_name'     => __( 'New Project Category Name', 'vw-painter-pro-posttype' ),
    'menu_name'         => __( 'Project Category', 'vw-painter-pro-posttype' ),
  );
  $args = array(
    'hierarchical'      => true,
    'labels'            => $labels,
    'show_ui'           => true,
    'show_admin_column' => true,
    'query_var'         => true,
    'rewrite'           => array( 'slug' => 'createcategory' ),
  );
  register_taxonomy( 'createcategory', array( 'project' ), $args );
}
/* Adds a meta box to the portfolio editing screen */
function vw_painter_pro_posttype_bn_work_meta_box() {
  add_meta_box( 'vw-painter-pro-posttype-portfolio-meta', __( 'Enter Details', 'vw-painter-pro-posttype' ), 'vw_painter_pro_posttype_bn_work_meta_callback', 'project', 'normal', 'high' );
}
// Hook things in for admin
if (is_admin()){
    add_action('admin_menu', 'vw_painter_pro_posttype_bn_work_meta_box');
}
/* Adds a meta box for custom post */
function vw_painter_pro_posttype_bn_work_meta_callback( $post ) {
  wp_nonce_field( basename( __FILE__ ), 'vw_project_meta_nonce' );
  $bn_stored_meta = get_post_meta( $post->ID );
  //date details
  if(!empty($bn_stored_meta['vw_work_project_url'][0]))
    $bn_vw_work_project_url = $bn_stored_meta['vw_work_project_url'][0];
  else
    $bn_vw_work_project_url = '';
  ?>
  <div id="portfolios_custom_stuff">
    <table id="list">
      <tbody id="the-list" data-wp-lists="list:meta">
        <tr id="meta-1">
          <td class="left">
            <?php esc_html_e( 'Project Url', 'vw-painter-pro-posttype' )?>
          </td>
          <td class="left" >
            <input type="url" name="vw_work_project_url" id="vw_work_project_url" value="<?php echo esc_attr( $bn_vw_work_project_url ); ?>" />
          </td>
        </tr>

      </tbody>
    </table>
  </div>
  <?php
}

/* Saves the custom meta input */
function vw_painter_pro_posttype_bn_meta_work_save( $post_id ) {
  if (!isset($_POST['vw_project_meta_nonce']) || !wp_verify_nonce($_POST['vw_project_meta_nonce'], basename(__FILE__))) {
    return;
  }

  if (!current_user_can('edit_post', $post_id)) {
    return;
  }

  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return;
  }

   // Save desig.
  if( isset( $_POST[ 'vw_work_project_url' ] ) ) {
    update_post_meta( $post_id, 'vw_work_project_url', esc_url($_POST[ 'vw_work_project_url']) );
  }
}

add_action( 'save_post', 'vw_painter_pro_posttype_bn_meta_work_save' );

/*--------------------- Services section ----------------------*/
// Serives section
function vw_painter_pro_posttype_images_metabox_enqueue($hook) {
  if ( 'post.php' === $hook || 'post-new.php' === $hook ) {
    wp_enqueue_script('vw_lawyer-pro-posttype-images-metabox', plugin_dir_url( __FILE__ ) . '/js/img-metabox.js', array('jquery', 'jquery-ui-sortable'));

    global $post;
    if ( $post ) {
      wp_enqueue_media( array(
          'post' => $post->ID,
        )
      );
    }

  }
}
add_action('admin_enqueue_scripts', 'vw_painter_pro_posttype_images_metabox_enqueue');
// Services Meta
function vw_painter_pro_posttype_bn_custom_meta_services() {

    add_meta_box( 'bn_meta', __( 'Icon Image', 'vw-painter-pro-posttype-posttype' ), 'vw_painter_pro_posttype_bn_meta_callback_services', 'services', 'normal', 'high' );
}
/* Hook things in for admin*/
if (is_admin()){
  add_action('admin_menu', 'vw_painter_pro_posttype_bn_custom_meta_services');
}

function vw_painter_pro_posttype_bn_meta_callback_services( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'bn_nonce' );
    $bn_stored_meta = get_post_meta( $post->ID );
    //Project category details
    if(!empty($bn_stored_meta['meta-image'][0]))
      $bn_meta_image = $bn_stored_meta['meta-image'][0];
    else
      $bn_meta_image = '';

    if(!empty($bn_stored_meta['services_external_url'][0]))
      $bn_services_external_url = $bn_stored_meta['services_external_url'][0];
    else
      $bn_services_external_url = '';
    
    ?>
  <div id="property_stuff">
    <table id="list-table">     
      <tbody id="the-list" data-wp-lists="list:meta">
        <tr id="meta-1">
          <p>
            <label for="meta-image"><?php echo esc_html('Icon Image'); ?></label><br>
            <input type="text" name="meta-image" id="meta-image" class="meta-image regular-text" value="<?php echo esc_attr($bn_meta_image); ?>">
            <input type="button" class="button image-upload" value="Browse">
          </p>
          <div class="image-preview"><img src="<?php echo $bn_stored_meta['meta-image'][0]; ?>" style="max-width: 250px;"></div>
        </tr>
        <tr id="meta-2">
          <td class="left">
            <?php _e( 'Link to an External Page', 'vw-painter-pro-posttype' )?>
          </td>
          <td class="left" >
            <input type="text" name="services_external_url" id="services_external_url" value="<?php echo esc_attr( $bn_services_external_url ); ?>" />
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  <?php
}

function vw_painter_pro_posttype_bn_meta_save_services( $post_id ) {

  if (!isset($_POST['bn_nonce']) || !wp_verify_nonce($_POST['bn_nonce'], basename(__FILE__))) {
    return;
  }

  if (!current_user_can('edit_post', $post_id)) {
    return;
  }

  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return;
  }
  // Save Image
  if( isset( $_POST[ 'meta-image' ] ) ) {
      update_post_meta( $post_id, 'meta-image', esc_url($_POST[ 'meta-image' ]) );
  }

  if( isset( $_POST[ 'services_external_url' ] ) ) {
      update_post_meta( $post_id, 'services_external_url', esc_url($_POST[ 'services_external_url' ]) );
  }
}
add_action( 'save_post', 'vw_painter_pro_posttype_bn_meta_save_services' );

/*----------------------Testimonial section ----------------------*/
/* Adds a meta box to the Testimonial editing screen */
function vw_painter_pro_posttype_bn_testimonial_meta_box() {
	add_meta_box( 'vw-painter-pro-posttype-testimonial-meta', __( 'Enter Details', 'vw-painter-pro-posttype' ), 'vw_painter_pro_posttype_bn_testimonial_meta_callback', 'testimonials', 'normal', 'high' );
}
// Hook things in for admin
if (is_admin()){
    add_action('admin_menu', 'vw_painter_pro_posttype_bn_testimonial_meta_box');
}
/* Adds a meta box for custom post */
function vw_painter_pro_posttype_bn_testimonial_meta_callback( $post ) {
	wp_nonce_field( basename( __FILE__ ), 'vw_painter_pro_posttype_posttype_testimonial_meta_nonce' );
  $bn_stored_meta = get_post_meta( $post->ID );
  if(!empty($bn_stored_meta['vw_painter_pro_posttype_testimonial_desigstory'][0]))
      $bn_vw_painter_pro_posttype_testimonial_desigstory = $bn_stored_meta['vw_painter_pro_posttype_testimonial_desigstory'][0];
    else
      $bn_vw_painter_pro_posttype_testimonial_desigstory = '';
	?>
	<div id="testimonials_custom_stuff">
		<table id="list">
			<tbody id="the-list" data-wp-lists="list:meta">
				<tr id="meta-1">
					<td class="left">
						<?php _e( 'Designation', 'vw-painter-pro-posttype' )?>
					</td>
					<td class="left" >
						<input type="text" name="vw_painter_pro_posttype_testimonial_desigstory" id="vw_painter_pro_posttype_testimonial_desigstory" value="<?php echo esc_attr( $bn_vw_painter_pro_posttype_testimonial_desigstory ); ?>" />
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<?php
}

/* Saves the custom meta input */
function vw_painter_pro_posttype_bn_metadesig_save( $post_id ) {
	if (!isset($_POST['vw_painter_pro_posttype_posttype_testimonial_meta_nonce']) || !wp_verify_nonce($_POST['vw_painter_pro_posttype_posttype_testimonial_meta_nonce'], basename(__FILE__))) {
		return;
	}

	if (!current_user_can('edit_post', $post_id)) {
		return;
	}

	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}

	// Save desig.
	if( isset( $_POST[ 'vw_painter_pro_posttype_testimonial_desigstory' ] ) ) {
		update_post_meta( $post_id, 'vw_painter_pro_posttype_testimonial_desigstory', sanitize_text_field($_POST[ 'vw_painter_pro_posttype_testimonial_desigstory']) );
	}
}

add_action( 'save_post', 'vw_painter_pro_posttype_bn_metadesig_save' );

/*------------------------- Team Section-----------------------------*/
/* Adds a meta box for Designation */
function vw_painter_pro_posttype_bn_team_meta() {
    add_meta_box( 'vw_painter_pro_posttype_bn_meta', __( 'Enter Details','vw-painter-pro-posttype' ), 'vw_painter_pro_posttype_ex_bn_meta_callback', 'team', 'normal', 'high' );
}
// Hook things in for admin
if (is_admin()){
    add_action('admin_menu', 'vw_painter_pro_posttype_bn_team_meta');
}
/* Adds a meta box for custom post */
function vw_painter_pro_posttype_ex_bn_meta_callback( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'vw_painter_pro_posttype_bn_nonce' );
    $bn_stored_meta = get_post_meta( $post->ID );

    //Email details
    if(!empty($bn_stored_meta['meta-desig'][0]))
      $bn_meta_desig = $bn_stored_meta['meta-desig'][0];
    else
      $bn_meta_desig = '';

    //Phone details
    if(!empty($bn_stored_meta['meta-call'][0]))
      $bn_meta_call = $bn_stored_meta['meta-call'][0];
    else
      $bn_meta_call = '';

    //facebook details
    if(!empty($bn_stored_meta['meta-facebookurl'][0]))
      $bn_meta_facebookurl = $bn_stored_meta['meta-facebookurl'][0];
    else
      $bn_meta_facebookurl = '';

    //linkdenurl details
    if(!empty($bn_stored_meta['meta-linkdenurl'][0]))
      $bn_meta_linkdenurl = $bn_stored_meta['meta-linkdenurl'][0];
    else
      $bn_meta_linkdenurl = '';

    //twitterurl details
    if(!empty($bn_stored_meta['meta-twitterurl'][0]))
      $bn_meta_twitterurl = $bn_stored_meta['meta-twitterurl'][0];
    else
      $bn_meta_twitterurl = '';

    //twitterurl details
    if(!empty($bn_stored_meta['meta-googleplusurl'][0]))
      $bn_meta_googleplusurl = $bn_stored_meta['meta-googleplusurl'][0];
    else
      $bn_meta_googleplusurl = '';

    //twitterurl details
    if(!empty($bn_stored_meta['meta-designation'][0]))
      $bn_meta_designation = $bn_stored_meta['meta-designation'][0];
    else
      $bn_meta_designation = '';

    ?>
    <div id="agent_custom_stuff">
        <table id="list-table">         
            <tbody id="the-list" data-wp-lists="list:meta">
                <tr id="meta-1">
                    <td class="left">
                        <?php esc_html_e( 'Email', 'vw-painter-pro-posttype' )?>
                    </td>
                    <td class="left" >
                        <input type="text" name="meta-desig" id="meta-desig" value="<?php echo esc_attr($bn_meta_desig); ?>" />
                    </td>
                </tr>
                <tr id="meta-2">
                    <td class="left">
                        <?php esc_html_e( 'Phone Number', 'vw-painter-pro-posttype' )?>
                    </td>
                    <td class="left" >
                        <input type="text" name="meta-call" id="meta-call" value="<?php echo esc_attr($bn_meta_call); ?>" />
                    </td>
                </tr>
                <tr id="meta-3">
                  <td class="left">
                    <?php esc_html_e( 'Facebook Url', 'vw-painter-pro-posttype' )?>
                  </td>
                  <td class="left" >
                    <input type="url" name="meta-facebookurl" id="meta-facebookurl" value="<?php echo esc_url($bn_meta_facebookurl); ?>" />
                  </td>
                </tr>
                <tr id="meta-4">
                  <td class="left">
                    <?php esc_html_e( 'Linkedin URL', 'vw-painter-pro-posttype' )?>
                  </td>
                  <td class="left" >
                    <input type="url" name="meta-linkdenurl" id="meta-linkdenurl" value="<?php echo esc_url($bn_meta_linkdenurl); ?>" />
                  </td>
                </tr>
                <tr id="meta-5">
                  <td class="left">
                    <?php esc_html_e( 'Twitter Url', 'vw-painter-pro-posttype' ); ?>
                  </td>
                  <td class="left" >
                    <input type="url" name="meta-twitterurl" id="meta-twitterurl" value="<?php echo esc_url( $bn_meta_twitterurl); ?>" />
                  </td>
                </tr>
                <tr id="meta-6">
                  <td class="left">
                    <?php esc_html_e( 'GooglePlus URL', 'vw-painter-pro-posttype' ); ?>
                  </td>
                  <td class="left" >
                    <input type="url" name="meta-googleplusurl" id="meta-googleplusurl" value="<?php echo esc_url($bn_meta_googleplusurl); ?>" />
                  </td>
                </tr>
                <tr id="meta-7">
                  <td class="left">
                    <?php esc_html_e( 'Designation', 'vw-painter-pro-posttype' ); ?>
                  </td>
                  <td class="left" >
                    <input type="text" name="meta-designation" id="meta-designation" value="<?php echo esc_attr($bn_meta_designation); ?>" />
                  </td>
                </tr>
            </tbody>
        </table>
    </div>
    <?php
}
/* Saves the custom Designation meta input */
function vw_painter_pro_posttype_ex_bn_metadesig_save( $post_id ) {
    if( isset( $_POST[ 'meta-desig' ] ) ) {
        update_post_meta( $post_id, 'meta-desig', esc_html($_POST[ 'meta-desig' ]) );
    }
    if( isset( $_POST[ 'meta-call' ] ) ) {
        update_post_meta( $post_id, 'meta-call', esc_html($_POST[ 'meta-call' ]) );
    }
    // Save facebookurl
    if( isset( $_POST[ 'meta-facebookurl' ] ) ) {
        update_post_meta( $post_id, 'meta-facebookurl', esc_url($_POST[ 'meta-facebookurl' ]) );
    }
    // Save linkdenurl
    if( isset( $_POST[ 'meta-linkdenurl' ] ) ) {
        update_post_meta( $post_id, 'meta-linkdenurl', esc_url($_POST[ 'meta-linkdenurl' ]) );
    }
    if( isset( $_POST[ 'meta-twitterurl' ] ) ) {
        update_post_meta( $post_id, 'meta-twitterurl', esc_url($_POST[ 'meta-twitterurl' ]) );
    }
    // Save googleplusurl
    if( isset( $_POST[ 'meta-googleplusurl' ] ) ) {
        update_post_meta( $post_id, 'meta-googleplusurl', esc_url($_POST[ 'meta-googleplusurl' ]) );
    }
    // Save designation
    if( isset( $_POST[ 'meta-designation' ] ) ) {
        update_post_meta( $post_id, 'meta-designation', esc_html($_POST[ 'meta-designation' ]) );
    }
}
add_action( 'save_post', 'vw_painter_pro_posttype_ex_bn_metadesig_save' );

add_action( 'save_post', 'bn_meta_save' );
/* Saves the custom meta input */
function bn_meta_save( $post_id ) {
  if( isset( $_POST[ 'vw_painter_pro_posttype_team_featured' ] )) {
      update_post_meta( $post_id, 'vw_painter_pro_posttype_team_featured', esc_attr(1));
  }else{
    update_post_meta( $post_id, 'vw_painter_pro_posttype_team_featured', esc_attr(0));
  }
}

/*------------------- Testimonial Shortcode -------------------------*/
function vw_painter_pro_posttype_testimonials_func( $atts ) {
    $testimonial = ''; 
    $testimonial = '<div id="testimonials"><div class="row testimonial_shortcodes">';
      $new = new WP_Query( array( 'post_type' => 'testimonials') );
      if ( $new->have_posts() ) :
        $k=1;
        while ($new->have_posts()) : $new->the_post();
          $post_id = get_the_ID();
          $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'medium' );
          $url = $thumb['0'];
          $excerpt = vw_painter_pro_string_limit_words(get_the_excerpt(),20);
          $designation = get_post_meta($post_id,'vw_painter_pro_posttype_testimonial_desigstory',true);

          $testimonial .= '<div class="col-lg-4 col-md-6 col-sm-6 mb-4"><div class="testimonial_box text-center">';
                if (has_post_thumbnail()){
                    $testimonial.= '<img src="'.esc_url($url).'">';
                    }
               $testimonial .= '<div class="qoute_text pb-3">'.$excerpt.'</div>
                <h4 class="testimonial_name"><a href="'.get_the_permalink().'">'.get_the_title().'</a> <cite>'.esc_html($designation).'</cite></h4>
              </div></div>';
          $k++;         
        endwhile; 
        wp_reset_postdata();
      else :
        $testimonial = '<div id="testimonial" class="testimonial_wrap col-md-3 mt-3 mb-4"><h2 class="center">'.__('Not Found','vw-painter-pro-posttype').'</h2></div>';
      endif;
    $testimonial .= '</div></div>';
    return $testimonial;
}
add_shortcode( 'vw-painter-pro-testimonials', 'vw_painter_pro_posttype_testimonials_func' );

/*------------------- Services Shortcode -------------------------*/
function vw_painter_pro_posttype_services_func( $atts ) {
    $services = ''; 
    $services = '<div id="services"><div class="row inner-test-bg">';
      $new = new WP_Query( array( 'post_type' => 'services') );
      if ( $new->have_posts() ) :
        $k=1;
        while ($new->have_posts()) : $new->the_post();
          $post_id = get_the_ID();
          $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'medium' );
          $url = $thumb['0'];
          $excerpt = vw_painter_pro_string_limit_words(get_the_excerpt(),20);
          $read_more_text = get_theme_mod('vw_painter_pro_services_read_more_text');
          $services .= '<div class="col-lg-4 col-md-6 col-sm-6 mb-4"> 
          <div class="service-box-shortcodes text-center">
              <div class="services-image">
              <img src="'.get_post_meta(get_the_ID(),'meta-image',true).'">
              </div>
            <div class="service_shortcodes">
              <h4><a href="'.get_the_permalink().'">'.get_the_title().'</a></h4>
              <div class="services_content_shortcodes">'.$excerpt.'</div>';
                $custom_url = '';
                if(get_post_meta(get_the_ID(), 'services_external_url', true != '')){  $custom_url = get_post_meta(get_the_ID(), 'services_external_url', true); }
                if($custom_url != ''){ echo esc_url($custom_url); } else { get_the_permalink(); }

              $services .= '<div class="read_more mb-4"><a href="'.esc_url($custom_url).'">'.esc_html($read_more_text).'</a></div>
            </div>
          </div>
        </div>';
          $k++;         
        endwhile; 
        wp_reset_postdata();
      else :
        $services = '<div id="services" class="col-md-3 mt-3 mb-4"><h2 class="center">'.__('Not Found','vw-painter-pro-posttype').'</h2></div></div></div>';
      endif;
    $services .= '</div></div>';
    return $services;
}
add_shortcode( 'vw-painter-pro-services', 'vw_painter_pro_posttype_services_func' );

/*---------------- Project Shortcode ---------------------*/
function vw_painter_pro_posttype_project_func( $atts ) {
    $project = ''; 
    $project = '<div id="our_project" class="row project_tab_content">';
      $new = new WP_Query( array( 'post_type' => 'project') );
      if ( $new->have_posts() ) :
        $k=1;
        while ($new->have_posts()) : $new->the_post();
          $post_id = get_the_ID();
          $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'medium' );
          $url = $thumb['0'];
          $excerpt = vw_painter_pro_string_limit_words(get_the_excerpt(),20);
          $project .= '<div class="col-lg-4 col-md-6 col-sm-6 mb-4">
              <div class="box">';
                if (has_post_thumbnail()){
                  $project.= '<img src="'.esc_url($url).'">';
                  }
                $project.= '<div class="box-content">
                    <h4 class="title"><a href="'.get_the_permalink().'">'.get_the_title().'</a></h4>
                    <ul class="icon">
                        <li><a href="'.get_the_permalink().'"><i class="fa fa-link"></i></a></li>
                    </ul>
                </div>
              </div>
            </div>';
          $k++;         
        endwhile; 
        wp_reset_postdata();
        $project.= '</div>';
      else :
        $project = '<div id="our_project" class="col-md-3 mt-3 mb-4"><h2 class="center">'.__('Not Found','vw-painter-pro-posttype').'</h2></div>';
      endif;
      $project.= '</div>';
    return $project;
}
add_shortcode( 'vw-painter-pro-project', 'vw_painter_pro_posttype_project_func' );

/*---------------- FAQ Shortcode ---------------------*/
function vw_painter_pro_posttype_faq_func( $atts ) {
    $faq = ''; 
    $faq = '<div id="our_faq" class="faq_tab_content">';
      $new = new WP_Query( array( 'post_type' => 'faq') );
      if ( $new->have_posts() ) :
        $k=1;
        while ($new->have_posts()) : $new->the_post();
          $post_id = get_the_ID();
          $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'medium' );
          $url = $thumb['0'];
          $excerpt = vw_painter_pro_string_limit_words(get_the_excerpt(),20);
          $faq .= '<div class="panel panel-default">
                <div class="panel-heading" role="tab" id="heading'.esc_attr($k).'">
                  <h4 class="panel-title">
                  <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse'.esc_attr($k).'" aria-expanded="false" aria-controls="collapse'.esc_attr($k).'">
                    '.get_the_title().'
                  </a>
                </h4>
                </div>
                <div id="collapse'.esc_attr($k).'" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading'.esc_attr($k).'">
                  <div class="panel-body">
                    <p>'.get_the_content().'</p>
                  </div>
                </div>
              </div>';
          $k++;         
        endwhile; 
        wp_reset_postdata();
        $faq.= '</div>';
      else :
        $faq = '<div id="our_faq" class="col-md-3 mt-3 mb-4"><h2 class="center">'.__('Not Found','vw-painter-pro-posttype').'</h2></div>';
      endif;
      $faq.= '</div>';
    return $faq;
}
add_shortcode( 'vw-painter-pro-faq', 'vw_painter_pro_posttype_faq_func' );