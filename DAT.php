<?php

use CreateFile\FILETYPES;

include "Table.php";
$testDb = new Database("localhost", "just_testing", 'root', '');

$userTable = new Table(
    "users",
    [
        new Column('id', 'INT', 6, 'AUTO_INCREMENT', false, true),
        new Column('first_name', 'VARCHAR', 30, null, false),
        new Column('last_name', 'VARCHAR', 30, null, false),
        new Column('email', 'VARCHAR', 50, null, false),
        new Column('phone_number', 'VARCHAR', 20, null, false),
        new Column('created_at', 'TIMESTAMP', null, 'CURRENT_TIMESTAMP', false),
        new Column('updated_at', 'TIMESTAMP', null, 'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP', true),
    ],
    $testDb
);

$myArray = array
(
    "first_name" => "erfan",
    "email" => "erfan@gmail.com",
    "last_name" => "dehghani",
    "phone_number" => "09179679459",
);
$my2Array = array
(
    "first_name" => "rezan",
    "email" => "reza@dhi",
    "last_name" => "dehg",
    "phone_number" => "09359679459",
);

include "CreateFile.php";

new CreateFile($userTable->getName(), FILETYPES::DATABASE_CLASS)
?>