<?php


/*
 * --------------------------------
 *           Let's begin
 * --------------------------------
 *
 */
if (version_compare(phpversion(), '7.0', '<')) exit('PHP 7+ is required.');
set_include_path(dirname(__FILE__));
header('Content-Type: text/html; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', true);


/*
 * --------------------------------
 *          Root directory
 * --------------------------------
 *
 */
$rootDirectory = substr(dirname($_SERVER['PHP_SELF']), 1);
$root = empty($rootDirectory) ? '' : $rootDirectory . '/';
define('ROOT', (strpos($root, 'index.php/') !== false ? substr($root, 0, strpos($root, 'index.php/')) : $root));
define('HTTP_ROOT', '/' . ROOT);
define('PHP_ROOT', rtrim(dirname(__FILE__), '/') . '/');


/*
 * --------------------------------
 *        Folder constants
 * --------------------------------
 *
 */
define('APP',      PHP_ROOT . 'app/');
define('BASE',          APP . 'base/');
define('CORE',          APP . 'core/');
define('DI',            APP . 'di/');
define('EXCEPTION',     APP . 'exception/');
define('MODEL',         APP . 'model/');
define('TEMPLATE',      APP . 'template/');
define('VENDOR',        APP . 'vendor/');
define('VIEW',          APP . 'view/');
define('CONTROLLER',    APP . 'controller/');
define('MODEL_BASE',    MODEL . 'base/');
define('MODEL_DOMAIN',  MODEL . 'domain/');
define('MODEL_MAPPER',  MODEL . 'mapper/');
define('MODEL_SERVICE', MODEL . 'service/');


/*
 * --------------------------------
 *           Load files
 * --------------------------------
 *
 */
foreach (glob(CORE . '*.class.php') as $file) require_once $file;
foreach (glob(EXCEPTION . '*.class.php') as $file) require_once $file;
foreach (glob(VENDOR . '*/autoload.php') as $file) require_once $file;


/*
 * --------------------------------
 *          Class loader
 * --------------------------------
 *
 */
spl_autoload_register(function ($classname) {
    $file = APP . strtolower(str_replace('\\', DIRECTORY_SEPARATOR, $classname)) . '.class.php';
    if (isset($file) and file_exists($file)) {
        require_once $file;
    } else {
        throw new InternalException('Cannot find "' . $classname . '" class.');
    }
});


/*
 * --------------------------------
 *        Exception handling
 * --------------------------------
 *
 */
set_exception_handler(function (Throwable $ex) {
    if (defined('DEBUG') and constant('DEBUG') === true) {
        echo '<br>' . PHP_EOL . '<h3>Error: <b>' . get_class($ex) . '</b></h3>';
        if (!empty($ex->getMessage())) echo '<h5>' . $ex->getMessage() . '</h5>';
        if (!empty($ex->getFile())) {
            echo '<p style="margin:0"><b>File:</b> ' . str_replace(PHP_ROOT, '/', $ex->getFile()) . ' (line <b>' . $ex->getLine() . '</b>)</p>';
            if (is_readable($ex->getFile())) {
                $lines = file($ex->getFile());
                $start = max($ex->getLine() - 3, 0);
                $end = min($ex->getLine() + 2, count($lines));
                $lspace = 999;
                $print = [];
                for ($i=$start; $i < $end; $i++) { 
                    $print['#' . ($i + 1) ] = $lines[$i];
                    $lspace = min($lspace, strlen($lines[$i])-strlen(ltrim($lines[$i])));
                }
                echo '<pre style="overflow: scroll;white-space: nowrap;">';
                foreach ($print as $key => $value) echo $key . str_replace(' ', '&nbsp;', ' ' . substr($value, $lspace)) . '<br>';
                echo '</pre>';
            }
        }
        echo '<p style="margin:0"><b>Stack trace: </b></p>' . PHP_EOL;
        echo '<pre style="font-size:12px; overflow: scroll;white-space: nowrap;">';
        echo str_replace([PHP_ROOT, "\n"], ['/', '<br>'], $ex->getTraceAsString());
        echo '</pre>';
    } else {
        echo '<div class="alert alert-danger">Une erreur est survenue..</div>';
    }
});


/*
 * --------------------------------
 *           Load Config
 * --------------------------------
 *
 */
$config = json_decode(file_get_contents(CORE . 'config.json'), true);
define('DEBUG', $config['debug']);


/*
 * --------------------------------
 *      Dependency Injection
 * --------------------------------
 *
 */
$container = new DI\Container;
$container->load($config['injector']);


/*
 * --------------------------------
 *         Analyse request
 * --------------------------------
 *
 */
$request = $container->get('Request');
$request->parse($_GET['page'] ?? '');
$request->setPost($_POST);


/*
 * --------------------------------
 *            And begin
 * --------------------------------
 *
 */
$app = new App;
$app->start($container, $config);
$app->follow($request);


/*
 * --------------------------------
 *       Helpers functions
 * --------------------------------
 *
 */
function e($html) {
    return htmlentities($html, ENT_QUOTES, 'UTF-8');
}

function get_class_name($class) {
    $classname = get_class($class);
    if ($pos = strrpos($classname, '\\')) $classname = substr($classname, $pos + 1);
    return $classname;
}

function snakeToCamel($string) { 
    return preg_replace_callback('/_([a-z])/', function ($c) { return strtoupper($c[1]); }, $string);
}

function camelToSnake($string) { 
    return ltrim(preg_replace_callback('/([A-Z]+)([a-z]+)?/', function ($c) { return '_' . strtolower($c[1]) . ($c[2] ?? ''); }, $string), '_');
}
