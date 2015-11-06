<?php

// Create meta box
function add_iumb_metabox($post_type) {
    $types = array('post');

    if (in_array($post_type, $types)) {
        add_meta_box(
            'image-uploader-meta-box',
            'Схема Yandex Product',
            'iumb_meta_callback',
            $post_type,
            'normal',
            'low'
        );
    }
}


// регистрируем файл стилей и добавляем его в очередь
function register_plugin_styles() {
    global $typenow;
    if ( 'post.php' || 'post-new.php' || $typenow == 'post' ) {
        wp_register_style('yaproduct', plugins_url('jekag_yaproduct/yaproduct.css'));
        wp_enqueue_style('yaproduct');
    }
}


// JS
function my_scripts_method() {
global $typenow;
if ( $typenow == 'post' ) {
    wp_enqueue_script('custom-script',
        plugins_url( '/js/yaproduct.js', __FILE__ ),
        array('jquery')
    );
}}





?>
