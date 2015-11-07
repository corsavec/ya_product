<?php

// Create meta box
function add_yaprod_metabox($post_type) {
    $types = array('post');

    if (in_array($post_type, $types)) {
        add_meta_box(
            'image-uploader-meta-box',
            'Схема Yandex Product',
            'yaprod_meta_callback',
            $post_type,
            'side',
            'default'
        );
    }
}

//прорисовка метабокса
function yaprod_meta_callback($post) {
    $id1 = $post->ID;
    wp_nonce_field( 'jekag_yaproduct.php', 'yaprod_meta_nonce' );
    $id = get_post_meta($id1, 'yaprod', true);
    $desc = get_post_meta($id1, 'yaproduct_description', true);
    $name = get_post_meta($id1, 'yaproduct_name', true);
    $price = get_post_meta($id1, 'yaproduct_price', true);
    $image = wp_get_attachment_image_src($id, 'full-size');
    $currency = get_post_meta($id1, 'yaproduct_currency', true);

?>
<p><label for="myplugin_new_field">Название услуги\товара</label><br>
    <input type="text" id= "myplugin_new_field" name="yaproduct_name" value="<?=$name;?>"  />
    </p><p><label for="myplugin_new_field">Описание услуги\товара</label><br>
    <textarea rows="4" name="yaproduct_description"><?=$desc;?></textarea>
</p><p><label for="myplugin_new_field">Цена услуги\товара</label>
</p><p><table><tr><td><input type="text" id= "myplugin_new_field" name="yaproduct_price" value="<?=$price;?>" size=10 />
        </td><td><input type="text" id= "myplugin_new_field" name="yaproduct_currency" value="<?=$currency;?>" size=5 /></td></tr></table></p>
<ul id="image-uploader-meta-box-list">
<?php if ($id) : ?>

    <input type="hidden" name="yaprod" value="<?=$id;?>">
    <li>
        <img class="image-preview" src="<?php echo $image ? $image[0] : ''; ?>">
    </li><br>

<?php endif; ?>

<?    if($id == ''){ ?>

<p class="description">Выбор изображения</p>
         <a class="yaprod-add button" href="#" data-uploader-title="Select an image" data-uploader-button-text="Select an image">Загрузить изображение</a> <a class="change-image button none" href="#" data-uploader-title="Select an image" data-uploader-button-text="Select an image">Изменить</a> <a class="remove-image button none" href="#">Убрать</a> <br />

    <?php } else { ?>

<p class="description">Выбор изображения</p>
         <a class="yaprod-add button none" href="#" data-uploader-title="Select an image" data-uploader-button-text="Select an image">Загрузить изображение</a> <a class="change-image button" href="#" data-uploader-title="Select an image" data-uploader-button-text="Select an image">Изменить</a> <a class="remove-image button" href="#">Убрать</a> <br />

    <?php } ?>
</ul>
<p>Скопируйте шорткод который можно вставить в текст страницы<br>
[yaprod]<?=$id?>[/yaprod]
</p>
<?php }

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


//обработка шорткода
function user_shortcode ($atts, $content, $yaprod_basename)
{
    global $post;

    wp_nonce_field( $yaprod_basename, 'yaprod_meta_nonce' );

    if (empty($content)) $id = $post->ID; else $id=$content;
    $desc = get_post_meta($id, 'yaproduct_description', true);
    $name = get_post_meta($id, 'yaproduct_name', true);
    $price = get_post_meta($id, 'yaproduct_price', true);
    $image = wp_get_attachment_image_src(get_post_meta($id, 'yaprod', true), 'full-size');
    $currency = get_post_meta($id, 'yaproduct_currency', true);

    return $id.'<div itemscope itemtype="http://schema.org/Product">
    <div itemprop="name"><h1>'.$name.'</h1></div>
    <a itemprop="image" href="'.$image[0].'"><img src="'.$image[0].'" title="'.$name.'"></a>

    <div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
    <div>'.$price.' '.$currency.'</div>
    <meta itemprop="price" content="'.$price.'">
    <meta itemprop="priceCurrency" content="'.$currency.'">
    </div>
    <div itemprop="description">'.$desc.'</div>
    </div>';
}

//сохранение данных метабокса
function yaproduct_meta_save($post_id) {
    if (!isset($_POST['yaprod_meta_nonce']) || !wp_verify_nonce($_POST['yaprod_meta_nonce'], 'jekag_yaproduct.php')) return;

    if (!current_user_can('edit_post', $post_id)) return $post_id;

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    if(isset($_POST['yaprod'])) {
        update_post_meta($post_id, 'yaprod', $_POST['yaprod']);
    }
    if(isset($_POST['yaproduct_name'])) {
        update_post_meta($post_id, 'yaproduct_name', $_POST['yaproduct_name']);
    }
    if(isset($_POST['yaproduct_description'])) {
        update_post_meta($post_id, 'yaproduct_description', $_POST['yaproduct_description']);
    }
    if(isset($_POST['yaproduct_price'])) {
        update_post_meta($post_id, 'yaproduct_price', $_POST['yaproduct_price']);
    }
    if(isset($_POST['yaproduct_currency'])) {
        update_post_meta($post_id, 'yaproduct_currency', $_POST['yaproduct_currency']);
    }
}

?>
