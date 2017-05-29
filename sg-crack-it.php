<?php
/*
    Plugin Name: SG-Crack-IT
    Plugin URI: www.smartgnan.com   
    Description: A powerful and beautiful quiz plugin for WordPress.
    Version: 0.1
    Author: Amrulla Khan
    Author URI: http://www.smartgnan.com
    Text Domain: sg-crack-it
*/

define('SGCRACKIT_VERSION', '0.1'); //0.1

define('SGCRACKIT_DEV', false);

define('SGCRACKIT_PATH', dirname(__FILE__)); //\wp-content\plugins\sg-crack-it
define('SGCRACKIT_URL', plugins_url('', __FILE__)); //http://localhost:8012/wp-content/plugins/sg-crack-it
define('SGCRACKIT_FILE', __FILE__); //\wp-content\plugins\sg-crack-it\sg-crack-it.php
define('SGCRACKIT_PPATH', dirname(plugin_basename(__FILE__))); //sg-crack-it
define('SGCRACKIT_PLUGIN_PATH', SGCRACKIT_PPATH . '/plugin'); //sg-crack-it/plugin

$uploadDir = wp_upload_dir();

define('SGCRACKIT_CAPTCHA_DIR', $uploadDir['basedir'] . '/wp_pro_quiz_captcha'); ///wp-content/uploads/wp_pro_quiz_captcha
define('SGCRACKIT_CAPTCHA_URL', $uploadDir['baseurl'] . '/wp_pro_quiz_captcha'); //http://localhost:8012/wp-content/uploads/wp_pro_quiz_captcha

register_activation_hook(__FILE__, 'sgCrackIt_pluginActivation');

register_deactivation_hook(__FILE__, 'sgCrackIt_pluginDeactivation');

add_action('plugins_loaded', 'sgCrackIt_pluginLoaded');

echo spl_autoload_register('sgCrackIt_autoload');

if(is_admin())
{
    new SgCrackIt_Controller_Admin();
}

function sgCrackIt_autoload($class)
{
    $c = explode('_', $class);

    if ($c === false || count($c) != 3 || $c[0] !== 'SgCrackIt') {
        return;
    }

    switch ($c[1]) {
        case 'View':
            $dir = 'view';
            break;
        case 'Model':
            $dir = 'model';
            break;
        case 'Helper':
            $dir = 'helper';
            break;
        case 'Controller':
            $dir = 'controller';
            break;
        case 'Plugin':
            $dir = 'plugin';
            break;
        default:
            return;
    }

    $classPath = SGCRACKIT_PATH . '/lib/' . $dir . '/' . $class . '.php';

    if (file_exists($classPath)) {
        /** @noinspection PhpIncludeInspection */
        include_once $classPath;
    }
}

function sgCrackIt_pluginActivation()
{
    
}

function sgCrackIt_pluginDeactivation()
{
    
}

function sgCrackIt_pluginLoaded()
{
    
}
