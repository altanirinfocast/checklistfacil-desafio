<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cakes', function (Blueprint $table) {
            // $table->id()->autoIncrement();
            $table->uuid('id')->unique();
            $table->string('name', 150)->unique()->nullable(false);
            $table->decimal('price', 8, 2)->nullable(false)->default(0.00);
            $table->bigInteger('weight')->nullable(false)->default(0);
            $table->integer('quantity')->nullable(false)->default(0);
            $table->timestamp('created_at')->nullable(false)->default(now());
            $table->timestamp('updated_at')->nullable();
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
        Schema::dropIfExists('cakes');
    }
};
