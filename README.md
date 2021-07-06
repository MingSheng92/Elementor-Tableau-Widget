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
PHP, javascript (Tableau Javascript API, html javscript for user event), html, css 

## Change Log

### Whats new 

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

## Installation Guide
1. Clone the repo, copy TebleauDashboard folder and paste into the plugins folder, the same folder where you can find elementor: ( wordpress\wordpress\wp-content\plugins) 
2. Login to wordpress and activate the plugin
3. Once activated, you should be able to find the widget in elementor's GENERAL tab

https://tableau.github.io/embedding-playbook/pages/01_embedding_and_jsapi
