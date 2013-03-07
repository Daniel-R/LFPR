<?php

class Add_Parametervalue_Table {

    /**
       * Make changes to the database.
       *
       * @return void
       */
      public function up() {
          DB::table('parameterValue')->insert(array(
                                                   'parameterID'    => 1,
                                                   'searchengineID' => 1,
                                                   'value'           => 'de'
                                              ));

          DB::table('parameterValue')->insert(array(
                                                   'parameterID'    => 1,
                                                   'searchengineID' => 2,
                                                   'value'           => 'paraWert1zu2'
                                              ));

          DB::table('parameterValue')->insert(array(
                                                   'parameterID'    => 1,
                                                   'searchengineID' => 3,
                                                   'value'           => '13'
                                              ));

          DB::table('parameterValue')->insert(array(
                                                   'parameterID'    => 2,
                                                   'searchengineID' => 1,
                                                   'value'           => '%s'
                                              ));

          DB::table('parameterValue')->insert(array(
                                                   'parameterID'    => 2,
                                                   'searchengineID' => 2,
                                                   'value'           => 'paraWert2zu2'
                                              ));

          DB::table('parameterValue')->insert(array(
                                                   'parameterID'    => 2,
                                                   'searchengineID' => 3,
                                                   'value'           => '23'
                                              ));

          DB::table('parameterValue')->insert(array(
                                                   'parameterID'    => 3,
                                                   'searchengineID' => 1,
                                                   'value'           => '%s'
                                              ));
      }

      /**
       * Revert the changes to the database.
       *('2', '1', '%s'),('2', '2', 'paraWert2zu2'),('2', '3', '23'),('3', '1', '%s');
       * @return void
       */
      public function down() {
          DB::table('parameterValue')->where('value', '=', 'de')->delete();
          DB::table('parameterValue')->where('value', '=', 'paraWert1zu2')->delete();
          DB::table('parameterValue')->where('value', '=', '13')->delete();
          DB::table('parameterValue')->where('value', '=', '%s')->delete();
          DB::table('parameterValue')->where('value', '=', 'paraWert2zu2')->delete();
          DB::table('parameterValue')->where('value', '=', '23')->delete();
      }

}