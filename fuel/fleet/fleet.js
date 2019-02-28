var fleets = [];
var Fleets = { createFleets: function ()
                             {
                                 var result = [];
                                 
                                 result.enumVessels = Fleets.enumVessels;
                                 
                                 return result;
                             },
               enumVessels:  function (callback)
                             {
                                for (var i = 0; i < this.length; ++ i)
                                {
                                    var fleet = this [i];

                                    fleet.vessels.forEach (function (vessel)
                                                           {
                                                               if (callback)
                                                                   callback (vessel, fleet);
                                                           });
                                }
                             } 
             };
             
function Vessel ()
{
    this.drawObjects = [];
    
    /*this.imo        = null;
    this.code       = null;
    this.lat        = null;
    this.lon        = null;
    this.lastReport = null;*/
    
    Cary.Serializable.apply (this, arguments);
}

Vessel.prototype = Object.create (Cary.Serializable.prototype);

Vessel.prototype.keys = ['id', 'name', 'lat', 'lon', 'type', 'firstReport', 'lastReport', 'device', 'sensors'];

Vessel.getTypeName = function (type)
{
    var result;
    
    switch (type)
    {
        case 1:
            result = 'Земснаряд'; break;
            
        case 2:
            result = 'Обстан.судно'; break;
            
        case 3:
            result = 'Водолей'; break;
            
        case 4:
            result = 'Наливное судно'; break;
            
        case 5:
            result = 'Бункеровщик'; break;
            
        case 6:
            result = 'Гр./отв.судно'; break;
            
        case 7:
            result = 'Плавкран'; break;
            
        case 8:
            result = 'Мотозавозня'; break;
            
        case 9:
            result = 'Буксир'; break;
            
        case 10:
            result = 'Пасс.судно'; break;
            
        default:
            result = 'Неизв.тип';
    }
    
    return result;
};

function Fleet ()
{
    this.name    = null;
    this.id      = null;
    this.vessels = [];
    
    Cary.Serializable.apply (this, arguments);
}

Fleet.prototype = Object.create (Cary.Serializable.prototype);

Fleet.prototype.serialize = function ()
{
    var result = Cary.Serializable.prototype.serialize.apply (this);
    
    result.vessels = [];
    
    this.vessels.forEach (function (vessel)
                          {
                              result.vessels.push (vessel.serialize ());
                          });
                          
    return result;
};

Fleet.prototype.deserialize = function (source)
{
    Cary.Serializable.prototype.deserialize.apply (this, arguments);
    
    this.vessels = [];
    
    source.vessels.forEach (addVessel, this);
    
    function addVessel (vesselSource)
    {
        var vessel = new Vessel (vesselSource.name);
        
        vessel.deserialize (vesselSource);
        
        this.vessels.push (vessel);
    };
};
