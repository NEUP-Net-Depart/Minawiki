<?php

namespace Tests\Feature;

use Tests\BrowserKitTestCase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Page;
use App\Version;

class VersionTest extends BrowserKitTestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBackend()
    {
        //Test add version
        $page = new Page();
        $page->father_id = 1;
        $page->title = "VersionTest";
        $page->is_folder = false;
        $page->save();

        $this->withSession(['user.id' => 1, 'user.power' => '3'])
            ->json('POST', '/VersionTest/update', ['text' => 'update1', 'is_little' => 'false'])
            ->seeJson([
                'result' => 'true',
                'msg' => 'success',
                'number' => 1
            ]);
        $this->withSession(['user.id' => 1, 'user.power' => '3'])
            ->json('POST', '/VersionTest/update', ['text' => 'update2', 'is_little' => 'true'])
            ->seeJson([
                'result' => 'true',
                'msg' => 'success',
                'number' => 2
            ]);
        $this->withSession(['user.id' => 1, 'user.power' => '3'])
            ->json('POST', '/fakeroot/update', ['text' => 'update1', 'is_little' => 'true'])
            ->seeJson([
                'result' => 'false',
                'msg' => 'invalid title'
            ]);
        //Test get version
        $this->withSession(['user.id' => 1, 'user.power' => '3'])
            ->json('GET', '/VersionTest/update')
            ->seeJson([
                'result' => 'true',
                'content' => '<p>update2</p>'
            ]);
        $this->withSession(['user.id' => 1, 'user.power' => '3'])
            ->json('GET', '/fakeroot/update')
            ->seeJson([
                'result' => 'false',
                'msg' => 'invalid title'
            ]);
        //Test history
        $this->withSession(['user.id' => 1, 'user.power' => '3'])
            ->json('GET', '/VersionTest/history')
            ->seeJson([
                'result' => 'true'
            ]);
        //Test delete version
        $this->withSession(['user.id' => 1, 'user.power' => '3', 'user.sessionReality' => true])
            ->json('DELETE', '/page/' . $page->id)
            ->seeJson([
                'result' => 'true',
                'msg' => 'success',
            ]);
        $this->assertTrue(Version::where('page_id', $page->id)->count() == 0);
    }
}
