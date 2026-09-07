<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
  Schema::table('products',function(Blueprint $t){$t->string('subcategory')->nullable()->after('category');$t->string('model')->nullable()->after('brand');$t->string('current_location')->nullable()->after('origin');$t->decimal('fob_price',12,2)->nullable()->after('price');$t->decimal('cif_price',12,2)->nullable()->after('fob_price');$t->decimal('shipping_cost',12,2)->nullable()->after('cif_price');$t->string('shipping_destination')->nullable()->after('shipping_cost');$t->string('delivery_time')->nullable()->after('shipping_destination');$t->text('shipping_information')->nullable()->after('delivery_time');$t->enum('purchase_mode',['quote','buy','both'])->default('quote')->after('featured');$t->string('seo_title')->nullable()->after('purchase_mode');$t->text('seo_description')->nullable()->after('seo_title');});
  Schema::create('product_images',function(Blueprint $t){$t->id();$t->foreignId('product_id')->constrained()->cascadeOnDelete();$t->string('path');$t->string('alt_text')->nullable();$t->unsignedInteger('sort_order')->default(0);$t->timestamps();});
 }
 public function down(): void {Schema::dropIfExists('product_images');Schema::table('products',function(Blueprint $t){$t->dropColumn(['subcategory','model','current_location','fob_price','cif_price','shipping_cost','shipping_destination','delivery_time','shipping_information','purchase_mode','seo_title','seo_description']);});}
};
