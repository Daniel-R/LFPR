<?php

class Base_Controller extends Controller {

    /**
     * Catch-all method for requests that can't be matched.
     *
     * @param  string    $method
     * @param  array     $parameters
     * @return Response
     */
    public function __call($method, $parameters) {
        return Response::error('404');
    }

//restful controler können keine action_ haben nur get_

    public function __construct() {
        //Assets
        Asset::add('jQuery', 'js/jquery-1.7.1.min.js');
        Asset::add('jQuery Validation', 'js/jquery.validate.js', 'jQuery');
        Asset::add('bootstrap-js', 'js/bootstrap.min.js');
//        Asset::add('bootstrap-css', 'css/bootstrap.min.css');
//        Asset::add('bootstrap-css-responsive', 'css/bootstrap-responsive.min.css', 'bootstrap-css');
        Asset::add('lfpr-css', 'css/textformate.css');
//        Asset::add('style', 'css/style.css');
        parent::__construct();
    }

}