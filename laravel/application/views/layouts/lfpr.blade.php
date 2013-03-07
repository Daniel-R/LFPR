<?php
/**
 * User: Daniel Reichelt, mydata GmbH
 * Package: application.views.layouts
 * Date: 23.11.12
 * Time: 11:31
 *
 */
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    @section('title')
    @yield_section
    {{ Asset::styles() }}
    {{ Asset::scripts() }}
</head>
<body>
<div id="kopfRahmen">
    <img src="img/LFPR509x99.png" width="509" height="99" id="logo" alt="<h1>LFPR</h1>" />
    @yield('head')
</div>
<div class="brecher"></div>
<div id="seite">
    @yield('content')
</div>
</body>
</html>