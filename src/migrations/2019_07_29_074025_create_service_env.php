<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServiceEnv extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_env', function (Blueprint $table) {
            $table->unsignedInteger("project_id");
            $table->string("project_online_id");
            $table->string("key");
            $table->longText("value");
            $table->foreign("project_id")->references("id")->on("rancher_projects");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_env');
    }
}
