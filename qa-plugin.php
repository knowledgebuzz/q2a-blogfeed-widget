<?php
/*
Plugin Name: Q2A Blog Feed Widget
Plugin URI: https://github.com/yourusername/q2a-blogfeed-widget
Plugin Description: Displays latest WordPress blog posts in Q2A sidebar with caching and admin controls.
Plugin Version: 2.0
Plugin Date: 2026-03-01
Plugin Author: United Africa Digital
Plugin License: GPLv2
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