<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('stores');
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->integer('is_featured')->default(0);
            $table->string('slug')->unique()->index();
            $table->string('location')->nullable();
            $table->string('avatar')->nullable();
            $table->integer('status')->default(0);
            $table->string('cover_image')->nullable();
            $table->text('description')->nullable();
            $table->integer('blocked')->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on("users")->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('category_id')->references('id')->on("categories")->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('city_id')->references('id')->on("cities")->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stores');
    }
};
