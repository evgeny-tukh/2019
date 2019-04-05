<?php

    $guiCfg = [];

    $guiCfg ['fms']  = [ 'link' => '2019/fms',
                         'buttons' => [ [ 'link' => 'fms.php?l=2019/fuel&c=fuel', 'name' => 'Топливо', 'icon' => 'oil32', 'active' => 1 ],
                                        [ 'link' => 'under_dev.php', 'name' => 'Ремонт', 'icon' => 'maint32', 'active' => 0 ],
                                        [ 'link' => 'under_dev.php', 'name' => 'Экипажи', 'icon' => 'crew32', 'active' => 0 ],
                                        [ 'link' => 'under_dev.php', 'name' => 'Эффективность', 'icon' => 'bigthumb32', 'active' => 0 ] ] ];

    $guiCfg ['fuel'] = [ 'link' => '2019/fuel',
                         'buttons' => [ [ 'link' => 'fms.php?l=2019/fms&c=fms', 'name' => 'Флот', 'icon' => 'tug32', 'active' => 1 ],
                                        [ 'link' => 'under_dev.php', 'name' => 'Ремонт', 'icon' => 'maint32', 'active' => 1 ],
                                        [ 'link' => 'under_dev.php', 'name' => 'Экипажи', 'icon' => 'crew32', 'active' => 1 ],
                                        [ 'link' => 'under_dev.php', 'name' => 'Эффективность', 'icon' => 'bigthumb32', 'active' => 1 ] ] ];
