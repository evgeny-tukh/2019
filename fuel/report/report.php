<?php
    require_once '../session/session_mgr.php';
    require_once '../db/database.php';
    require_once '../util/util.php';

    $sessionMgr = new SessionManager ();

    $curTime = time ();
    
    /*if (!$sessionMgr->isAuthenticated () || $sessionMgr->isSessionExpired ())
    {
        include ('../login.html');
    }
    else
    {*/
        $vesselID = getArrayValue ($_REQUEST, 'v', NULL);
        $month    = getArrayValue ($_REQUEST, 'm', NULL);
        $year     = getArrayValue ($_REQUEST, 'y', NULL);

        if (!$vesselID)
            die ('Vessel ID not present');

        if (!$month || $month < 1 || $month > 12)
            die ('Month not present');

        if (!$year || $year < 2018)
            die ('Year not present');

        $vesselID = intval ($vesselID);
        $month    = intval ($month);
        $year     = intval ($year);

        $data = [ 'vesselID' => $vesselID, 'year' => $year, 'month' => $month ];

        $database = new Database ();

        if ($database)
        {
            $cb = function ($row) use (&$data)
                  {
                      $data ['vesselName'] = $row ['vnm'];
                      $data ['ownerName']  = $row ['onm'];
                  };

            $database->processResult ("select v.name vnm,o.name onm from vessels v left join ship_owners o on v.owner=o.id where v.id=$vesselID", $cb);
            $database->close ();
        }

        $sessionMgr->setAccessTime ();
        
        $features = $sessionMgr->getUserFeatures ();
        ?>

        <!DOCTYPE html>
        <html>
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>
                    Report
                </title>
                <link rel="stylesheet" href="report.css"/>
                <script>
                    var month = <?php echo $month; ?>, year = <?php echo $year; ?>;
                </script>
                <script src="./report.js"></script>
            </head>
            <body onload="init ();">
                <table cellpadding="5" cellspacing="0" cols="10" border="1" bordercolor="gray" rules="all" width="800">
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="f20 bold cntTxt" colspan="10">
                            МЕСЯЧНЫЙ ОТЧЕТ
                            <button class="save f14" id="save">Сохранить</button>
                        </td>
                    </tr>
                    <tr>
                        <td class="rgtTxt bold" colspan="4">
                            по топливу и смазке за
                        </td>
                        <td class="cntTxt bold" colspan="3">
                            <select size="1" id="month" class="sel f14">
                                <option <?php selectedAttr (1, $month); ?>>Январь</option>
                                <option <?php selectedAttr (2, $month); ?>>Февраль</option>
                                <option <?php selectedAttr (3, $month); ?>>Март</option>
                                <option <?php selectedAttr (4, $month); ?>>Апрель</option>
                                <option <?php selectedAttr (5, $month); ?>>Май</option>
                                <option <?php selectedAttr (6, $month); ?>>Июнь</option>
                                <option <?php selectedAttr (7, $month); ?>>Июль</option>
                                <option <?php selectedAttr (8, $month); ?>>Август</option>
                                <option <?php selectedAttr (9, $month); ?>>Сентябрь</option>
                                <option <?php selectedAttr (10, $month); ?>>Октябрь</option>
                                <option <?php selectedAttr (11, $month); ?>>Ноябрь</option>
                                <option <?php selectedAttr (12, $month); ?>>Декабрь</option>
                            </select>
                        </td>
                        <td class="lftTxt bold" colspan="3">
                            месяц
                            <select size="1" id="year" class="sel f14" style="margin-left: 5px;">
                                <option <?php selectedAttr (2018, $year); ?>>2018</option>
                                <option <?php selectedAttr (2019, $year); ?>>2019</option>
                                <option <?php selectedAttr (2020, $year); ?>>2020</option>
                                <option <?php selectedAttr (2021, $year); ?>>2021</option>
                                <option <?php selectedAttr (2022, $year); ?>>2022</option>
                                <option <?php selectedAttr (2023, $year); ?>>2023</option>
                                <option <?php selectedAttr (2024, $year); ?>>2024</option>
                                <option <?php selectedAttr (2025, $year); ?>>2025</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="cntTxt bold" colspan="3">
                            Теплоход
                        </td>
                        <td id="ship" class="cntTxt bold f18 field" colspan="7">
                            <?php echo $data ['vesselName']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="cntTxt bold" colspan="3">
                            Владелец
                        </td>
                        <td id="owner" class="cntTxt bold f15 field" colspan="7">
                            <?php echo $data ['ownerName']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                        </td>
                        <td class="cntTxt bold f14" colspan="4">
                             1. Баланс топлива (в кг)
                        </td>
                        <td colspan="3">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="10">
                        </td>
                    </tr>
                    <tr>
                        <td class="cntTxt f12 bold" colspan="2">
                            Наименование топлива
                        </td>
                        <td class="cntTxt f12 bold" colspan="2">
                            Наличие на 1е число
                        </td>
                        <td class="cntTxt f12 bold" colspan="2">
                            Получено за отчетный месяц
                        </td>
                        <td class="cntTxt f12 bold" colspan="1">
                            Выдано
                        </td>
                        <td class="cntTxt f12 bold" colspan="2">
                            Израсходовано натурального топлива
                        </td>
                        <td class="cntTxt f12 bold" colspan="1">
                            Остаток на 1е число сл.месяца
                        </td>
                    </tr>
                    <tr>
                        <td class="cntTxt f11" colspan="2">
                            ТБД вид 2
                        </td>
                        <td id="type2beginAmount" class="cntTxt f15" colspan="2">
                            16451
                        </td>
                        <td id="type2received" class="cntTxt f15" colspan="2">
                        </td>
                        <td id="type2withdrawn" class="cntTxt f15" colspan="1">
                        </td>
                        <td id="type2used" class="cntTxt f15" colspan="2">
                            5778
                        </td>
                        <td id="type2endAmount" class="cntTxt f15" colspan="1">
                            10673
                        </td>
                    </tr>
                    <tr>
                        <td class="cntTxt f11" colspan="2">
                            Судовое топливо (средний дистиллят)
                        </td>
                        <td id="distBegAmount" class="cntTxt f15" colspan="2">
                            0
                        </td>
                        <td id="distReceived" class="cntTxt f15" colspan="2">
                        </td>
                        <td id="distWithdrawn" class="cntTxt f15" colspan="1">
                        </td>
                        <td id="distUsed" class="cntTxt f15" colspan="2">
                        </td>
                        <td id="distEndAmount" class="cntTxt f15" colspan="1">
                            0
                        </td>
                    </tr>
                    <tr>
                        <td class="cntTxt f11" colspan="2">
                            ИТОГО
                        </td>
                        <td id="totalBegAmount" class="cntTxt f15 bold" colspan="2">
                            16451
                        </td>
                        <td id="totalReceived" class="cntTxt f15 bold" colspan="2">
                            0
                        </td>
                        <td id="totalWithdrawn" class="cntTxt f15 bold" colspan="1">
                            0
                        </td>
                        <td id="totalUsed" class="cntTxt f15 bold" colspan="2">
                            5778
                        </td>
                        <td id="totalEndAmount" class="cntTxt f15 bold" colspan="1">
                            10673
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                        </td>
                        <td class="cntTxt bold f14" colspan="6">
                             2. Результат топливоиспользования
                        </td>
                        <td colspan="2">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="10">
                        </td>
                    </tr>
                    <tr>
                        <td class="cntTxt bold f12" colspan="3">
                            Фактически израсходовано
                        </td>
                        <td id="actuallyUsed" class="cntTxt f15 field" colspan="2">
                            5778
                        </td>
                        <td class="lftTxt bold f12" colspan="1">
                            кг
                        </td>
                        <td class="cntTxt bold f12" colspan="1">
                            Экономия
                        </td>
                        <td id="economy" class="cntTxt f14 field" colspan="2">
                        </td>
                        <td class="cntTxt bold f12" colspan="1">
                            кг
                        </td>
                    </tr>
                    <tr>
                        <td class="cntTxt bold f12" colspan="3">
                            Расход по норме
                        </td>
                        <td id="consumptionNorm" class="cntTxt f14 field" colspan="2">
                            5778
                        </td>
                        <td class="lftTxt bold f12" colspan="1">
                            кг
                        </td>
                        <td class="cntTxt bold f12" colspan="1">
                            Пережог
                        </td>
                        <td id="usedOver" class="cntTxt f14 field" colspan="2">
                        </td>
                        <td class="cntTxt bold f12" colspan="1">
                            кг
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                        </td>
                        <td class="cntTxt bold f14" colspan="6">
                             3. Выполнение норм расхода топлива
                        </td>
                        <td colspan="2">
                        </td>
                    </tr>
                    <tr>
                        <td class="cntTxt bold f12" colspan="3">
                            Виды работ судна
                        </td>
                        <td class="cntTxt bold f12" colspan="2">
                            Фактическое время работы в часах
                        </td>
                        <td class="lftTxt bold f12" colspan="1">
                            Норма топлива на час работы судна
                        </td>
                        <td class="cntTxt bold f12" colspan="1">
                            Расход топлива по норме, кг
                        </td>
                        <td class="cntTxt bold f12" colspan="1">
                            Фактический расход топлива, кг
                        </td>
                        <td class="cntTxt bold f12" colspan="1">
                            Экономия/пережог
                        </td>
                        <td class="cntTxt bold f12" colspan="1">
                            Примечание
                        </td>
                    </tr>
                    <tr>
                        <td class="cntTxt bold f12" colspan="10">
                            Для земснарядов
                        </td>
                    </tr>
                    <tr>
                        <td class="cntTxt f11 bold" colspan="3">
                            Технологический режим
                        </td>
                        <td id="workingTime_techMode" class="cntTxt f14 field" colspan="2">
                        </td>
                        <td id="hourNorm_techMode" class="lftTxt f14 field" colspan="1">
                        </td>
                        <td id="consNorm_techMode" class="cntTxt f14 field" colspan="1">
                        </td>
                        <td id="consActual_techMode" class="cntTxt f14 field" colspan="1">
                        </td>
                        <td id="econOverUse_techMode" class="cntTxt f14 field" colspan="1">
                        </td>
                        <td id="notes_techMode" class="cntTxt f14 field" colspan="1">
                        </td>
                    </tr>
                    <tr>
                        <td class="cntTxt f11 bold" colspan="3">
                            На гребные винты
                        </td>
                        <td id="workingTime_propulsors" class="cntTxt f14 field" colspan="2">
                        </td>
                        <td id="hourNorm_propulsors" class="lftTxt f14 field" colspan="1">
                        </td>
                        <td id="consNorm_propulsors" class="cntTxt f14 field" colspan="1">
                        </td>
                        <td id="consActual_propulsors" class="cntTxt f14 field" colspan="1">
                        </td>
                        <td id="econOverUse_propulsors" class="cntTxt f14 field" colspan="1">
                        </td>
                        <td id="notes_propulsors" class="cntTxt f14 field" colspan="1">
                        </td>
                    </tr>
                    <tr>
                        <td class="cntTxt f11 bold" colspan="3">
                            Стоянка (лето)
                        </td>
                        <td id="workingTime_parkSummer" class="cntTxt f14 field" colspan="2">
                        </td>
                        <td id="hourNorm_parkSummer" class="lftTxt f14 field" colspan="1">
                        </td>
                        <td id="consNorm_parkSummer" class="cntTxt f14 field" colspan="1">
                        </td>
                        <td id="consActual_parkSummer" class="cntTxt f14 field" colspan="1">
                        </td>
                        <td id="econOverUse_parkSummer" class="cntTxt f14 field" colspan="1">
                        </td>
                        <td id="notes_parkSummer" class="cntTxt f14 field" colspan="1">
                        </td>
                    </tr>
                    <tr>
                        <td class="cntTxt f11 bold" colspan="3">
                            Стоянка (осень)
                        </td>
                        <td id="workingTime_parkAutumn" class="cntTxt f14 field" colspan="2">
                        </td>
                        <td id="hourNorm_parkAutumn" class="lftTxt f14 field" colspan="1">
                        </td>
                        <td id="consNorm_parkAutumn" class="cntTxt f14 field" colspan="1">
                        </td>
                        <td id="consActual_parkAutumn" class="cntTxt f14 field" colspan="1">
                        </td>
                        <td id="econOverUse_parkAutumn" class="cntTxt f14 field" colspan="1">
                        </td>
                        <td id="notes_parkAutumn" class="cntTxt f14 field" colspan="1">
                        </td>
                    </tr>
                    <tr>
                        <td class="cntTxt f11 bold" colspan="3">
                            Стоянка
                        </td>
                        <td id="workingTime_park" class="cntTxt f14 field" colspan="2">
                        </td>
                        <td id="hourNorm_park" class="lftTxt f14 field" colspan="1">
                        </td>
                        <td id="consNorm_park" class="cntTxt f14 field" colspan="1">
                        </td>
                        <td id="consActual_park" class="cntTxt f14 field" colspan="1">
                        </td>
                        <td id="econOverUse_park" class="cntTxt f14 field" colspan="1">
                        </td>
                        <td id="notes_park" class="cntTxt f14 field" colspan="1">
                        </td>
                    </tr>
                    <tr>
                        <td class="cntTxt bold f12" colspan="10">
                            Для теплоходов
                        </td>
                    </tr>
                    <tr>
                        <td class="cntTxt bold f12" colspan="3">
                            Ход порожнем
                        </td>
                        <td id="workingTime_lightRun" class="cntTxt f14 field" colspan="2">
                        </td>
                        <td id="hourNorm_lightRun" class="lftTxt f14 field" colspan="1">
                        </td>
                        <td id="consNorm_lightRun" class="cntTxt f14 field" colspan="1">
                        </td>
                        <td id="consActual_lightRun" class="cntTxt f14 field" colspan="1">
                        </td>
                        <td id="econOverUse_lightRun" class="cntTxt f14 field" colspan="1">
                        </td>
                        <td id="notes_lightRun" class="cntTxt f14 field" colspan="1">
                        </td>
                    </tr>
                    <tr>
                        <td class="cntTxt bold f12" colspan="3">
                            Буксировка
                        </td>
                        <td id="workingTime_towing" class="cntTxt f14 field" colspan="2">
                        </td>
                        <td id="hourNorm_towing" class="lftTxt f14 field" colspan="1">
                        </td>
                        <td id="consNorm_towing" class="cntTxt f14 field" colspan="1">
                        </td>
                        <td id="consActual_towing" class="cntTxt f14 field" colspan="1">
                        </td>
                        <td id="econOverUse_towing" class="cntTxt f14 field" colspan="1">
                        </td>
                        <td id="notes_towing" class="cntTxt f14 field" colspan="1">
                        </td>
                    </tr>
                    <tr>
                        <td class="cntTxt bold f12" colspan="3">
                            Маневры
                        </td>
                        <td id="workingTime_maneuvers" class="cntTxt f14 field" colspan="2">
                        </td>
                        <td id="hourNorm_maneuvers" class="lftTxt f14 field" colspan="1">
                        </td>
                        <td id="consNorm_maneuvers" class="cntTxt f14 field" colspan="1">
                        </td>
                        <td id="consActual_maneuvers" class="cntTxt f14 field" colspan="1">
                        </td>
                        <td id="econOverUse_maneuvers" class="cntTxt f14 field" colspan="1">
                        </td>
                        <td id="notes_maneuvers" class="cntTxt f14 field" colspan="1">
                        </td>
                    </tr>
                    <tr>
                        <td class="cntTxt bold f12" colspan="3">
                            Работа котла
                        </td>
                        <td id="workingTime_boilerWork" class="cntTxt f14 field" colspan="2">
                        </td>
                        <td id="hourNorm_boilerWork" class="lftTxt f14 field" colspan="1">
                        </td>
                        <td id="consNorm_boilerWork" class="cntTxt f14 field" colspan="1">
                        </td>
                        <td id="consActual_boilerWork" class="cntTxt f14 field" colspan="1">
                        </td>
                        <td id="econOverUse_boilerWork" class="cntTxt f14 field" colspan="1">
                        </td>
                        <td id="notes_boilerWork" class="cntTxt f14 field" colspan="1">
                        </td>
                    </tr>
                    <tr>
                        <td class="cntTxt bold f120" colspan="3">
                            Работа вспомогательного двигателя
                        </td>
                        <td id="workingTime_servEngWork" class="cntTxt f14 field" colspan="2">
                        </td>
                        <td id="hourNorm_servEngWork" class="lftTxt f14 field" colspan="1">
                        </td>
                        <td id="consNorm_servEngWork" class="cntTxt f14 field" colspan="1">
                        </td>
                        <td id="consActual_servEngWork" class="cntTxt f14 field" colspan="1">
                        </td>
                        <td id="econOverUse_servEngWork" class="cntTxt f14 field" colspan="1">
                        </td>
                        <td id="notes_servEngWork" class="cntTxt f14 field" colspan="1">
                        </td>
                    </tr>
                    <tr>
                        <td class="cntTxt bold f12" colspan="3">
                            Итого
                        </td>
                        <td id="workingTime_total" class="cntTxt f14 field" colspan="2">
                        </td>
                        <td id="hourNorm_total" class="lftTxt f14 field" colspan="1">
                        </td>
                        <td id="consNorm_total" class="cntTxt f14 field" colspan="1">
                        </td>
                        <td id="consActual_total" class="cntTxt f14 field" colspan="1">
                        </td>
                        <td id="econOverUse_total" class="cntTxt f14 field" colspan="1">
                        </td>
                        <td id="notes_total" class="cntTxt f14 field" colspan="1">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="10">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                        </td>
                        <td class="cntTxt bold f14" colspan="4">
                             4. Получение топлива
                        </td>
                        <td colspan="3">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="10">
                        </td>
                    </tr>
                    <tr>
                        <td class="cntTxt bold f12" colspan="1">
                            Дата
                        </td>
                        <td class="cntTxt bold f12" colspan="2">
                            Место бункировки, бункировщик
                        </td>
                        <td class="lftTxt bold f12" colspan="4">
                            Название, марка топлива, краткая характеристика 
                        </td>
                        <td class="cntTxt bold f12" colspan="1">
                            Получено топлива, т
                        </td>
                        <td class="cntTxt bold f12" colspan="1">
                            № бункерной накладной
                        </td>
                        <td class="cntTxt bold f12" colspan="1">
                            Примечание
                        </td>
                    </tr>
                    <tr>
                        <td class="cntTxt" id="date1" class="cntTxt f12 field" colspan="1">
                            . .
                        </td>
                        <td class="cntTxt" id="placeTankerName1" class="cntTxt f12 field" colspan="2">
                        </td>
                        <td class="lftTxt" id="fuel1" class="cntTxt f12 field" colspan="4">
                        </td>
                        <td class="cntTxt" id="recvAmount1" class="cntTxt f12 field" colspan="1">
                        </td>
                        <td class="cntTxt" id="docNumber1" class="cntTxt f12 field" colspan="1">
                        </td>
                        <td class="cntTxt" id="note1" class="cntTxt f12 field" colspan="1">
                        </td>
                    </tr>
                    <tr>
                        <td class="cntTxt" id="date2" class="cntTxt f12 field" colspan="1">
                            . .
                        </td>
                        <td class="cntTxt" id="placeTankerName2" class="cntTxt f12 field" colspan="2">
                        </td>
                        <td class="lftTxt" id="fuel2" class="cntTxt f12 field" colspan="4">
                        </td>
                        <td class="cntTxt" id="recvAmount2" class="cntTxt f12 field" colspan="1">
                        </td>
                        <td class="cntTxt" id="docNumber2" class="cntTxt f12 field" colspan="1">
                        </td>
                        <td class="cntTxt" id="note2" class="cntTxt f12 field" colspan="1">
                        </td>
                    </tr>
                    <tr>
                        <td class="cntTxt" id="date3" class="cntTxt f12 field" colspan="1">
                            . .
                        </td>
                        <td class="cntTxt" id="placeTankerName3" class="cntTxt f12 field" colspan="2">
                        </td>
                        <td class="lftTxt" id="fuel3" class="cntTxt f12 field" colspan="4">
                        </td>
                        <td class="cntTxt" id="recvAmount3" class="cntTxt f12 field" colspan="1">
                        </td>
                        <td class="cntTxt" id="docNumber3" class="cntTxt f12 field" colspan="1">
                        </td>
                        <td class="cntTxt" id="note3" class="cntTxt f12 field" colspan="1">
                        </td>
                    </tr>
                    <tr>
                        <td class="cntTxt" id="date4" class="cntTxt f12 field" colspan="1">
                            . .
                        </td>
                        <td class="cntTxt" id="placeTankerName4" class="cntTxt f12 field" colspan="2">
                        </td>
                        <td class="lftTxt" id="fuel4" class="cntTxt f12 field" colspan="4">
                        </td>
                        <td class="cntTxt" id="recvAmount4" class="cntTxt f12 field" colspan="1">
                        </td>
                        <td class="cntTxt" id="docNumber4" class="cntTxt f12 field" colspan="1">
                        </td>
                        <td class="cntTxt" id="note4" class="cntTxt f12 field" colspan="1">
                        </td>
                    </tr>
                    <tr>
                        <td class="cntTxt" id="date5" class="cntTxt f12 field" colspan="1">
                            . .
                        </td>
                        <td class="cntTxt" id="placeTankerName5" class="cntTxt f12 field" colspan="2">
                        </td>
                        <td class="lftTxt" id="fuel5" class="cntTxt f12 field" colspan="4">
                        </td>
                        <td class="cntTxt" id="recvAmount5" class="cntTxt f12 field" colspan="1">
                        </td>
                        <td class="cntTxt" id="docNumber5" class="cntTxt f12 field" colspan="1">
                        </td>
                        <td class="cntTxt" id="note5" class="cntTxt f12 field" colspan="1">
                        </td>
                    </tr>
                    <tr>
                        <td class="cntTxt" id="date6" class="cntTxt f12 field" colspan="1">
                            . .
                        </td>
                        <td class="cntTxt" id="placeTankerName6" class="cntTxt f12 field" colspan="2">
                        </td>
                        <td class="lftTxt" id="fuel6" class="cntTxt f12 field" colspan="4">
                        </td>
                        <td class="cntTxt" id="recvAmount6" class="cntTxt f12 field" colspan="1">
                        </td>
                        <td class="cntTxt" id="docNumber6" class="cntTxt f12 field" colspan="1">
                        </td>
                        <td class="cntTxt" id="note6" class="cntTxt f12 field" colspan="1">
                        </td>
                    </tr>
                    <tr>
                        <td class="cntTxt" id="date7" class="cntTxt f12 field" colspan="1">
                            . .
                        </td>
                        <td class="cntTxt" id="placeTankerName7" class="cntTxt f12 field" colspan="2">
                        </td>
                        <td class="lftTxt" id="fuel7" class="cntTxt f12 field" colspan="4">
                        </td>
                        <td class="cntTxt" id="recvAmount7" class="cntTxt f12 field" colspan="1">
                        </td>
                        <td class="cntTxt" id="docNumber7" class="cntTxt f12 field" colspan="1">
                        </td>
                        <td class="cntTxt" id="note7" class="cntTxt f12 field" colspan="1">
                        </td>
                    </tr>
                    <tr>
                        <td class="cntTxt" id="date8" class="cntTxt f12 field" colspan="1">
                            . .
                        </td>
                        <td class="cntTxt" id="placeTankerName8" class="cntTxt f12 field" colspan="2">
                        </td>
                        <td class="lftTxt" id="fuel8" class="cntTxt f12 field" colspan="4">
                        </td>
                        <td class="cntTxt" id="recvAmount8" class="cntTxt f12 field" colspan="1">
                        </td>
                        <td class="cntTxt" id="docNumber8" class="cntTxt f12 field" colspan="1">
                        </td>
                        <td class="cntTxt" id="note8" class="cntTxt f12 field" colspan="1">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="10">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                        </td>
                        <td class="cntTxt bold f14" colspan="4">
                             5. Расход смазочных масел
                        </td>
                        <td colspan="3">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="10">
                        </td>
                    </tr>
                    <tr>
                        <td class="cntTxt bold f12" colspan="2">
                            Наименование масел, марка
                        </td>
                        <td class="cntTxt bold f12" colspan="2">
                            Наличие на 1е число
                        </td>
                        <td class="lftTxt bold f12" colspan="1">
                            Получено
                        </td>
                        <td class="cntTxt bold f12" colspan="1">
                            Выдано
                        </td>
                        <td class="cntTxt bold f12" colspan="1">
                            Израсходовано
                        </td>
                        <td class="cntTxt bold f12" colspan="1">
                            Остаток
                        </td>
                        <td class="cntTxt bold f12" colspan="1">
                            Экспл.норма от расхода топлива, %
                        </td>
                        <td class="cntTxt bold f12" colspan="1">
                            Факт.расход от расхода топлива, %
                        </td>
                    </tr>
                    <tr>
                        <td class="cntTxt bold f12" colspan="2">
                            Дизельное М-16Г2ЦС
                        </td>
                        <td id="mdo_beginAmount" class="cntTxt f14 field" colspan="2">
                            470
                        </td>
                        <td id="mdo_received" class="lftTxt f14 field" colspan="1">
                        </td>
                        <td id="mdo_withdrawn" class="cntTxt f14 field" colspan="1">
                        </td>
                        <td id="mdo_used" class="cntTxt f14 field" colspan="1">
                            14
                        </td>
                        <td id="mdo_rest" class="cntTxt f14 field" colspan="1">
                            456
                        </td>
                        <td id="mdo_explConsRate" class="cntTxt f14 field" colspan="1">
                            3.0
                        </td>
                        <td id="mdo_actualConsRate" class="cntTxt f14 field" colspan="1">
                            0.24
                        </td>
                    </tr>
                    <tr>
                        <td class="cntTxt bold f12" colspan="2">
                            Литол
                        </td>
                        <td id="litol_beginAmount" class="cntTxt f14 field" colspan="2">
                        </td>
                        <td id="litol_received" class="lftTxt f14 field" colspan="1">
                        </td>
                        <td id="litol_withdrawn" class="cntTxt f14 field" colspan="1">
                        </td>
                        <td id="litol_used" class="cntTxt f14 field" colspan="1">
                        </td>
                        <td id="litol_rest" class="cntTxt f14 field" colspan="1">
                            0
                        </td>
                        <td id="litol_explConsRate" class="cntTxt f14 field" colspan="1">
                        </td>
                        <td id="litol_actualConsRate" class="cntTxt f14 field" colspan="1">
                        </td>
                    </tr>
                    <tr>
                        <td class="cntTxt bold f12" colspan="2">
                            Солидол
                        </td>
                        <td id="solidol_beginAmount" class="cntTxt f14 field" colspan="2">
                        </td>
                        <td id="solidol_received" class="lftTxt f14 field" colspan="1">
                        </td>
                        <td id="solidol_withdrawn" class="cntTxt f14 field" colspan="1">
                        </td>
                        <td id="solidol_used" class="cntTxt f14 field" colspan="1">
                        </td>
                        <td id="solidol_rest" class="cntTxt f14 field" colspan="1">
                            0
                        </td>
                        <td id="solidol_explConsRate" class="cntTxt f14 field" colspan="1">
                        </td>
                        <td id="solidol_actualConsRate" class="cntTxt f14 field" colspan="1">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="10">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                        </td>
                        <td class="cntTxt bold f12" colspan="6">
                             Отметки о смене масла в главных двигателях
                        </td>
                        <td colspan="2">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="10">
                        </td>
                    </tr>
                    <tr>
                        <td class="cntTxt bold f12" colspan="1">
                            Дата
                        </td>
                        <td id="oilChangeDate" class="cntTxt f14 field" colspan="3">
                        </td>
                        <td colspan="6">
                        </td>
                    </tr>
                    <tr>
                        <td class="lftTxt bold f12" colspan="8">
                            Число часов работы гл. двигателя без смены масла
                        </td>
                        <td id="mainEngWithSameOilHrs" class="cntTxt f14 field" colspan="2">
                        </td>
                    </tr>
                    <tr>
                        <td class="lftTxt bold f12" colspan="8">
                            Количество масла в кг. при полной его замене
                        </td>
                        <td id="oilChangeAmount" class="cntTxt f14 field" colspan="2">
                            180
                        </td>
                    </tr>
                    <tr>
                        <td id="oilChangeReason" class="lftTxt bold f12" colspan="5">
                            Причина смены масла и перерасхода
                        </td>
                        <td class="cntTxt f14 field" colspan="5">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="10">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                        </td>
                        <td class="cntTxt bold f14" colspan="6">
                             6. Сведения о работе главных двигателей
                        </td>
                        <td colspan="2">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="10">
                        </td>
                    </tr>
                    <tr>
                        <td class="cntTxt bold f12" colspan="2" rowspan="2">
                            Марка двигателя
                        </td>
                        <td class="cntTxt bold f12" colspan="4" rowspan="2">
                            Полный номер двигателя
                        </td>
                        <td class="cntTxt bold f12" colspan="3">
                            Отработано часов двигателями
                        </td>
                        <td class="cntTxt bold f12" colspan="1" rowspan="2">
                            Примечания
                        </td>
                    </tr>
                    <tr>
                        <td class="cntTxt bold f12" colspan="1">
                            за отчетный месяц
                        </td>
                        <td class="cntTxt bold f12" colspan="1">
                            с начала навигации
                        </td>
                        <td class="cntTxt bold f12" colspan="1">
                            с начала эксплутатации
                        </td>
                    </tr>
                        <td id="engType1" class="cntTxt f12 field" colspan="2">
                            DH17000155
                        </td>
                        <td id="engNumber1" class="cntTxt f12 field" colspan="4">
                            1714С000809 (лев)
                        </td>
                        <td id="engRunningHrsMonth1" class="cntTxt f12 field" colspan="1">
                            64
                        </td>
                        <td id="engRunningHrsSeason1" class="cntTxt f12 field" colspan="1">
                            242
                        </td>
                        <td id="engRunningHrsTotal1" class="cntTxt f12 field" colspan="1">
                            242
                        </td>
                        <td id="engNotes1" class="cntTxt f12 field" colspan="1">
                        </td>
                    </tr>
                    <tr>
                        <td id="engType2" class="cntTxt f12 field" colspan="2">
                            DH17000155
                        </td>
                        <td id="engNumber2" class="cntTxt f12 field" colspan="4">
                            1714С000809 (прав)
                        </td>
                        <td id="engRunningHrsMonth2" class="cntTxt f12 field" colspan="1">
                            64
                        </td>
                        <td id="engRunningHrsSeason2" class="cntTxt f12 field" colspan="1">
                            242
                        </td>
                        <td id="engRunningHrsTotal2" class="cntTxt f12 field" colspan="1">
                            242
                        </td>
                        <td id="engNotes2" class="cntTxt f12 field" colspan="1">
                        </td>
                    </tr>
                    <tr>
                        <td id="engType3" class="cntTxt f12 field" colspan="2">
                            .
                        </td>
                        <td id="engNumber3" class="cntTxt f12 field" colspan="4">
                        </td>
                        <td id="engRunningHrsMonth3" class="cntTxt f12 field" colspan="1">
                        </td>
                        <td id="engRunningHrsSeason3" class="cntTxt f12 field" colspan="1">
                        </td>
                        <td id="engRunningHrsTotal3" class="cntTxt f12 field" colspan="1">
                        </td>
                        <td id="engNotes3" class="cntTxt f12 field" colspan="1">
                        </td>
                    </tr>
                    <tr>
                        <td id="engType4" class="cntTxt f12 field" colspan="2">
                            .
                        </td>
                        <td id="engNumber4" class="cntTxt f12 field" colspan="4">
                        </td>
                        <td id="engRunningHrsMonth4" class="cntTxt f12 field" colspan="1">
                        </td>
                        <td id="engRunningHrsSeason4" class="cntTxt f12 field" colspan="1">
                        </td>
                        <td id="engRunningHrsTotal4" class="cntTxt f12 field" colspan="1">
                        </td>
                        <td id="engNotes4" class="cntTxt f12 field" colspan="1">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="10">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                        </td>
                        <td class="cntTxt bold f14" colspan="6">
                             7. Дополнительные сведения
                        </td>
                        <td colspan="2">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="10">
                        </td>
                    </tr>
                    <tr>
                        <td class="lftTxt bold f12" colspan="4">
                            Профилактика произведена
                        </td>
                        <td id="profMonth" class="cntTxt f12 bold field" colspan="2">
                            марта
                        </td>
                        <td class="cntTxt bold f12" colspan="1">
                            месяца
                        </td>
                        <td class="cntTxt" colspan="3">
                        </td>
                    </tr>
                    <tr>
                        <td class="lftTxt bold f12" colspan="1">
                            Через
                        </td>
                        <td id="profAfterHrs" class="cntTxt bold f12 field" colspan="4">
                        </td>
                        <td class="cntTxt bold f12" colspan="2">
                            часов работы двигателя
                        </td>
                        <td class="cntTxt" colspan="3">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="10">
                        </td>
                    </tr>
                    <tr>
                        <td class="lftTxt" colspan="5">
                        </td>
                        <td class="rgtTxt bold f12" colspan="2">
                            Командир-капитан
                        </td>
                        <td class="lftTxt" colspan="3">
                        </td>
                    </tr>
                    <tr>
                        <td class="lftTxt" colspan="5">
                        </td>
                        <td class="rgtTxt bold f12" colspan="2">
                            Механик
                        </td>
                        <td class="lftTxt" colspan="3">
                        </td>
                    </tr>
                    <tr>
                        <td class="lftTxt" colspan="2">
                        </td>
                        <td class="rgtTxt bold f12" colspan="1">
                            М.П.
                        </td>
                        <td class="lftTxt" colspan="7">
                        </td>
                    </tr>
                    <tr>
                        <td class="lftTxt" colspan="7">
                        </td>
                        <td id="year2" class="rgtTxt bold f12" colspan="1">
                            <?php echo $year; ?> г.
                        </td>
                        <td class="lftTxt" colspan="2">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="10">
                        </td>
                    </tr>
                    <tr>
                        <td class="lftTxt" colspan="5">
                        </td>
                        <td class="bold f12 cntTxt" colspan="3">
                            Отчет проверен
                        </td>
                        <td class="cntTxt" colspan="2">
                        </td>
                    </tr>
               </table>
            </body>
        </html>

<?php
    /*}*/

    function selectedAttr ($curVal, $val)
    {
        if ($curVal === $val)
            echo 'selected';
    }