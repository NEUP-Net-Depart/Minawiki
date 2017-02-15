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
        $this->post('/install', ['tel' => '12312312312', 'password' => 'admin', 'title' => 'Minawikiroot']);

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

    public function testBackend()
    {
        $this->post('/install', ['tel' => '12312312312', 'password' => 'admin', 'title' => 'Minawikiroot']);

        //Test Add
        $this->withSession(['user.id' => 1, 'user.power' => '3'])
            ->post('/page')
            ->assertNotEquals(200, $this->response->status());
        $this->withSession(['user.id' => 1, 'user.power' => '3'])
            ->json('POST', '/page', ['title' => 'testtest', 'father_id' => '1'])
            ->seeJson([
                'result' => 'true',
                'msg' => 'success',
            ]);
        $this->withSession(['user.id' => 1, 'user.power' => '3'])
            ->json('POST', '/page', ['title' => 'testtest', 'father_id' => '1'])
            ->seeJson([
                'result' => 'false',
                'msg' => 'page already exists',
            ]);
        $this->withSession(['user.id' => 1, 'user.power' => '3'])
            ->json('POST', '/page', ['title' => '*', 'father_id' => '1'])
            ->seeJson([
                'result' => 'false',
                'msg' => 'restricted',
            ]);
        $this->withSession(['user.id' => 1, 'user.power' => '3'])
            ->json('POST', '/page', ['title' => 'page', 'father_id' => '1'])
            ->seeJson([
                'result' => 'false',
                'msg' => 'reserved',
            ]);
        //Test Edit
        $this->withSession(['user.id' => 1, 'user.power' => '3'])
            ->put('/page/2')
            ->assertNotEquals(200, $this->response->status());
        $this->withSession(['user.id' => 1, 'user.power' => '3'])
            ->json('PUT', '/page/2', ['title' => 'testtest2'])
            ->seeJson([
                'result' => 'true',
                'msg' => 'success',
            ]);
        $this->withSession(['user.id' => 1, 'user.power' => '3'])
            ->json('PUT', '/page/2', ['title' => 'testtest2'])
            ->seeJson([
                'result' => 'true',
                'msg' => 'success',
            ]);
        $this->withSession(['user.id' => 1, 'user.power' => '3'])
            ->json('PUT', '/page/2', ['title' => 'Minawikiroot', 'father_id' => '1'])
            ->seeJson([
                'result' => 'false',
                'msg' => 'page already exists',
            ]);
        $this->withSession(['user.id' => 1, 'user.power' => '3'])
            ->json('PUT', '/page/2', ['title' => '*', 'father_id' => '1'])
            ->seeJson([
                'result' => 'false',
                'msg' => 'restricted',
            ]);
        $this->withSession(['user.id' => 1, 'user.power' => '3'])
            ->json('PUT', '/page/2', ['title' => 'page', 'father_id' => '1'])
            ->seeJson([
                'result' => 'false',
                'msg' => 'reserved',
            ]);
        //Test Move
        $this->withSession(['user.id' => 1, 'user.power' => '3'])
            ->post('/page/move/2')
            ->assertNotEquals(200, $this->response->status());
        $this->withSession(['user.id' => 1, 'user.power' => '3'])
            ->json('POST', '/page/move/2', ['father_title' => 'testtest'])
            ->seeJson([
                'result' => 'true',
                'msg' => 'success',
            ]);
        $this->withSession(['user.id' => 1, 'user.power' => '3'])
            ->json('POST', '/page/move/2', ['father_title' => 'testtest2'])
            ->seeJson([
                'result' => 'false',
                'msg' => 'improper father',
            ]);
        $this->withSession(['user.id' => 1, 'user.power' => '3'])
            ->json('POST', '/page/move/2', ['father_title' => 'inexitfathe'])
            ->seeJson([
                'result' => 'false',
                'msg' => 'father not exist',
            ]);
        //Test redirect
        $page = new Page();
        $page->father_id = 1;
        $page->title = "cool1";
        $page->is_folder = false;
        $page->save();
        $this->withSession(['user.id' => 1, 'user.power' => '3'])
            ->json('PUT', '/page/' . $page->id, ['title' => 'cool2'])
            ->seeJson([
                'result' => 'true',
                'msg' => 'success',
            ]);
        $this->withSession(['user.id' => 1, 'user.power' => '3'])
            ->json('PUT', '/page/' . $page->id, ['title' => 'cool3'])
            ->seeJson([
                'result' => 'true',
                'msg' => 'success',
            ]);
        $this->visit('/page/left-nav/cool1')
            ->see('cool3');
        //Test Del
        $this->withSession(['user.id' => 1, 'user.power' => '3', 'user.sessionReality' => true])
            ->json('DELETE', '/page/2')
            ->seeJson([
                'result' => 'true',
                'msg' => 'success',
            ]);
        //Test sophisticated del
        $page1 = new Page();
        $page1->father_id = 1;
        $page1->title = "s1";
        $page1->is_folder = true;
        $page1->save();
        $page2 = new Page();
        $page2->father_id = $page1->id;
        $page2->title = "s2";
        $page2->is_folder = true;
        $page2->save();
        $page3 = new Page();
        $page3->father_id = $page2->id;
        $page3->title = "s3";
        $page3->is_folder = false;
        $page3->save();
        $this->withSession(['user.id' => 1, 'user.power' => '3'])
            ->json('PUT', '/page/' . $page3->id, ['title' => 's4'])
            ->seeJson([
                'result' => 'true',
                'msg' => 'success',
            ]);
        $this->withSession(['user.id' => 1, 'user.power' => '3'])
            ->json('PUT', '/page/' . $page3->id, ['title' => 's5'])
            ->seeJson([
                'result' => 'true',
                'msg' => 'success',
            ]);
        $this->withSession(['user.id' => 1, 'user.power' => '3', 'user.sessionReality' => true])
            ->json('DELETE', '/page/' . $page1->id)
            ->seeJson([
                'result' => 'true',
                'msg' => 'success',
            ]);
        $this->get('/s1')->assertResponseStatus(404);
        $this->get('/s2')->assertResponseStatus(404);
        $this->get('/s3')->assertResponseStatus(404);
        $this->get('/s4')->assertResponseStatus(404);
        $this->get('/s5')->assertResponseStatus(404);
        //Test illegal access
        $this->visit('/auth/logout');
        $this->put('/page/2', ['title' => 'testtest2'])
            ->assertResponseStatus(404);
        $this->post('/page/move/2', ['father_title' => 'testtest'])
            ->assertResponseStatus(404);
        $this->delete('/page/2')
            ->assertResponseStatus(404);
        $this->json('POST', '/page')
            ->assertRedirectedTo('/auth/login?continue=%2Fpage');
        $this->withSession(['user.id' => 1, 'user.power' => '3'])
            ->json('DELETE', '/page/2')
            ->assertRedirectedTo('/auth/confirm?continue=%2Fpage%2F2');
    }
}
