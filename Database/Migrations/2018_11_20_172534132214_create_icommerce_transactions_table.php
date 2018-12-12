<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIcommerceTransactionsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('icommerce__transactions', function (Blueprint $table) {
      $table->engine = 'InnoDB';
      $table->increments('id');
      // Your fields
  
      $table->string('external_id')->nullable();
      $table->integer('order_id')->unsigned();
      $table->foreign('order_id')->references('id')->on('icommerce__orders')->onDelete('restrict');
      
      $table->string('name');
      $table->float('amount', 8, 2);
      $table->tinyInteger('status')->unsigned();
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
    Schema::dropIfExists('icommerce__transactions');
  }
}