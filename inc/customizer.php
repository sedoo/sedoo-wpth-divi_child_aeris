<?php
/**
 * aeris Theme Customizer
 *
 * @package aeris
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function theme_aeris_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
}
add_action( 'customize_register', 'theme_aeris_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function theme_aeris_customize_preview_js() {
	wp_enqueue_script( 'theme_aeris_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'theme_aeris_customize_preview_js' );


/*************************************************************************************************************
*  Ajout du support de l'image d'entête personnalisée dans le customizer
*
*/

$args = array(
	'width'         => 1600,
	'height'        => 300,
	'default-image' => get_template_directory_uri() . '/images/atmosphere-cover.jpg',
);
add_theme_support( 'custom-header', $args );

/**************************************************************************************************************
*  Ajout du controleur de couleur personnalisée dans le customizer
*
*  source : https://codex.wordpress.org/Plugin_API/Action_Reference/customize_register
*/

function theme_aeris_customize_color( $wp_customize )
{
   //All our sections, settings, and controls will be added here

// remove section colors
	$wp_customize->remove_section('colors');

//1. Define a new section (if desired) to the Theme Customizer
 	$wp_customize->add_section('theme_aeris_color_scheme', array(
        'title'    => __('Options du thème', 'theme-aeris'),
        'description' => '',
        'priority' => 30,
    ));

//2. Register new settings to the WP database...

    //  =================================
    //  = Select Box pour theme color   =
    //  =================================
     $wp_customize->add_setting('theme_aeris_main_color', array(
        'default'        => 'custom',
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
 
    ));
 
//3. Finally, we define the control itself (which links a setting to a section and renders the HTML controls)...
	
	$wp_customize->add_control( 'theme_aeris_main_color', array(
        'settings' => 'theme_aeris_main_color',
        'label'   => 'Sélectionner une couleur dominante:',
        'section' => 'theme_aeris_color_scheme',
        'type'    => 'select',
        'choices'    => array(        	
            '#90BCD1' => 'Bleu clair (Atmosphere)',
            '#08A5E0' => 'Bleu clair 2 (Pic du Midi)',
            '#1F7E9E' => 'Bleu océan (Hydrosphère)',
            '#4765a0' => 'Bleu (Aeris)',
            '#2D4F59' => 'Bleu foncé (Astronomie)',
            '#B6CC49' => 'Vert clair (Biosphère)',
            '#7DBF3B' => 'Vert foncé Environnement',
            '#DD9946' => 'Orange (Géosciences)',
            '#E25B3D' => 'Rouge (Planétologie)',
            '#CCC' => 'Gris clair',
            '#AAA' => 'Gris',
            '#777' => 'Gris foncé',
            '#000' => 'Noir',
			'custom' => 'Autre, remplir le champs "code couleur personnalisé"',
        ),
    ));

	//  =============================
    //  = Text Input color code     =
    //  =============================
    $wp_customize->add_setting('theme_aeris_color_code', array(
        'default'        => '#CCC',
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
 
    ));

	$wp_customize->add_control('theme_aeris_color_code', array(
        'label'      => __('...ou un code couleur personnalisé', 'theme_aeris'),
        'section'    => 'theme_aeris_color_scheme',
        'settings'   => 'theme_aeris_color_code',
    ));


    //  =============================
    //  = Radio Input boxes or not  =
    //  =============================
    $wp_customize->add_setting('theme_aeris_box', array(
        'default'        => 'nobox',
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
    ));

    $wp_customize->add_control('theme_aeris_box', array(
        'label'      => __('Mode d\'affichage', 'theme_aeris'),
        'section'    => 'theme_aeris_color_scheme',
        'settings'   => 'theme_aeris_box',
        'type'       => 'radio',
        'choices'    => array(
            'value1' => 'Tous en boîte',
            'value2' => 'Aplat',
        ),
    ));

	//  =============================
    //  = Text Input copyright     =
    //  =============================
    $wp_customize->add_setting('theme_aeris_copyright', array(
        'default'        => 'Pôle Aeris '.date('Y').'- Service de données OMP (SEDOO)',
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
 
    ));

	$wp_customize->add_control('theme_aeris_copyright', array(
        'label'      => __('© Copyright', 'theme_aeris'),
        'section'    => 'theme_aeris_color_scheme',
        'settings'   => 'theme_aeris_copyright',
    ));

}
add_action( 'customize_register', 'theme_aeris_customize_color' );

/*****
* 
* chargement du code couleur selectionné ou saisi
* 
*/

function theme_aeris_color_style() {
	
	if (get_theme_mod('theme_aeris_main_color') == "custom" ) {
		$code_color = get_theme_mod( 'theme_aeris_color_code' );
	}
	else {
		$code_color	= get_theme_mod( 'theme_aeris_main_color' );
	}

	$rgb_color = hex2rgb($code_color); // array 0 => r , 1 => g, 2 => b
	
	?>
         <style type="text/css">
			h1,
			h2,
			h3,
			blockquote,
			.main-navigation ul ul li:first-child,
			.main-navigation .nav-menu > .current_page_item > a,
			.main-navigation .nav-menu > .current-menu-item > a,
			.main-navigation .nav-menu > .current_page_ancestor > a,
			.main-navigation .nav-menu > .current-menu-ancestor > a,
			[role="listNews"] article > header > h3 {
				border-color: <?php echo $code_color;?>; 
			}

			a,
			.main-navigation .nav-menu > li > a:hover,
			.main-navigation .nav-menu > li > a:focus,
			.main-navigation .nav-menu > .current_page_item > a,
			.main-navigation .nav-menu > .current-menu-item > a,
			.main-navigation .nav-menu > .current_page_ancestor > a,
			.main-navigation .nav-menu > .current-menu-ancestor > a
			{
				color: <?php echo $code_color;?>;
			}

			aside .widget-title,
			.bkg,
			[role="listNews"] article.format-quote > header > blockquote,
            [role="listProgram"] > header > h2 {
				background: <?php echo $code_color;?>;
			}

			a:hover,
			a:focus,
			a:active {
				color: #009FDE;
			}

			.site-branding h1 a {
				background-color: rgba(<?php echo $rgb_color[0].",".$rgb_color[1].",".$rgb_color[2].",.5)"; ?>;
			}
         </style>
    <?php

}
add_action( 'wp_head', 'theme_aeris_color_style');

/*****
* 
* Ajout des styles Boxes
* 
*/

function theme_aeris_box_style() {
	
	if( get_theme_mod( 'theme_aeris_box' ) == "value1") {
	wp_enqueue_style('theme-aeris-box', get_bloginfo('template_directory') . '/css/boxes.css');
	}

}
add_action( 'wp_enqueue_scripts', 'theme_aeris_box_style' );

/******************************************************************
* Ajout du css custom dans le customizer 
*/
function custom_customize_enqueue() {
    wp_enqueue_style('custom-css-customize', get_bloginfo('template_directory') . '/css/customizer.css');
}
add_action( 'customize_controls_enqueue_scripts', 'custom_customize_enqueue' );
?>