<?php
use Illuminate\Database\Migrations\Migration; use Illuminate\Database\Schema\Blueprint; use Illuminate\Support\Facades\Schema;
return new class extends Migration {public function up():void {Schema::create('chat_messages',function(Blueprint $t){$t->id();$t->foreignId('user_id')->constrained()->cascadeOnDelete();$t->enum('sender_role',['user','admin'])->default('user');$t->text('message');$t->timestamp('read_at')->nullable();$t->timestamps();$t->index(['user_id','created_at']);});} public function down():void {Schema::dropIfExists('chat_messages');}};
