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
        if(Page::where('id', 1)->count() == 0) {
            $root = new Page();
            $root->id = 1;
            $root->father_id = 0;
            $root->title = "Minawikiroot";
            $root->is_folder = true;
            $root->save();
        }

        $this->visit('/')
            ->see('Minawiki');
    }
}
