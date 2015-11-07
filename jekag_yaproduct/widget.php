<?php



/**
 * Adds Foo_Widget widget.
 */
class Foo_Widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct(
            'foo_widget', // Base ID
            __( 'Yandex Product', 'text_domain' ), // Name
            array( 'description' => __( 'Реализация схемы Yandex Product', 'text_domain' ), ) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        global $post;
        echo $args['before_widget'];
        //if ( ! empty( $instance['title'] ) ) {
        //    echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
        //}

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

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {

        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'New title', 'text_domain' );
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

        return $instance;
    }

} // class Foo_Widget

// register Foo_Widget widget
function register_foo_widget() {
    register_widget( 'Foo_Widget' );
}
add_action( 'widgets_init', 'register_foo_widget' );



?>
