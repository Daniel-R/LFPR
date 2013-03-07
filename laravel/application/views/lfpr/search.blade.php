@layout('layouts/lfpr')

@section('title')
@parent
<title>LFPR - neue Suche</title>
@endsection

@section('head')
<div id="kopf">
    <a class="kopfTabGewaehlt" href="search">Neue Suche</a>

    <div class="teiler"></div>
    <a class="kopfTab" href="searchresult">Letzte Pruefung</a>

    <div class="teiler"></div>
    <a class="kopfTab" href="archiv">Archivierte Resultate</a>

    <div class="teiler"></div>
    <a class="kopfTab" href="settings">Admin (deaktiviert)</a>

    <div class="teiler"></div>
    <a class="kopfTab" href="logout">{{ Auth::user()->username }} (Logout)</a>
</div>
@endsection

@section('content')
<div id="seiteninhaltKlein">
    <form action="/searchresult" id="suchDaten" name="suchForm" method="post">
        <p class="brecher">
            <label class="infoSuche" for="domaenen">Seite:</label>
            <input class="eingabeSuche" id="domaenen" type="text" name="domaenen" size="54"
                   maxlength="40" value="" />
            <label class="error" for="domaenen">Fehlende Angaben.</label>
        </p>

        <p class="brecher">
            <label class="infoSuche" for="schlagworte">Schlagworte:</label>
            <input class="eingabeSuche" id="schlagworte" type="text" name="schlagworte" size="54"
                   maxlength="40" value="" />
            <label class="error" for="schlagworte">Fehlende Angaben.</label>
        </p>

        <div class="brecher">
            <p class="infoSuchmaschine">Suchmaschinen:</p>
            <input class="eingabeSuchmaschine" id="alleSuchmaschinen" type="checkbox" name="suchmaschinen[]"
                   value="alle">
            <label for="alleSuchmaschinen">Alle</label><br>
            <input class="eingabeSuchmaschine" id="googleDESuchmaschine" type="checkbox" name="suchmaschinen[]"
                   value="1">
            <label for="googleDESuchmaschine">Google.de</label><br>
            <input class="eingabeSuchmaschine" id="googleCOMSuchmaschine" type="checkbox" name="suchmaschinen[]"
                   value="2">
            <label for="googleCOMSuchmaschine">Google.com</label><br>
            <input class="eingabeSuchmaschine" id="yahooDESuchmaschine" type="checkbox" name="suchmaschinen[]"
                   value="3">
            <label for="yahooDESuchmaschine">Yahoo.de</label><br>
            <input class="eingabeSuchmaschine" id="YahooCOMSuchmaschine" type="checkbox" name="suchmaschinen[]"
                   value="4">
            <label for="YahooCOMSuchmaschine">Yahoo.com</label><br>
            <input class="eingabeSuchmaschine" id="bingDESuchmaschine" type="checkbox" name="suchmaschinen[]"
                   value="5">
            <label for="bingDESuchmaschine">Bing.de</label><br>
            <input class="eingabeSuchmaschine" id="duckDuckGoCOMSuchmaschine" type="checkbox" name="suchmaschinen[]"
                   value="6">
            <label for="duckDuckGoCOMSuchmaschine">DuckDuckGO.com</label><br>
        </div>

        <p class="brecher">
            <label class="infoSuche" for="selectPruefTiefe">Resultate:</label>
            <select class="eingabeSuche" id="selectPruefTiefe" name="entryCount" size="1">
                <option>100</option>
                <option>300</option>
                <option>500</option>
                <option>1000</option>
            </select>
        </p>

        <p class="brecher">
            <input id="loginButton" type="submit" value="searchresult" />
        </p>
    </form>
</div>
@endsection