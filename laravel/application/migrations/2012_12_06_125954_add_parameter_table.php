<?php

class Add_Parameter_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
    public function up() {
        DB::table('parameter')->insert(array(
                                            'label'=> 'hl'
                                     ));

        DB::table('parameter')->insert(array(
                                            'label'=> 'q'
                                     ));

        DB::table('parameter')->insert(array(
                                            'label'=> 'start'
                                     ));
    }

    /**
     * Revert the changes to the database.
     *
     * @return void
     */
    public function down() {
        DB::table('parameter')->where('label', '=', 'hl')->delete();
        DB::table('parameter')->where('label', '=', 'q')->delete();
        DB::table('parameter')->where('label', '=', 'start')->delete();
    }

}