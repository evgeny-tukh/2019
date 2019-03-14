var fleets       = null;
var notToGoAreas = null;
var beginTime, endTime;

function showIndicator (type, show)
{
    document.getElementById (type + '_ind').style.display = show ? null : 'none';
}

function init ()
{
    var curOpacity = 1.0;
    var curStep    = -0.1;

    loadNotToGoAreas ();

    fleets = Fleets.createFleets ();

    Cary.tools.sendRequest ({ url: 'requests/get_vessels.php', method: 'get', content: Cary.tools.contentTypes.plainText, onLoad: onFleetLoaded, 
                              resType: Cary.tools.resTypes.json });

    showIndicator ('fuel', false);
    showIndicator ('maint', false);
    showIndicator ('crew', false);
    showIndicator ('sno', false);
    showIndicator ('ice', false);
    showIndicator ('eff', false);
    showIndicator ('dpt', false);
    showIndicator ('wat', false);
    showIndicator ('fms', false);

    //setInterval (updateIndicatorOpacity, 100);

    function updateIndicatorOpacity ()
    {
        var icons = document.getElementsByClassName ('ind');

        for (var i = 0; i < icons.length; ++ i)
            icons [i].style.opacity = curOpacity.toFixed (1);

        curOpacity += curStep;

        if (curOpacity < 0)
        {
            curStep    = 0.1;
            curOpacity = 0.1;
        }
        else if (curOpacity > 1)
        {
            curStep    = -0.1;
            curOpacity = 0.9;
        }
    }
}

function checkIfVesselCrossedNTGAreas (vessel, onCrosses, onDoesNotCross, param)
{
    var track = new Track (vessel);
    
    track.load ({ begin: beginTime, end: endTime, onLoaded: onLoaded });
    
    function onLoaded (loadedTrack)
    {
        if (loadedTrack.points.length > 0)
        {
            var ntgaCrossed;

            for (var i = 0, ntgaCrossed = false; !ntgaCrossed && i < notToGoAreas.objects.length; ++ i)
            {
                var area = notToGoAreas.objects [i];
                
                ntgaCrossed = area.properties.enabled && loadedTrack.crossesContour (area.points);
            }

            if (ntgaCrossed)
                onCrosses (param);
            else
                onDoesNotCross (param);
        }
    }
}

function updateVesselItemStates ()
{
    showIndicator ('fms', false);

    fleets.enumVessels (function (vessel)
                        {
                            checkIfVesselCrossedNTGAreas (vessel, function () { showIndicator ('fms', true); }, function () {}, null);
                        });
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

    updateVesselItemStates ();
}

function loadNotToGoAreas ()
{
    notToGoAreas = new Cary.userObjects.ObjectCollection ();
    
    Cary.tools.sendRequest ({ method: Cary.tools.methods.get, content: Cary.tools.contentTypes.plainText, resType: Cary.tools.resTypes.json, 
                              url: 'requests/ntga_get_list.php', onLoad: onLoad });
    
    function onLoad (data)
    {
        var options = {};
        
        notToGoAreas.deserialize (data, function () { return new Cary.userObjects.UserPolygon; });

        data.objects.forEach (function (object, index)
                              {
                                  notToGoAreas.objects [index].properties.enabled = object.enabled;
                              });
    }
}

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

