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
                <col width="248">
                <col width="43">
                <col width="43">
                <col width="248">
                <col width="43">
                <col width="77">
                <col width="83">
                <col width="63">
                <col width="107">
            </colgroup>
            <thead>
            <tr>
                <th>Nr</th>
                <th>Datum</th>
                <th>Seite</th>
                <th>Suchm.</th>
                <th>gen. Suchm.</th>
                <th>Schlagwort</th>
                <th>Pos.</th>
                <th>Gesammt</th>
                <th>Resultate</th>
                <th>Export</th>
                <th>Aktualisieren</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>1</td>
                <td>28.08.2012 12:21:35</td>
                <td>index.html</td>
                <td><img class="" src="img/googleDEIcon16x16.png" alt="">.de</td>
                <td><img class="" src="img/googleDEIcon16x16.png" alt="">.com</td>
                <td>suchbegriffA</td>
                <td>1.500</td>
                <td>1.000</td>
                <td>1.005</td>
                <td><img class="exportDateiTyp" src="img/txtIcon.png" alt="export CSV" />
                    <img class="exportDateiTyp" src="img/xlsIcon.png" alt="export Excel" />
                    <img class="exportDateiTyp" src="img/pdfIcon.png" alt="export PDF" /></td>
                <td><img class="exportDateiTyp" src="img/aktualisierenIcon16x16.png" alt="aktualisierenButton" />
                </td>
            </tr>
            <tr>
                <td>3</td>
                <td>28.08.2012 12:21:35</td>
                <td>myData/samples.php</td>
                <td><img class="" src="img/yahooDEIcon16x16.png" alt="">.de</td>
                <td><img class="" src="img/yahooDEIcon16x16.png" alt="">.de</td>
                <td>SuchbegriffC</td>
                <td>15</td>
                <td>100</td>
                <td>14.050.000</td>
                <td><img class="exportDateiTyp" src="img/txtIcon.png" alt="export CSV">
                    <img class="exportDateiTyp" src="img/xlsIcon.png" alt="export Excel">
                    <img class="exportDateiTyp" src="img/pdfIcon.png" alt="export PDF"></td>
                <td><img class="exportDateiTyp" src="img/aktualisierenIcon16x16.png" alt="aktualisierenButton" />
                </td>
            </tr>
            </tbody>
        </table>

    </div>
</div>
@endsection