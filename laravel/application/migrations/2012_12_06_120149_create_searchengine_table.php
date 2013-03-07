<?php

class Create_Searchengine_Table {

    /**
     * Make changes to the database.
     *
     * @return void
     */
    public function up() {
        $table = new \Laravel\Database\Schema\Table('searchengine');
        $table->create();

        $table->increments('id');
        $table->string('name');
        $table->string('url');
        //post?
        $table->integer('positionsEachSite');

        Schema::execute($table);
    }

    /**
     * Revert the changes to the database.
     *
     * @return void
     */
    public function down() {
        Schema::drop('searchengine');
    }

}