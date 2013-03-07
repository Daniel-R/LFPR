<?php

class Create_Searchterm_Table {

    /**
     * Make changes to the database.
     *
     * @return void
     */
    public function up() {
        $table = new \Laravel\Database\Schema\Table('searchterm');
        $table->create();

        $table->increments('id');
        $table->string('searchterm');

        Schema::execute($table);
    }

    /**
     * Revert the changes to the database.
     *
     * @return void
     */
    public function down() {
        Schema::drop('searchterm');
    }

}