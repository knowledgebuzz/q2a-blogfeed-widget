<?php
/*
Plugin Name: Q2A Blog Feed Widget
Plugin URI: https://github.com/knowledgebuzz/q2a-blogfeed-widget
Plugin Description: A lightweight Question2Answer widget that displays the latest WordPress blog posts in the sidebar with built-in caching, customizable settings, and admin configuration controls.
Plugin Version: 1.0.0
Plugin Date: 2026-03-01
Plugin Author: Davis Simiyu Wanyonyi
Plugin Author URI: https://unitedafrica.digital/davis
Plugin License: GPLv2
Plugin Minimum Question2Answer Version: 1.8
Plugin Update Check URI: https://github.com/knowledgebuzz/q2a-blogfeed-widget
Plugin Support URI: https://unitedafrica.digital/uad-plugin
*/

if (!defined('QA_VERSION')) {
    header('Location: ../../');
    exit;
}

qa_register_plugin_module(
    'widget',
    'qa-blog-widget.php',
    'qa_blogfeed_widget',
    'Blog Feed Widget'
);

qa_register_plugin_module(
    'layer',
    'qa-blog-layer.php',
    'qa_blogfeed_layer',
    'Blog Feed CSS'
);
