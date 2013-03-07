<?php

class Add_Pagerank_Table {

    /**
     * Make changes to the database.
     *
     * @return void
     */
    public function up() {
        DB::table('pageRank')->insert(array(
                                           'domainID'              => 1,
                                           'searchengineID'        => 1,
                                           'searchengineFoundID'   => 2,
                                           'searchtermID'          => 3,
                                           'userID'                => 2,
                                           'position'              => 15,
                                           'foundURL'              => 'www.ersteURL.we/a',
                                           'ammountResults'        => 100000,
                                           'resultDepth'           => 100,
                                           'date'                  => date('Y-m-d H:m:s')
                                      ));

        DB::table('pageRank')->insert(array(
                                           'domainID'              => 3,
                                           'searchengineID'        => 3,
                                           'searchengineFoundID'   => 3,
                                           'searchtermID'          => 1,
                                           'userID'                => 2,
                                           'position'              => 10,
                                           'foundURL'              => 'www.zweiteURL.we/b',
                                           'ammountResults'        => 5000,
                                           'resultDepth'           => 500,
                                           'date'                  => date('Y-m-d H:m:s')
                                      ));

        DB::table('pageRank')->insert(array(
                                           'domainID'              => 3,
                                           'searchengineID'        => 1,
                                           'searchengineFoundID'   => 2,
                                           'searchtermID'          => 3,
                                           'userID'                => 2,
                                           'position'              => 15,
                                           'foundURL'              => 'www.dritteURL.we/c',
                                           'ammountResults'        => 100000,
                                           'resultDepth'           => 100,
                                           'date'                  => date('Y-m-d H:m:s')
                                      ));
    }

    /**
     * Revert the changes to the database.
     *
     * @return void
     */
    public function down() {
        DB::table('pageRank')->where('searchtermID', '=', '3')->delete();
        DB::table('pageRank')->where('searchtermID', '=', '1')->delete();
        DB::table('pageRank')->where('searchtermID', '=', '3')->delete();
    }

}