@layout('layouts/lfpr')

@section('title')
@parent
<title>LFPR - Ergebniss einer Suche</title>
@endsection

@section('head')
<div id="kopf">
    <a class="kopfTab" href="search">Neue Suche</a>

    <div class="teiler"></div>
    <a class="kopfTab" href="searchresult">Letzte Pruefung</a>

    <div class="teiler"></div>
    <a class="kopfTabGewaehlt" href="archiv">Archivierte Resultate</a>

    <div class="teiler"></div>
    <a class="kopfTab" href="settings">Admin (deaktiviert)</a>

    <div class="teiler"></div>
    <a class="kopfTab" href="logout">{{ Auth::user()->username }} Logout</a>
</div>
@endsection

@section('content')
<div id="seiteninhaltSuchergebnis">
    <div id="ergebnisLegende">
        <p class="legende" id="domaene">Seite: www.ersteURL.de</p>
    </div>

    <div id="export">
        <p id="exportHeadline">Export</p>

        <img class="exportDateiTyp" src="img/txtIcon.png" alt="export CSV">

        <img class="exportDateiTyp" src="img/xlsIcon.png" alt="export Excel">

        <img class="exportDateiTyp" src="img/pdfIcon.png" alt="export PDF">
    </div>

    <div id="ergebnisTabelleBox">
        <table id="ergebnisTabelle" border="1" rules="all">
            <colgroup>
                <col width="23">
                <col width="83">
                <col width="214">
                <col width="62">
                <col width="62">
                <col width="260">
                <col width="61">
                <col width="87">
                <col width="43">
                <col width="63">
                <col width="107">
            </colgroup>
            <thead>
            <tr>
                <th>Nr</th>
                <th>Datum</th>
                <th>Domaene</th>
                <th>Suchm.</th>
                <th>gen. Suchm.</th>
                <th>Schlagwort</th>
                <th>1. Pos.</th>
                <th>Gesammt</th>
                <th>Resultate</th>
                <th>Export</th>
                <th>Aktualisieren</th>
            </tr>
            </thead>

            <tbody>

            <?php
            //    $pageRank = PageRank::where('id', '=', '2');
            $pageRanks = PageRank::all();
//            var_dump($pageRanks);
            foreach ($pageRanks as $pageRank) {
                require_once("application/controllers/class/ParserID.php");

                $parser = "";
                if ($pageRank->searchengineid == ParserID::GOOGLEDE) {
                    $parser = 'img/googleDEIcon16x16.png';
                }
                else if ($pageRank->searchengineid == ParserID::GOOGLECOM) {
                    $parser = 'img/googleDEIcon16x16.png';
                }
                else if ($pageRank->searchengineid == ParserID::YAHOODE) {
                    $parser = 'img/yahooDEIcon16x16.png';
                }
                else if ($pageRank->searchengineid == ParserID::YAHOOCOM) {
                    $parser = 'img/yahooDEIcon16x16.png';
                }
                else if ($pageRank->searchengineid == ParserID::BINGDE) {
                    $parser = 'img/BingDE2Icon16x16.png';
                }
                else if ($pageRank->searchengineid == ParserID::DUCKDUCKGO) {
                    $parser = 'img/duckDuckCOMIcon16x16.png';
                }

                $parserfound = "";
                if ($pageRank->searchenginefoundid == ParserID::GOOGLEDE) {
                    $parserfound = 'img/googleDEIcon16x16.png';
                }
                else if ($pageRank->searchenginefoundid == ParserID::GOOGLECOM) {
                    $parserfound = 'img/googleDEIcon16x16.png';
                }
                else if ($pageRank->searchenginefoundid == ParserID::YAHOODE) {
                    $parserfound = 'img/yahooDEIcon16x16.png';
                }
                else if ($pageRank->searchenginefoundid == ParserID::YAHOOCOM) {
                    $parserfound = 'img/yahooDEIcon16x16.png';
                }
                else if ($pageRank->searchenginefoundid == ParserID::BINGDE) {
                    $parserfound = 'img/BingDE2Icon16x16.png';
                }
                else if ($pageRank->searchenginefoundid == ParserID::DUCKDUCKGO) {
                    $parserfound = 'img/duckDuckCOMIcon16x16.png';
                }
//                var_dump($pageRank->id);
//                echo '<br>';
                echo '<tr >
                        <td ><a href = "details" > ' . $pageRank->id . '</a ></td >
                        <td > ' . $pageRank->date . '</td >
                        <td >' . $pageRank->foundurl . '.we,
                        </td >
                        <td ><img class="" src ="'.$parser.'" alt ="" >.com </td >
                        <td ><img class="" src = "'.$parserfound.'" alt ="" >.de </td >
                        <td > ' . $pageRank->searchtermid . ',<br />SuchbegriffC</td >
                        <td > ' . $pageRank->position . ',<br />6,<br />239</td >
                        <td > ' . $pageRank->ammountresults . ',<br />239.000</td >
                        <td > ' . $pageRank->resultdepth . ',<br />500</td >
                        <td ><img class="exportDateiTyp" src = "img/txtIcon.png" alt ="export CSV" >
                             <img class="exportDateiTyp" src = "img/xlsIcon.png" alt ="export Excel" >
                             <img class="exportDateiTyp" src = "img/pdfIcon.png" alt ="export PDF" ></td >
                        <td ><img class="exportDateiTyp" src = "img/aktualisierenIcon16x16.png" alt ="aktualisierenButton" />
                        </td >
                        </tr >';
            }
            ?>


<!--            <tr>-->
<!--                <td><a href="details">2</a></td>-->
<!--                <td>28.08.2012 12:21:35</td>-->
<!--                <td>www.zweiteURL.we,-->
<!--                </td>-->
<!--                <td><img class="" src="img/duckDuckCOMIcon16x16.png" alt="">.com</td>-->
<!--                <td><img class="" src="img/googleDEIcon16x16.png" alt="">.de</td>-->
<!--                <td>SuchbegriffA,<br />SuchbegriffC</td>-->
<!--                <td>15,<br />6,<br />239</td>-->
<!--                <td>14.050.000,<br />239.000</td>-->
<!--                <td>100,<br />500</td>-->
<!--                <td><img class="exportDateiTyp" src="img/txtIcon.png" alt="export CSV">-->
<!--                    <img class="exportDateiTyp" src="img/xlsIcon.png" alt="export Excel">-->
<!--                    <img class="exportDateiTyp" src="img/pdfIcon.png" alt="export PDF"></td>-->
<!--                <td><img class="exportDateiTyp" src="img/aktualisierenIcon16x16.png" alt="aktualisierenButton" />-->
<!--                </td>-->
<!--            </tr>-->
<!--            <tr>-->
<!--                <td><a href="details">3</a></td>-->
<!--                <td>28.08.2012 12:21:35</td>-->
<!--                <td>www.dritteURL.we</td>-->
<!--                <td><img class="" src="img/yahooDEIcon16x16.png" alt="">.com</td>-->
<!--                <td><img class="" src="img/bingDE2Icon16x16.png" alt="">.de</td>-->
<!--                <td>SuchbegriffA,<br />SuchbegriffB,<br />SuchbegriffC</td>-->
<!--                <td>15,<br />12,<br />239.000</td>-->
<!--                <td>1.050.000,<br />239.000,<br />.50.000</td>-->
<!--                <td>300,<br />300,<br />500</td>-->
<!--                <td><img class="exportDateiTyp" src="img/txtIcon.png" alt="export CSV">-->
<!--                    <img class="exportDateiTyp" src="img/xlsIcon.png" alt="export Excel">-->
<!--                    <img class="exportDateiTyp" src="img/pdfIcon.png" alt="export PDF"></td>-->
<!--                <td><img class="exportDateiTyp" src="img/aktualisierenIcon16x16.png" alt="aktualisierenButton" />-->
<!--                </td>-->
<!--            </tr>-->
            </tbody>
        </table>

    </div>
</div>
@endsection