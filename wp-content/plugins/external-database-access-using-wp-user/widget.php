<?php
/**
 * @package Akismet
 */

class Ext_Widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			'Ext_widget',
			__( 'ExtDB' ),
			array( 'description' => __( 'Display user data from external database.' ) )
		);

		if ( is_active_widget( false, false, $this->id_base ) ) {
			add_action( 'wp_head', array( $this, 'css' ) );
		}
	}
	/* This method is required */
  function widget($args, $instance) {
		ext_db_data_display_options();
  }
}

function ext_register_widgets() {
	register_widget( 'Ext_Widget' );
}

add_action( 'widgets_init', 'ext_register_widgets' );
