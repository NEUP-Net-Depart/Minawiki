<?php

namespace Tests\Feature;

use Tests\BrowserKitTestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthTest extends BrowserKitTestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        //Test register
        $this->withSession(['captcha.tel' => '23333333333'])
            ->json('POST', '/auth/register', ['tel' => '13333333333', 'password' => 'cool2645'])
            ->seeJson([
                'result' => 'false',
                'msg' => 'distorted telephone',
            ]);
        $this->withSession(['captcha.tel' => '13333333333'])
            ->json('POST', '/auth/register', ['tel' => '13333333333', 'password' => 'cool2645'])
            ->seeJson([
                'result' => 'true',
                'msg' => 'success',
            ]);
        //Test login
        $this->json('POST', '/auth/login', ['tel' => '13333333333', 'password' => 'wrongpwd'])
            ->seeJson([
                'result' => 'false',
                'msg' => 'wrong',
            ]);
        $this->json('POST', '/auth/login', ['tel' => '13333333333', 'password' => 'cool2645'])
            ->seeJson([
                'result' => 'true',
                'msg' => 'success',
            ]);
    }
}
