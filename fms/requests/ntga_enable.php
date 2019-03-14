<?php

    require_once '../db/database.php';
    require_once 'global_func.php';

    $id      = array_key_exists ('a', $_REQUEST) ? $_REQUEST ['a'] : NULL;
    $enabled = array_key_exists ('e', $_REQUEST) ? $_REQUEST ['e'] : NULL;

    if ($id === NULL || $enabled === NULL)
        die ('Wrong parameters');

    $enabled = $enabled ? 1 : 0;

    $database = new Database ();

    if ($database)
    {
        $database->execute ("update ntg_areas set enabled=$enabled where id=$id");
        $database->close ();

        echo 'ok';
    }
    else
    {
        echo 'fail';
    }
