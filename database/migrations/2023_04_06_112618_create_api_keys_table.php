<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApiKeysTable extends Migration
{
    public function up()
    {
        Schema::create('api_keys', function (Blueprint $table) {
            $table->id();
            $table->text('api_key'); // JWT token
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('api_keys');
    }

}
