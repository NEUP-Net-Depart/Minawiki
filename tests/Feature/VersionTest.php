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
        $this->post('/install', ['tel' => '12312312312', 'password' => 'admin', 'title' => 'Minawikiroot']);

        //Test add version
        $page = new Page();
        $page->father_id = 1;
        $page->title = "VersionTest";
        $page->is_folder = false;
        $page->power = 2;
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
        $this->withSession(['user.id' => 1, 'user.power' => '1'])
            ->json('POST', '/VersionTest/update', ['text' => 'update2', 'is_little' => 'true'])
            ->seeJson([
                'result' => 'false',
                'msg' => 'permission denied'
            ]);
        //Test get one version
        $this->withSession(['user.id' => 1, 'user.power' => '3'])
            ->json('GET', '/VersionTest/history/1')
            ->seeJson([
                'result' => 'true',
                'content' => '<p>update1</p>'
            ]);
        $this->withSession(['user.id' => 1, 'user.power' => '3'])
            ->json('GET', '/VersionTest/history/1000')
            ->seeJson([
                'result' => 'false',
                'msg' => 'invalid version id'
            ]);
        //Test history
        $this->withSession(['user.id' => 1, 'user.power' => '3'])
            ->visit('/VersionTest/history')
            ->see("VersionTest");
        //Test restore
        $this->withSession(['user.id' => 1, 'user.power' => '3'])
            ->json('PUT', '/faketitle/restore/1')
            ->seeJson([
                'result' => 'false',
                'msg' => 'invalid title'
            ]);
        $this->withSession(['user.id' => 1, 'user.power' => '3'])
            ->json('PUT', '/VersionTest/restore/1000')
            ->seeJson([
                'result' => 'false',
                'msg' => 'invalid version id'
            ]);
        $this->withSession(['user.id' => 1, 'user.power' => '1'])
            ->json('PUT', '/VersionTest/restore/1')
            ->seeJson([
                'result' => 'false',
                'msg' => 'permission denied'
            ]);
        $this->withSession(['user.id' => 1, 'user.power' => '3'])
            ->json('PUT', '/VersionTest/restore/1')
            ->seeJson([
                'result' => 'true',
                'msg' => 'success'
            ]);
        $this->withSession(['user.id' => 1, 'user.power' => '3'])
            ->json('GET', '/VersionTest/history/3')
            ->seeJson([
                'result' => 'true',
                'content' => '<p>update1</p>'
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
