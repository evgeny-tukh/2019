<?php

    require_once '../db/database.php';
    require_once 'global_func.php';

    $areas    = [];
    $database = new Database ();
    $curArea = 0;

    $cb = function ($row) use (&$areas, &$curArea)
          {
              $area = intval ($row ['id']);

              if ($area !== $curArea)
              {
                  array_push ($areas, ['id' => $area, 'name' => $row ['name'], 'enabled' => intval ($row ['enabled']) !== 0, 'type' => 3, 'points' => []]);

                  $curArea = $area;
              }

              array_push ($areas [count ($areas) - 1]['points'], [ 'lat' => doubleval ($row ['lat']), 'lon' => doubleval ($row ['lon'])]);
          };

    $query = "select a.id,a.name,a.enabled,p.lat,p.lon from ntg_areas a left join ntg_areas_points p on a.id=p.area order by a.id,p.`order`";

    $database->enumResult ($query, $cb);
    $database->close ();

    echo json_encode ([ 'name' => 'Not-to-go-areas', 'id' => 1, 'objects' => $areas ]);
