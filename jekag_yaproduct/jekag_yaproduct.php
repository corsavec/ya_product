<?php
/*
Plugin Name: Yandex Product
Description: Плагин с помощью которого можно будет реализовывать схему Product для страниц и постов WordPress
В админке добавляет метабокс на страницу добавления\редактирования поста\страницы (в сайдбаре), в котором можно задавать: название услуги\товара, описание услуги\товара, изображение услуги\товара (используем медиаменеджер), цену услуги\товара, валюту услуги\товара
Также плагин создает виджет, который выводит указанные данные, если они заданы для страницы, на которой он вызывается. Если данные не заданы, то просто не выводим виджет на этой странице
Так же плагин создает шорткод который можно вставить в текст страницы и который будет выводить те же данные в виде блока. Шорткод принимает значение в виде id записи\поста. Если это значение не задано, то выводит данные той записи, из которой вызван.
Version: 1.0
Author: Гресько Евгений
Author URI: http://vk.com/g.jeka
*/
require('functions.php');

add_action('add_meta_boxes', 'add_iumb_metabox');


function iumb_meta_callback($post) {
    wp_nonce_field( basename(__FILE__), 'iumb_meta_nonce' );
    $id = get_post_meta($post->ID, 'iumb', true);
    $desc = get_post_meta($post->ID, 'yaproduct_description', true);
    $name = get_post_meta($post->ID, 'yaproduct_name', true);
    $price = get_post_meta($post->ID, 'yaproduct_price', true);
    $image = wp_get_attachment_image_src($id, 'full-size');
    $currency = get_post_meta($post->ID, 'yaproduct_currency', true);

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

    <input type="hidden" name="iumb" value="<?=$id;?>">
    <li>
        <img class="image-preview" src="<?php echo $image ? $image[0] : ''; ?>">
    </li><br>


<?php endif; ?>


<?    if($id == ''){ ?>

<p class="description">Выбор изображения</p>
         <a class="iumb-add button" href="#" data-uploader-title="Select an image" data-uploader-button-text="Select an image">Загрузить изображение</a> <a class="change-image button none" href="#" data-uploader-title="Select an image" data-uploader-button-text="Select an image">Изменить</a> <a class="remove-image button none" href="#">Убрать</a> <br />

    <?php } else { ?>

<p class="description">Выбор изображения</p>
         <a class="iumb-add button none" href="#" data-uploader-title="Select an image" data-uploader-button-text="Select an image">Загрузить изображение</a> <a class="change-image button" href="#" data-uploader-title="Select an image" data-uploader-button-text="Select an image">Изменить</a> <a class="remove-image button" href="#">Убрать</a> <br />

    <?php } ?>

</ul>

<?php }

function yaproduct_meta_save($post_id) {
    if (!isset($_POST['iumb_meta_nonce']) || !wp_verify_nonce($_POST['iumb_meta_nonce'], basename(__FILE__))) return;

    if (!current_user_can('edit_post', $post_id)) return $post_id;

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    if(isset($_POST['iumb'])) {
        update_post_meta($post_id, 'iumb', $_POST['iumb']);
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


//при сохранении поста
add_action('save_post', 'yaproduct_meta_save');
// CSS
add_action( 'admin_head', 'register_plugin_styles' );
//JS
add_action('admin_footer', 'my_scripts_method');

?>
