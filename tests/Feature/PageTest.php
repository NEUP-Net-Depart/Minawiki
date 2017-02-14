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
        Page::firstOrCreate(['id' => 1, 'father_id' => 0, 'title' => "Minawikiroot", 'is_folder' => true]);

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
        $page2->is_folder = false;
        $page2->save();
        $page3 = new Page();
        $page3->father_id = $page->id;
        $page3->title = "test3";
        $page3->is_folder = true;
        $page3->save();

        $this->visit('/page/left-nav')
            ->see('test1');
        $this->visit('/page/left-nav/test1')
            ->see('test2')
            ->see('test3');
        $this->visit('/page/left-nav/test2')
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
            ->visit('/page/left-nav/')
            ->see('')
            ->visit('/page/left-nav/test1')
            ->see('test2')
            ->click('test2')
            ->seePageIs('/test2');
    }

    public function testBackend() {
        Page::firstOrCreate(['id' => 1, 'father_id' => 0, 'title' => "Minawikiroot", 'is_folder' => true]);
        //Test Add
        $this->withSession(['user.power' => '3'])
            ->post('/page')
            ->assertNotEquals(200, $this->response->status());
        $this->withSession(['user.power' => '3'])
            ->json('POST', '/page', ['title' => 'testtest', 'father_id' => '1'])
            ->seeJson([
                'result' => 'true',
                'msg' => 'success',
            ]);
        $this->withSession(['user.power' => '3'])
            ->json('POST', '/page', ['title' => 'testtest', 'father_id' => '1'])
            ->seeJson([
                'result' => 'false',
                'msg' => 'page already exists',
            ]);
        $this->withSession(['user.power' => '3'])
            ->json('POST', '/page', ['title' => '*', 'father_id' => '1'])
            ->seeJson([
                'result' => 'false',
                'msg' => 'restricted',
            ]);
        $this->withSession(['user.power' => '3'])
            ->json('POST', '/page', ['title' => 'page', 'father_id' => '1'])
            ->seeJson([
                'result' => 'false',
                'msg' => 'reserved',
            ]);
        //Test Edit
        $this->withSession(['user.power' => '3'])
            ->put('/page/2')
            ->assertNotEquals(200, $this->response->status());
        $this->withSession(['user.power' => '3'])
            ->json('PUT', '/page/2', ['title' => 'testtest2'])
            ->seeJson([
                'result' => 'true',
                'msg' => 'success',
            ]);
        $this->withSession(['user.power' => '3'])
            ->json('PUT', '/page/2', ['title' => 'testtest2'])
            ->seeJson([
                'result' => 'true',
                'msg' => 'success',
            ]);
        $this->withSession(['user.power' => '3'])
            ->json('PUT', '/page/2', ['title' => 'Minawikiroot', 'father_id' => '1'])
            ->seeJson([
                'result' => 'false',
                'msg' => 'page already exists',
            ]);
        $this->withSession(['user.power' => '3'])
            ->json('PUT', '/page/2', ['title' => '*', 'father_id' => '1'])
            ->seeJson([
                'result' => 'false',
                'msg' => 'restricted',
            ]);
        $this->withSession(['user.power' => '3'])
            ->json('PUT', '/page/2', ['title' => 'page', 'father_id' => '1'])
            ->seeJson([
                'result' => 'false',
                'msg' => 'reserved',
            ]);
        //Test Move
        $this->withSession(['user.power' => '3'])
            ->post('/page/move/2')
            ->assertNotEquals(200, $this->response->status());
        $this->withSession(['user.power' => '3'])
            ->json('POST', '/page/move/2', ['father_title' => 'testtest'])
            ->seeJson([
                'result' => 'true',
                'msg' => 'success',
            ]);
        $this->withSession(['user.power' => '3'])
            ->json('POST', '/page/move/2', ['father_title' => 'testtest2'])
            ->seeJson([
                'result' => 'false',
                'msg' => 'improper father',
            ]);
        $this->withSession(['user.power' => '3'])
            ->json('POST', '/page/move/2', ['father_title' => 'inexitfathe'])
            ->seeJson([
                'result' => 'false',
                'msg' => 'father not exist',
            ]);
        //Test Del
        $this->withSession(['user.power' => '3'])
            ->json('DELETE', '/page/2')
            ->seeJson([
                'result' => 'true',
                'msg' => 'success',
            ]);
        //Test illegal access
        $this->visit('/auth/logout');
        $this->put('/page/2', ['title' => 'testtest2'])
            ->assertResponseStatus(404);
        $this->post('/page/move/2', ['father_title' => 'testtest'])
            ->assertResponseStatus(404);
        $this->delete('/page/2')
            ->assertResponseStatus(404);
    }
}
