<?php
function areoi_render_block_dropdown_item( $attributes, $content ) 
{
	$class 			= 	trim( 
		areoi_get_class_name_str( array( 
			( !empty( $attributes['type'] ) ? $attributes['type'] : '' ),
			( !empty( $attributes['active'] ) ? 'active' : '' ),
			( !empty( $attributes['disabled'] ) ? 'disabled' : '' ),
			( !empty( $attributes['className'] ) ? $attributes['className'] : '' ),
		) ) 
		. ' ' . 
		areoi_get_display_class_str( $attributes, 'block' ) 
	);

	switch ( $attributes['type'] ) {
		case 'dropdown-item':
			$button_open = '
				<a 
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
			$button_open .= '>';

			$output = 
				$button_open . '		
					' . esc_attr( $attributes['text'] ) . ' 
				</a>
			';
			break;
		
		default:
			$output = '
				<div ' . areoi_return_id( $attributes ) . ' class="' . areoi_format_block_id( $attributes['block_id'] ) . ' ' . $class . '">			
					' . $attributes['text'] . ' 
				</div>
			';
			break;
	}

	return $output;
}