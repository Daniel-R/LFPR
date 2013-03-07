<?php

class Create_Domain_Table {

    /**
     * Make changes to the database.
     *
     * @return void
     */
    public function up() {
        $table = new \Laravel\Database\Schema\Table('domain');
        $table->create();

        $table->increments('id');
        $table->string('name');
        $table->string('url');

        Schema::execute($table);
    }

    /**
     * Revert the changes to the database.
     *
     * @return void
     */
    public function down() {
        Schema::drop('domain');
    }

}