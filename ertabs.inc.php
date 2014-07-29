<?php



function erta_func( $atts ) {
		$atts = shortcode_atts( array(
		  'orientation' => 'horizontal',
		  'group' => '0'
		), $atts );
		
		$atts['orientation'] = ($atts['orientation']=='vertical'?'vertical':'horizontal');
		
            
			
		if($atts['group']>0){
			
			$tab_content_divs = array();
			$tab_titles = get_post_meta( $atts['group'], 'tab_titles', true );
			$tab_descriptions = get_post_meta( $atts['group'], 'tab_descriptions', true);
			$tab_id = $atts['orientation'].'Tab-'.$atts['group'];
			
			
			$tab_content .= '<div id="'.$tab_id.'" class="ertab-'.$atts['group'].'">';
		
			$tab_content .= '<ul class="resp-tabs-list">';
			if(!empty($tab_titles)){
				$tab_count = 0;
				foreach($tab_titles as $key=>$titles){
					
					$tab_count++;
		
					
					
					$tab_content.='<li>'.$titles.'</li>';
					$tab_content_divs[] = '<div>
						<p>'.$tab_descriptions[$key].'</p>
					</div>';
					
				}
			}
			$tab_content .= '</ul>';
			$tab_content .= '<div class="resp-tabs-container">'.implode(' ', $tab_content_divs).'</div></div>';
			
			 $tab_content .= '<script type="text/javascript" language="javascript"> jQuery(document).ready(function($) { $("#'.$tab_id.'").easyResponsiveTabs({ type: \''.$atts['orientation'].'\',width: \'auto\',fit: true
        }); }); </script>';
		}
            
            
      
	 return $tab_content;
}
add_shortcode( 'ertabs', 'erta_func' );



/**
 * Adds a box to the main column on the Post and Page edit screens.
 */
function ertabs_add_meta_box() {

	$screens = array( 'post', 'page' );

	foreach ( $screens as $screen ) {

		add_meta_box(
			'ertabs_sectionid',
			__( 'Easy Responsive Tabs to Accordion', 'ertabs_textdomain' ),
			'ertabs_meta_box_callback',
			$screen
		);
	}
}
add_action( 'add_meta_boxes', 'ertabs_add_meta_box' );

/**
 * Prints the box content.
 * 
 * @param WP_Post $post The object for the current post/page.
 */
function ertabs_meta_box_callback( $post ) {

	// Add an nonce field so we can check for it later.
	wp_nonce_field( 'ertabs_meta_box', 'ertabs_meta_box_nonce' );

	/*
	 * Use get_post_meta() to retrieve an existing value
	 * from the database and use the value for the form.
	 */

	$tab_titles = get_post_meta( $post->ID, 'tab_titles', true );
	$tab_descriptions = get_post_meta( $post->ID, 'tab_descriptions', true);
	


	echo '<label for="ertabs_new_field">';
	
	_e( 'ERT Shortcode:', 'ertabs_textdomain' );
	echo '</label> ';
	echo '<input readonly="readonly" type="text" id="ertabs_new_field" name="ertabs_new_field" value="[ertabs group='.$post->ID.' orientation=horizontal]" size="45" />';
	
	
	include('admin/ertabs_metabox.php');
}


/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function ertabs_save_meta_box_data( $post_id ) {

	/*
	 * We need to verify this came from our screen and with proper authorization,
	 * because the save_post action can be triggered at other times.
	 */

	// Check if our nonce is set.
	if ( ! isset( $_POST['ertabs_meta_box_nonce'] ) ) {
		return;
	}

	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['ertabs_meta_box_nonce'], 'ertabs_meta_box' ) ) {
		return;
	}

	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Check the user's permissions.
	if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}

	} else {

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}

	/* OK, it's safe for us to save the data now. */
	
	// Make sure that it is set.
	if ( ! isset( $_POST['ertabs_new_field'] ) ) {
		return;
	}

	// Sanitize user input.
	$tab_title = ( $_POST['tab_title'] );
	$tab_description = ( $_POST['tab_description'] );
	

	// Update the meta field in the database.
	update_post_meta( $post_id, 'tab_titles', $tab_title );
	update_post_meta( $post_id, 'tab_descriptions', $tab_description );
	
}
add_action( 'save_post', 'ertabs_save_meta_box_data' );

