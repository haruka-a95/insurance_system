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
        Schema::create('insurance_policies', function (Blueprint $table) {
            $table->id();
            $table->string('policy_number', 50)->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->decimal('premium_amount', 12, 2)->nullable();
            $table->enum('status', ['active', 'cancelled', 'expired'])->default('active');
            $table->timestamps();

            //外部キー制約
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });

        //中間テーブルを作成
        Schema::create('insurance_policy_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('insurance_policy_id')->constrained()->cascadeOnDelete();
            $table->foreignId('insurance_product_id')->constrained()->cascadeOnDelete();
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
        Schema::dropIfExists('insurance_policy_product');
        Schema::dropIfExists('insurance_policies');
    }
};
