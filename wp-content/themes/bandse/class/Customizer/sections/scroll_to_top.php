<?php

$options = array();

$options[] = array(
	'id'    => 'jnews_scroll_to_top_button',
	'type'  => 'jnews-header',
	'label' => esc_html__( 'Scroll To Top Button', 'jnews' ),
);

$options[] = array(
	'id'          => 'jnews_scroll_to_top_mobile',
	'default'     => false,
	'type'        => 'jnews-toggle',
	'label'       => esc_html__( 'Enable in Mobile Devices', 'jnews' ),
	'description' => esc_html__( 'Enable Scroll to Top Button in Mobile Device', 'jnews' ),
);

return $options;
