<?php

if (!defined('QA_VERSION')) {
    header('Location: ../../');
    exit;
}

class qa_blogfeed_layer {

    function head_css($qa_content) {
        $qa_content['head_lines'][] =
            '<link rel="stylesheet" type="text/css" href="' .
            QA_HTML_THEME_LAYER_URLTOROOT .
            'blog-widget.css">';
        return $qa_content;
    }
}