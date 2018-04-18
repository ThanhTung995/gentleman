<?php

namespace PureElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Controls_Manager;
use Elementor\Control_Media;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;

if ( !defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * @since 1.1.0
 */
class Info_Box extends Widget_Base
{

	/**
	 * Retrieve the widget name.
	 *
	 * @since  1.1.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {

		return 'pure-info-box';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since  1.1.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {

		return __( 'Info Box', 'pure-elementor' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since  1.1.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {

		return 'eicon-info-box';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since  1.1.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {

		return [ 'general-elements' ];
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since  1.1.0
	 *
	 * @access protected
	 */
	protected function _register_controls() {

		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'pure-elementor' ),
			]
		);

		$this->add_control(
			'title',
			[
				'label'   => __( 'Title', 'pure-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Title', 'pure-elementor' ),
			]
		);

		$this->add_control(
			'image',
			[
				'label'   => __( 'Choose Image', 'elementor' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);
		
		$this->add_control(
			'content',
			[
				'label'   => __( 'Content', 'pure-elementor' ),
				'type'    => Controls_Manager::WYSIWYG,
				'default' => __( 'Content', 'pure-elementor' ),
			]
		);

		$this->add_control(
			'link',
			[
				'label' => __( 'Link to', 'elementor' ),
				'type' => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'elementor' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'link_text',
			[
				'label' => __( 'Link text', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Read More', 'elementor' ),
			]
		);

		$this->add_control(
			'title_tag',
			[
				'label' => __( 'Title HTML Tag', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'p' => 'p',
				],
				'default' => 'h3',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_content',
			[
				'label' => __( 'Content', 'elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'heading_title',
			[
				'label' => __( 'Title', 'elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'title_align',
			[
				'label' => __( 'Alignment', 'elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'center',
				'options' => [
					'left' => [
						'title' => __( 'Left', 'elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'elementor' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'elementor' ),
						'icon' => 'fa fa-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'elementor' ),
						'icon' => 'fa fa-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .pure-info-box-title' => 'text-align: {{VALUE}};',
				],
			]
		);
//
//		$this->add_control(
//			'title_color',
//			[
//				'label' => __( 'Title Color', 'elementor' ),
//				'type' => Controls_Manager::COLOR,
//				'selectors' => [
//					'{{WRAPPER}} .pure-info-box-title' => 'color: {{VALUE}};',
//				],
//			]
//		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .pure-info-box-title',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
			]
		);

		$this->add_control(
			'title_bg_color',
			[
				'label' => __( 'Background Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .pure-info-box-title:before' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'title_padding',
			[
				'label' => __( 'Padding', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .pure-info-box-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'title_margin',
			[
				'label' => __( 'Margin', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .pure-info-box-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since  1.1.0
	 *
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings();
		?>
        <div class="pure-info-box">
            <?php
            if ( ! empty( $settings['title'] ) ) {
	            $this->add_render_attribute( 'title', 'class', 'pure-info-box-title' );

	            $this->add_inline_editing_attributes( 'title', 'none' );

	            $title_html = $settings['title'];

	            printf( '<%1$s %2$s><a href="%4$s">%3$s</a></%1$s>', $settings['title_tag'], $this->get_render_attribute_string( 'title' ), $title_html, $settings['link']['url'] );
            }

            if ( ! empty( $settings['image']['url'] ) ) {
	            $this->add_render_attribute( 'image', 'src', $settings['image']['url'] );
	            $this->add_render_attribute( 'image', 'alt', Control_Media::get_image_alt( $settings['image'] ) );
	            $this->add_render_attribute( 'image', 'title', Control_Media::get_image_title( $settings['image'] ) );

	            $image_html = '<img ' . $this->get_render_attribute_string( 'image' ) . '>';

	            if ( ! empty( $settings['link']['url'] ) ) {
		            $image_html = '<a ' . $this->get_render_attribute_string( 'link' ) . '>' . $image_html . '</a>';
	            }

	            echo '<figure class="pure-info-box-img">' . $image_html . '</figure>';
            }

            if ( ! empty( $settings['content'] ) ) {
	            $this->add_render_attribute( 'content', 'class', 'pure-info-box-content' );

	            $this->add_inline_editing_attributes( 'content', 'advanced' );

	            printf( '<div %1$s>%2$s</div>', $this->get_render_attribute_string( 'content' ), $settings['content'] );
            }

            if ( ! empty( $settings['link_text'] ) ) {
	            $this->add_render_attribute( 'link', 'class', 'pure-info-box-link ttu db text-center pdtb10 border-top bg-secondary-bg--hover' );

	            $this->add_inline_editing_attributes( 'link_text', 'none' );

	            printf( '<a href="%3$s" %1$s>%2$s</a>', $this->get_render_attribute_string( 'link' ), $settings['link_text'], $settings['link']['url'] );
            }
            ?>
        </div>
		<?php
	}

	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since  1.1.0
	 *
	 * @access protected
	 */
	protected function _content_template() {

		?>
        <#
        var html = '<div class="pure-info-box">';

		if( settings.title ) {
		view.addRenderAttribute( 'title', 'class', 'pure-info-box-title' );

		view.addInlineEditingAttributes( 'title', 'none' );

		html += '<' + settings.title_tag + ' ' + view.getRenderAttributeString( 'title' ) + '>' + settings.title + '</' + settings.title_tag + '>';
		}

        if ( settings.image.url ) {
        var imageHtml = '<img src="' + settings.image.url + '" />';

        html += '<figure class="elementor-image-box-img">' + imageHtml + '</figure>';
        }

        if ( settings.content ) {
        view.addRenderAttribute( 'content', 'class', 'pure-info-box-content' );

        view.addInlineEditingAttributes( 'content', 'advanced' );

        html += '<div ' + view.getRenderAttributeString( 'content' ) + '>' + settings.content + '</div>';
        }
        
        if( settings.link_text ) {
			view.addRenderAttribute( 'link_text', 'class', 'pure-info-box-link ttu db text-center pdtb10 border-top bg-primary-bg bg-secondary-bg--hover' );

			view.addInlineEditingAttributes( 'link_text', 'none' );

			var link_html = settings.link_text;
			if ( settings.link.url ) {
			link_html = '<a href="' + settings.link.url + '">' + link_html + '</a>';
			}

			html += '<div ' + view.getRenderAttributeString( 'link_text' ) + '>' + link_html + '</div>';
        }

		html += '</div>';
        print( html );
        #>
		<?php
	}
}
