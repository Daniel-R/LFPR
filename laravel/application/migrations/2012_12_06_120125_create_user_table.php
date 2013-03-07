<?php

class Create_User_Table {

    /**
     * Make changes to the database.
     *
     * @return void
     */
    public function up() {
        $table = new \Laravel\Database\Schema\Table('user');
        $table->create();

        $table->increments('id');
        $table->integer('categoryID');
        $table->string('name');
        $table->string('username');
        $table->string('email');
        $table->string('password');
        $table->boolean('administrator');
        $table->timestamps();
        $table->foreign('categoryID')->references('id')->on('category');

        Schema::execute($table);
    }

    /**
     * Revert the changes to the database.
     *
     * @return void
     */
    public function down() {
        Schema::drop('user');
    }

    //    /**
    //     * Make changes to the database.
    //     *
    //     * @return void
    //     */
    //    public function up() {
    //        $table = new \Laravel\Database\Schema\Table('benutzer');
    //        $table->create();
    //
    //        $table->increments('id');
    //        $table->integer('gruppeID');
    //        $table->string('username');
    //        $table->string('loginname');
    //        $table->string('email');
    //        $table->string('password');
    //        $table->boolean('administrator');
    //        $table->date('lastLoginDate')->nullable();
    //        $table->foreign('gruppeID')->references('id')->on('gruppe');
    //
    //        Schema::execute($table);
    //    }
    //
    //    /**
    //     * Revert the changes to the database.
    //     *
    //     * @return void
    //     */
    //    public function down() {
    //        Schema::drop('benutzer');
    //    }

}