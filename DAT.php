<?php

include "Database.php";
$testDb = new Database("localhost", "just_testing", 'root', '');

$usersTable = new Table(
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


$row = $usersTable->fetchAll();
$usersTable->update(array('id' => '1', 'first_name' => 'erfan'));


// function e(array $argv)
// {
//     print_r($argv);
//     switch ($argv[1]){
//         case "ct":
//             try
//             {
//                 $tableName = $argv[2];
//                 ($$tableName)->createTable();
//             }
//             catch (Exception $e)
//             {
//                 echo "An error occurred: " . $e->getMessage();
//             }
//             break;
//         default:
//             echo "unknown command.";
//     }
// }

// e($argv);
?>