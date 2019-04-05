<?php

    set_time_limit (0);

    $output = array ();

    $date = date ("Y/m/d");
    $cmd  = "perl home/virtwww/w_jecat-ru_f48c2fba/http/AMPBR/main.pl $date";

    echo "Prefetching ($cmd)...<br/>";

    exec ($cmd, $output);

    echo "Done.<br/>";

    foreach ($output as $string)
        echo $string."<br/>";

?>
