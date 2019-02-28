<?php

     require_once 'get_month_rep.php';

     $link = genReport (1000, 2018, 3);

?>

<html>
    <head>
        <title>
            Test
        </title>
        <style>
            body
            {
                background-color: lightblue;
            }

            iframe
            {
                position: absolute;
                left: 0px;
                top: 0px;
                background-color: cyan;
                border-width: 2px;
                border-style: solid;
                width: 1000px;
                height: 700px;
            }
        </style>
    </head>
    <body>
        <iframe src="<?php echo $link;?>">
            Not supported
        </iframe>
    </body>
</html>