<?php

class Add_Searchengine_Table {

    /**
         * Make changes to the database.
         *
         * @return void
         */
        public function up() {
            DB::table('searchengine')->insert(array(
                                                   'name'              => 'googleDE',
                                                   'url'               => 'www.google.de',
                                                   'positionsEachSite' => 10
                                              ));

            DB::table('searchengine')->insert(array(
                                                   'name'              => 'suchmaschineB',
                                                   'url'               => 'www.suchmaschineB.de',
                                                   'positionsEachSite' => 10
                                              ));
            DB::table('searchengine')->insert(array(
                                                   'name'              => 'suchmaschineC',
                                                   'url'               => 'www.suchmaschineC.de',
                                                   'positionsEachSite' => 20
                                              ));
        }

        /**
         * Revert the changes to the database.'1', 'googleDE', 'www.google.de', '10'),('2', 'suchmaschineB', 'www.suchmaschineB.de', '10');
         *
         * @return void
         */
        public function down() {
            DB::table('searchengine')->where('name', '=', 'googleDE')->delete();
            DB::table('searchengine')->where('name', '=', 'suchmaschineB')->delete();
            DB::table('searchengine')->where('name', '=', 'suchmaschineC')->delete();
        }

}