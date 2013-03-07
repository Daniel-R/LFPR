<?php

class Add_Category_Table {

    /**
         * Make changes to the database.
         *
         * @return void
         */
        public function up() {
            DB::table('category')->insert(array(
                                             'name'=> 'benutzer'
                                        ));

            DB::table('category')->insert(array(
                                             'name'=> 'administrator'
                                        ));
        }

        /**
         * Revert the changes to the database.
         *
         * @return void
         */
        public function down() {
            DB::table('category')->where('name', '=', 'benutzer')->delete();
            DB::table('category')->where('name', '=', 'administrator')->delete();
        }


}