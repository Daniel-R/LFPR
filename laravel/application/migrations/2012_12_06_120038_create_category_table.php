<?php

class Create_Category_Table {

    /**
     * Make changes to the database.
     *
     * @return void
     */
    public function up() {
        $table = new \Laravel\Database\Schema\Table('category');
        $table->create();

        $table->increments('id');
        $table->string('name');
        $table->timestamps();

        Schema::execute($table);
    }

    /**
     * Revert the changes to the database.
     *
     * @return void
     */
    public function down() {
        Schema::drop('category');
    }
}