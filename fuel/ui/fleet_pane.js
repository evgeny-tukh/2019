function FleetPane (callbacks, options)
{
    var parent, height;
    
    this.embedMode = 'parent' in options;
    
    if (this.embedMode)
    {
        parent = options.parent;
        height = parent.clientHeight - 20;
    }
    else
    {
        parent = document.getElementsByTagName ('body') [0];
        height = window.innerHeight - 20;
    }
    
    this.paneHeight = height;
    
    if (Cary.tools.isNothing (callbacks))
        callbacks = {};
    
    if (Cary.tools.isNothing (options))
        options = {};
    
    this.options   = Cary.tools.isNothing (options) ? {} : options;
    this.callbacks = Cary.tools.isNothing (callbacks) ? {} : callbacks;
    
    Cary.ui.Window.apply (this, [{ position: { top: 0, right: 0, width: '400px', height: Cary.tools.int2pix (height), absolute: true }, 
                                 title: stringTable.vessels, parent: parent }]);
}

FleetPane.prototype = Object.create (Cary.ui.Window.prototype);

FleetPane.prototype.onInitialize = function ()
{
    var ctlBlkStyle = { padding: 0, 'padding-left': 10, 'margin-bottom': 8, 'margin-top': 8, height: 25, 'text-align': 'left', 'line-height': 25, 'font-size': 17 };
    var columns     = [{ title: stringTable.name, width: 150, onItemClick: onSelectVessel },
                       { title: stringTable.type, width: 100, onItemClick: onSelectVessel },
                       { title: stringTable.lastReport, width: 110, onItemClick: onSelectVessel }];
    var fleetBlock  = new Cary.ui.ControlBlock ({ parent: this.client, visible: true, anchor: Cary.ui.anchor.TOP, text: stringTable.fleet }, ctlBlkStyle);
    var fleetCtl    = new Cary.ui.ListBox ({ parent: fleetBlock.htmlObject, comboBox: true, visible: true, onItemSelect: onFleetSelect },
                                           { display: 'inline', float: 'right', width: 250, height: 25, 'margin-right': 20, padding: 0, 'font-size': 17 });
    var listHeight  = this.embedMode ? this.paneHeight - 70 : 270;
    var vesselList  = new Cary.ui.ListView ({ parent: this.client, columns: columns, visible: true, onItemClick: onSelectVessel },
                                             { position: 'absolute', top: 40, left: 5, width: 400, height: listHeight });
    var dataPane    = new DataPane ({}, { parent: this.client });

    fleetCtl.addItem (stringTable.allVessel, null);
    fleetCtl.setCurSel (0);
    
    fleets.forEach (function (fleet) { fleetCtl.addItem (fleet.name, fleet); });

    loadAllVessels ();
    
    if (this.embedMode)
        dataPane.hide ();
    else
        dataPane.show ();
    
    function loadAllVessels ()
    {
        fleets.enumVessels (addVesselItem);
    }
    
    function addVesselItem (vessel)
    {
        var lastReport;

        if (vessel.lastReport)
            lastReport = Cary.tools.formatDateHours (vessel.lastReport);
        else
            lastReport = stringTable.noData;

        vesselList.addItem ([vessel.name, Vessel.getTypeName (vessel.type), lastReport], vessel);
    }
    
    function onFleetSelect ()
    {
        var fleet = fleetCtl.getSelectedData ();
        
        vesselList.removeAllItems ();
        
        if (fleet)
            fleet.vessels.forEach (addVesselItem);
        else
            loadAllVessels ();
    }
    
    function removeOldTrack (curSelection)
    {
        var i, count;
        
        // Make sure that the only track is shown at the moment
        for (i = 0, count = vesselList.getItemCount (); i < count; ++ i)
        {
            if (i !== curSelection)
            {
                var curVessel =  vesselList.getItemData (i);

                if (curVessel.track)
                {
                    undrawTrack (curVessel.track, false);

                    curVessel.track = null;
                }
            }
        }
    }
    
    function onSwitchVesselTrack (row)
    {
        var vessel = vesselList.getItemData (row);

        removeOldTrack (row);
        
        vessel.track = new Track (vessel);

        vessel.track.load ({ begin: beginTime, end: endTime, onLoaded: onLoaded });

        function onLoaded ()
        {
            drawTrack (vessel.track, Track.modes.SIMPLIFIED);
            showTrack (vessel.track);
        }
    }
    
    function onSelectVessel (row, column, item)
    {
        var vessel = vesselList.getItemData (row);
        var now    = new Date ();

        document.getElementById ('fuelFrame').src = 'report/report.php?v=' + vessel.id.toString () + '&m=' + (now.getMonth () + 1).toString () +
                                                    '&y=' + now.getFullYear ().toString ();
    }
};

FleetPane.prototype.queryClose = function ()
{
    this.hide ();
    
    return false;
};

