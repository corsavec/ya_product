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







//при сохранении поста
add_action('save_post', 'yaproduct_meta_save');
// CSS
add_action( 'admin_head', 'register_plugin_styles' );
//JS
add_action('admin_footer', 'my_scripts_method');
//активация обработки шорткода
if (function_exists ('add_shortcode') ) {
    add_shortcode('ljuser', 'user_shortcode', basename(__FILE__));
}

register_uninstall_hook (__FILE__,'jekag_plugin_uninstall' ); //при удалении плагина удаляем таблицу
add_action('before_delete_post', 'del_jekag_row'); //при удалении поста удаляем строку продукта

?>
