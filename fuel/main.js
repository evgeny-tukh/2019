var globalInterface = {};
var fleets;
var stringTable;
var firstReport;
var lastReport;
var endTime;
var beginTime;
var timeInterval;

function init ()
{
    var fleetPane   = null;
    var consumBut   = document.getElementById ('consumBut');
    var bunkerBut   = document.getElementById ('bunkerBut');
    var schemaBut   = document.getElementById ('schemaBut');
    var consumDiv   = document.getElementById ('consum');
    var bunkerDiv   = document.getElementById ('bunker');
    var schemaDiv   = document.getElementById ('schema');
    var pageButtons = [consumBut, bunkerBut, schemaBut];
    var pageDivs    = [consumDiv, bunkerDiv, schemaDiv];
    
    fleets = Fleets.createFleets ();
    
    pageButtons.forEach (function (button, index)
                         {
                            button.onclick = function ()
                                             {
                                                 var activeIndex = pageButtons.indexOf (this);
                                                 
                                                 pageButtons.forEach (function (but, butIndex)
                                                                      {
                                                                          but.className = butIndex === activeIndex ? 'switchButActive' : 'switchBut';
                                                                      });
                                                 pageDivs.forEach (function (div, divIndex)
                                                                   {
                                                                       div.style.display = divIndex === activeIndex ? null : 'none';
                                                                   });
                                             };
                         });
                         
    consumBut.onclick ();
    
    endTime      = Cary.tools.getTimestamp ();
    beginTime    = endTime - 365 * 24 * 3600000;
    timeInterval = 3600;
    
    new SessionWatchdog ();

    stringTable = new strings.StringTable ('russian.st', 2000);
    
    // Load a fleet
    loadSerializable ('get_vessels.php', onFleetLoaded);
    
    function getMaximalTimFrame ()
    {
        firstReport = Cary.tools.getTimestamp (),
        lastReport  = 0;
    
        fleets.enumVessels (function (vessel)
                            {
                                if (vessel.firstReport && vessel.firstReport < firstReport)
                                    firstReport = vessel.firstReport;
                                
                                if (vessel.lastReport && vessel.lastReport > lastReport)
                                    lastReport = vessel.lastReport;
                            });
                               
        beginTime = firstReport;
        endTime   = lastReport;
    }
    
    function onFleetLoaded (fleetsData)
    {
        fleets = Fleets.createFleets ();
    
        fleetsData.forEach (function (fleetData)
                            {
                                var fleet = new Fleet ();

                                fleet.deserialize (fleetData);
                                
                                fleets.push (fleet);
                            });
        
        
        getMaximalTimFrame ();
        
        Cary.tools.WaitForCondition (function ()
                                     {
                                         return stringTable.loaded;
                                     },
                                     function ()
                                     {
                                         var options = {};
                                         
                                         if (authKey)
                                             options.parent = document.getElementById ('vesselPane');
                                         
                                         fleetPane = new FleetPane ({}, options);
                                         
                                         if (authKey)
                                             showFleetPane ();
                                     });
    }
    
    function showFleetPane ()
    {
        if (fleetPane)
            fleetPane.show ();
    }
    
    function setTimeFrame ()
    {
        new TimeSettingsWnd ({ onOk: onOk }, firstReport, lastReport, timeInterval);
        
        function onOk (from, to, period)
        {
            beginTime    = from;
            endTime      = to;
            timeInterval = period;
        }
    }
    
    function logout ()
    {
        Cary.tools.sendRequest ({ url: 'logout.php', method: Cary.tools.methods.get, content: Cary.tools.contentTypes.plainText });

        if (authKey)
        {
            if (logoutLink)
                window.location = logoutLink;
            else
                window.history.back ();
        }
        else
        {
            window.location = 'login.html';
        }
    }
}    
