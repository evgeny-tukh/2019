function Track (vessel, begin, end)
{
    if (Cary.tools.isNothing (end))
        end = new Date ().getTime ();
    
    this.begin       = begin;
    this.end         = end;
    this.vessel      = vessel;
    this.points      = [];
    this.drawObjects = [];
}

Track.modes = { SIMPLIFIED: 0, FULL: 1 };

Track.prototype.load = function (desc)
{
    var instance = this;
    var onLoaded = 'onLoaded' in desc ? desc.onLoaded : null;
    var begin    = 'begin' in desc ? desc.begin : 0;
    var end      = 'end' in desc ? desc.end : Cary.tools.getTimestamp ();
    
    loadSerializable ('get_track.php?v=' + this.vessel.id.toString () + '&b=' + begin.toString () + '&e=' + end.toString (), onTrackLoaded);
    
    function onTrackLoaded (trackData)
    {
        instance.points = [];
        
        trackData.data.forEach (function (trackPoint)
                                {
                                    instance.points.push (trackPoint);
                                }, instance);
                                
        if (!Cary.tools.isNothing (onLoaded))
            onLoaded (instance);
    }
};

Track.prototype.enumLegs = function (callback, enumAll)
{
    for (var i = 1; i < this.points.length; ++ i)
    {
        if (!enumAll && !callback (this.points [i-1], this.points [i]))
            break;
    }
};

Track.prototype.crossesContour = function (points)
{
    var crossed = false;
    
    for (var i = 1; !crossed && i < points.length; ++ i)
    {
        var begin = points [i-1];
        var end   = points [i];
        
        this.enumLegs (checkLeg, false);
    
        function checkLeg (legBegin, legEnd)
        {
            result = Cary.geo.legCrossesLeg (begin, end, legBegin, legEnd);
            
            if (result.cross)
                crossed = true;
            
            return !crossed;
        }
    }
    
    return crossed;
};
