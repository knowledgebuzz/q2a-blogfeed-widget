<?php

class qa_blogfeed_widget {

    function allow_template($template)
    {
        return true;
    }

    function allow_region($region)
    {
        return true;
    }

/* ==============================
   ADMIN SETTINGS
============================== */
function admin_form(&$qa_content)
{
    $saved = false;

    if (qa_clicked('blogfeed_save')) {

        qa_opt('blogfeed_title', qa_post_text('blogfeed_title'));
        qa_opt('blogfeed_blog_url', rtrim(qa_post_text('blogfeed_blog_url'), '/'));
        qa_opt('blogfeed_count', (int)qa_post_text('blogfeed_count'));
        qa_opt('blogfeed_show_date', (int)qa_post_text('blogfeed_show_date'));
        qa_opt('blogfeed_show_excerpt', (int)qa_post_text('blogfeed_show_excerpt'));
        qa_opt('blogfeed_excerpt_length', (int)qa_post_text('blogfeed_excerpt_length'));
        qa_opt('blogfeed_show_readmore', (int)qa_post_text('blogfeed_show_readmore'));
        qa_opt('blogfeed_show_readall', (int)qa_post_text('blogfeed_show_readall'));
        qa_opt('blogfeed_color_mode', qa_post_text('blogfeed_color_mode'));

        $saved = true;
    }

    return array(
        'ok' => $saved ? 'Blog Feed settings saved successfully.' : null,

        'fields' => array(

            array(
                'label' => 'Widget Title',
                'value' => qa_opt('blogfeed_title') ?: 'Latest Blog Posts',
                'tags'  => 'name="blogfeed_title"',
            ),

            array(
                'label' => 'WordPress Blog URL',
                'value' => qa_opt('blogfeed_blog_url'),
                'tags'  => 'name="blogfeed_blog_url" placeholder="https://example.com/blog"',
            ),

            array(
                'label' => 'Number of Posts',
                'type'  => 'number',
                'value' => qa_opt('blogfeed_count') ?: 5,
                'tags'  => 'name="blogfeed_count" min="1" max="20"',
            ),

            array(
                'label' => 'Show Date',
                'type'  => 'checkbox',
                'value' => qa_opt('blogfeed_show_date'),
                'tags'  => 'name="blogfeed_show_date"',
            ),

            array(
                'label' => 'Show Excerpt',
                'type'  => 'checkbox',
                'value' => qa_opt('blogfeed_show_excerpt'),
                'tags'  => 'name="blogfeed_show_excerpt"',
            ),

            array(
                'label' => 'Excerpt Length (characters)',
                'type'  => 'number',
                'value' => qa_opt('blogfeed_excerpt_length') ?: 120,
                'tags'  => 'name="blogfeed_excerpt_length" min="50" max="500"',
            ),

            array(
                'label' => 'Enable Read More Button',
                'type'  => 'checkbox',
                'value' => qa_opt('blogfeed_show_readmore'),
                'tags'  => 'name="blogfeed_show_readmore"',
            ),

            array(
                'label' => 'Enable Read All Button',
                'type'  => 'checkbox',
                'value' => qa_opt('blogfeed_show_readall'),
                'tags'  => 'name="blogfeed_show_readall"',
            ),

            array(
                'label' => 'Color Mode',
                'type'  => 'select',
                'value' => qa_opt('blogfeed_color_mode') ?: 'auto',
                'tags'  => 'name="blogfeed_color_mode"',
                'options' => array(
                    'auto'  => 'Auto (Follow Device)',
                    'light' => 'Force Light Mode',
                ),
            ),
        ),

        'buttons' => array(
            array(
                'label' => 'Save Changes',
                'tags'  => 'name="blogfeed_save"',
            ),
            array(
                'label' => 'Donate ❤️',
                'tags'  => 'onclick="window.open(\'https://unitedafrica.digital/donate\', \'_blank\'); return false;" class="qa-form-light-button"',
            ),
        ),
    );
}

    /* ==============================
       FETCH WORDPRESS POSTS
    ============================== */
    private function fetch_posts($blog_url, $count)
    {
        if (!$blog_url) return array();

        $api_url = $blog_url . '/wp-json/wp/v2/posts?per_page=' . $count;

        $response = @file_get_contents($api_url);
        if (!$response) return array();

        $posts = json_decode($response, true);
        if (!is_array($posts)) return array();

        return $posts;
    }

    /* ==============================
       OUTPUT WIDGET
    ============================== */
    function output_widget($region, $place, $themeobject, $template, $request, $qa_content)
    {
        /* Load CSS safely */
        $themeobject->output(
            '<link rel="stylesheet" type="text/css" href="' .
            qa_path_to_root() . 'qa-plugin/qa-blogfeed-widget/blog-widget.css?v=6.0" />'
        );

        $title = qa_opt('blogfeed_title') ?: 'Latest Blog Posts';
        $blog_url = qa_opt('blogfeed_blog_url');
        $count = qa_opt('blogfeed_count') ?: 5;
        $show_date = qa_opt('blogfeed_show_date');
        $show_excerpt = qa_opt('blogfeed_show_excerpt');
        $excerpt_length = qa_opt('blogfeed_excerpt_length') ?: 120;
        $show_readmore = qa_opt('blogfeed_show_readmore');
        $show_readall = qa_opt('blogfeed_show_readall');
        $color_mode = qa_opt('blogfeed_color_mode') ?: 'auto';

        $posts = $this->fetch_posts($blog_url, $count);

        /* FORCE LIGHT MODE SAFELY */
        if ($color_mode === 'light') {
            echo '<style>
            .qa-blog-widget {
                background: #ffffff !important;
                border-color: #e5e7eb !important;
            }
            .qa-blog-widget .qa-blog-header,
            .qa-blog-widget .qa-blog-title {
                color: #111827 !important;
            }
            .qa-blog-widget .qa-blog-title:hover {
                color: #2563eb !important;
            }
            .qa-blog-widget .qa-blog-date {
                color: #6b7280 !important;
            }
            .qa-blog-widget .qa-blog-excerpt {
                color: #374151 !important;
            }
            .qa-blog-widget .qa-blog-button {
                background: #2563eb !important;
                color: #ffffff !important;
            }
            </style>';
        }

        echo '<div class="qa-blog-widget">';
        echo '<div class="qa-blog-header">'.htmlspecialchars($title).'</div>';

        foreach ($posts as $post) {

            $post_title = strip_tags($post['title']['rendered']);
            $post_link  = $post['link'];
            $post_date  = date('F j, Y', strtotime($post['date']));
            $excerpt_raw = strip_tags($post['excerpt']['rendered']);
            $excerpt = substr($excerpt_raw, 0, $excerpt_length);

            echo '<div class="qa-blog-post">';
            echo '<a class="qa-blog-title" href="'.$post_link.'" target="_blank">'.$post_title.'</a>';

            if ($show_date) {
                echo '<div class="qa-blog-date">'.$post_date.'</div>';
            }

            if ($show_excerpt) {
                echo '<div class="qa-blog-excerpt">'.$excerpt.'...</div>';
            }

            if ($show_readmore) {
                echo '<a class="qa-blog-button" href="'.$post_link.'" target="_blank">Read More</a>';
            }

            echo '</div>';
        }

        if ($show_readall && $blog_url) {
            echo '<div class="qa-blog-readall">';
            echo '<a class="qa-blog-button qa-blog-button-full" href="'.$blog_url.'" target="_blank">Read All Articles</a>';
            echo '</div>';
        }

        echo '</div>';
    }
}
