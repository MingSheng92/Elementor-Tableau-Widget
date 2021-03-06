# Tableau Dashboard Tab Custom Widget

## Background

In Elementor there is a widget that allows the user to switch between tabs to view different content. This is very useful to diplay multiple contents, however, there are some issues with the way that Tableau embeds are loaded in these tabs:

1. In tabs widget, all the content will be loaded altogether and only display the selected tab. Hence this will have impact on website's performance.
2. if all dashboards is loaded at once in tabs manner, the layout of other dashboard will not be set properly.
3. due to container size, default embedded code will sometimes render mobile version of the dashbords in desktop screen size.

## Solution 

Stumbled upon tableau blog post where the official recommendation where it stated <b>"only very simple embedding scenarios should use the embed code. Most deployments should instead use the JavaScript API."</b> 

The overall widget design is fairly straight forward, we will create a widget that can dynamic load any amount of dashboards that is added into the widget and only loads dashboards that is selected by the user and nothing more. With dynamic loading, we increase the site loading speed as it will load only one dashboard as compared to all dashboards with the original embedded approach. 

### Language used:
PHP, Alpine JS, javascipt, html, css 

## Change Log

### Whats new 

#### v 1.2.1
1. added exception handling to display error message if Tableau Javascript API fails to load.

#### v 1.2.0
1. Migrate from javscript to alpine to support multiple usage of the widget
2. added slight margin between dashboard tabs and dashboard area to avoid z-fighting issues when tableau API renders the dashboard.

#### v 1.1.2
1. added min-height options for dashboards
2. added new styling control for tab margin 
3. added new styling control for tab padding
4. added middle alignment for viz that is smaller than the column size

#### v 1.1.1
1. added single viz display mode
2. added logic create visualization function will not trigger when url string is empty
3. adjust onclick into onload for live editor template to fix settings undefined error
4. remove enqueue Tableau Javascript API and only inject when plugin template is created

#### v 1.1.0
1. remove package information in widget.php to avioud diplication in wordpress admin
2. removed tab styling customization panel 
3. Added sticky option to customization panel 
4. Added styling based on figma design
5. Added a seperate mobile menu  
6. revised menu logic and code clean up  
7. load tableau script in header to fix tableau cannot be found error

#### v 1.0.4
1. Added alignment configuration 
2. Padding configuration for tab title
3. file clean up 

#### v 1.0.3
1. Added more styling options for tab. 
2. Bug ID #1 fix

#### v 1.0.2
Removed script injection and replaced with wordpress's wp_enqueue_script for cdn script loading.

#### v 1.0.1
1. UI change and cleanup (removed default dashboards settings and set the 1st tab to always the default, change into textarea for narratives) 
2. Style controller tab implemented
3. content_template implemented to support elementor live editing feature.

#### v 1.0.0
1. Direct port from the base html source code
2. Added basic widget controls

## Controls

Below image shows the current configuration that can be performed with the widget.
![Figure1](https://github.com/MingSheng92/Elementor-Tableau-Widget/blob/main/images/widget_dashboard.JPG)

## Sample Result:

Note: custom widget width is based on the theme container width (Just like any other widgets).
![Figure2](https://github.com/MingSheng92/Elementor-Tableau-Widget/blob/main/images/Demo.JPG)

Check out the widget in action : https://data.undp.org/country/moldova/

## Installation Guide

### Option 1
1. Clone the repo, copy TebleauDashboard folder and paste into the plugins folder, the same folder where you can find elementor: ( wordpress\wordpress\wp-content\plugins) 
2. Login to wordpress and activate the plugin
3. Once activated, you should be able to find the widget in elementor's GENERAL tab

### Option 2 
1. Clone the repo, zip the folder TableauDashboard with any compress tool (E.g 7zip)
2. Log in to wordpress -> plugins -> Add New -> Upload (Select the zipped TableauDashboard Folder) 
3. Select Install and activate the plugin 

Tested both options.

## Additional Notes: 

1. Due to conflicting issue with Tableau embedded code, we inject JavaScript during render.
One issue that we found during test is that once we load Tableau JavaScript API tableau embedded code will not work due to function conflicting issues. Hence we cannot use enqueue script in Wordpress anymore(Enqueue will load script in the header doesnt matter if you have use the widget in the page or not).

## Reference: 
https://tableau.github.io/embedding-playbook/pages/01_embedding_and_jsapi <br />
https://developers.elementor.com/creating-a-new-widget/ <br />
https://developer.wordpress.org/plugins/ <br />
https://naledi.co.uk/blog/javascript-api-and-alpine <br />
