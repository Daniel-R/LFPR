<?php

class Create_Parametervalue_Table {

    /**
     * Make changes to the database.
     *
     * @return void
     */
    public function up() {

        $table = new \Laravel\Database\Schema\Table('parameterValue');
        $table->create();

        $table->increments('id');
        $table->integer('parameterID');
        $table->integer('searchengineID');
        $table->string('value');
        $table->foreign('parameterID')->references('id')->on('parameter');
        $table->foreign('searchengineID')->references('id')->on('searchengine');
//        $table->unique(array('parameterID', 'searchengineID'));

        Schema::execute($table);
    }

    /**
     * Revert the changes to the database.
     *
     * @return void
     */
    public function down() {
        \Laravel\Database\Schema::drop('parameterValue');
    }
}