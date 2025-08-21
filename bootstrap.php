<?php
session_start();

define('ROOT_PATH', __DIR__ . '/');
define('BASE_URL', '/');

define('CONTENTS_MANAGEMENT_PATH', ROOT_PATH . 'contentsManagement/');
define('CONTENTS_EDITING_PATH', CONTENTS_MANAGEMENT_PATH . 'editing/');
define('CONTENTS_INSERTING_PATH', CONTENTS_MANAGEMENT_PATH . 'insertion/');
define('CSS_PATH', BASE_URL . 'css/');
define('DB_PATH', ROOT_PATH . 'db/');
define('JS_PATH', BASE_URL . 'js/');
define('IMAGES_PATH', BASE_URL . 'sites/default/images/');
define('TEMPLATE_PATH', ROOT_PATH . 'template/');
define('UTILS_PATH', ROOT_PATH . 'utils/');
define('CONTENT_ADDERS_SCRIPT_PATH', UTILS_PATH . 'contentAdders/');
define('CONTENT_EDITORS_SCRIPT_PATH', UTILS_PATH . 'contentEditors/');
define('CONTENT_REMOVERS_SCRIPT_PATH', UTILS_PATH . 'contentRemovers/');
define('CONTENT_GETTERS_SCRIPT_PATH', UTILS_PATH . 'getters/');
define('VENDOR_FOULDER_PATH', ROOT_PATH . 'vendor/');

define('INDEX_PAGE_PATH', BASE_URL . 'index.php');
define('LOGIN_PAGE_PATH', BASE_URL . 'login.php');
define('ADMIN_PAGE_PATH', BASE_URL . 'admin.php');
define('BASE_TEMPLATE_PATH', TEMPLATE_PATH . 'base.php');
define('TINYMCE_IMAGE_SELECTOR_PATH', CONTENTS_MANAGEMENT_PATH . 'imageSelectorTinyMCE.php');

require_once(UTILS_PATH . "functions.php");
require_once(DB_PATH . "dbConfig.php");
$dbh = new DatabaseHelper();
?>