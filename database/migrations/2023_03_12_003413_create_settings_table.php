<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('content');

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });

        $data = [
            ['title' => 'official_phone1', 'content' => '9876543211'],
            ['title' => 'official_phone2', 'content' => '9876543212'],
            ['title' => 'official_email', 'content' => 'support@trisala.com'],
            ['title' => 'full_company_name', 'content' => 'Trisala Instruments LLP'],
            ['title' => 'pretty_company_name', 'content' => 'Trisala'],
            ['title' => 'company_short_desc', 'content' => '<p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Magni consequuntur expedita doloribus!</p>'],
            ['title' => 'company_full_address', 'content' => 'Lorem ipsum dolor sit amet.'],
            ['title' => 'google_map_address_link', 'content' => 'Lorem ipsum dolor sit amet.'],
        ];

        DB::table('settings')->insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
