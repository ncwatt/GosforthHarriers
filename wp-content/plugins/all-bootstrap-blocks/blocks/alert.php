<?php
function areoi_render_block_alert( $attributes, $content ) 
{
	$class 			= 	trim( 
		areoi_get_class_name_str( array( 
			'alert',
			( !empty( $attributes['className'] ) ? $attributes['className'] : '' ),
			( !empty( $attributes['style'] ) ? $attributes['style'] : 'alert-primary' ),
			( !empty( $attributes['close'] ) ? 'alert-dismissible fade show' : '' ),
			( !empty( $attributes['icon'] ) ? 'd-flex align-items-center' : '' ),
		) ) 
		. ' ' . 
		areoi_get_display_class_str( $attributes, 'flex' ) 
	);

	$width = ( !empty( $attributes['icon']['width'] ) ? esc_attr( $attributes['icon']['width'] ) : 50 );
	$height = ( !empty( $attributes['icon']['height'] ) ? esc_attr( $attributes['icon']['height'] ) : 50 );
	$alt = ( !empty( $attributes['icon']['alt'] ) ? esc_attr( $attributes['icon']['alt'] ) : '' );

	$close = !empty( $attributes['close'] ) ? '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' : '';
	$icon = !empty( $attributes['icon'] ) ? '<img class="icon bi flex-shrink-0 me-2" src="' . esc_attr( $attributes['icon']['url'] ) . '" width="' . $width . '" height="' . $height . '" alt="' . $alt . '">' : '';
	$output = '
		<div ' . areoi_return_id( $attributes ) . ' class="' . areoi_format_block_id( $attributes['block_id'] ) . ' ' . $class . '" role="alert">
			' . $icon . '
			<div>' . $content . '</div>
			' . $close . ' 
		</div>
	';

	return $output;
}
