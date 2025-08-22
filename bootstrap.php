<?php
session_start();

//PATH
define('ROOT_PATH', __DIR__ . '/');
define('CONTENTS_MANAGEMENT_PATH', ROOT_PATH . 'contentsManagement/');
define('DB_PATH', ROOT_PATH . 'db/');
define('IMAGES_PATH', ROOT_PATH . 'sites/default/images/');
define('TEMPLATE_PATH', ROOT_PATH . 'template/');
define('UTILS_PATH', ROOT_PATH . 'utils/');
define('VENDOR_FOULDER_PATH', ROOT_PATH . 'vendor/');
define('BASE_TEMPLATE_PATH', TEMPLATE_PATH . 'base.php');
define('TINYMCE_IMAGE_SELECTOR_PATH', CONTENTS_MANAGEMENT_PATH . 'imageSelectorTinyMCE.php');

//URL
define('BASE_URL', '/');
define('CSS_URL', BASE_URL . 'css/');
define('JS_URL', BASE_URL . 'js/');
define('IMAGES_URL', BASE_URL . 'sites/default/images/');
define('UTILS_URL', BASE_URL . 'utils/');
define('CONTENTS_MANAGEMENT_URL', BASE_URL . 'contentsManagement/');
define('CONTENTS_EDITING_URL', CONTENTS_MANAGEMENT_URL . 'editing/');
// define('CONTENTS_INSERTING_URL', CONTENTS_MANAGEMENT_URL . 'insertion/');
define('CONTENT_ADDERS_SCRIPT_URL', UTILS_URL . 'contentAdders/');
define('CONTENT_EDITORS_SCRIPT_URL', UTILS_URL . 'contentEditors/');
define('CONTENT_REMOVERS_SCRIPT_URL', UTILS_URL . 'contentRemovers/');
define('CONTENT_GETTERS_SCRIPT_URL', UTILS_URL . 'getters/');
define('INDEX_PAGE_URL', BASE_URL . 'index.php');
define('LOGIN_PAGE_URL', BASE_URL . 'login.php');
define('ADMIN_PAGE_URL', BASE_URL . 'admin.php');

require_once(UTILS_PATH . "functions.php");
require_once(DB_PATH . "dbConfig.php");
$dbh = new DatabaseHelper();
?>