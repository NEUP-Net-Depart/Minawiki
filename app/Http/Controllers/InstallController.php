<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Page;
use App\User;
use Illuminate\Support\Facades\Hash;

class InstallController extends Controller
{
    protected $reserved = ['auth', 'page', 'install'];
    protected $restricted = ['/', '\\', ':', '%', '&', '#', '=', '<', '>', '-', '*', '"', '\''];

    private function isInString($haystack, $needles)
    {
        $result = false;
        foreach ($needles as $needle) {
            $result = $result || (false !== strpos($haystack, $needle));
        }
        return $result;
    }

    private function isEqualString($haystack, $needles)
    {
        $result = false;
        foreach ($needles as $needle) {
            $result = $result || (false !== ($haystack == $needle));
        }
        return $result;
    }

    public function index()
    {
        if (Page::where('id', 1)->count() > 0 && User::where('power', '>', 2)->count() > 0)
            return redirect('/');
        return view('install');
    }

    public function install(Request $request)
    {
        if (Page::where('id', 1)->count() > 0 && User::where('power', '>', 2)->count() > 0)
            return redirect('/');
        //Generate random salt
        $salt = base64_encode(random_bytes(24));
        //Construct new user
        $user = new User;
        $user->tel = $request->tel;
        $user->password = Hash::make($salt . $request->password);
        $user->salt = $salt;
        $user->disable = false;
        $user->active_point = 500;
        $user->contribute_point = 500;
        $user->power = 3;
        $user->admin_name = "root";
        $user->theme = "yayin";
        $user->no_disturb = false;
        $user->token = $user->tel . strval(time());
        //Save new user
        $user->save();

        $this->validate($request, [
            'title' => 'required',
        ]);
        //Check keywords in title
        if ($this->isInString($request->title, $this->restricted))
            return json_encode([
                'result' => 'false',
                'msg' => 'restricted',
            ]);
        if ($this->isEqualString($request->title, $this->reserved))
            return json_encode([
                'result' => 'false',
                'msg' => 'reserved',
            ]);

        $page = new Page();

        $page->id = 1;
        $page->father_id = 0;
        $page->title = $request->title;

        $page->is_folder = true;
        $page->is_notice = false;
        $page->protect_children = true;
        $page->power = 3;

        $page->save();

        return redirect('/');
    }
}
