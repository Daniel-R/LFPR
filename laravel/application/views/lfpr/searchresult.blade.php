@layout('layouts/lfpr')

@section('title')
@parent
<title>LFPR - Ergebniss einer Suche</title>
@endsection

@section('head')
<div id="kopf">
    <a class="kopfTab" href="search">Neue Suche</a>

    <div class="teiler"></div>
    <a class="kopfTabGewaehlt" href="searchresult">Letzte Pruefung</a>

    <div class="teiler"></div>
    <a class="kopfTab" href="archiv">Archivierte Resultate</a>

    <div class="teiler"></div>
    <a class="kopfTab" href="settings">Admin (deaktiviert)</a>

    <div class="teiler"></div>
    <a class="kopfTab" href="logout">{{ Auth::user()->username }} Logout</a>
</div>
@endsection

@section('content')
<div id="seiteninhaltSuchergebnis">
    <div id="ergebnisLegende">
        <p class="legende" id="domaene">Seite: {{ $domain }}</p>

        <p class="legende" id="Suchmaschine">Suchmaschine: www.SuchmaschineA.de (genutzt www.SuchmaschineB.de)</p>

        <p class="legende" id="Schlagworte">Schlagworte: {{ $searchterm }}</p>

        <p class="legende" id="Resultate">Resultate: {{ $entryCount }}</p>
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
                <col width="25">
                <col width="237">
                <col width="45">
                <col width="85">
                <col width="57">
                <col width="85">
                <col width="45">
                <col width="85">
                <col width="57">
                <col width="85">
                <col width="45">
                <col width="85">
                <col width="57">
                <col width="85">
            </colgroup>
            <thead>
            <tr>
                <th>Nr</th>
                <th>Schlagwort</th>
                <th><img class="" src="img/googleDEIcon16x16.png" alt="">.de Pos.</th>
                <th><img class="" src="img/googleDEIcon16x16.png" alt="">.de Gesamt</th>
                <th><img class="" src="img/googleDEIcon16x16.png" alt="">.com Pos.</th>
                <th><img class="" src="img/googleDEIcon16x16.png" alt="">.com Gesamt</th>
                <th><img class="" src="img/yahooDEIcon16x16.png" alt="">.de Pos.</th>
                <th><img class="" src="img/yahooDEIcon16x16.png" alt="">.de Gesamt</th>
                <th><img class="" src="img/yahooDEIcon16x16.png" alt="">.com Pos.</th>
                <th><img class="" src="img/yahooDEIcon16x16.png" alt="">.com Gesamt</th>
                <th><img class="" src="img/bingDE2Icon16x16.png" alt="">.de Pos.</th>
                <th><img class="" src="img/bingDE2Icon16x16.png" alt="">.de Gesamt</th>
                <th><img class="" src="img/duckDuckCOMIcon16x16.png" alt="">.com Pos.</th>
                <th><img class="" src="img/duckDuckCOMIcon16x16.png" alt="">.com Gesamt</th>
            </tr>
            </thead>
            <!--<tfoot>-->
            <!--</tfoot>-->
            <tbody>
            @foreach ($pageRanks as $pageRank)
            <tr>
                <td>{{ $pageRank->getID() }}</td>
                <td>{{ $pageRank->getSuchbegriff()->getSuchbegriff() }}</td>
                <td>{{ $pageRank->getPosition() }}</td>
                <td>{{ $pageRank->getAnzahlErgebnisse() }}</td>
                <td>x</td>
                <td>x</td>
                <td>x</td>
                <td>x</td>
                <td>x</td>
                <td>x</td>
                <td>x</td>
                <td>x</td>
                <td>x</td>
                <td>x</td>
            </tr>
            @endforeach

            <tr>
                <td>1</td>
                <td>Schnittlauch,<br />Scheibenkäse,<br />Dönerkleeblatt</td>
                <td>4,<br />2,<br />8</td>
                <td>31.000,<br />12.000,<br />500.000</td>
                <td>N/A,<br />4.000,<br />382</td>
                <td>N/A,<br />20.000,<br />135.000</td>
                <td>123,<br />14,<br />173</td>
                <td>1.123.000,<br />54.000.000,<br />603.000</td>
                <td>99,<br />18,<br />1</td>
                <td>723.000,<br />723.000,<br />575.000</td>
                <td>32,<br />2,<br />18</td>
                <td>1.293.000,<br />N/A,<br />123.000</td>
                <td>132</td>
                <td>2.132.000,<br />260.000,<br />479.000</td>
            </tr>
            <tr>
                <td>2</td>
                <td>Schnittlauch,<br />Scheibenkäse,<br />Dönerkleeblatt</td>
                <td>18,<br />3,<br />12</td>
                <td>31.000.000,<br />12.000,<br />500.000</td>
                <td>N/A,<br />4001,<br />398</td>
                <td>N/A,<br />20.000,<br />135.000</td>
                <td>138,<br />48,<br />240/td>
                <td>1.123.000,<br />54.000.000,<br />603.000</td>
                <td>N/A,<br />22,<br />9</td>
                <td>723.000,<br />723.000,<br />575.000</td>
                <td>95</td>
                <td>1.293.000,<br />N/A,<br />123.000</td>
                <td>172</td>
                <td>2.132.000,<br />260.000,<br />479.000</td>
            </tr>
            <tr>
                <td>3</td>
                <td>Schnittlauch,<br />Scheibenkäse</td>
                <td>26,<br />4</td>
                <td>31.000.000,<br />12.000</td>
                <td>N/A,<br />10</td>
                <td>N/A,<br />,20.000</td>
                <td>160,<br />64</td>
                <td>1.123.000,<br />54.000.000</td>
                <td>31</td>
                <td>723.000,<br />723.000</td>
                <td>312</td>
                <td>1.293.000,<br />N/A</td>
                <td>416</td>
                <td>2.132.000,<br />260.000</td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection