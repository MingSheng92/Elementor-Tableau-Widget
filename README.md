# Tableau Dashboard Tab Custom Widget

## Background

In Elementor there is a widget that allows the user to switch between tabs to view different content. This is very useful to diplay multiple contents, however, there are some issues with the way that Tableau embeds are loaded in these tabs:

1. In tabs widget, all the content will be loaded altogether and only display the selected tab. Hence this will have impact on website's performance.
2. if all dashboards is loaded at once in tabs manner, the layout of other dashboard will not be set properly.
3. due to container size, embedded code will sometimes render mobile version of the dashbords in desktop screen size.

## Solution 

Stumbled upon tableau blog post where the official recommendation where it stated <b>"only very simple embedding scenarios should use the embed code. Most deployments should instead use the JavaScript API."</b> 

The overall widget design is fairly straight forward, we will create a widget that can dynamic load any amount of dashboards that is added into the widget and only loads dashboards that is selected by the user and nothing more. By creating a custom widget, we will be able to increase work effeciency and anyone can work with this widget without any knowledge to html/css or even javascript. With dynamic loading, we increase the site loading speed as it will load only one dashboard as compared to all dashboards with the original embedded approach. 

We will use Tableau Javascript API to perform the dynamic loading, 

### Language used:
PHP, javascript (Tableau Javascript API, html javscript for user event), html, css 

## Controls


## Installation Guide




https://tableau.github.io/embedding-playbook/pages/01_embedding_and_jsapi
