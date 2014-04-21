<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCmsTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('cms_menus', function(Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('slug', 255);
            $table->string('title', 255);
            $table->integer('edit_order');
        });

        \Cms\Models\Menu::create(array(
            "slug" => "main-menu",
            "title" => "Main menu",
            "edit_order" => 10
        ));

        Schema::create('cms_pages', function(Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->dateTime('publish_start');
            $table->dateTime('publish_end');
            $table->integer('is_home');
            $table->integer('menu_id');
            $table->integer('allow_dropdown');
            $table->integer('parent_id');
            $table->string('link', 150);
            $table->string('slug', 150);
            $table->string('url', 100);
            $table->string('controller', 100);
            $table->string('template', 255);
            $table->integer('order');
            $table->integer('published');
            $table->integer('crawled');
        });

        Schema::create('cms_page_content', function(Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('page_id');
            $table->integer('removed');
            $table->string('title', 255);
            $table->longText('body');
        });

        Schema::create('cms_settings', function(Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('name', 100);
            $table->string('value', 255);
        });

        Cms\Models\Setting::create(array(
            "name" => "company_name",
            "value" => "Some Company",
        ));

        Cms\Models\Setting::create(array(
            "name" => "company_logo_url",
            "value" => "",
        ));

        Cms\Models\Setting::create(array(
            "name" => "datepicker_format",
            "value" => "yyyy-MM-dd hh:mm:ss",
        ));

        Cms\Models\Setting::create(array(
            "name" => "extend_url_template_page",
            "value" => "cms::layouts.page",
        ));

        Schema::create('cms_users', function(Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('username', 30);
            $table->string('password', 68);
            $table->smallInteger('edit');
            $table->smallInteger('admin');
            $table->string('remember_token', 100)->nullable();
        });

        \Cms\Models\User::create(array(
            "username" => "admin",
            "password" => Hash::make("admin"),
            "edit" => 1,
            "admin" => 1
        ));

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('cms_menus');
        Schema::drop('cms_pages');
        Schema::drop('cms_page_content');
        Schema::drop('cms_users');
        Schema::drop('cms_settings');
    }

}
