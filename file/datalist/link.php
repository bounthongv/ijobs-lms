
<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', 'https://job.apis.com.la/file/datalist/');
    // define('BASE_URL', 'http://localhost:81/project_person_card/file/');
}

// ใช้ __DIR__ ชี้ตำแหน่งของ link.php เอง (ไม่ว่าใครจะ include มาจากไหน)
$file_dir = __DIR__ . '/';
?>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="<?= BASE_URL ?>js/insert.js?v=<?= filemtime($file_dir . 'js/insert.js') ?>"></script>
<script src="<?= BASE_URL ?>js/edit.js?v=<?= filemtime($file_dir . 'js/edit.js') ?>"></script>
<script src="<?= BASE_URL ?>js/del.js?v=<?= filemtime($file_dir . 'js/del.js') ?>"></script>
<script src="<?= BASE_URL ?>js/search.js?v=<?= filemtime($file_dir . 'js/search.js') ?>"></script>
<script src="<?= BASE_URL ?>js/print.js?v=<?= filemtime($file_dir . 'js/print.js') ?>"></script>
