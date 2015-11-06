<?php
function del_jekag_row ($post_id) {
    global $wpdb;
    $table_name = $wpdb->prefix.'jekag_yaproduct';
    $wpdb->delete( $table_name, array( 'post_id' => $post_id ) );
}



function jekag_plugin_install(){
    global $wpdb;
    $table_name = $wpdb->prefix.'jekag_yaproduct';
    $sql = "CREATE TABLE IF NOT EXISTS ".$table_name." (
  post_id int(10) unsigned NOT NULL,
  name tinytext NOT NULL,
  description text NOT NULL,
  image tinytext NOT NULL,
  price float unsigned NOT NULL,
  currency tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (post_id)
  );";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
function jekag_plugin_uninstall(){
    global $wpdb;
    $table_name = $wpdb->prefix.'jekag_yaproduct';
    $sql = "DROP TABLE ".$table_name.";";
    $wpdb->query($sql);
}


function user_shortcode ($atts, $content = null)
{
    global $wpdb;
    global $post;
    $table_name = $wpdb->prefix.'jekag_yaproduct';
    $prodrow = $wpdb->get_row("SELECT * FROM $table_name WHERE post_id = ".$post->ID);


    return '<div itemscope itemtype="http://schema.org/Product">
    <div itemprop="name"><h1>'.$prodrow->name.'</h1></div>
    <a itemprop="image" href="'.$prodrow->image.'">
    <img src="'.$prodrow->image.'" title="'.$prodrow->name.'">
    </a>

    <div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
    <div>'.$prodrow->price.' '.$prodrow->currency.'</div>
    <meta itemprop="price" content="'.$prodrow->price.'">
    <meta itemprop="priceCurrency" content="'.$prodrow->currency.'">
    </div>
    <div itemprop="description">'.$prodrow->description.'</div>
    </div>';
}



function myplugin_add_custom_box() {
    $screens = array( 'post', 'page' );
    foreach ( $screens as $screen )
        add_meta_box( 'myplugin_sectionid', 'Схема Product', 'myplugin_meta_box_callback', $screen, 'side' );
}


/* HTML код блока */
function myplugin_meta_box_callback() {
    // Используем nonce для верификации
    wp_nonce_field( plugin_basename(__FILE__), 'myplugin_noncename' );
    global $wpdb;
    global $post;
    $table_name = $wpdb->prefix.'jekag_yaproduct';
    $prodrow = $wpdb->get_row("SELECT * FROM $table_name WHERE post_id = ".$post->ID);
    // Поля формы для введения данных
    echo '<p><label for="myplugin_new_field">' . __("Название услуги\товара", 'myplugin_textdomain' ) . '</label><br>';
    echo '<input type="text" id= "myplugin_new_field" name="jekag_name" value="'.$prodrow->name.'"  /><br>';
    echo '<p></p><label for="myplugin_new_field">' . __("Описание услуги\товара", 'myplugin_textdomain' ) . '</label><br>';
    echo '<textarea rows="4" name="jekag_description">'.$prodrow->description.'</textarea><br>';
    echo '<p></p><label for="myplugin_new_field">' . __("Цена услуги\товара", 'myplugin_textdomain' ) . '</label><br>';
    echo '<table><tr><td><input type="text" id= "myplugin_new_field" name="jekag_price" value="'.$prodrow->price.'" size=10 /></td>';
    echo '<td><select name="jekag_currency">
            <option value="'.$prodrow->currency.'" selected>руб.</option>
            <option value="1">$</option>
            <option value="2">грн.</option>
          </select></td></tr></table></p>';
}


?>
