<?php

/**
 * Do not edit anything in this file unless you know what you're doing
 */

use Roots\Sage\Config;
use Roots\Sage\Container;

/**
 * Helper function for prettying up errors
 * @param string $message
 * @param string $subtitle
 * @param string $title
 */
$sage_error = function ($message, $subtitle = '', $title = '') {
    $title = $title ?: __('Sage &rsaquo; Error', 'sage');
    $footer = '<a href="https://roots.io/sage/docs/">roots.io/sage/docs/</a>';
    $message = "<h1>{$title}<br><small>{$subtitle}</small></h1><p>{$message}</p><p>{$footer}</p>";
    wp_die($message, $title);
};

/**
 * Ensure compatible version of PHP is used
 */
if (version_compare('7', phpversion(), '>=')) {
    $sage_error(__('You must be using PHP 7 or greater.', 'sage'), __('Invalid PHP version', 'sage'));
}

/**
 * Ensure compatible version of WordPress is used
 */
if (version_compare('4.7.0', get_bloginfo('version'), '>=')) {
    $sage_error(__('You must be using WordPress 4.7.0 or greater.', 'sage'), __('Invalid WordPress version', 'sage'));
}

/**
 * Ensure dependencies are loaded
 */
if (!class_exists('Roots\\Sage\\Container')) {
    if (!file_exists($composer = __DIR__.'/../vendor/autoload.php')) {
        $sage_error(
            __('You must run <code>composer install</code> from the Sage directory.', 'sage'),
            __('Autoloader not found.', 'sage')
        );
    }
    require_once $composer;
}

/**
 * Sage required files
 *
 * The mapped array determines the code library included in your theme.
 * Add or remove files to the array as needed. Supports child theme overrides.
 */
array_map(function ($file) use ($sage_error) {
    $file = "../app/{$file}.php";
    if (!locate_template($file, true, true)) {
        $sage_error(sprintf(__('Error locating <code>%s</code> for inclusion.', 'sage'), $file), 'File not found');
    }
}, ['helpers', 'setup', 'filters', 'admin']);

/**
 * Here's what's happening with these hooks:
 * 1. WordPress initially detects theme in themes/sage/resources
 * 2. Upon activation, we tell WordPress that the theme is actually in themes/sage/resources/views
 * 3. When we call get_template_directory() or get_template_directory_uri(), we point it back to themes/sage/resources
 *
 * We do this so that the Template Hierarchy will look in themes/sage/resources/views for core WordPress themes
 * But functions.php, style.css, and index.php are all still located in themes/sage/resources
 *
 * This is not compatible with the WordPress Customizer theme preview prior to theme activation
 *
 * get_template_directory()   -> /srv/www/example.com/current/web/app/themes/sage/resources
 * get_stylesheet_directory() -> /srv/www/example.com/current/web/app/themes/sage/resources
 * locate_template()
 * ├── STYLESHEETPATH         -> /srv/www/example.com/current/web/app/themes/sage/resources/views
 * └── TEMPLATEPATH           -> /srv/www/example.com/current/web/app/themes/sage/resources
 */
array_map(
    'add_filter',
    ['theme_file_path', 'theme_file_uri', 'parent_theme_file_path', 'parent_theme_file_uri'],
    array_fill(0, 4, 'dirname')
);
Container::getInstance()
    ->bindIf('config', function () {
        return new Config([
            'assets' => require dirname(__DIR__).'/config/assets.php',
            'theme' => require dirname(__DIR__).'/config/theme.php',
            'view' => require dirname(__DIR__).'/config/view.php',
        ]);
    }, true);

// Register Custom Navigation Walker
require_once('class-wp-bootstrap-navwalker.php');

function wpb_add_google_fonts() {
    wp_enqueue_style( 'wpb-google-fonts', 'https://fonts.googleapis.com/css?family=Muli:400,600,700,800', false );
    wp_enqueue_style( 'icon-fonts', 'https://file.myfontastic.com/yvyEpy3bCUGK6Fi8iMHGFZ/icons.css', false );
}

add_action( 'wp_enqueue_scripts', 'wpb_add_google_fonts' );

function get_link_by_slug($slug, $type = 'post'){
  $post = get_page_by_path($slug, OBJECT, $type);
  // echo get_permalink($post);
  // var_dump($post); die();
  return get_permalink($post);
}

add_action( 'wp_ajax_contact_form', 'contact_form' );
add_action( 'wp_ajax_nopriv_contact_form', 'contact_form' );

function contact_form () {
    $rawPostBody = file_get_contents('php://input');
    $postData = json_decode($rawPostBody, true);

    ob_start(); ?>
    <h3>Pedidos | Total: R$<?php echo $postData['total'] ?></h3>
    <?php foreach ($postData['categorias'] as $key => $categoria): ?>
        <h4><?php echo $categoria['title'] ?></h4>
        <div>
            <?php foreach ($categoria['pedidos'] as $pedido):
                if (!$pedido['qtd']) continue;?>
                <p>
                    <?php echo $pedido['produto']['product']['title'] ?> -
                    <?php echo $pedido['produto']['product']['prices'][0]['title'] ?>
                    | <?php echo $pedido['qtd'] ?> x
                      R$<?php echo $pedido['produto']['price'] ?> =
                      R$<?php echo $pedido['total'] ?>
                </p>
            <?php endforeach ?>
        </div>
    <?php endforeach ?>
    <h3>Contato</h3>
    <p>Nome: <?php echo $postData['contato']['name']; ?></p>
    <p>Telefone: <?php echo $postData['contato']['phone']; ?></p>
    <p>Endereço: <?php echo $postData['contato']['address']; ?></p>
    <p>Email: <?php echo $postData['contato']['email']; ?></p>
    <p>Comentário: <?php echo $postData['contato']['comment']; ?></p>
    <p>Data do evento: <?php echo $postData['contato']['event_date']; ?></p>
    <?php
    $to = "erictonussi@gmail.com";
    $subject = "Pedido de orçamento via site";
    $message = ob_get_contents();
    $headers = array('Content-Type: text/html; charset=UTF-8');

    ob_end_clean();
    // echo $message;
    // $message = "message message message message message message message ";

    if ( wp_mail($to, $subject, $message, $headers) ){
        echo "mail_sent";
    } else {
        echo "mail_not_sent";
    }

    // var_dump($postData);
    die(1);
}

add_action( 'wp_ajax_index_contact_form', 'index_contact_form' );
add_action( 'wp_ajax_nopriv_index_contact_form', 'index_contact_form' );

function index_contact_form () {
    ob_start(); ?>
        <h3>Contato</h3>
        <p>Nome: <?php echo $_POST['name']; ?></p>
        <p>Telefone: <?php echo $_POST['phone']; ?></p>
        <p>Endereço: <?php echo $_POST['address']; ?></p>
        <p>Email: <?php echo $_POST['email']; ?></p>
        <p>Mensagem: <?php echo $_POST['message']; ?></p>
    <?php
    $to = array(get_option('admin_email'), 'pedido@girassoldoces.com.br');
    $subject = "Contato via site";
    $message = ob_get_contents();
    $headers = array('Content-Type: text/html; charset=UTF-8');

    ob_end_clean();
    // echo $message;

    if ( wp_mail($to, $subject, $message, $headers) ){
        echo "mail_sent";
    } else {
        echo "mail_not_sent";
    }

    die(1);
}

function wpse27856_set_content_type(){
    return "text/html";
}
