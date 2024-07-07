<?php
function areoi_render_block_list_group_item( $attributes, $content ) 
{
	$class 			= 	trim( 
		areoi_get_class_name_str( array( 
			'list-group-item',
			( !empty( $attributes['className'] ) ? $attributes['className'] : '' ),
			( !empty( $attributes['style'] ) && empty( $attributes['item_style'] ) ? $attributes['style'] : '' ),
			( !empty( $attributes['item_style'] ) ? $attributes['item_style'] : '' ),
			( !empty( $attributes['active'] ) ? 'active' : '' ),
			( !empty( $attributes['disabled'] ) ? 'disabled' : '' ),
			( !empty( $attributes['action'] ) ? 'list-group-item-action' : '' ),
		) ) 
		. ' ' . 
		areoi_get_display_class_str( $attributes, 'block' ) 
	);

	if ( !empty( $attributes['url'] ) ) {
		$output = '
			<a 
				href="' . esc_attr( $attributes['url'] ) . '" 
				rel="' . (!empty( $attributes['rel'] ) ? esc_attr( $attributes['rel'] ) : '') . '" 
				target="' . (!empty( $attributes['linkTarget'] ) ? esc_attr( $attributes['linkTarget'] ) : '') . '" 
				id="block-' . esc_attr( $attributes['block_id'] ) . '" 
				class="' . $class . '"
			>
				' . $attributes['text'] . ' 
			</a>
		';
	} else {
		$output = '
			<div ' . areoi_return_id( $attributes ) . ' class="' . areoi_return_id( $attributes ) . ' ' . $class . '">
				' . ( !empty( $attributes['text'] ) ? $attributes['text'] : '' ) . '
			</div>
		';
	}

	return $output;
}