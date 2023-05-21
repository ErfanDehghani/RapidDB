<?php
include "Database.php";
include "Table.php";


// Create an instance of your database with entering the needed info for connecting to it
$myDatabase = new Database(
    host: "localhost",
    databaseName: "just_testing",
    username: 'root',
    password: ''
);


// Here is where you declare your tables to be created later with 'php rapid migrate' command.
$userTable = new Table(
    
    TableName: "users",

    ColumnsArray: 
    [
        new Column('id', 'INT', 6, 'AUTO_INCREMENT', false, true),
        new Column('first_name', 'VARCHAR', 30, null, false),
        new Column('last_name', 'VARCHAR', 30, null, false),
        new Column('email', 'VARCHAR', 50, null, false),
        new Column('phone_number', 'VARCHAR', 20, null, false),
        new Column('created_at', 'TIMESTAMP', null, 'CURRENT_TIMESTAMP', false),
        new Column('updated_at', 'TIMESTAMP', null, 'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP', true),
    ],

    Database: $testDb
);