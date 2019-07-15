<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRancherProjects extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rancher_projects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('gitlab_url');
            $table->string('rancher_project_id');
            $table->string('remark');
            $table->unsignedBigInteger('stack_id');
            $table->foreign('stack_id')->references('id')->on('rancher_stacks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rancher_projects');
    }
}
