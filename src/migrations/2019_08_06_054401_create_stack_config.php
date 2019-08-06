<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStackConfig extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stack_config', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger("template_id");
            $table->foreign('template_id')->references('id')->on('stack_template')->onDelete('cascade');
            $table->string("name");
            $table->longText("generated_docker_compose_yml");
            $table->longText("generated_rancher_compose_yml");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stack_config');
    }
}
