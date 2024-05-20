<?php 

require_once __DIR__ . "/inc/UserAjaxHandler.php";

// Scripts and styles
add_action( 'wp_enqueue_scripts', 'scripts_initialization' ); 
function scripts_initialization() {    
    // JS
    wp_enqueue_script( "jquery" );
	wp_enqueue_script( "general-text-notification", get_template_directory_uri() . "/assets/js/modules/notification/GeneralUserTextNotification.js", [], "", true );
	wp_enqueue_script( "error-text-notification", get_template_directory_uri() . "/assets/js/modules/notification/ErrorUserTextNotification.js", [], "", true );
    wp_enqueue_script( "success-text-notification", get_template_directory_uri() . "/assets/js/modules/notification/SuccessUserTextNotification.js", [], "", true );
	wp_enqueue_script( "validator", get_template_directory_uri() . "/assets/js/modules/validator/Validator.js", [], "", true );
	wp_enqueue_script( "timer", get_template_directory_uri() . "/assets/js/modules/timer/Timer.js", [], "", true );
	wp_enqueue_script( "ajax-handler", get_template_directory_uri() . "/assets/js/modules/ajax/AjaxHandler.js", [], "", true );
	wp_enqueue_script( "registration-form", get_template_directory_uri() . "/assets/js/registration-form.js", [], "", true );
    wp_enqueue_script( "login-form", get_template_directory_uri() . "/assets/js/login-form.js", [], "", true );
    wp_enqueue_script( "forgot-password-form", get_template_directory_uri() . "/assets/js/forgot-password-form.js", [], "", true );
    wp_enqueue_script( "reset-password-form", get_template_directory_uri() . "/assets/js/reset-password-form.js", [], "", true );
    wp_enqueue_script( "change-password-form", get_template_directory_uri() . "/assets/js/change-password-form.js", [], "", true );
    wp_enqueue_script( "isotope-filter", get_template_directory_uri() . "/assets/js/isotope.pkgd.min.js", [], "", true );
	wp_enqueue_script( 'filter', get_template_directory_uri() . '/assets/js/filter.js', [], '1.0', true );
	wp_enqueue_script( 'sort', get_template_directory_uri() . '/assets/js/sort.js', [], '1.0', true );

	$jsAjaxFileNamesList = [
        "registration-form",
        "login-form",
        "forgot-password-form",
        "reset-password-form",
        "change-password-form",
    ];
    foreach ($jsAjaxFileNamesList as $jsFileName) {
        wp_localize_script( $jsFileName, 'myajax', // добавление в файл js ссылки на файл обрабатывающий AJAX запросы
            array(
                'ajax_url' => admin_url('admin-ajax.php')
            )
        );
    }
	
    wp_localize_script( 'registration-form', 'wp', // добавление в файл js ссылки на главную страницу
        array(
            'home_url' => get_home_url(),
        )
    );
}
// remove_filter( 'authenticate', 'wp_authenticate_email_password', 20, 3 ); // Убираем аутентификацию по почте
add_filter( 'send_email_change_email', '__return_false' );
add_filter( 'send_password_change_email', '__return_false' );


?>