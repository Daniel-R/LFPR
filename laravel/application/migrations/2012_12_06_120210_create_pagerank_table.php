<?php

class Create_Pagerank_Table {

    /**
     * Make changes to the database.
     *
     * @return void
     */
    public function up() {

        $table = new \Laravel\Database\Schema\Table('pageRank');
        $table->create();

        $table->increments('id');
        $table->integer('domainID');
        $table->integer('searchengineID');
        $table->integer('searchengineFoundID');
        $table->integer('searchtermID');
        $table->integer('userID');
        $table->integer('position');
        $table->string('foundURL');
        $table->integer('ammountResults');
        $table->integer('resultDepth');
        $table->timestamp('date');
        $table->foreign('domainID')->references('id')->on('domain');
        $table->foreign('searchengineID')->references('id')->on('searchengine');
        $table->foreign('searchengineFoundID')->references('id')->on('searchengine');
        $table->foreign('searchtermID')->references('id')->on('searchterm');
        $table->foreign('userID')->references('id')->on('user');

        Schema::execute($table);
    }

    /**
     * Revert the changes to the database.
     *
     * @return void
     */
    public function down() {
        \Laravel\Database\Schema::drop('pageRank');
    }

}