<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSessionToken extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('cms_users', function(Blueprint $table) {
            $table->dropColumn('remember_token');
            $table->string('session_token', 68);
        });

        //New password for new login handler
        \Cms\Models\User::where("username", "=", "admin")->update(array(
            "password" => md5(md5("admin"))
        ));
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('cms_users', function(Blueprint $table) {
            $table->string('remember_token', 68);
            $table->dropColumn('session_token');
        });
	}

}
