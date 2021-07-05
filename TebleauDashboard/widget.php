<?php
/**
 * Plugin Name: Elementor Tableau Dashboard Widget
 * Description: Custom Elementor Widget to display tableau dashboard dynamically.
 * Version:     1.0.1
 * Author:      Ming Sheng Choo
 * Author URI:  https://github.com/MingSheng92
 */
namespace Solid_Dashboard;

use Elementor\Repeater;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Dashboard_Widget extends Widget_Base {
	// constructor 
	public function __construct($data = [], $args = null) {
		parent::__construct($data, $args);
		
		// uncomment if we have dependencies on any javscript libraries later
		//wp_register_script( 'script-handle', 'path/to/file.js', [ 'elementor-frontend' ], '1.0.0', true );

		// add our css file here 
		wp_register_style( 'style-handle', '/wp-content/plugins/TebleauDashboard/css/dWidget.css');
	}

	public static $slug = 'elementor-dashboard';

	public function get_name() { return self::$slug; }

	public function get_title() { return __('Custom Tableau Dashboard', self::$slug); }

	public function get_icon() { return 'fa fa-line-chart'; }

	public function get_categories() { return [ 'general' ]; }

	//public function get_script_depends() { return [ 'script-handle' ]; }

	public function get_style_depends() { return [ 'style-handle' ]; }

	protected function _register_controls() {

		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Dashboards', self::$slug ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		// Use the repeater to define one one set of the items we want to repeat look like
		$repeater = new Repeater();

		$repeater->add_control(
			'dashboard_name',
			[
				'label' => __( "Dashboard's name", self::$slug ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( "Dashboard's name", self::$slug ),
				'placeholder' => __( 'Value Attribute', self::$slug ),
			]
		);

		$repeater->add_control(
			'dashboard_default',
			[
				'label' => __( 'Default Dashboard', self::$slug ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'yes' => __( 'Yes', self::$slug ),
					'no' => __( 'No', self::$slug ),
				],
				'default' => 'no',
			]
		);		

		$repeater->add_control(
			'dashboard_url',
			[
				'label' => __( "Dashboard's URL", self::$slug ),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => __( 'https://public.tableau.com/views/<Your Dashboard>', 'plugin-domain' ),
			]
		); 

		$repeater->add_control(
			'dashboard_narr',
			[
				'label' => __( "Dashboard's narrative", self::$slug ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( "The Dashboard's narrative", self::$slug ),
			]
		); 

        // Initilize the dashboards settins into a control
		$this->add_control(
			'dashboards_Settings',
			[
				'label' => __( 'Dashboard List', self::$slug ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[]
				],
				'title_field' => '{{{ dashboard_name }}}'
			]
		);

		// end section for content 
		$this->end_controls_section();

		// start a new style tab
		$this->start_controls_section(
			'style_section',
			[
				'label' => __( 'Dashboard', self::$slug ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Tab\'s Title Color', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'content_color',
			[
				'label' => __( 'Tab\'s Content Color', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .title' => 'color: {{VALUE}}',
				],
			]
		);
		
		// end section for content 
		$this->end_controls_section();
	}

	protected function render() {
        // select the control in _register_controls()
		$dashboards = $this->get_settings_for_display('dashboards_Settings');

		// select the control style settings
		$settings = $this->get_settings_for_display();

        // generate tabs for tableau dashboards
        echo "<div class='tab'>";
		foreach ($dashboards as $dashboard_item) {
            if ($dashboard_item['dashboard_default'] == 'yes') {
				echo "<button id='defaultDashboard' class='tablinks' style='color : ".$settings['title_color']."' onclick='createViz(event, ".json_encode($dashboard_item['dashboard_url']['url']).", ".json_encode($dashboard_item['dashboard_narr'])." );'>{$dashboard_item['dashboard_name']}</button>";
			}
            else {
                echo "<button class='tablinks' style='color : ".$settings['title_color']."' onclick='createViz(event, ".json_encode($dashboard_item['dashboard_url']['url']).", ".json_encode($dashboard_item['dashboard_narr'])." );'>{$dashboard_item['dashboard_name']}</button>";
            }
		}
        echo "</div>";

        // generate content for tabs 
        echo "<div class='tabcontent'>";
        echo "<p id='narrative' style='color : ".$settings['content_color']."'></p>";
        echo "<div id='vizContainer'></div>";
        echo "</div>";

        // inject javscript into the widget
        // inject Tableau Javascript API 
        echo "<script type='text/javascript' src='https://public.tableau.com/javascripts/api/tableau-2.8.0.min.js'></script>";
        // now inject custom script into the widget
        echo <<<source_code
        <script>
            var viz;
            document.getElementById("defaultDashboard").click();
            function createViz(event, url, narrative) { 
                document.getElementById("narrative").innerHTML = narrative;
                var vizDiv = document.getElementById("vizContainer"),
                    options = {
                        hideToolbar: true,
                        hideTabs: true,
                        device: window.innerWidth > 500 ? 'desktop' : 'phone'
                    };
                if (viz) { 
                    viz.dispose();
                }
                viz = new tableau.Viz(vizDiv, url, options);
            }
        </script>
        source_code;
	}
}