<?php

class Add_Searchterm_Table {

    /**
    	 * Make changes to the database.
    	 *
    	 * @return void
    	 */
        public function up() {
            DB::table('searchterm')->insert(array(
                                              'searchterm'=> 'Probenverwaltung'
                                         ));

            DB::table('searchterm')->insert(array(
                                              'searchterm' => 'mydata'
                                         ));

            DB::table('searchterm')->insert(array(
                                              'searchterm' => 'Laborshop'
                                         ));
        }

        /**
         * Revert the changes to the database.
         *
         * @return void
         */
        public function down() {
            DB::table('searchterm')->where('searchterm', '=', 'Probenverwaltung')->delete();
            DB::table('searchterm')->where('searchterm', '=', 'mydata')->delete();
            DB::table('searchterm')->where('searchterm', '=', 'Laborshop')->delete();
        }

}