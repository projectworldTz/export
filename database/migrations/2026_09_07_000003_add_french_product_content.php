<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {public function up():void{Schema::table('products',function(Blueprint $t){$t->string('name_fr')->nullable()->after('name');$t->longText('description_fr')->nullable()->after('description');$t->string('seo_title_fr')->nullable()->after('seo_title');$t->text('seo_description_fr')->nullable()->after('seo_description');});}public function down():void{Schema::table('products',fn(Blueprint $t)=>$t->dropColumn(['name_fr','description_fr','seo_title_fr','seo_description_fr']));}};
