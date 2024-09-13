<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;
use App\Models\Element;

class ElementSeeder extends Seeder
{
    public function run()
    {
        // Temukan halaman dengan ID yang relevan
        $page = Page::find(1); // Ganti 1 dengan ID halaman yang sesuai

        // Tambahkan elemen baru
        $page->elements()->create([
            'type' => 'text',
            'properties' => json_encode(['content' => 'Hello World', 'x' => 100, 'y' => 200])
        ]);

        $page->elements()->create([
            'type' => 'image',
            'properties' => json_encode(['url' => 'https://example.com/image.jpg', 'x' => 50, 'y' => 50])
        ]);
    }
}
