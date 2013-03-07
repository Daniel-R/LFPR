<?php

class Add_Domain_Table {

    /**
         * Make changes to the database.
         *
         * @return void
         */
        public function up() {
            DB::table('domain')->insert(array(
                                              'name'=> 'domaeneA',
                                              'url' => 'www.gesuchtA.de'
                                         ));

            DB::table('domain')->insert(array(
                                              'name'=> 'domaeneB',
                                              'url' => 'www.gesuchtB.de'
                                         ));

            DB::table('domain')->insert(array(
                                              'name'=> 'domaeneC',
                                              'url' => 'www.gesuchtC.de'
                                         ));
        }

        /**
         * Revert the changes to the database.
         *
         * @return void
         */
        public function down() {
            DB::table('domain')->where('name', '=', 'domaeneA')->delete();
            DB::table('domain')->where('name', '=', 'domaeneB')->delete();
            DB::table('domain')->where('name', '=', 'domaeneC')->delete();
        }

}