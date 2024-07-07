<?php
function areoi_render_block_button( $attributes, $content ) 
{
	$class 			= 	trim( 
		areoi_get_class_name_str( array( 
			'btn',
			'areoi-has-url',
			'position-relative',
			( !empty( $attributes['className'] ) ? $attributes['className'] : '' ),
			( !empty( $attributes['style'] ) ? $attributes['style'] : '' ),
			( !empty( $attributes['size'] ) ? $attributes['size'] : '' ),
			( !empty( $attributes['dropdown'] ) ? 'dropdown-toggle' : '' ),
			( !empty( $attributes['text_wrap'] ) ? $attributes['text_wrap'] : '' ),
		) ) 
		. ' ' . 
		areoi_get_display_class_str( $attributes, 'inline-block' )
		. ' ' . 
		areoi_get_display_block_class_str( $attributes, 'inline-block' )
	);

	$badge_class = areoi_get_class_name_str( array( 
		'badge',
		( !empty( $attributes['badge_style'] ) ? $attributes['badge_style'] : '' ),
		( !empty( $attributes['badge_background'] ) ? $attributes['badge_background'] : '' ),
		( !empty( $attributes['badge_text_color'] ) ? $attributes['badge_text_color'] : '' ),
		( !empty( $attributes['badge_classes'] ) ? $attributes['badge_classes'] : '' ),
	) );

	$dropdown_class = areoi_get_class_name_str( array( 
		'dropdown-menu',
		( !empty( $attributes['dropdown_style'] ) ? $attributes['dropdown_style'] : '' ),
		( !empty( $attributes['dropdown_menu_alignment'] ) ? $attributes['dropdown_menu_alignment'] : '' )
	) );

	$tooltip = null;
	if ( !empty( $attributes['tooltip'] ) ) {
		$tooltip = '
			data-bs-placement="' . esc_attr( $attributes['tooltip_direction'] ) . '"
			data-bs-toggle="tooltip"
			data-bs-html="true"	
			title="' . esc_attr( $attributes['tooltip_content'] ) . '"
		';
	}

	$badge = null;
	if ( !empty( $attributes['badge'] ) ) {
		$badge = '
			<span 
				class="' . $badge_class . '"
			>
				' . esc_attr( $attributes['badge_content'] ) . '
			</span>
		';
	}

	$icon = null;
	if ( !empty( $attributes['include_icon'] ) && !empty( $attributes['icon'] ) && !empty( $attributes['icon_size'] ) ) {
		$icon_margin = !empty( $attributes['icon_position'] ) && $attributes['icon_position'] == 'prepend' ? 'me-3' : 'ms-3';
		$icon_size = !empty( $attributes['icon_size'] ) ? esc_attr( $attributes['icon_size'] ) : '24'; 
		$icon = '
			<i class="' . esc_attr( $attributes['icon'] ) . ' ' . $icon_margin . ' align-middle " style="font-size: ' . $icon_size . 'px;"></i>
		';
	}

	$button_open = '
		<' . esc_attr( $attributes['type'] ) . ' 
			' . areoi_return_id( $attributes ) . '
			class="' . areoi_format_block_id( $attributes['block_id'] ) . ' ' . $class . '"
	';
	if ( !empty( $attributes['url'] ) ) {
		$button_open .= ' href="' . esc_attr( $attributes['url'] ) . '"';
	}
	if ( !empty( $attributes['rel'] ) ) {
		$button_open .= ' rel="' . esc_attr( $attributes['rel'] ) . '"';
	}
	if ( !empty( $attributes['linkTarget'] ) ) {
		$button_open .= ' target="' . esc_attr( $attributes['linkTarget'] ) . '"';
	}
	if ( !empty( $attributes['dropdown'] ) ) {
		$button_open .= ' data-bs-toggle="dropdown"';
		$button_open .= ' data-bs-auto-close="' . esc_attr( $attributes['dropdown_auto_close'] ) . '"';
	}

	if ( !empty( $attributes['url'] ) && !empty( $attributes['link_to_modal'] ) && !areoi2_get_option( 'areoi-dashboard-global-bootstrap-js', 1 ) ) {
		$button_open .= ' data-bs-toggle="modal"';
		$button_open .= ' data-bs-target="' . esc_attr( $attributes['url'] ) . '"';
	}
	$button_open .= ' ' . $tooltip . '>';

	$output = '';

	if ( !empty( $attributes['dropdown'] ) ) {
		$output .= '
			<div class="' . areoi_get_display_block_class_str( $attributes, 'inline-block' ) . ' ' . esc_attr( $attributes['dropdown_direction'] ) . '">
		';
	}
		if ( !empty( $attributes['popover'] ) ) {
			$output .= '
				<span
					class="popover-container ' . areoi_get_display_block_class_str( $attributes, 'inline-block' ) . '" 
					data-bs-container="body"
	                title="' . ( !empty($attributes['popover_title']) ? esc_attr( $attributes['popover_title'] ) : '') . '"
	                data-bs-content="' . ( !empty($attributes['popover_content']) ? esc_attr( $attributes['popover_content'] ) : '') . '"
	                data-bs-placement="' . ( !empty($attributes['popover_direction']) ? esc_attr( $attributes['popover_direction'] ) : '') . '"
	                data-bs-trigger="focus ' . ( !empty($attributes['popover_trigger']) ? esc_attr( $attributes['popover_trigger'] ) : 'click') . '"
	                data-bs-toggle="popover"
	                tabindex="0"
	            >
			';
		}

			$output .= '
				' . $button_open . '
					' . ( !empty( $attributes['icon_position'] ) && $attributes['icon_position'] == 'prepend' && $icon ? $icon : '' ) . '
					' . ( !empty( $attributes['text'] ) ? $attributes['text'] : '' ) . ' 
					' . ( !empty( $attributes['icon_position'] ) && $attributes['icon_position'] == 'append' && $icon ? $icon : '' ) . '
					' . $badge . ' 
				</' . esc_attr( $attributes['type'] ) . '>
			';

		if ( !empty( $attributes['popover'] ) ) {
			$output .= '</span>';
		}

	if ( !empty( $attributes['dropdown'] ) ) {

			$output .= '
				<div 
					class="' . $dropdown_class . '" 
					aria-labelledby="' . ( !empty( $attributes['anchor'] ) ? esc_attr( $attributes['anchor'] ) : '' ) . '"
				>
					' . $content . '
				</div>';

		$output .= '</div>';
	}

	return $output;
}