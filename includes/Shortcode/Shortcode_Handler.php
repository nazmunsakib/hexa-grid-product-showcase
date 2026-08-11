<?php

namespace HexaGrid\Shortcode;

use HexaGrid\Assets\Asset_Manager;
use HexaGrid\Layout\Grid_Layout;
use HexaGrid\Layout\List_Layout;
use HexaGrid\Layout\Slider_Layout;
use HexaGrid\Layout\Table_Layout;
use HexaGrid\Preset\Preset_Config;
use HexaGrid\Preset\Preset_Loader;
use HexaGrid\Query\Query_Builder;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Shortcode_Handler
 *
 * Registers and processes the [hexagrid_product_showcase] shortcode.
 */
class Shortcode_Handler {

	/**
	 * Counter for generating deterministic unique IDs.
	 *
	 * @var int
	 */
	private static $instance_count = 0;

	/**
	 * Initialize hooks.
	 */
	public function init() {
		add_shortcode( 'hexagrid_product_showcase', [ $this, 'render_shortcode' ] );
	}

	/**
	 * Render the shortcode.
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string Rendered HTML.
	 */
	public function render_shortcode( $atts ) {
		$atts = (array) $atts;
		$preset_id = isset( $atts['preset_id'] ) ? absint( $atts['preset_id'] ) : 0;

		// Load preset configuration merged with explicit shortcode overrides.
		// Must happen BEFORE applying defaults, otherwise default values look
		// like explicit shortcode overrides and overwrite the preset.
		$preset_loader = new Preset_Loader();
		$atts          = $preset_loader->load( $preset_id, $atts );

		// Apply defaults for any missing values.
		$atts = shortcode_atts(
			array_merge( [ 'preset_id' => '' ], Preset_Config::get_shortcode_defaults() ),
			$atts,
			'hexagrid_product_showcase'
		);

		// This plugin currently supports product display only.
		$atts['content_type'] = 'product';

		// Generate deterministic unique ID for scoping.
		$atts['wrapper_id'] = $this->generate_unique_id( $preset_id );

		// Enqueue frontend assets only when the shortcode is used.
		$asset_manager = new Asset_Manager();
		$asset_manager->enqueue();

		// Build query.
		$query_builder = new Query_Builder();
		$query_builder->set_limit( $atts['limit'] )
			          ->set_order( $atts['orderby'], $atts['order'] );

		if ( ! empty( $atts['ids'] ) ) {
			$query_builder->set_ids( $atts['ids'] );
		}

		if ( ! empty( $atts['exclude_ids'] ) ) {
			$query_builder->set_exclude_ids( $atts['exclude_ids'] );
		}

		$query = $query_builder->get_query();

		// Render layout.
		$renderer = $this->get_layout_renderer( $atts['layout'] );

		if ( $renderer ) {
			return $renderer->render( $query, $atts );
		}

		return '';
	}

	/**
	 * Get the appropriate layout renderer.
	 *
	 * @param string $layout Layout identifier.
	 * @return Layout\Layout_Interface|null
	 */
	private function get_layout_renderer( $layout ) {
		switch ( $layout ) {
			case 'list':
				return new List_Layout();
			case 'slider':
				return new Slider_Layout();
			case 'table':
				return new Table_Layout();
			case 'grid':
			default:
				return new Grid_Layout();
		}
	}

	/**
	 * Generate a deterministic unique ID for the shortcode wrapper.
	 *
	 * @param int $preset_id Preset ID or 0 for inline shortcodes.
	 * @return string
	 */
	private function generate_unique_id( $preset_id ) {
		self::$instance_count++;

		$base = $preset_id ? 'hexagrid-preset-' . $preset_id : 'hexagrid-inline';

		return $base . '-' . self::$instance_count;
	}
}
