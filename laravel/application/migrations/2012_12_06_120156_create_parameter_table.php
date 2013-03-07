<?php

class Create_Parameter_Table {

    /**
     * Make changes to the database.
     *
     * @return void
     */
    public function up() {
        $table = new \Laravel\Database\Schema\Table('parameter');
        $table->create();

        $table->increments('id');
        $table->string('label');

        Schema::execute($table);
    }

    /**
     * Revert the changes to the database.
     *
     * @return void
     */
    public function down() {
        Schema::drop('parameter');
    }

}