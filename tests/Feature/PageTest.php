<?php

namespace Tests\Feature;

use Tests\BrowserKitTestCase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use App\Page;
use App\User;

class PageTest extends BrowserKitTestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIndex()
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
            ->see(env('APP_NAME'));

        $this->get('/inexisturi')
            ->assertResponseStatus(404);

        $page = new Page();
        $page->father_id = 1;
        $page->title = "test1";
        $page->is_folder = true;
        $page->save();
        $page2 = new Page();
        $page2->father_id = $page->id;
        $page2->title = "test2";
        $page2->is_folder = true;
        $page2->save();
        $page3 = new Page();
        $page3->father_id = $page->id;
        $page3->title = "test3";
        $page3->is_folder = true;
        $page3->save();

        $this->visit('/')
            ->see('test1');
        $this->visit('/test1')
            ->see('test2')
            ->see('test3');
        $this->visit('test2')
            ->see('test2');

        //Construct new user
        $user = new User;
        $user->tel = '126452645';
        $salt = base64_encode(random_bytes(24));
        $user->password = Hash::make($salt . '123456');
        $user->salt = $salt;
        $user->disable = false;
        $user->active_point = 0;
        $user->contribute_point = 0;
        $user->power = 3;
        $user->admin_name = "root";
        $user->theme = "yayin";
        $user->no_disturb = false;
        $user->token = $user->tel . strval(time());
        //Save new user
        $user->save();

        $this->withSession(['user.id' => $user->id])
            ->withSession(['user.power' => $user->power])
            ->visit('/')
            ->see('')
            ->visit('/test1')
            ->see('test2')
            ->click('test2')
            ->seePageIs('/test2');
    }
}
