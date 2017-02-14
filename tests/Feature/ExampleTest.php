<?php

namespace Tests\Feature;

use Tests\TestCase;
use Tests\BrowserKitTestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Page;


class ExampleTest extends BrowserKitTestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        Page::firstOrCreate(['id' => 1, 'father_id' => 0, 'title' => "Minawikiroot", 'is_folder' => true]);

        $this->visit('/')
            ->see('Minawiki');
    }
}
