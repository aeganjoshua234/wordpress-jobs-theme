<?php
// Load parent theme styles.
add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'child-style', get_stylesheet_uri(), array( 'parent-style' ) );
} );

// 1. Register the "Jobs" custom post type.
function jobs_register_post_type() {
	register_post_type( 'job', array(
		'labels' => array(
			'name'          => 'Jobs',
			'singular_name' => 'Job',
			'add_new_item'  => 'Add New Job',
			'edit_item'     => 'Edit Job',
			'all_items'     => 'All Jobs',
		),
		'public'       => true,
		'has_archive'  => true,
		'rewrite'      => array( 'slug' => 'jobs' ),
		'menu_icon'    => 'dashicons-businessperson',
		'supports'     => array( 'title', 'editor' ),
		'show_in_rest' => true,
	) );
}
add_action( 'init', 'jobs_register_post_type' );

// Flush permalinks once when the theme is activated.
add_action( 'after_switch_theme', function () {
	jobs_register_post_type();
	flush_rewrite_rules();
} );

// 2. Custom fields meta box: Job Title, Salary, Location.
function jobs_fields() {
	return array(
		'job_title'    => 'Job Title',
		'job_salary'   => 'Salary',
		'job_location' => 'Location',
	);
}

add_action( 'add_meta_boxes', function () {
	add_meta_box( 'job_details', 'Job Details', 'jobs_render_meta_box', 'job', 'normal', 'high' );
} );

function jobs_render_meta_box( $post ) {
	wp_nonce_field( 'jobs_save_meta', 'jobs_meta_nonce' );
	foreach ( jobs_fields() as $key => $label ) {
		$value = get_post_meta( $post->ID, $key, true );
		printf(
			'<p><label for="%1$s"><strong>%2$s</strong></label><br><input type="text" id="%1$s" name="%1$s" value="%3$s" class="widefat"></p>',
			esc_attr( $key ),
			esc_html( $label ),
			esc_attr( $value )
		);
	}
}

add_action( 'save_post_job', function ( $post_id ) {
	if ( ! isset( $_POST['jobs_meta_nonce'] ) || ! wp_verify_nonce( $_POST['jobs_meta_nonce'], 'jobs_save_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	foreach ( array_keys( jobs_fields() ) as $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			update_post_meta( $post_id, $key, sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) );
		}
	}
} );

// Helper used by both templates.
function jobs_display_fields( $post_id ) {
	echo '<ul class="job-meta">';
	foreach ( jobs_fields() as $key => $label ) {
		$value = get_post_meta( $post_id, $key, true );
		if ( $value !== '' ) {
			printf( '<li><strong>%s:</strong> %s</li>', esc_html( $label ), esc_html( $value ) );
		}
	}
	echo '</ul>';
}
