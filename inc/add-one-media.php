<?php

/**
 * Создание блока метаполей для постов
 */
function add_one_meta()
{
    add_meta_box('add_one_meta', 'Popup content', 'add_one_meta_view', 'portfolio');
}

add_action('add_meta_boxes', 'add_one_meta');

/**
 * HTML код блока
 */
function add_one_meta_view()
{
    global $post;
     
    // Если это пост отличный от необходимого, уйдем отсюда, и ничего не отобразим
    if ($post->post_type != 'portfolio') {
        return;
    }
     
    // Используем nonce для верификации
    wp_nonce_field(plugin_basename(__FILE__), 'add_one_nonce');
     
    // Заберем значение прикрепленного файла
    $add_file_id = get_post_meta($post->ID, 'add_file_id', true);
     
    // Ссылка на добавление файлов, если js отколючен
    $upload_link = esc_url(get_upload_iframe_src('null', $post->ID));
     
    // Поле для выбора файла
    echo '
    <div class="custom_field_itm">
        <div class="js-add-wrap">';
     
    if ($add_file_id) :
        $file_info = get_post($add_file_id);
        $file_icon = wp_get_attachment_image($add_file_id, 'thumbnail', true);
         
        echo '<div class="add_file js-add_file_itm">
            <input type="hidden" name="add_file_id" value="' . $add_file_id . '" />
            <div class="add_file_icon">' . $file_icon . '</div>
            <p class="add_file_name">' . $file_info->post_title . '</p>
            <a href="#" class="button button-primary button-large js-add-file-remove">Unattach file</a>
        </div>';
    endif;
     
    echo '</div><br/>
        <a href="' . $upload_link . '" class="button button-primary button-large js-add-file">Attach file</a>
    </div>';
}

/**
 * Сохраняем данные, когда пост сохраняется
 *
 * @param $post_id
 * @param $post
 *
 * @return mixed
 */
function omsu_doc_save_postdata($post_id, $post)
{
    // Если это пост отличный от документа, уйдем отсюда
    if ($post->post_type != 'portfolio') {
        return $post_id;
    }
     
    // проверяем nonce нашей страницы, потому что save_post может быть вызван с другого места.
    if ( ! isset($_POST['add_one_nonce']) || ! wp_verify_nonce($_POST['add_one_nonce'],
            plugin_basename(__FILE__))
    ) {
        return $post_id;
    }
     
    // проверяем, если это автосохранение ничего не делаем с данными нашей формы.
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }
     
    // проверяем разрешено ли пользователю указывать эти данные
    if ('page' == $_POST['post_type'] && ! current_user_can('edit_page', $post_id)) {
        return $post_id;
    } elseif ( ! current_user_can('edit_post', $post_id)) {
        return $post_id;
    }
     
    // Сохраним прикрепленный файл
		if ( !empty($_POST['add_file_id']) ) {
			$add_file_id = (int)$_POST['add_file_id'];
		} else {
			$add_file_id = null;
		}
		update_post_meta($post_id, 'add_file_id', $add_file_id);
     
    return $post_id;
}
 
add_action('save_post', 'omsu_doc_save_postdata', 10, 2);
