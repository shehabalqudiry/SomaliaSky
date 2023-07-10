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
        Schema::create('announcements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->text('title')->nullable();
            $table->longText('description')->nullable();
            $table->integer('is_featured')->default(0);
            $table->double('price', 8, 2)->nullable()->default(0.00);
            $table->integer('status')->default(1);
            $table->text('images')->nullable();
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
        Schema::dropIfExists('announcements');
    }
};
