@layout('layouts/lfpr')

@section('title')
@parent
<title>LFPR - login</title>
@endsection

@section('head')
<div id="kopf">
    <a class="kopfTab" href="search">Neue Suche</a>

    <div class="teiler"></div>
    <a class="kopfTab" href="searchresult">Letzte Pruefung</a>

    <div class="teiler"></div>
    <a class="kopfTab" href="archiv">Archivierte Resultate</a>

    <div class="teiler"></div>
    <a class="kopfTab" href="settings">Admin (deaktiviert)</a>

    <div class="teiler"></div>
    <a class="kopfTabGewaehlt" href="logout">Login</a>
</div>
@endsection

@section('content')
<div id="seiteninhaltKlein">

    <?php echo Form::open('search', 'POST', array('class' => 'well', 'id' => 'loginDaten')); ?>
    <p class="brecher">
        <!-- username field -->
        <?php echo Form::label('userName', 'User:', array('class' => 'loginInfo')); ?>
        <?php echo Form::text('userName', '', array('class' => 'loginEingabe')); ?>
    </p>

    <p class="brecher">
        <!-- password field -->
        <?php echo Form::label('password', 'Password:', array('class' => 'loginInfo')); ?>
        <?php echo Form::password('password', array('class' => 'loginEingabe')); ?>
    </p>

    <p class="brecher">
        <!-- login button -->
        <?php echo Form::submit('Login');?>
    </p>
    <?php echo Form::close(); ?>

<!--    <form class="well" action="http://localhost/search" id="loginDaten" name="loginForm" method="POST">-->
<!--        <p class="brecher">-->
<!--            <label class="loginInfo" for="userName">Benutzername:</label>-->
<!--            <input class="loginEingabe" id="userName" type="text" name="benutzer" size="24" maxlength="40"-->
<!--                   value="" />-->
<!--        </p>-->
<!---->
<!--        <p class="brecher">-->
<!--            <label class="loginInfo" for="password">Passwort:</label>-->
<!--            <input class="loginEingabe" id="password" type="text" name="password" size="24" maxlength="40"-->
<!--                   value="" />-->
<!--        </p>-->
<!---->
<!--        <p class="brecher">-->
<!--            <input id="loginButton" type="submit" value="Login" />-->
<!--        </p>-->
    </form>
</div>
@endsection