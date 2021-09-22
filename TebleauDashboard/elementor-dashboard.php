<?php
/**
 * Plugin Name: Elementor Tableau Dashboard Widget
 * Description: Custom Elementor Widget to display tableau dashboard dynamically.
 * Version:     1.1.2
 * Author:      Ming Sheng Choo
 * Author URI:  https://github.com/MingSheng92
 * package URI: https://github.com/MingSheng92/Elementor-Tableau-Widget
 */
namespace Solid_Dashboard;
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// The Widget_Base class is not available immediately after plugins are loaded, so
// we delay the class' use until Elementor widgets are registered
add_action( 'elementor/widgets/widgets_registered', function() {
	require_once('widget.php');

    // define widget class
	$tableau_dashboard_widget =	new Dashboard_Widget();

	// Let Elementor know about our widget
	Plugin::instance()->widgets_manager->register_widget_type( $tableau_dashboard_widget );
});