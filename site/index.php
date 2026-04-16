<?php

require_once __DIR__ . '/modules/database.php';
require_once __DIR__ . '/modules/page.php';
require_once __DIR__ . '/config.php';

$db = new Database($config["db"]["path"]);
$page = new Page(__DIR__ . '/templates/index.tpl');

// проверяет установленно ли значение параметра page в url запросе
$pageId = isset($_GET['page']) ? (int)$_GET['page'] : 1;

$data = $db->Read("page", $pageId);

if (!$data) {
    $data = [
        "title" => "Not found",
        "content" => "Page does not exist"
    ];
}

echo $page->Render($data);