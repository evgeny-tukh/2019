<?php

    require '../db/database.php';
    require '../custom_obj/obj.php';

    set_time_limit (0);

    $database = new Database ();

    $layerIDs = array ();
    $result   = array ();

    $callback = function ($row) use (&$layerIDs)
    {
        array_push ($layerIDs, intval ($row ['id']));
    };

    $database->enumResult ("select id from layers", $callback);

    foreach ($layerIDs as $layerID)
    {
        $layer = new ObjectLayer ();

        $layer->getFromDatabase ($database, $layerID);

        array_push ($result, $layer->toJSON ());
    }

    $database->close ();

    echo json_encode ($result);
