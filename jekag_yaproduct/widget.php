<?php

class yaprod_Widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct(
            'yaprod_widget', // Base ID
            __( 'Yandex Product', 'text_domain' ), // Name
            array( 'description' => __( 'Реализация схемы Yandex Product', 'text_domain' ), ) // Args
        );
    }

    public function widget( $args, $instance ) {
        global $post;
        echo $args['before_widget'];
        
        $id = $post->ID;
        if (! empty(get_post_meta($id, 'yaproduct_name', true))){
        $desc = get_post_meta($id, 'yaproduct_description', true);
        $name = get_post_meta($id, 'yaproduct_name', true);
        $price = get_post_meta($id, 'yaproduct_price', true);
        $image = wp_get_attachment_image_src(get_post_meta($id, 'yaprod', true), 'full-size');
        $currency = get_post_meta($id, 'yaproduct_currency', true);

        echo '<div itemscope itemtype="http://schema.org/Product">
    <div itemprop="name"><h1>'.$name.'</h1></div>
    <a itemprop="image" href="'.$image[0].'"><img src="'.$image[0].'" title="'.$name.'"></a>

    <div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
    <div>'.$price.' '.$currency.'</div>
    <meta itemprop="price" content="'.$price.'">
    <meta itemprop="priceCurrency" content="'.$currency.'">
    </div>
    <div itemprop="description">'.$desc.'</div>
    </div>';}
        echo $args['after_widget'];
    }

    public function form( $instance ) {

       // $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'New title', 'text_domain' );
        echo '<p></p>';
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

        return $instance;
    }

}

function register_yaprod_widget() {
    register_widget( 'yaprod_Widget' );
}

add_action( 'widgets_init', 'register_yaprod_widget' );

?>
