<?php

class Add_User_Table {

    /**
     * Make changes to the database.
     *
     * @return void
     */
    public function up() {
        DB::table('user')->insert(array(
                                       'categoryID'    => 2,
                                       'name'          => 'Administrator',
                                       'username'      => 'Admin',
                                       'email'         => 'roman.hecht@mydata.de',
                                       'password'      => Hash::make('password'),
                                       'administrator' => true,
                                       'created_at'    => date('Y-m-d H:m:s'),
                                       'updated_at'    => date('Y-m-d H:m:s')
                                  ));

        DB::table('user')->insert(array(
                                       'categoryID'   => 1,
                                       'name'         => 'Praktikant',
                                       'username'     => 'Praktikant',
                                       'email'        => 'praktikant@mydata.de',
                                       'password'     => Hash::make('password'),
                                       'administrator'=> false,
                                       'created_at'   => date('Y-m-d H:m:s'),
                                       'updated_at'   => date('Y-m-d H:m:s')
                                  ));

    }

    /**
     * Revert the changes to the database.
     *
     * @return void
     */
    public function down() {
        DB::table('user')->where('username', '=', 'Admin')->delete();
        DB::table('user')->where('username', '=', 'Praktikant')->delete();
    }


    //    /**
    //     * Make changes to the database.
    //     *
    //     * @return void
    //     */
    //    public function up() {
    //        DB::table('benutzer')->insert(array(
    //                                           'gruppeID'     => 2,
    //                                           'name'         => 'Administrator',
    //                                           'loginname'    => 'Admin',
    //                                           'email'        => '',
    //                                           'password'     => '5f4dcc3b5aa765d61d8327deb882cf99',
    //                                           'administrator'=> true,
    //                                           'lastLoginDate'=> date('Y-m-d H:m:s')
    //                                      ));
    //
    //        DB::table('benutzer')->insert(array(
    //                                           'gruppeID'     => 1,
    //                                           'name'         => 'Praktikant',
    //                                           'loginname'    => 'Praktikant',
    //                                           'email'        => '',
    //                                           'password'     => '5f4dcc3b5aa765d61d8327deb882cf99',
    //                                           'administrator'=> false,
    //                                           'lastLoginDate'=> date('Y-m-d H:m:s')
    //                                      ));
    //
    //    }
    //
    //    /**
    //     * Revert the changes to the database.
    //     *
    //     * @return void
    //     */
    //    public function down() {
    //        DB::table('benutzer')->where('loginname', '=', 'Admin')->delete();
    //        DB::table('benutzer')->where('loginname', '=', 'Praktikant')->delete();
    //    }

}