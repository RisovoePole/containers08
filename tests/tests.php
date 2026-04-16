<?php

require_once __DIR__ . '/testframework.php';
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../modules/database.php';
require_once __DIR__ . '/../modules/page.php';

$testFramework = new TestFramework();

/**
 * 1. DB connection test
 */
function testDbConnection() {
    global $config;

    try {
        $db = new Database($config["db"]["path"]);
        return assertExpression($db instanceof Database, "DB connection OK", "DB connection FAIL");
    } catch (Exception $e) {
        error("DB connection exception: " . $e->getMessage());
        return false;
    }
}

/**
 * 2. Count test
 */
function testDbCount() {
    global $config;

    try {
        $db = new Database($config["db"]["path"]);
        $count = $db->Count("page");

        return assertExpression($count >= 0, "Count OK ($count)", "Count FAIL");
    } catch (Exception $e) {
        error("Count exception: " . $e->getMessage());
        return false;
    }
}

/**
 * 3. Create test
 */
function testDbCreate() {
    global $config;

    try {
        $db = new Database($config["db"]["path"]);

        $id = $db->Create("page", [
            "title" => "Test Page",
            "content" => "Hello world"
        ]);

        return assertExpression($id > 0, "Create OK (ID $id)", "Create FAIL");
    } catch (Exception $e) {
        error("Create exception: " . $e->getMessage());
        return false;
    }
}

/**
 * 4. Read test
 */
function testDbRead() {
    global $config;

    try {
        $db = new Database($config["db"]["path"]);

        $id = $db->Create("page", [
            "title" => "Read Test",
            "content" => "Read content"
        ]);

        $row = $db->Read("page", $id);

        $ok = $row && isset($row["title"]) && $row["title"] === "Read Test";

        return assertExpression($ok, "Read OK", "Read FAIL");
    } catch (Exception $e) {
        error("Read exception: " . $e->getMessage());
        return false;
    }
}

/**
 * Register tests
 */
$testFramework->add('Database connection', 'testDbConnection');
$testFramework->add('Database count', 'testDbCount');
$testFramework->add('Database create', 'testDbCreate');
$testFramework->add('Database read', 'testDbRead');

/**
 * Run tests
 */
$testFramework->run();

echo PHP_EOL . "RESULT: " . $testFramework->getResult() . PHP_EOL;