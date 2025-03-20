<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateContentHomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content_homes', function (Blueprint $table) {
            $table->id();
           
            $table->string('title1');
            $table->text('title1_desc');
            $table->string('title1_author');
            $table->string('title1_image');
            $table->string('title1_video');
            $table->string('why_choose_us_title');
            $table->string('why_choose_us_image');

            $table->string('why_choose_us_section1_title');
            $table->string('why_choose_us_section1_image');
            $table->text('why_choose_us_section1_desc');

            $table->string('why_choose_us_section2_title');
            $table->string('why_choose_us_section2_image');
            $table->text('why_choose_us_section2_desc');
            
            $table->string('why_choose_us_section3_title');
            $table->string('why_choose_us_section3_image');
            $table->text('why_choose_us_section3_desc');

            $table->string('why_choose_us_section4_title');
            $table->string('why_choose_us_section4_image');
            $table->text('why_choose_us_section4_desc');

            $table->string('project_completed');
            $table->string('happy_customer');
            $table->string('solar_panels');
            $table->string('distributors');
            
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('content_homes');
    }
}
