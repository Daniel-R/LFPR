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
    <a class="kopfTab" href="archiv">Archivierte Resultate</a>

    <div class="teiler"></div>
    <a class="kopfTabGewaehlt" href="settings">Admin (deaktiviert)</a>

    <div class="teiler"></div>
    <a class="kopfTab" href="logout">{{ Auth::user()->username }} Logout</a>
</div>
@endsection

@section('content')
    <div id="seiteninhaltKlein">
        <form action="" id="loginDaten" name="loginForm" method="post">
            <p class="brecher">
                <label class="loginInfo" for="benutzer">altes Password:</label>
                <input class="loginEingabe" id="benutzer" type="text" name="ansprechpartner" size="24" maxlength="40"
                       value="" />
                <label class="error" for="benutzer">Fehlend</label>
            </p>

            <p class="brecher">
                <label class="loginInfo" for="password">neues Passwort:</label>
                <input class="loginEingabe" id="password" type="text" name="ansprechpartner" size="24" maxlength="40"
                       value="" />
                <label class="error" for="password">Fehlend</label>
            </p>

            <p class="brecher">
                <label class="loginInfo" for="password">neues Passwort:</label>
                <input class="loginEingabe" id="" type="text" name="ansprechpartner" size="24" maxlength="40"
                       value="" />
                <label class="error" for="password">Fehlend</label>
            </p>

            <p class="brecher">
                <!--<input id="button" type="submit" name="formtype_mail" value="Login" />-->
                <input id="loginButton" type="button" value="Login" onclick="self.location.href='neueSuche.html'" />
            </p>
        </form>
        <div id="test"></div>
    </div>
@endsection