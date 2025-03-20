<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSeoDetailsOnPageContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('page_contents', function (Blueprint $table) {
            $table->string('meta_title')->nullable()->after('description');
        $table->text('meta_description')->nullable()->after('meta_title');
        $table->string('meta_keyword')->nullable()->after('meta_description');// Add a nullable string column for the image
        });
    }
    
    public function down()
    {
        Schema::table('page_contents', function (Blueprint $table) {
            $table->dropColumn(['meta_title', 'meta_description', 'meta_keyword']);
        });
    }
}
