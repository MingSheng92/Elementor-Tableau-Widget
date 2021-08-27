<?php
namespace Solid_Dashboard;

use Elementor\Repeater;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Dashboard_Widget extends Widget_Base {
	// constructor 
	public function __construct($data = [], $args = null) {
		parent::__construct($data, $args);
		
		// include tableau cdn script for dynamic loading
		wp_register_script( 'tableau', 'https://public.tableau.com/javascripts/api/tableau-2.8.0.min.js', null, null, false );
		wp_enqueue_script('tableau');

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

		// create new repeater control
		// never use the repeater code sample from tabs widget 
		// will encounter css bug according to documentation
		// https://github.com/elementor/elementor/issues/13809
		$repeater = new Repeater();

		$repeater->add_control(
			'tab_title',
			[
				'label' => __( "Dashboard Title", self::$slug ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( "Dashboard Title", self::$slug ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'tab_content',
			[
				'label' => esc_html__( "Dashboard's content", self::$slug ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'rows' => 10,
				'default' => __( "The Dashboard's narrative", self::$slug ),
			]
		);

		$repeater->add_control(
			'tab_url',
			[
				'label' => __( "Dashboard URL", self::$slug ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => __( 'https://public.tableau.com/views/<Your Dashboard>', 'plugin-domain' ),
				'label_block' => true,				
			]
		);

        // Initilize the dashboards settins into a control
		$this->add_control(
			'tabs',
			[
				'label' => __( 'Dashboard Tabs', self::$slug ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'tab_title' => __( "Dashboard #1", self::$slug ),
						'tab_content' => __( "Dashboard content", self::$slug ),
						'tab_url' => __( "Dashboard URL", self::$slug ),
					],
				],
				'title_field' => '{{{ tab_title }}}'
			]
		);

		/*
		$this->add_responsive_control(
			'content_align',
			[
				'label' => __( 'Alignment',  self::$slug ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left',  self::$slug ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', self::$slug ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', self::$slug ),
						'icon' => 'fa fa-align-right',
					],
					'space-evenly' => [
						'title' => __( 'Justified', self::$slug ),
						'icon' => 'fa fa-align-justify',
					]
				],
				'default' => 'left',
				'selectors' => [
					'{{WRAPPER}} .custom-tabs' => 'justify-content: {{VALUE}};',
				],
			]
		);
		*/

		$this->add_control(
			'tabs_position',
			[
				'label' => __( 'Tabs Position', self::$slug ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'static',
				'options' => [
					'static'  => __( 'Static', self::$slug ),
					'sticky' => __( 'Sticky', self::$slug ),
				],
				'selectors' => [
					'{{WRAPPER}} .custom-tabs, {{WRAPPER}} .custom-mobile-container' => 'position: {{VALUE}};',
				],
			]
		);		

		$this-> add_control(
			'view',
			[
				'label' =>  __( "Dashboard's content", self::$slug ),
				'type' => \Elementor\Controls_Manager::HIDDEN,
				'default' => 'traditional',
			]
		);

		$this-> end_controls_section();

		// STYLE SECTION 
		$this->start_controls_section(
			'section_tabs_style',
			[
				'label' => __( 'Dashboard', self::$slug ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'border_width',
			[
				'label' => __( 'Border Width', self::$slug ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'default' => [
					'size' => 1,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .custom-tab-content' => 'border-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'border_color',
			[
				'label' => __( 'Border Color', self::$slug ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .custom-tab-content' => 'border-color: {{VALUE}};',
				],
			]
		);

		/* **********************************************************
		* STYLE Section for Tab Title
		*************************************************************/
		$this->add_control(
			'heading_title',
			[
				'label' => __( 'Tab', self::$slug ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		/* Remove the customization control for the following as to follow figma template
		Add back if needed
		$this->add_control(
			'Tab_border',
			[
				'label' => __( 'Tab border', self::$slug ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .custom-tab-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

	
		$this->add_control(
			'Tab_padding',
			[
				'label' => __( 'Tab padding', self::$slug ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .custom-tab-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'background_color',
			[
				'label' => __( 'Background Color', self::$slug ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .custom-tab-title' => 'background-color: {{VALUE}};',
				],
			]
		);		
		
		$this->add_control(
			'tab_active_color',
			[
				'label' => __( 'Active Color', 'elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .custom-tab-title.elementor-active' => 'color: {{VALUE}};',
				],
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_4,
				],
			]
		);		

		$this->add_control(
			'font_color',
			[
				'label' => __( 'Font Color', self::$slug ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .custom-tab-title' => 'color: {{VALUE}};',
				],
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
			]
		);
		*/

		$this->add_control(
			'hover_color',
			[
				'label' => __( 'onHover Color', 'elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#B6D3FE',
				'selectors' => [
					'{{WRAPPER}} .custom-tab-container:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'tab_typography',
				'selector' => '{{WRAPPER}} .custom-tab-item',
				'scheme' => \Elementor\Scheme_Typography::TYPOGRAPHY_1,
			]
		);

		/* **********************************************************
		* STYLE Section for Tab Content
		*************************************************************/
		$this->add_control(
			'heading_content',
			[
				'label' => __( 'Content', self::$slug ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
	
		$this->add_control(
			'margin',
			[
				'label' => __( 'Text Margin', self::$slug ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .custom-tab-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'content_color',
			[
				'label' => __( 'Font color', self::$slug ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .custom-tab-text' => 'color: {{VALUE}};',
				],
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_3,
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'selector' => '{{WRAPPER}} .custom-tab-content',
				'scheme' => \Elementor\Scheme_Typography::TYPOGRAPHY_3,
			]
		);

		$this->add_control(
			'dashboard_ margin',
			[
				'label' => __( 'Dashboard Margin', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .custom-tab-dashboard' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this-> end_controls_section();
	}

	/**
	 * Render custom widget output on the frontend
	 * @access protected 
	 * 
	*/
	protected function render() {
		$tabs = $this->get_settings( 'tabs' );
		
		$id_int = substr( $this->get_id_int(), 0, 3 );
		?>

		<!-- Navigation tab for mobile -->
		<div class="custom-mobile-container">
			<div class="custom-mobile-tab">
				<?php foreach ($tabs as $index => $item) :
					$tab_count = $index + 1;
				?>
					<div 
						class = 'itab-container fade'
						id = "itab-container-<?php echo $tab_count ?>"
						data-tab = "<?php echo$tab_count ?>"
						tabindex = "<?php echo $id_int . $tab_count ?>"
						role = 'mobile-tab'
						aria-control = "itab-container-<?php echo $id_int . $tab_count ?>"
						data-url="<?php echo $item['tab_url']['url'] ?>"
						data-content="<?php echo $item['tab_content'] ?>"
					>
						<div class="custom-mobile-tab-item num">
							<?php echo $tab_count?>.
						</div>
						<div class="custom-mobile-tab-item">
							<?php echo $item['tab_title'] ?>
						</div>		
					</div>
				<?php endforeach ?>

				<a class="prev" onclick="mobileVizHandler(-1)">&#10094;</a>
				<a class="next" onclick="mobileVizHandler(1)">&#10095;</a>
			</div>
		</div>

		<!-- Navigation tab for Desktop -->
		<div class="custom-tabs" role="tablist">
			<?php foreach ($tabs as $index => $item) :
				$tab_count = $index + 1;

				$tab_container_setting_key = $this->get_repeater_setting_key('tab_title', 'tabs', $index);

				// reset
				$ttl_attr = [];

				//'id' => 'custom-tab-title-'. $id_int . $tab_count,
				$ttl_attr = [
					'class' => ['custom-tab-container'],
					'id' => 'dashboard-'. $tab_count,
					'data-tab' => $tab_count,
					'tabindex' => $id_int . $tab_count,
					'role' => 'tab',
					'aria-controls' => 'custom-tab-containers-' . $id_int . $tab_count,
				];

				// append attribute 
				$this->add_render_attribute( $tab_container_setting_key,  $ttl_attr);

				/* back up  
				<div <?php echo $this->get_render_attribute_string( $tab_container_setting_key ); ?>
					onclick="createViz(event, '<?php echo $item['tab_url']['url'] ?>', '<?php echo $item['tab_content'] ?>');"
				>
				*/
			?> 
				<div <?php echo $this->get_render_attribute_string( $tab_container_setting_key ); ?>
					data-url="<?php echo $item['tab_url']['url'] ?>"
					data-content="<?php echo $item['tab_content'] ?>"
					onclick="vizHandler(event, <?php echo $tab_count?>);"
				>
					<div class="custom-tab-item num">
						<?php echo $tab_count?>.
					</div>
					<div class="custom-tab-item">
						<?php echo $item['tab_title'] ?>
					</div>
				</div>
			<?php endforeach ?>
		</div>

		<?php 
			// get settings for tab content
			$tab_content_setting_key = $this->get_repeater_setting_key('tab_content', 'tabs', 0);

			$cont_attr = [
				'class' => ['custom-tab-content'],
				'data-tab' => 1,
				'role' => 'tabpanel',
				'aria-controls' => 'custom-tab-content-1',
			];

			$this->add_render_attribute( $tab_content_setting_key,  $cont_attr);
		?>

		<!-- generate content for tabs -->
		<div <?php echo $this->get_render_attribute_string($tab_content_setting_key) ?>>
			<p id="narrative" class="custom-tab-text" ></p>
			<div id="vizContainer" class="custom-tab-dashboard"></div>
        </div>

		<!-- Adding custom Javascript -->
		<script>
			// to keep track of current active mobile tab 
            var tabIndex = 1;
			// to keep the viz variable so that it can be 
			// remove and recreate at anytime
			var viz;
            
			// set first click to display the first viz in the tabs
			document.getElementById('dashboard-' + tabIndex.toString()).click();

			function mobileVizHandler(n) {
				tabIndex += n;

				var mobileTabs = document.getElementsByClassName("itab-container");
				// reset tabindex rule
				if (tabIndex > mobileTabs.length) { tabIndex = 1 }
				if (tabIndex < 1) { tabIndex = mobileTabs.length }

				//console.log(tabIndex);
				// click the desktop menu 
				document.getElementById('dashboard-' + tabIndex.toString()).click();
			} 

			function vizHandler(event, n) {
				// handlle mobile tabs menu 
				var mobileTabs = document.getElementsByClassName("itab-container");

				// handle the desktop nav menu 
				// styling handler for the datalink button to set active state
				var desktopTabs = document.getElementsByClassName("custom-tab-container");
				// run through all containers to clear and set active class
				for  (i =0; i<desktopTabs.length; i++) {
					// reset all 
					desktopTabs[i].className = desktopTabs[i].className.replace(" active", "");
					mobileTabs[i].style.display = "none";
				}
				// next set the clicked container to active
				event.currentTarget.className += " active";
				// set to display
				mobileTabs[n-1].style.display = "block";

				// call to remove and create the viz 
				let container = document.getElementById('dashboard-' + n.toString());
				let url = container.getAttribute('data-url');
				let content = container.getAttribute('data-content');

				// call function to create visualization 
				createViz(url, content);
			}

			// function to create visualization with tableau javascriptAPI 
			function createViz(url, content) {
				// insert narrative for the dashboard 
                document.getElementById("narrative").innerHTML = content;

				// set viz container id and options for the tableau visualization 
                var vizDiv = document.getElementById("vizContainer"),
                    options = {
                        hideToolbar: true,
                        hideTabs: true,
                        device: window.innerWidth > 500 ? 'desktop' : 'phone'
                    };

				// dispose of existing visualisation 
                if (viz) { 
                    viz.dispose();
                }
				// create a brand new visualisation 
                viz = new tableau.Viz(vizDiv, url, options);
			}
			
		</script>

		<?php	
	}

	/**
	 * Render custom widget output in live editor 
	 * content template has to be written in javascript
	 * @access protected 
	 * 
	*/
	protected function _content_template() {
		?>
		<!-- Navigation tab for mobile -->
		<div class="custom-mobile-container">
			<div class="custom-mobile-tab">
				<#
				if (settings.tabs) {
					var tabindex = view.getIDInt().toString().substr( 0, 3 );
					
					_.each(settings.tabs, function (item, index) {
						var tabCount = index + 1;
				#>
						<div 
							class = 'itab-container fade'
							id = "itab-container-{{ tabCount }}"
							data-tab = "{{ tabCount }}"
							tabindex = "{{ tabindex + tabCount }}"
							role = 'mobile-tab'
							aria-control = "itab-container-{{ tabindex + tabCount }}"
							data-url="<?php echo $item['tab_url']['url'] ?>"
							data-content="<?php echo $item['tab_content'] ?>"
						>
							<div class="custom-mobile-tab-item num">
								{{ tabCount }}.
							</div>
							<div class="custom-mobile-tab-item">
								{{ item.tab_title }}
							</div>		
						</div>
				<# 
					});
				}
				#>

				<a class="prev" onclick="mobileVizHandler(-1)">&#10094;</a>
				<a class="next" onclick="mobileVizHandler(1)">&#10095;</a>
			</div>
		</div>

		<!-- Navigation tab for Desktop -->
		<div class="custom-tabs" role="tablist">
			<#
			if (settings.tabs) {
				var tabindex = view.getIDInt().toString().substr( 0, 3 );
				#>
				<!--<div class="custom-tabs-wrapper">-->
					<#
					_.each(settings.tabs, function (item, index) {
						var tabCount = index + 1;
						#>
						<div
							class="custom-tab-title tablinks"
							id="dashboard-{{ tabCount }}"
							data-tab="{{ tabCount }}"
							tabindex="{{ tabindex + tabCount }}" 
							role = 'tab',
							aria-controls = "custom-tab-containers-{{ tabindex + tabCount }}"
							data-url="<?php echo $item['tab_url']['url'] ?>"
							data-content="<?php echo $item['tab_content'] ?>"
							onclick="vizHandler(event, {{ tabCount }});"
						>
							<div class="custom-tab-item num">
								{{ tabCount }}.
							</div>
							<div>
								{{ item.tab_title }}
							</div>
						</div>
					<# }); #>
				<!--</div>-->	
			<# } #>
		</div>

		<!-- generate content for tabs -->
		<div class="custom-tab-content">
			<p id="narrative" class="custom-tab-text" ></p>
        	<div id="vizContainer" class="custom-tab-dashboard"></div>
        </div>
		
		<!-- Adding custom Javascript -->
		<script>
			// to keep track of current active mobile tab 
            var tabIndex = 1;
			// to keep the viz variable so that it can be 
			// remove and recreate at anytime
			var viz;
            
			// set first click to display the first viz in the tabs
			document.getElementById('dashboard-' + tabIndex.toString()).click();

			function mobileVizHandler(n) {
				tabIndex += n;

				var mobileTabs = document.getElementsByClassName("itab-container");
				// reset tabindex rule
				if (tabIndex > mobileTabs.length) { tabIndex = 1 }
				if (tabIndex < 1) { tabIndex = mobileTabs.length }

				//console.log(tabIndex);
				// click the desktop menu 
				document.getElementById('dashboard-' + tabIndex.toString()).click();
			} 

			function vizHandler(event, n) {
				// handlle mobile tabs menu 
				var mobileTabs = document.getElementsByClassName("itab-container");

				// handle the desktop nav menu 
				// styling handler for the datalink button to set active state
				var desktopTabs = document.getElementsByClassName("custom-tab-container");
				// run through all containers to clear and set active class
				for  (i =0; i<desktopTabs.length; i++) {
					// reset all 
					desktopTabs[i].className = desktopTabs[i].className.replace(" active", "");
					mobileTabs[i].style.display = "none";
				}
				// next set the clicked container to active
				event.currentTarget.className += " active";
				// set to display
				mobileTabs[n-1].style.display = "block";

				// call to remove and create the viz 
				let container = document.getElementById('dashboard-' + n.toString());
				let url = container.getAttribute('data-url');
				let content = container.getAttribute('data-content');

				// call function to create visualization 
				createViz(url, content);
			}

			// function to create visualization with tableau javascriptAPI 
			function createViz(url, content) {
				// insert narrative for the dashboard 
                document.getElementById("narrative").innerHTML = content;

				// set viz container id and options for the tableau visualization 
                var vizDiv = document.getElementById("vizContainer"),
                    options = {
                        hideToolbar: true,
                        hideTabs: true,
                        device: window.innerWidth > 500 ? 'desktop' : 'phone'
                    };

				// dispose of existing visualisation 
                if (viz) { 
                    viz.dispose();
                }
				// create a brand new visualisation 
                viz = new tableau.Viz(vizDiv, url, options);
			}
			
		</script>
		<?php
	}
}