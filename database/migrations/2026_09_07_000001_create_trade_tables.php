<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
  Schema::create('products', function(Blueprint $t){$t->id();$t->string('name');$t->string('slug')->unique();$t->string('category');$t->string('brand')->nullable();$t->string('origin');$t->decimal('price',12,2)->nullable();$t->string('currency',3)->default('USD');$t->string('condition')->default('New');$t->string('availability')->default('In Stock');$t->string('image');$t->string('stock_no')->unique();$t->unsignedSmallInteger('year')->nullable();$t->json('specifications')->nullable();$t->text('description');$t->boolean('featured')->default(false);$t->timestamps();});
  Schema::create('quote_requests', function(Blueprint $t){$t->id();$t->string('reference')->unique();$t->string('full_name');$t->string('company')->nullable();$t->string('email');$t->string('phone');$t->string('whatsapp')->nullable();$t->string('country');$t->string('product');$t->unsignedInteger('quantity')->default(1);$t->string('destination_country');$t->string('destination_port')->nullable();$t->string('shipping_method');$t->text('message')->nullable();$t->string('status')->default('New');$t->timestamps();});
  Schema::create('import_requests', function(Blueprint $t){$t->id();$t->string('reference')->unique();$t->string('customer_name');$t->string('email');$t->string('phone');$t->string('product_name');$t->string('category');$t->string('brand')->nullable();$t->string('model')->nullable();$t->unsignedInteger('quantity')->default(1);$t->string('origin')->nullable();$t->decimal('budget',12,2)->nullable();$t->string('destination');$t->text('description');$t->string('status')->default('New');$t->timestamps();});
  Schema::create('contact_messages', function(Blueprint $t){$t->id();$t->string('name');$t->string('email');$t->string('phone')->nullable();$t->string('subject');$t->text('message');$t->timestamps();});
  Schema::create('shipments', function(Blueprint $t){$t->id();$t->string('tracking_number')->unique();$t->string('order_number')->unique();$t->string('product');$t->string('origin');$t->string('destination');$t->string('current_status');$t->json('events');$t->timestamps();});
 }
 public function down(): void {Schema::dropIfExists('shipments');Schema::dropIfExists('contact_messages');Schema::dropIfExists('import_requests');Schema::dropIfExists('quote_requests');Schema::dropIfExists('products');}
};
