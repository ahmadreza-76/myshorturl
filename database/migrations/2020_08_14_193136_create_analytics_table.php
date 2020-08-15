<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnalyticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('analytics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('short_url',8);
            $table->foreign('short_url')->references('short_url')->on('urls');
            $table->enum('type',['today','yesterday','week','month']);
            $table->unsignedInteger('view_total')->default(0);
            $table->unsignedInteger('view_phone')->default(0);
            $table->unsignedInteger('view_desktop')->default(0);
            $table->unsignedInteger('view_others')->default(0);

            $table->unsignedInteger('view_browser_chrome')->default(0);
            $table->unsignedInteger('view_browser_firefox')->default(0);
            $table->unsignedInteger('view_browser_edge')->default(0);
            $table->unsignedInteger('view_browser_ie')->default(0);
            $table->unsignedInteger('view_browser_safari')->default(0);
            $table->unsignedInteger('view_browser_opera')->default(0);
            $table->unsignedInteger('view_browser_others')->default(0);

            $table->unsignedInteger('unique_total')->default(0);
            $table->unsignedInteger('unique_phone')->default(0);
            $table->unsignedInteger('unique_desktop')->default(0);
            $table->unsignedInteger('unique_others')->default(0);

            $table->unsignedInteger('unique_browser_chrome')->default(0);
            $table->unsignedInteger('unique_browser_firefox')->default(0);
            $table->unsignedInteger('unique_browser_edge')->default(0);
            $table->unsignedInteger('unique_browser_ie')->default(0);
            $table->unsignedInteger('unique_browser_safari')->default(0);
            $table->unsignedInteger('unique_browser_opera')->default(0);
            $table->unsignedInteger('unique_browser_others')->default(0);



            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('analytics');
    }
}
