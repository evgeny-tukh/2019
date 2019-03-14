<?php

    require_once '../db/database.php';
    require_once 'global_func.php';

    $database = new Database ();

    $result      = [];
    $curFleetID  = -1;
    $curVesselID = -1;
    $curFleet    = NULL;
    $curVessel   = [ 'sensors' => [] ];

    $callback = function ($row, $db) use (&$result, &$curVesselID, &$curFleetID)
    {
        $fleetID  = intval ($row ['fid']);
        $vesselID = $row ['vid'] ? intval ($row ['vid']) : NULL;

        if ($fleetID !== $curFleetID)
        {
            array_push ($result, [ 'id' => $fleetID, 'name' => $row ['fname'], 'vessels' => [] ]);

            $curFleetID  = $fleetID;
            $curVesselID = -1;
        }

        $fleetIndex = count ([$result]) - 1;

        if ($curVesselID != $vesselID)
        {
            if ($vesselID)
                array_push ($result [$fleetIndex]['vessels'],
                            [ 'id' => $vesselID, 'name' => $row ['vname'], 'device' => $row ['vdev'], 'type' => intval ($row ['vtype']), 'sensors' => [] ]);

            $curVesselID = $vesselID;
        }

        $vesselIndex = count ($result [$fleetIndex]['vessels']) - 1;

        array_push ($result [$fleetIndex]['vessels'][$vesselIndex]['sensors'],
                    [
                      'id'     => intval ($row ['sid']),
                      'num'    => intval ($row ['snum']),
                      'analog' => intval ($row ['sanl']) != 0,
                      'descr'  => $row ['sdsc'], //mb_convert_encoding ($row ['sdsc'], 'UTF-8', 'Windows-1251'),
                      'minOut' => doubleval ($row ['smino']),
                      'maxOut' => doubleval ($row ['smaxo']),
                      'minAct' => doubleval ($row ['smina']),
                      'maxAct' => doubleval ($row ['smaxa'])
                    ]);
    };

    $query = 'select f.id fid,f.name fname,v.id vid,v.name vname,v.device vdev,v.type vtype,s.id sid,s.sensor_num snum,s.analog sanl,s.description sdsc,'.
             's.min_output smino,s.max_output smaxo,s.min_actual smina,s.max_actual smaxa '.
             'from fleets f left join vessels v on f.id=v.fleet left join sensors s on v.id=s.vessel order by f.id,v.id,s.sensor_num';

    $database->enumResult ($query, $callback);

    //foreach ($result as $fleet)
    for ($j = 0; $j < count ($result); ++ $j)
    {
        //$vessels = $fleet ['vessels'];
        $vessels = $result [$j]['vessels'];

        for ($i = 0; $i < count ($vessels); ++ $i)
        {
            $lat         = NULL;
            $lon         = NULL;
            $firstReport = NULL;
            $lastReport  = NULL;

            $callback = function ($row) use (&$lat, &$lon)
                        {
                            $lat = doubleval ($row ['lat']);
                            $lon = doubleval ($row ['lon']);
                        };

            $query = 'select lat,lon from tracks where vessel='.$vessels [$i]['id'].' order by timestamp desc limit 1';
            $database->processResult ($query, $callback);

            //$vessels [$i]['lat'] = $lat;
            //$vessels [$i]['lon'] = $lon;
            $result [$j]['vessels'][$i]['lat'] = $lat;
            $result [$j]['vessels'][$i]['lon'] = $lon;

            $callback = function ($row) use (&$firstReport, &$lastReport)
                        {
                            if ($row ['tmin'] && $row ['tmax'])
                            {
                                $firstReport = getTimestamp ($row ['tmin'], TRUE);
                                $lastReport  = getTimestamp ($row ['tmax'], TRUE);
                            }
                        };

            $database->processResult ('select max(timestamp) tmax,min(timestamp) tmin from tracks where timestamp is not null and vessel='.$vessels [$i]['id'], $callback);

            $result [$j]['vessels'][$i]['firstReport'] = $firstReport;
            $result [$j]['vessels'][$i]['lastReport']  = $lastReport;
        }
    }

    $database->close ();

    echo json_encode ($result);
