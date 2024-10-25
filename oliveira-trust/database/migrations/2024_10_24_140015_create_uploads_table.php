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
        Schema::create('uploads', function (Blueprint $table) {
            $table->id();
            $table->string('file_name');
            $table->string('file_path');
            $table->dateTime('uploaded_at');
            $table->string('TckrSymb')->nullable(); 
            $table->date('RptDt')->nullable();      
            $table->string('MktNm')->nullable(); 
            $table->string('SctyCtgyNm')->nullable(); 
            $table->string('ISIN')->nullable(); 
            $table->string('CrpnNm')->nullable(); 

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
        Schema::dropIfExists('uploads');
    }
};
