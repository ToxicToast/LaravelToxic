<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string( 'name' )->unique();
            $table->string( 'slug' );
            $table->text('about');
            $table->string( 'email' )->unique();
            $table->string( 'password' , 64 );
            $table->string( 'password_raw' , 64 );
            $table->unsignedInteger('avatar_id')->nullable();    // users_images
            $table->unsignedInteger('hero_id')->nullable();     // users_hero_images
            $table->unsignedInteger('toasts')->default(0);
            $table->enum('active', ['0', '1'])->default('0');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
