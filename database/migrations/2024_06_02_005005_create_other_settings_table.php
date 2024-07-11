<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateOtherSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('other_settings', function (Blueprint $table) {
            $table->id();
            $table->integer("pb01")->default(11);
            $table->integer("layanan")->default(0);
            $table->time("time_start")->nullable();
            $table->time("time_close")->nullable();
            $table->timestamps();
        });

        // Insert initial data
        DB::table('other_settings')->insert([
            'pb01' => 10,
            'layanan' => 5000,
            'time_start' => '10:00:00',
            'time_close' => '22:00:00',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('other_settings');
    }
}
