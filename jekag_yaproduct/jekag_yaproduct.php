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
include('functions.php');
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
add_action('add_meta_boxes', 'add_iumb_metabox');

function iumb_meta_callback($post) {
    wp_nonce_field( basename(__FILE__), 'iumb_meta_nonce' );
    $id = get_post_meta($post->ID, 'iumb', true);
    $desc = get_post_meta($post->ID, 'yaproduct_description', true);
    $name = get_post_meta($post->ID, 'yaproduct_name', true);
    $image = wp_get_attachment_image_src($id, 'full-size');
    ?>

    <?php if($id == ''){ ?>



    <p><label for="myplugin_new_field">Название услуги\товара</label><br>
    <input type="text" id= "myplugin_new_field" name="yaproduct_name" value="<?php echo $name; ?>"  /><br>
    <p></p><label for="myplugin_new_field">Описание услуги\товара</label><br>
    <textarea rows="4" name="yaproduct_description"><?php echo $desc; ?></textarea><br>
    <p></p><label for="myplugin_new_field">Цена услуги\товара</label><br>
    <table><tr><td><input type="text" id= "myplugin_new_field" name="yaproduct_price" value="<?php echo $image ? $image[0] : ''; ?>" size=10 /></td>
    <td><select name="yaproduc_currency">
            <option value="<?php echo $image ? $image[1] : ''; ?>" selected>руб.</option>
            <option value="1">$</option>
            <option value="2">грн.</option>
          </select></td></tr></table></p>



        <input class="iumb" type="text" name="_iumb" value="<?php echo $image ? $image[0] : ''; ?>"> <a class="iumb-add button" href="#" data-uploader-title="Select an image" data-uploader-button-text="Select an image">Upload</a> <a class="change-image button none" href="#" data-uploader-title="Select an image" data-uploader-button-text="Select an image">Change</a> <a class="remove-image button none" href="#">Remove</a> <br />
        <p class="description">Select an image</p>

    <?php } else { ?>



    <p><label for="myplugin_new_field">Название услуги\товара</label><br>
    <input type="text" id= "myplugin_new_field" name="yaproduct_name" value="<?php echo $name; ?>"  /><br>
    <p></p><label for="myplugin_new_field">Описание услуги\товара</label><br>
    <textarea rows="4" name="yaproduct_description"><?php echo $desc; ?></textarea><br>
    <p></p><label for="myplugin_new_field">Цена услуги\товара</label><br>
    <table><tr><td><input type="text" id= "myplugin_new_field" name="yaproduct_price" value="<?php echo $image ? $image[0] : ''; ?>" size=10 /></td>
    <td><select name="jekag_currency">
            <option value="<?php echo $image ? $image[1] : ''; ?>" selected>руб.</option>
            <option value="1">$</option>
            <option value="2">грн.</option>
          </select></td></tr></table></p>



        <input class="iumb" type="text" name="_iumb" value="<?php echo $image ? $image[0] : ''; ?>"> <a class="iumb-add button none" href="#" data-uploader-title="Select an image" data-uploader-button-text="Select an image">Upload</a> <a class="change-image button" href="#" data-uploader-title="Select an image" data-uploader-button-text="Select an image">Change</a> <a class="remove-image button" href="#">Remove</a> <br />
        <p class="description">Select an image</p>

    <?php } ?>

    <ul id="image-uploader-meta-box-list">
        <?php if ($id) : ?>





            <input type="hidden" name="iumb" value="<?php echo $id; ?>">
            <li>
                <img class="image-preview" src="<?php echo $image[0]; ?>">
            </li>

        <?php endif; ?>
    </ul>

<?php }

function iumb_meta_save($post_id) {
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
}

add_action('save_post', 'iumb_meta_save');





// CSS

function iumb_css(){
    global $typenow;
    if ( 'post.php' || 'post-new.php' || $typenow == 'post' ) {
        ?>

        <style type="text/css">
            #image-uploader-meta-box-list:after{
                display:block;
                content:'';
                clear:both;
            }
            #image-uploader-meta-box-list li {
                float: left;
                width: 150px;
                height:auto;
                text-align: center;
                margin: 10px 10px 10px 0;
            }
            input.iumb{
                width:50%;
            }
            #image-uploader-meta-box-list li img{
                max-width:150px;
            }
            a.iumb-add.none, a.change-image.none, a.remove-image.none{
                display:none;
                visibility:hidden;
            }
        </style>

    <?php }
}
add_action('admin_head', 'iumb_css');

// JS
function iumb_js(){
    global $typenow;
    if ( $typenow == 'post' ) {
        ?>

        <script type="text/javascript">

            jQuery(function($) {

                var file_frame;

                $(document).on('click', '#image-uploader-meta-box a.iumb-add', function(e) {

                    e.preventDefault();

                    if (file_frame) file_frame.close();

                    file_frame = wp.media.frames.file_frame = wp.media({
                        title: $(this).data('uploader-title'),
                        // Tell the modal to show only images.
                        library: {
                            type: 'image'
                        },
                        button: {
                            text: $(this).data('uploader-button-text'),
                        },
                        multiple: false
                    });

                    file_frame.on('select', function() {
                        var listIndex = $('#image-uploader-meta-box-list li').index($('#image-uploader-meta-box-list li:last')),
                            selection = file_frame.state().get('selection');

                        selection.map(function(attachment) {
                            attachment = attachment.toJSON(),

                                index      = listIndex;

                            $('#image-uploader-meta-box-list').append('<li><input type="hidden" name="iumb" value="' + attachment.id + '"><img class="image-preview" src="' + attachment.sizes.thumbnail.url + '"></li>');

                            $('input[name="_iumb"]').val(attachment.url);

                            $('#image-uploader-meta-box a.iumb-add').addClass('none');
                            $('a.change-image').removeClass('none').show();
                            $('a.remove-image').removeClass('none').show();

                        });
                    });

                    makeSortable();

                    file_frame.open();

                });

                $(document).on('click', '#image-uploader-meta-box a.change-image', function(e) {

                    e.preventDefault();

                    var that = $(this);

                    if (file_frame) file_frame.close();

                    file_frame = wp.media.frames.file_frame = wp.media({
                        title: $(this).data('uploader-title'),
                        button: {
                            text: $(this).data('uploader-button-text'),
                        },
                        multiple: false
                    });

                    file_frame.on( 'select', function() {
                        attachment = file_frame.state().get('selection').first().toJSON();

                        that.parent().find('input:hidden').attr('value', attachment.id);
                        that.parent().find('img.image-preview').attr('src', attachment.sizes.thumbnail.url);
                    });

                    file_frame.open();

                });

                function resetIndex() {
                    $('#image-uploader-meta-box-list li').each(function(i) {
                        $(this).find('input:hidden').attr('name', 'iumb');
                    });
                }

                function makeSortable() {
                    $('#image-uploader-meta-box-list').sortable({
                        opacity: 0.6,
                        stop: function() {
                            resetIndex();
                        }
                    });
                }

                $(document).on('click', '#image-uploader-meta-box a.remove-image', function(e) {


                    $('#image-uploader-meta-box a.iumb-add').removeClass('none');
                    $('a.change-image').hide();
                    $(this).hide();

                    $('input[name="iumb"]').val('');
                    $('input[name="_iumb"]').val('');

                    $('#image-uploader-meta-box-list li').animate({ opacity: 0 }, 200, function() {
                        $(this).remove();
                        resetIndex();
                    });

                    e.preventDefault();

                });

                makeSortable();

            });

        </script>

    <?php }
}
add_action('admin_footer', 'iumb_js');

?>
