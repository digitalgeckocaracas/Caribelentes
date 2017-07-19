<?php

/** Constants */
defined( 'THEME_URI' ) || define( 'THEME_URI', get_template_directory_uri() );
defined( 'THEME_PATH' ) || define( 'THEME_PATH', realpath( __DIR__ ) );

include_once THEME_PATH . '/includes/functions.php';
require_once THEME_PATH . '/includes/register-sidebar.php';

// Constants
defined( 'DISALLOW_FILE_EDIT' ) || define( 'DISALLOW_FILE_EDIT', FALSE );
defined( 'TEXT_DOMAIN' ) || define( 'TEXT_DOMAIN', 'jp-basic' );
define( 'JPB_THEME_PATH', realpath( __DIR__ ) );


//include_once __DIR__ . '/includes/register-script.php';
include_once __DIR__ . '/includes/register-script-local.php';
include_once __DIR__ . '/includes/register-style.php';
//include_once __DIR__ . '/includes/register-style-local.php';

add_action( 'wp_enqueue_scripts', function () {

	/* Styles */
	wp_enqueue_style( 'bootstrap' );
	wp_enqueue_style( 'animate' );
	wp_enqueue_style( 'hover' );
	wp_enqueue_style( 'font-awesome' );
	// Theme
	wp_enqueue_style( 'main-theme' );

	/* Scripts */
	wp_enqueue_script( 'modernizr' );
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'bootstrap' );
	wp_enqueue_script( 'jquery-form' );

	// Bootstrap Alerts
	wp_register_script( 'bootstrap-alerts', apply_filters( 'js_cdn_uri', THEME_URI . '/js/bootstrap-alerts.min.js', 'bootstrap-alerts' ), array( 'jquery', 'bootstrap' ), NULL, TRUE );
	wp_enqueue_script( 'bootstrap-alerts' );


	// Bootstrap Theme
	/*wp_register_style( 'bootstrap-theme', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css', array( 'bootstrap' ), '3.3.4' );
	wp_enqueue_style( 'bootstrap-theme' );*/


	// Add defer atribute
	do_action( 'defer_script', array( 'jquery-form', 'bootstrap-alerts' ) );

	// Bootstrap complemetary text align
	wp_register_style( 'bs-text-align', THEME_URI . '/css/bootstrap-text-align.min.css', array( 'bootstrap' ), '1.0' );
	wp_enqueue_style( 'bs-text-align' );

	// Wordpress Core
	wp_register_style( 'wordpress-core', THEME_URI . '/css/wordpress-core.min.css', array( 'bootstrap', 'bs-text-align' ), '1.0' );
	wp_enqueue_style( 'wordpress-core' );

	if ( is_child_theme() ) {
		// Theme
		wp_register_style( 'theme', get_stylesheet_uri(), array( 'animate' ), '1.0' );
		wp_enqueue_style( 'theme' );
	}
} );

include_once __DIR__ . '/includes/theme-features.php';

/**
 * Encoded Mailto Link
 *
 * Create a spam-protected mailto link written in Javascript
 *
 * @param	string	the email address
 * @param	string	the link title
 * @param	mixed	any attributes
 * @return	string
 */
function safe_mailto( $email, $title = '', $attributes = '' ) {
	$title = (string) $title;

	if ( $title === '' ) {
		$title = $email;
	}

	$x = str_split( '<a href="mailto:', 1 );

	for ( $i = 0, $l = strlen( $email ); $i < $l; $i++ ) {
		$x[] = '|' . ord( $email[$i] );
	}

	$x[] = '"';

	if ( $attributes !== '' ) {
		if ( is_array( $attributes ) ) {
			foreach ( $attributes as $key => $val ) {
				$x[] = ' ' . $key . '="';
				for ( $i = 0, $l = strlen( $val ); $i < $l; $i++ ) {
					$x[] = '|' . ord( $val[$i] );
				}
				$x[] = '"';
			}
		} else {
			for ( $i = 0, $l = strlen( $attributes ); $i < $l; $i++ ) {
				$x[] = $attributes[$i];
			}
		}
	}

	$x[] = '>';

	$temp = array();
	for ( $i = 0, $l = strlen( $title ); $i < $l; $i++ ) {
		$ordinal = ord( $title[$i] );

		if ( $ordinal < 128 ) {
			$x[] = '|' . $ordinal;
		} else {
			if ( count( $temp ) === 0 ) {
				$count = ($ordinal < 224) ? 2 : 3;
			}

			$temp[] = $ordinal;
			if ( count( $temp ) === $count ) {
				$number = ($count === 3) ? (($temp[0] % 16) * 4096) + (($temp[1] % 64) * 64) + ($temp[2] % 64) : (($temp[0] % 32) * 64) + ($temp[1] % 64);
				$x[] = '|' . $number;
				$count = 1;
				$temp = array();
			}
		}
	}

	$x[] = '<';
	$x[] = '/';
	$x[] = 'a';
	$x[] = '>';

	$x = array_reverse( $x );

	$output = "<script type=\"text/javascript\">\n"
					. "\t//<![CDATA[\n"
					. "\tvar l=new Array();\n";

	for ( $i = 0, $c = count( $x ); $i < $c; $i++ ) {
		$output .= "\tl[" . $i . "] = '" . $x[$i] . "';\n";
	}

	$output .= "\n\tfor (var i = l.length-1; i >= 0; i=i-1) {\n"
					. "\t\tif (l[i].substring(0, 1) === '|') document.write(\"&#\"+unescape(l[i].substring(1))+\";\");\n"
					. "\t\telse document.write(unescape(l[i]));\n"
					. "\t}\n"
					. "\t//]]>\n"
					. '</script>';

	return $output;
}

require_once __DIR__ . '/admin/admin.php';

// Register Custom Navigation Walker
require_once('wp_bootstrap_navwalker.php');

class Custom_Walker extends Walker_Nav_Menu {

    function start_el(&$output, $item, $depth, $args) {
        global $wp_query;
        $indent = ( $depth > 0 ? str_repeat("\t", $depth) : '' ); // code indent
        // depth dependent classes
        $depth_classes = array(
            ( $depth == 0 ? 'main-menu-item' : 'sub-menu-item' ),
            ( $depth >= 2 ? 'sub-sub-menu-item' : '' ),
            ( $depth % 2 ? 'menu-item-odd' : 'menu-item-even' ),
            'menu-item-depth-' . $depth
        );
        $depth_class_names = esc_attr(implode(' ', $depth_classes));

        // passed classes
        $classes = empty($item->classes) ? array() : (array) $item->classes;

        if (!in_array($item->object, array('custom'))) {
            $post_data = get_post($item->object_id);
            $classes[] = $post_data->post_type . '-' . $post_data->post_name;
        }

        $class_names = esc_attr(implode(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item)));

        // build html
        $output .= $indent . '<li id="nav-menu-item-' . $item->ID . '" class="' . $depth_class_names . ' ' . $class_names . '">';

        // link attributes
        $attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
        $attributes .=!empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
        $attributes .=!empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
        $attributes .=!empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';
        $attributes .= ' class="menu-link ' . ( $depth > 0 ? 'sub-menu-link' : 'main-menu-link' ) . '"';

        $item_output = sprintf('%1$s<a%2$s>%3$s%4$s%5$s</a>%6$s', $args->before, $attributes, $args->link_before, apply_filters('the_title', $item->title, $item->ID), $args->link_after, $args->after
        );

        // build html
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }

}


function rw_register_meta_box() {
    if (!class_exists('RW_Meta_Box') or ! is_admin())
        return;
    $post_ID = !empty($_POST['post_ID']) ?
            $_POST['post_ID'] :
            (!empty($_GET['post']) ? $_GET['post'] : FALSE);

    if ($post_ID) {
        $current_post = get_post($post_ID);
        $current_post_type = $current_post->post_type;
        /*
          if (!in_array($current_post_type, array('page')))
          return;
         */
    }

    $meta_box[] = array(
        'id' => 'info_paquete',
        'title' => 'Detalles del paquete',
        'pages' => array('paquete_recomendado'),
        'context' => 'normal',
        'priority' => 'high',
        'fields' => array(
            array(
                'name' => 'Precio',
                'id' => 'precio_global',
                'type' => 'text',
            ),
            array(
                'name' => 'estadia',
                'id' => 'estadia_global',
                'type' => 'text',
            ),
    ));

    $meta_box[] = array(
        'id' => 'inf_destino',
        'title' => 'Detalles del destino',
        'pages' => array('nacional', 'internacional', 'mochileros'),
        'context' => 'normal',
        'priority' => 'high',
        'fields' => array(
            array(
                'name' => 'Precio',
                'id' => 'price_global',
                'type' => 'text',
            )
    ));

    if ($current_post->post_name == 'inicio') {
        $meta_box[] = array(
            'id' => 'info_contacto',
            'title' => 'Informacion de Contacto',
            'pages' => array('page'),
            'context' => 'normal',
            'priority' => 'high',
            'fields' => array(
                array(
                    'name' => 'Dirección',
                    'id' => 'dir_global',
                    'type' => 'textarea',
                ),
                array(
                    'name' => 'Telefono 1',
                    'id' => 'num_telf1',
                    'type' => 'text',
                ),
                array(
                    'name' => 'Correo General',
                    'id' => 'mail_gen',
                    'type' => 'text',
                ),
                array(
                    'name' => 'Coordenadas Google Maps',
                    'id' => 'map_coord',
                    'type' => 'text',
                )
        ));


        $meta_box[] = array(
            'id' => 'r_scl',
            'title' => 'Redes Sociales',
            'pages' => array('page'),
            'context' => 'normal',
            'priority' => 'high',
            'fields' => array(
                array(
                    'name' => 'Facebook',
                    'id' => 'lnk_fb',
                    'type' => 'text',
                ),
                array(
                    'name' => 'Twitter',
                    'id' => 'lnk_tw',
                    'type' => 'text',
                ),
                array(
                    'name' => 'Instagram',
                    'id' => 'lnk_ig',
                    'type' => 'text',
                ),
                array(
                    'name' => 'Whatsapp',
                    'id' => 'wa_dat',
                    'type' => 'text',
                ),
                array(
                    'name' => 'Blackberry Pin',
                    'id' => 'pin_dat',
                    'type' => 'text',
                ),
        ));
    }

    $meta_box[] = array(
        'id' => 'info_paquetes',
        'title' => 'Informacion del paquete',
        'pages' => array('paquetes'),
        'context' => 'normal',
        'priority' => 'high',
        'fields' => array(
            array(
                'name' => 'Precio del Paquete',
                'id' => 'info_pack',
                'type' => 'text',
            )
    ));

    if (is_array($meta_box)) {
        foreach ($meta_box as $value) {
            new RW_Meta_Box($value);
        }
    }
}

add_action('admin_init', 'rw_register_meta_box');