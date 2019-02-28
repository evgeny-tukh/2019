<?php

    require_once ('../../db/database.php');

    set_time_limit (0);

    $layer = 35;

    $data = [[59+54.671/60.0,30+14.858/60.0],
             [59+54.667/60.0,30+14.715/60.0],
             [59+54.658/60.0,30+14.527/60.0],
             [59+54.648/60.0,30+14.406/60.0],
             [59+54.631/60.0,30+14.282/60.0],
             [59+54.593/60.0,30+14.183/60.0],
             [59+54.508/60.0,30+14.076/60.0],
             [59+54.409/60.0,30+13.945/60.0],
             [59+54.344/60.0,30+13.858/60.0],
             [59+54.263/60.0,30+13.752/60.0],
             [59+54.188/60.0,30+13.657/60.0],
             [59+54.064/60.0,30+13.484/60.0],
             [59+54.010/60.0,30+13.425/60.0],
             [59+53.910/60.0,30+13.123/60.0],
             [59+53.856/60.0,30+13.220/60.0],
             [59+53.774/60.0,30+13.131/60.0],
             [59+53.697/60.0,30+13.002/60.0],
             [59+53.583/60.0,30+12.852/60.0],
             [59+53.478/60.0,30+12.714/60.0],
             [59+53.382/60.0,30+12.691/60.0],
             [59+53.375/60.0,30+12.849/60.0],
             [59+53.454/60.0,30+12.949/60.0],
             [59+53.542/60.0,30+13.067/60.0],
             [59+53.571/60.0,30+13.157/60.0],
             [59+53.574/60.0,30+13.247/60.0],
             [59+53.668/60.0,30+13.373/60.0],
             [59+53.743/60.0,30+13.476/60.0],
             [59+53.829/60.0,30+13.590/60.0],
             [59+53.826/60.0,30+13.667/60.0],
             [59+53.788/60.0,30+13.716/60.0],
             [59+53.742/60.0,30+13.844/60.0],
             [59+53.666/60.0,30+13.905/60.0],
             [59+53.708/60.0,30+13.94/60.0 ],
             [59+53.524/60.0,30+13.716/60.0],
             [59+53.441/60.0,30+13.604/60.0],
             [59+53.335/60.0,30+13.513/60.0],
             [59+53.289/60.0,30+13.434/60.0],
             [59+53.199/60.0,30+15.559/60.0],
             [59+53.447/60.0,30+15.886/60.0],
             [59+53.516/60.0,30+13.974/60.0],
             [59+53.601/60.0,30+14.087/60.0],
             [59+53.548/60.0,30+14.346/60.0],
             [59+53.626/60.0,30+14.246/60.0],
             [59+53.483/60.0,30+14.266/60.0],
             [59+53.488/60.0,30+14.184/60.0],
             [59+53.343/60.0,30+14.083/60.0],
             [59+53.254/60.0,30+13.963/60.0],
             [59+52.719/60.0,30+13.074/60.0],
             [59+52.781/60.0,30+12.956/60.0],
             [59+52.853/60.0,30+12.819/60.0],
             [59+52.903/60.0,30+12.721/60.0],
             [59+52.973/60.0,30+12.597/60.0],
             [59+53.052/60.0,30+12.447/60.0],
             [59+53.123/60.0,30+12.317/60.0],
             [59+53.212/60.0,30+12.15/60.0 ],
             [59+53.220/60.0,30+12.093/60.0],
             [59+53.005/60.0,30+11.465/60.0],
             [59+52.967/60.0,30+11.646/60.0],
             [59+52.959/60.0,30+11.835/60.0],
             [59+52.931/60.0,30+11.982/60.0],
             [59+52.856/60.0,30+12.124/60.0],
             [59+52.779/60.0,30+12.270/60.0],
             [59+52.539/60.0,30+12.352/60.0],
             [59+52.484/60.0,30+12.669/60.0],
             [59+52.407/60.0,30+12.138/60.0],
             [59+52.398/60.0,30+12.289/60.0],
             [59+52.29/60.0, 30+12.489/60.0],
             [59+52.401/60.0,30+11.996/60.0],
             [59+52.466/60.0,30+11.874/60.0],
             [59+52.533/60.0,30+11.753/60.0],
             [59+52.618/60.0,30+11.551/60.0],
             [59+52.733/60.0,30+11.478/60.0],
             [59+52.83/60.0, 30+11.298/60.0],
             [59+52.959/60.0,30+10.974/60.0],
             [59+53.034/60.0,30+10.942/60.0],
             [59+53.142/60.0,30+10.265/60.0],
             [59+53.077/60.0,30+10.402/60.0],
             [59+53.360/60.0,30+10.023/60.0],
             [59+53.360/60.0,30+10.023/60.0],
             [59+55.207/60.0,30+15.285/60.0],
             [59+55.174/60.0,30+15.389/60.0],
             [59+55.116/60.0,30+15.667/60.0],
             [59+55.167/60.0,30+15.826/60.0],
             [59+55.177/60.0,30+15.894/60.0],
             [59+55.226/60.0,30+16.018/60.0],
             [59+55.348/60.0,30+16.106/60.0],
             [59+55.434/60.0,30+16.144/60.0],
             [59+55.533/60.0,30+16.182/60.0],
             [59+55.649/60.0,30+16.249/60.0],
             [59+54.738/60.0,30+14.100/60.0],
             [59+54.676/60.0,30+14.021/60.0],
             [59+54.608/60.0,30+13.933/60.0],
             [59+54.554/60.0,30+13.926/60.0],
             [59+54.479/60.0,30+13.886/60.0],
             [59+54.761/60.0,30+13.855/60.0],
             [59+54.703/60.0,30+13.835/60.0],
             [59+54.635/60.0,30+13.797/60.0],
             [59+54.573/60.0,30+13.710/60.0],
             [59+54.543/60.0,30+13.626/60.0],
             [59+54.501/60.0,30+13.591/60.0],
             [59+54.424/60.0,30+13.507/60.0],
             [59+54.819/60.0,30+13.991/60.0],
             [59+54.437/60.0,30+13.378/60.0],
             [59+54.817/60.0,30+14.153/60.0],
             [59+54.425/60.0,30+13.228/60.0],
             [59+54.663/60.0,30+13.371/60.0],
             [59+52.851/60.0,30+14.178/60.0],
             [59+52.858/60.0,30+14.095/60.0],
             [59+52.858/60.0,30+14.095/60.0],
             [59+52.846/60.0,30+14.028/60.0],
             [59+52.799/60.0,30+14.155/60.0],
             [59+52.739/60.0,30+14.322/60.0],
             [59+52.701/60.0,30+13.712/60.0],
             [59+52.632/60.0,30+13.607/60.0],
             [59+52.566/60.0,30+13.519/60.0],
             [59+52.548/60.0,30+13.412/60.0],
             [59+53.005/60.0,30+13.636/60.0],
             [59+53.025/60.0,30+13.691/60.0],
             [59+53.012/60.0,30+13.746/60.0],
             [59+53.045/60.0,30+13.969/60.0],
             [59+52.906/60.0,30+13.875/60.0],
             [59+52.882/60.0,30+14.131/60.0],
             [59+52.936/60.0,30+13.628/60.0],
             [59+52.951/60.0,30+13.740/60.0],
             [59+52.216/60.0,30+13.205/60.0],
             [59+52.282/60.0,30+13.041/60.0],
             [59+52.341/60.0,30+12.92/60.0 ],
             [59+52.091/60.0,30+13.511/60.0],
             [59+52.137/60.0,30+13.4/60.0  ],
             [59+52.181/60.0,30+13.294/60.0],
             [59+51.973/60.0,30+13.815/60.0],
             [59+52.053/60.0,30+13.617/60.0],
             [59+52.362/60.0,30+13.176/60.0],
             [59+52.395/60.0,30+13.102/60.0],
             [59+52.431/60.0,30+12.975/60.0],
             [59+52.448/60.0,30+12.817/60.0],
             [59+52.092/60.0,30+13.164/60.0],
             [59+52.129/60.0,30+13.023/60.0],
             [59+52.162/60.0,30+12.901/60.0],
             [59+52.193/60.0,30+12.784/60.0],
             [59+52.223/60.0,30+12.68/60.0 ],
             [59+52.253/60.0,30+12.58/60.0 ],
             [59+55.965/60.0,30+17.326/60.0],
             [59+55.965/60.0,30+17.326/60.0],
             [59+55.965/60.0,30+17.326/60.0],
             [59+55.929/60.0,30+16.655/60.0],
             [59+55.929/60.0,30+16.655/60.0],
             [59+55.643/60.0,30+14.332/60.0],
             [59+55.566/60.0,30+14.139/60.0],
             [59+55.595/60.0,30+14.037/60.0],
             [59+55.613/60.0,30+14.025/60.0],
             [59+55.478/60.0,30+14.359/60.0],
             [59+55.626/60.0,30+14.162/60.0],
             [59+58.138/60.0,30+13.469/60.0],
             [60+00.310/60.0,29+42.873/60.0],
             [60+00.322/60.0,29+43.003/60.0],
             [60+00.068/60.0,29+42.572/60.0],
             [60+00.210/60.0,29+42.740/60.0],
             [59+59.655/60.0,29+42.552/60.0],
             [59+59.600/60.0,29+42.548/60.0],
             [59+59.572/60.0,29+42.268/60.0],
             [59+55.524/60.0,29+46.231/60.0],
             [59+55.625/60.0,29+46.367/60.0],
             [59+55.651/60.0,29+46.449/60.0],
             [59+55.662/60.0,29+46.377/60.0],
             [59+55.723/60.0,29+46.402/60.0],
             [59+55.770/60.0,29+46.608/60.0],
             [59+59.605/60.0,29+42.063/60.0],
             [60+01.522/60.0,29+50.235/60.0]];

    $db  = new Database ();
    $cnt = 100;

    foreach ($data as $item)
    {
        $db->execute ("insert into objects(name,type,userType,layer) values('point$cnt',4,null,$layer)");

        $objID = $db->insertID ();
        $lat   = $item [0];
        $lon   = $item [1];

        $db->execute ("insert into object_props(object,name,value) values($objID,'path','res/settings26.png')");
        $db->execute ("insert into object_props(object,name,value) values($objID,'lat','$lat')");
        $db->execute ("insert into object_props(object,name,value) values($objID,'lon','$lon')");

        $cnt ++;
    }

    $db->close ();


                   