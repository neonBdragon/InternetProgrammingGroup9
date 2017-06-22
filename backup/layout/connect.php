<?php
    try
        {

            $dbConnection = new PDO('mysql:dbname=group9;host=localhost;charset=utf8','group9','group9hack');
            $dbConnection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        }

    catch (PDOException $e)
        {
            die("Could not connect: " . $e->getMessage());
        }
?>