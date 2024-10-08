<?php
/**
 * @author : Jegtheme
 */

namespace JNews\Module\Carousel;

use JNews\Module\ModuleOptionAbstract;

abstract Class CarouselOptionAbstract extends ModuleOptionAbstract {
	protected $default_number = 5;

	public function compatible_column() {
		return array( 4, 6, 8, 12 );
	}

	public function set_options() {
		$this->set_carousel_option();
		$this->set_show_post_sticky_option();
		$this->set_content_filter_option( $this->default_number );
		$this->set_style_option();
	}

	public function get_category() {
		return esc_html__( 'JNews - Carousel', 'jnews' );
	}

	public function set_carousel_option() {
		$this->options[] = array(
			'type'        => 'checkbox',
			'param_name'  => 'show_nav',
			'heading'     => esc_html__( 'Show Nav', 'jnews' ),
			'description' => esc_html__( 'Check this option to show navigation for your carousel.', 'jnews' ),
			'default'     => true
		);
		$this->options[] = array(
			'type'        => 'slider',
			'param_name'  => 'number_item',
			'heading'     => esc_html__( 'Number of Item', 'jnews' ),
			'description' => esc_html__( 'Set number of carousel item on each slide.', 'jnews' ),
			'min'         => 1,
			'max'         => 6,
			'step'        => 1,
			'std'         => 3,
		);
		$this->options[] = array(
			'type'        => 'slider',
			'param_name'  => 'margin',
			'heading'     => esc_html__( 'Item Margin', 'jnews' ),
			'description' => esc_html__( 'Set margin width for each slider item.', 'jnews' ),
			'min'         => 0,
			'max'         => 100,
			'step'        => 1,
			'std'         => 20,
		);
		$this->options[] = array(
			'type'        => 'checkbox',
			'param_name'  => 'enable_autoplay',
			'heading'     => esc_html__( 'Enable Autoplay', 'jnews' ),
			'description' => esc_html__( 'Check this option to enable auto play.', 'jnews' ),
		);
		$this->options[] = array(
			'type'        => 'slider',
			'param_name'  => 'autoplay_delay',
			'heading'     => esc_html__( 'Autoplay Delay', 'jnews' ),
			'description' => esc_html__( 'Set your autoplay delay (in millisecond).', 'jnews' ),
			'min'         => 1000,
			'max'         => 10000,
			'step'        => 500,
			'std'         => 3000,
			'dependency'  => array( 'element' => 'enable_autoplay', 'value' => 'true' )
		);
		$this->options[] = array(
			'type'        => 'dropdown',
			'param_name'  => 'date_format',
			'heading'     => esc_html__( 'Choose Date Format', 'jnews' ),
			'description' => esc_html__( 'Choose which date format you want to use.', 'jnews' ),
			'std'         => 'default',
			'value'       => array(
				esc_html__( 'Relative Date/Time Format (ago)', 'jnews' ) => 'ago',
				esc_html__( 'WordPress Default Format', 'jnews' )        => 'default',
				esc_html__( 'Custom Format', 'jnews' )                   => 'custom',
			)
		);
		$this->options[] = array(
			'type'        => 'textfield',
			'param_name'  => 'date_format_custom',
			'heading'     => esc_html__( 'Custom Date Format', 'jnews' ),
			'description' => wp_kses( sprintf( __( 'Please write custom date format for your module, for more detail about how to write date format, you can refer to this <a href="%s" target="_blank">link</a>.', 'jnews' ), 'https://codex.wordpress.org/Formatting_Date_and_Time' ), wp_kses_allowed_html() ),
			'std'         => 'Y/m/d',
			'dependency'  => array( 'element' => 'date_format', 'value' => array( 'custom' ) )
		);

		$this->options[] = array(
			'type'        => 'checkbox',
			'param_name'  => 'force_normal_image_load',
			'heading'     => esc_html__( 'Use Normal Image Load', 'jnews' ),
			'description' => esc_html__( 'Force to use the normal image load for this carousel.', 'jnews' ),
		);
	}

	public function set_typography_option( $instance ) {

		$instance->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'        => 'title_typography',
				'label'       => esc_html__( 'Title Typography', 'jnews' ),
				'description' => esc_html__( 'Set typography for post title', 'jnews' ),
				'selector'    => '{{WRAPPER}} .jeg_post_title > a',
			]
		);

		$instance->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'        => 'meta_typography',
				'label'       => esc_html__( 'Meta Typography', 'jnews' ),
				'description' => esc_html__( 'Set typography for post meta', 'jnews' ),
				'selector'    => '{{WRAPPER}} .jeg_post_meta, {{WRAPPER}} .jeg_post_meta .fa, {{WRAPPER}}.jeg_postblock .jeg_subcat_list > li > a:hover, {{WRAPPER}} .jeg_pl_md_card .jeg_post_category a, {{WRAPPER}}.jeg_postblock .jeg_subcat_list > li > a.current, {{WRAPPER}} .jeg_pl_md_5 .jeg_post_meta, {{WRAPPER}} .jeg_pl_md_5 .jeg_post_meta .fa, {{WRAPPER}} .jeg_post_category a',
			]
		);
	}

	public function set_show_post_sticky_option(){
		$this->options[] = array(
			'type'        => 'checkbox',
			'param_name'  => 'sticky_post',
			'heading'     => esc_html__( 'Show Sticky Post', 'jnews' ),
			'description' => esc_html__( 'Enabling this option will display the Sticky Post at the first place in this module', 'jnews' ),
			'group'       => esc_html__( 'Content Filter', 'jnews' ),
			'std'         => false,
			'dependency'  => array(
				'element' => 'post_type',
				'value'   => 'post',
			),
		);
	}
}
