<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateContentAboutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content_abouts', function (Blueprint $table) {
            $table->id();

            $table->string('page_title');
            $table->string('page_banner');
            $table->string('sub_heading1');
            $table->text('sub_heading_more');
            $table->string('sub_heading_btn_text');
            $table->string('sub_heading_btn_link');

            $table->string('section_1_heading');
            $table->text('section_1_desc');
            $table->string('section_1_btn_text');
            $table->string('section_1_btn_link');
            $table->string('section_1_image_path');

            $table->string('section_2_heading');
            $table->text('section_2_desc');
            $table->string('section_2_btn_text');
            $table->string('section_2_btn_link');
            $table->string('section_2_image_path');

            $table->string('section_3_heading');
            $table->text('section_3_desc');
            $table->string('section_3_btn_text');
            $table->string('section_3_btn_link');
            $table->string('section_3_image_path');

            $table->string('section_4_heading');
            $table->text('section_4_desc');
            $table->string('section_4_btn_text');
            $table->string('section_4_btn_link');
            $table->string('section_4_image_path');

            $table->string('section_5_heading');
            $table->text('section_5_desc');
            $table->string('section_5_btn_text');
            $table->string('section_5_btn_link');
            $table->string('section_5_image_path');

            $table->text('vision_text');
            $table->text('mission_text');
            $table->text('values_text');

            $table->string('highlight_heading');
            $table->string('highlight_sub_heading');
            $table->text('highlight_desc');
            $table->string('highlight_btn_text');
            $table->string('highlight_btn_link');

            $table->string('highlight2_image_path');
            $table->string('highlight2_sub1_heading');
            $table->string('highlight2_sub1_desc');
            $table->string('highlight2_sub2_heading');
            $table->string('highlight2_sub2_desc');
            $table->string('highlight2_sub3_heading');
            $table->string('highlight2_sub3_desc');

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
        Schema::dropIfExists('content_abouts');
    }
}
