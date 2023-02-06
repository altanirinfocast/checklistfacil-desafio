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
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->integer('nsu', true);
            $table->foreignUuid('cake_id')->references('id')->on('cakes');
            $table->foreignUuid('customer_id')->references('id')->on('customers');
            $table->integer('quantity')->nullable(false)->default(0);
            $table->decimal('amount', 8, 2)->nullable(false)->default(0.00);
            $table->string('status', 15)->nullable()->default('pending');
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
        Schema::dropIfExists('orders');
    }
};
