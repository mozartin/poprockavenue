<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('live_events', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->json('title');
            $table->json('description')->nullable();
            $table->json('ticket_info')->nullable();
            $table->string('venue_name');
            $table->string('venue_address')->nullable();
            $table->string('city')->nullable();
            $table->dateTime('starts_at');
            $table->string('poster_path')->nullable();
            $table->string('info_url')->nullable();
            $table->string('ticket_url')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_featured')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        DB::table('live_events')->insert([
            'slug' => 'everyone-rocks-2026',
            'title' => json_encode([
                'en' => 'Everyone Rocks',
                'nl' => 'Everyone Rocks',
                'uk' => 'Everyone Rocks',
                'ru' => 'Everyone Rocks',
            ], JSON_UNESCAPED_UNICODE),
            'description' => json_encode([
                'en' => 'Inclusive music day at Baroeg Rotterdam. Pop/Rock Avenue joins the line-up with classic pop and rock — music, dance and connection for everyone.',
                'nl' => 'Inclusieve muziekdag in Baroeg Rotterdam. Pop/Rock Avenue speelt bekende pop- en rockhits — muziek, dans en verbinding voor iedereen.',
                'uk' => 'Інклюзивний музичний день у Baroeg у Роттердамі. Pop/Rock Avenue у програмі з улюбленими поп- і рок-хітами — музика, танці й єднання для всіх.',
                'ru' => 'Инклюзивный музыкальный день в Baroeg в Роттердаме. Pop/Rock Avenue в программе с любимыми поп- и рок-хитами — музыка, танцы и единение для всех.',
            ], JSON_UNESCAPED_UNICODE),
            'ticket_info' => json_encode([
                'en' => 'Pre-sale €14 · Door €15',
                'nl' => 'Vvk €14 · Entree €15',
                'uk' => 'Передпродаж €14 · На вході €15',
                'ru' => 'Предпродажа €14 · На входе €15',
            ], JSON_UNESCAPED_UNICODE),
            'venue_name' => 'Baroeg',
            'venue_address' => 'Spinozaweg 300, 3076 ET Rotterdam',
            'city' => 'Rotterdam',
            'starts_at' => '2026-10-03 14:00:00',
            'poster_path' => null,
            'info_url' => null,
            'ticket_url' => null,
            'sort_order' => 1,
            'is_featured' => true,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('live_events');
    }
};
