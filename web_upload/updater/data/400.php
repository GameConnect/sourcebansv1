<?php

$ret = $GLOBALS['db']->Execute("ALTER DATABASE `".DB_NAME."` CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci");
if (!$ret)
    return false;

$tables = $GLOBALS['db']->GetAll('SHOW TABLES');

foreach ($tables as $table) {
    $name = $table['Tables_in_'.DB_NAME];

    $ret = $GLOBALS['db']->Execute("ALTER TABLE `$name` ENGINE=InnoDB");
    if (!$ret)
        return false;

    $ret = $GLOBALS['db']->Execute("ALTER TABLE `$name` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    if (!$ret)
        return false;

    $ret = $GLOBALS['db']->Execute("REPAIR TABLE `$name`");
    if (!$ret)
        return false;

    $ret = $GLOBALS['db']->Execute("OPTIMIZE TABLE `$name`");
    if (!$ret)
        return false;
}

return true;
