<?php

namespace Database\Seeders;

use App\Models\File;
use Illuminate\Database\Seeder;
use Illuminate\Http\Testing\File as TestingFile;
use Illuminate\Support\Facades\Storage;

class FileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        copy(
            public_path('images\sample-logo.jpg'),
            base_path('storage\app\public\company\sample-logo-copy.jpg')
        );
        File::create([
            'path' => 'company/sample-logo-copy.jpg',
            'type' => Storage::disk('public')->mimeType('company/sample-logo-copy.jpg'),
            'size' => Storage::disk('public')->size('company/sample-logo-copy.jpg'),
        ]);
    }
}
