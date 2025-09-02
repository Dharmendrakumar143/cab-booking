<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Page;
use App\Models\PageContent;

class PageContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if the 'Terms and Conditions' page exists, otherwise create it
        $page = Page::firstOrCreate(
            ['slug' => 'terms_and_conditions'], // Conditions to find the existing page
            ['publish' => 'published'] // Values to set if the page does not exist
        );

        // Check if the associated PageContent exists for 'Terms and Conditions', otherwise create it
        PageContent::firstOrCreate(
            ['page_id' => $page->id], // Conditions to find the existing page content
            ['name' => 'Terms and Conditions','page_content' => 'Terms and Conditions'] // Values to set if the page content does not exist
        );

        // Check if the 'Privacy Policy' page exists, otherwise create it
        $page = Page::firstOrCreate(
            ['slug' => 'privacy_policy'], // Conditions to find the existing page
            ['publish' => 'published'] // Values to set if the page does not exist
        );

        // Check if the associated PageContent exists for 'Privacy Policy', otherwise create it
        PageContent::firstOrCreate(
            ['page_id' => $page->id], // Conditions to find the existing page content
            ['name' => 'Privacy Policy','page_content' => 'Privacy Policy'] // Values to set if the page content does not exist
        );


    }
}
