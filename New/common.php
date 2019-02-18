<?php

    const PROJECT_TITLE = 'Новый проект (Демо)';
    
    function generateCommonHeadPart ()
    {
        echo "<meta charset=\"UTF-8\">\n".
             "<link rel=\"stylesheet\" href=\"common.css\"/>\n".
             "<link rel=\"stylesheet\" href=\"page.css\"/>\n".
             "<script src=\"page.js\"></script>\n";
        echo '<title>'.PROJECT_TITLE."</title>\n";
    }
    
    function generateCommonBodyPart ()
    {
        echo "<div class=\"docker\">\n".
             "    <iframe id=\"docker\" width=\"100%\" height=\"100%\" frameborder=\"no\" marginheight=\"0\" marginwidth=\"0\" vspace=\"0\" hspace=\"0\"></iframe>\n".
             "</div>\n".
             "<img id=\"loading\" src=\"res/loader.gif\" style=\"position: absolute; display: none; left: 40%; top: 40%;\"/>\n";
    }