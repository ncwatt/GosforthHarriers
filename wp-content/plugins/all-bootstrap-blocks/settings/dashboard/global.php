<?php
/*
Name: Global
Slug: global
Description: Only untick these options if you plan to include your own version of Bootstrap CSS and JS in your theme, otherwsie your Bootstrap blocks will not be styled and will display incorrectly. When including your own version the block editor may not match up with the front end so we suggest keeping these checked.
Position: 10
Theme: 
*/
$slug = AREOI__PREPEND . ( !empty( $section ) ? '-' . $section : '' )  . '-global-';

return array(
	array(
		'label' => 'Bootstrap Version',
		'name' => $slug . 'bootstrap-version',
		'variable' => '',
		'row' => 'default',
		'input' => 'select',
		'default' => '5.0.2',
		'description' => 'Choose the version number of Bootstrap you would like to include.',
		'allow_reset' => false,
		'options' => array(
			array(
				'id'			=> '5.0.2',
				'label' 		=> '5.0.2 (Default)',
				'description' 	=> null
			),
			array(
				'id'			=> '5.3.0',
				'label' 		=> '5.3.0',
				'description' 	=> null
			),
			array(
				'id'			=> '5.3.3',
				'label' 		=> '5.3.3 (Beta)',
				'description' 	=> null
			),
		)
	),
	array(
		'label' => 'Include Bootstrap CSS',
		'name' => $slug . 'bootstrap-css',
		'variable' => '',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 1,
		'description' => 'If checked, then Bootstrap CSS will automatically be inserted into the head of your website. You will be able to manage all of your Bootstrap settings via Wordpress and each time you save variables, Bootstrap will be recompiled and minified.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Include Bootstrap Icon CSS',
		'name' => $slug . 'bootstrap-icon-css',
		'variable' => '',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => false,
		'description' => 'If checked, then Bootstrap Icons CSS will automatically be inserted into the head of your website.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Bootstrap CSS Priority',
		'name' => $slug . 'bootstrap-css-priority',
		'variable' => '',
		'row' => 'default',
		'input' => 'text',
		'default' => null,
		'exclude_variables' => true,
		'description' => 'If using All Bootstrap Blocks to include your Botstrap CSS you can control where this gets added to the <head> section of the site. Use this field to specify when the CSS should be included. Setting this value below 10 (1 to 9) will include the Bootstrap CSS before the WordPress global styles from your theme.json file.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Include Bootstrap JS',
		'name' => $slug . 'bootstrap-js',
		'variable' => '',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 1,
		'description' => 'If checked, then Bootstrap JS will automatically be inserted into the footer of your website. This will allow you to make use of things like tooltips, popovers and modals as well as all other Bootstrap JS functionality.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Display Units',
		'name' => $slug . 'display-units',
		'variable' => '',
		'row' => 'default',
		'input' => 'select',
		'default' => 'px',
		'description' => 'Choose the units you would like to apply when setting display settings such as padding and margin on blocks.',
		'allow_reset' => false,
		'options' => array(
			array(
				'id'			=> 'px',
				'label' 		=> 'px (Default)',
				'description' 	=> null
			),
			array(
				'id'			=> 'rem',
				'label' 		=> 'rem',
				'description' 	=> null
			),
			array(
				'id'			=> 'em',
				'label' 		=> 'em',
				'description' 	=> null
			),
			array(
				'id'			=> 'vw',
				'label' 		=> 'vw',
				'description' 	=> null
			),
			array(
				'id'			=> 'vh',
				'label' 		=> 'vh',
				'description' 	=> null
			),
		)
	),
	array(
		'label' => 'Default Blocks',
		'name' => '',
		'variable' => '',
		'row' => 'default',
		'input' => 'header',
		'default' => '',
		'description' => 'Wordpress includes a number of blocks by default that this plugin also includes. Below you can hide the default Wordpress blocks if needed to stop confusion when selecting blocks on a page.',
		'allow_reset' => true,
		'options' => array()
	),
	array(
		'label' => 'Hide Buttons Block',
		'name' => $slug . 'hide-buttons-block',
		'variable' => '',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 0,
		'description' => 'If checked the default Wordpress block will not display in the page builder.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Hide Columns Block',
		'name' => $slug . 'hide-columns-block',
		'variable' => '',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 0,
		'description' => 'If checked the default Wordpress block will not display in the page builder.',
		'allow_reset' => false,
		'options' => array()
	),

	array(
		'label' => 'Exclude Blocks',
		'name' => '',
		'variable' => '',
		'row' => 'default',
		'input' => 'header',
		'default' => '',
		'description' => 'In some cases you may wish to exclude some of the available blocks included within this plugin.',
		'allow_reset' => true,
		'options' => array()
	),
	array(
		'label' => 'Hide strip block',
		'name' => $slug . 'hide-strip-block',
		'variable' => '',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 0,
		'description' => 'If checked the strip block will not display in the page builder.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Hide container block',
		'name' => $slug . 'hide-container-block',
		'variable' => '',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 0,
		'description' => 'If checked the container block will not display in the page builder.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Hide row block',
		'name' => $slug . 'hide-row-block',
		'variable' => '',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 0,
		'description' => 'If checked the row block will not display in the page builder.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Hide column block',
		'name' => $slug . 'hide-column-block',
		'variable' => '',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 0,
		'description' => 'If checked the column block will not display in the page builder.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Hide column-break block',
		'name' => $slug . 'hide-column-break-block',
		'variable' => '',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 0,
		'description' => 'If checked the column-break block will not display in the page builder.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Hide accordion block',
		'name' => $slug . 'hide-accordion-block',
		'variable' => '',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 0,
		'description' => 'If checked the accordion block will not display in the page builder.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Hide alert block',
		'name' => $slug . 'hide-alert-block',
		'variable' => '',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 0,
		'description' => 'If checked the alert block will not display in the page builder.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Hide breadcrumb block',
		'name' => $slug . 'hide-breadcrumb-block',
		'variable' => '',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 0,
		'description' => 'If checked the breadcrumb block will not display in the page builder.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Hide button block',
		'name' => $slug . 'hide-button-block',
		'variable' => '',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 0,
		'description' => 'If checked the button block will not display in the page builder.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Hide button-group block',
		'name' => $slug . 'hide-button-group-block',
		'variable' => '',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 0,
		'description' => 'If checked the button-group block will not display in the page builder.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Hide card block',
		'name' => $slug . 'hide-card-block',
		'variable' => '',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 0,
		'description' => 'If checked the card block will not display in the page builder.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Hide card-group block',
		'name' => $slug . 'hide-card-group-block',
		'variable' => '',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 0,
		'description' => 'If checked the card-group block will not display in the page builder.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Hide carousel block',
		'name' => $slug . 'hide-carousel-block',
		'variable' => '',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 0,
		'description' => 'If checked the carousel block will not display in the page builder.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Hide collapse block',
		'name' => $slug . 'hide-collapse-block',
		'variable' => '',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 0,
		'description' => 'If checked the collapse block will not display in the page builder.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Hide div block',
		'name' => $slug . 'hide-div-block',
		'variable' => '',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 0,
		'description' => 'If checked the div block will not display in the page builder.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Hide list-group block',
		'name' => $slug . 'hide-list-group-block',
		'variable' => '',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 0,
		'description' => 'If checked the list-group block will not display in the page builder.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Hide modal block',
		'name' => $slug . 'hide-modal-block',
		'variable' => '',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 0,
		'description' => 'If checked the modal block will not display in the page builder.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Hide tabs block',
		'name' => $slug . 'hide-tabs-block',
		'variable' => '',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 0,
		'description' => 'If checked the tabs block will not display in the page builder.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Hide nav-and-tab block',
		'name' => $slug . 'hide-nav-and-tab-block',
		'variable' => '',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 0,
		'description' => 'If checked the nav-and-tab block will not display in the page builder.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Hide offcanvas block',
		'name' => $slug . 'hide-offcanvas-block',
		'variable' => '',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 0,
		'description' => 'If checked the offcanvas block will not display in the page builder.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Hide progress block',
		'name' => $slug . 'hide-progress-block',
		'variable' => '',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 0,
		'description' => 'If checked the progress block will not display in the page builder.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Hide spinner block',
		'name' => $slug . 'hide-spinner-block',
		'variable' => '',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 0,
		'description' => 'If checked the spinner block will not display in the page builder.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Hide toast block',
		'name' => $slug . 'hide-toast-block',
		'variable' => '',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 0,
		'description' => 'If checked the toast block will not display in the page builder.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Hide icon block',
		'name' => $slug . 'hide-icon-block',
		'variable' => '',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 0,
		'description' => 'If checked the icon block will not display in the page builder.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Hide banner block',
		'name' => $slug . 'hide-banner-block',
		'variable' => '',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 0,
		'description' => 'If checked the banner block will not display in the page builder.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Hide content-with-media block',
		'name' => $slug . 'hide-content-with-media-block',
		'variable' => '',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 0,
		'description' => 'If checked the content-with-media block will not display in the page builder.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Hide content-grid block',
		'name' => $slug . 'hide-content-grid-block',
		'variable' => '',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 0,
		'description' => 'If checked the content-grid block will not display in the page builder.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Hide post-grid block',
		'name' => $slug . 'hide-post-grid-block',
		'variable' => '',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 0,
		'description' => 'If checked the post-grid block will not display in the page builder.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Hide media-grid block',
		'name' => $slug . 'hide-media-grid-block',
		'variable' => '',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 0,
		'description' => 'If checked the media-grid block will not display in the page builder.',
		'allow_reset' => false,
		'options' => array()
	),
);