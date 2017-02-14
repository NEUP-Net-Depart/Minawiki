<?php

namespace App\Http\Controllers;

use App\Http\Middleware\RedirectIfAuthenticated;
use App\User;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Germey\Geetest\GeetestCaptcha;


class AuthController extends Controller
{
    use GeetestCaptcha;

    /**
     * @param Request $request
     * @return view
     */
    public function showRegisterView(Request $request)
    {
        if($request->session()->has('user.id'))
            return redirect('/');
        return view('auth.register');
    }

    /**
     * @param Request $request
     * @return view
     */
    public function showLoginView(Request $request)
    {
        if($request->session()->has('user.id'))
            return redirect('/');
        return view('auth.login');
    }

    /**
     * @param Request $request
     * @return view
     */
    public function showForgetView(Request $request)
    {
        if($request->session()->has('user.id'))
            return redirect('/');
        return view('auth.resetpass');
    }

    /**
     * @param Request $request
     * @return bool
     */
    private function geetestValidate(Request $request)
    {
        $this->validate($request, [
            'geetest_challenge' => 'geetest',
        ], [
            'geetest' => config('geetest.server_fail_alert')
        ]);

        return true;
    }

    /**
     * @param Request $request
     * @return string(json)
     */
    public function sendTextCaptcha(Request $request)
    {
        //Check Geetest status
        if(!$this->geetestValidate($request))
            return redirect('/');
        //Check last sent time
        if($request->session()->has('captcha.timestamp'))
        {
            if(time() - intval($request->session()->get('captcha.timestamp')) <= 25)
                return json_encode(array('result' => 'false', 'msg' => 'send interval too short'));
        }

        //Check if tel exist
        if (User::where('tel', $request->tel)->count() > 0)
            return json_encode(array('result' => 'false', 'msg' => 'telephone already exists'));
        //Generate random captcha
        $captcha = "";
        for ($i = 0; $i < 6; ++$i)
            $captcha .= strval(rand(1, 9));
        //Save info to session
        $request->session()->put('captcha.tel', $request->tel);
        $request->session()->put('captcha.text', $captcha);
        $request->session()->put('captcha.timestamp', time());
        //Post to Yunpian api
        $ch = curl_init();

        $apikey = env('YUNPIAN_KEY');
        $mobile = $request->tel;
        $text = "【东大水站】您的验证码是" . $captcha;

        $data = array('text' => $text, 'apikey' => $apikey, 'mobile' => $mobile);
        curl_setopt($ch, CURLOPT_URL, 'https://sms.yunpian.com/v2/sms/single_send.json');
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        return strval(curl_exec($ch));
    }

    /**
     * @param Request $request
     */
    public function addUser(Request $request)
    {
        if (env('APP_ENV') != 'testing')
            $this->validate($request, [
                'tel' => 'required',
                'password' => 'required',
                'captcha' => 'required'
            ]);
        else
            $this->validate($request, [
                'tel' => 'required',
                'password' => 'required'
            ]);
        //Check if tel distorted
        if ($request->tel != $request->session()->get('captcha.tel'))
            return json_encode(array('result' => 'false', 'msg' => 'distorted telephone'));
        if (env('APP_ENV') != 'testing') {
            //Validate text captcha
            if (!isset($request->captcha) || !$request->session()->has('captcha.text') ||
                !$request->captcha == $request->session()->get('captcha.text')
            )
                return json_encode(array('result' => 'false', 'msg' => 'invalid captcha'));
            if (!$request->session()->has('captcha.timestamp') ||
                time() - intval($request->session()->get('captcha.timestamp')) > 5 * 60
            )
                return json_encode(array('result' => 'false', 'msg' => 'expired captcha'));
        }
        //Check if tel exist
        if (User::where('tel', $request->tel)->count() > 0)
            return json_encode(array('result' => 'false', 'msg' => 'telephone already exists'));
        //Generate random salt
        $salt = base64_encode(random_bytes(24));
        //Construct new user
        $user = new User;
        $user->tel = $request->tel;
        $user->password = Hash::make($salt . $request->password);
        $user->salt = $salt;
        $user->disable = false;
        $user->active_point = 0;
        $user->contribute_point = 0;
        $user->power = 0;
        $user->admin_name = "";
        $user->theme = "yayin";
        $user->no_disturb = false;
        $user->token = $user->tel . strval(time());
        //Save new user
        $user->save();
        $request->session()->put('user.id', $user->id);
        $request->session()->put('user.tel', $user->tel);
        $request->session()->put('user.theme', $user->theme);
        $request->session()->put('user.power', $user->power);
        $request->session()->put('user.admin', $user->admin_name);
        $cookie = Cookie::make('user.token', $user->token, 2 * 30 * 24 * 60);
        return response(json_encode(array('result' => 'true', 'msg' => 'success')))
            ->withCookie($cookie);
    }

    /**
     * @param Request $request
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'tel' => 'required',
            'password' => 'required'
        ]);
        if (User::where('tel', $request->tel)->count() == 0)
            return json_encode(array('result' => 'false', 'msg' => 'wrong'));
        $user = User::where('tel', $request->tel)->first();

        if (Hash::check($user->salt . $request->password, $user->password)) {
            //Generate and save token
            $user->token = $user->tel . strval(time());
            $user->save();
            $request->session()->put('user.id', $user->id);
            $request->session()->put('user.tel', $user->tel);
            $request->session()->put('user.theme', $user->theme);
            $request->session()->put('user.power', $user->power);
            $request->session()->put('user.admin', $user->admin_name);

            $cookie = Cookie::make('user.token', $user->token, 2 * 30 * 24 * 60);
            return response(json_encode(array('result' => 'true', 'msg' => 'success')))
                ->withCookie($cookie);
        }
        else
            return json_encode(array('result' => 'false', 'msg' => 'wrong'));
    }

    /**
     * @param Request $request
     */
    public function logout(Request $request)
    {
        //Clear cookie and session
        $cookie = Cookie::forget('user.token');
        $request->session()->forget('user.id');
        $request->session()->forget('user.tel');
        $request->session()->forget('user.theme');
        $request->session()->forget('user.power');
        $request->session()->forget('user.admin');
        return redirect('/')->withCookie($cookie);
    }

    /**
     * @param Request $request
     * @return string(json)
     */
    public function sendForgetTextCaptcha(Request $request)
    {
        //Check Geetest status
        if(!$this->geetestValidate($request))
            return redirect('/');
        //Check last sent time
        if($request->session()->has('captcha.timestamp'))
        {
            if(time() - intval($request->session()->get('captcha.timestamp')) <= 25)
                return json_encode(array('result' => 'false', 'msg' => 'send interval too short'));
        }

        //Check if tel exists
        if (User::where('tel', $request->tel)->count() == 0)
            return json_encode(array('result' => 'false', 'msg' => 'wrong telephone'));
        //Generate random captcha
        $captcha = "";
        for ($i = 0; $i < 6; ++$i)
            $captcha .= strval(rand(1, 9));
        //Save info to session
        $request->session()->put('captcha.tel', $request->tel);
        $request->session()->put('captcha.text', $captcha);
        $request->session()->put('captcha.timestamp', time());
        //Post to Yunpian api
        $ch = curl_init();

        $apikey = env('YUNPIAN_KEY');
        $mobile = $request->tel;
        $text = "【东大水站】您的验证码是" . $captcha;

        $data = array('text' => $text, 'apikey' => $apikey, 'mobile' => $mobile);
        curl_setopt($ch, CURLOPT_URL, 'https://sms.yunpian.com/v2/sms/single_send.json');
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        return strval(curl_exec($ch));
    }

    /**
     * @param Request $request
     */
    public function changePassword(Request $request)
    {
        if (env('APP_ENV') != 'testing')
            $this->validate($request, [
                'tel' => 'required',
                'password' => 'required',
                'captcha' => 'required'
            ]);
        else
            $this->validate($request, [
                'tel' => 'required',
                'password' => 'required'
            ]);
        //Check if tel distorted
        if ($request->tel != $request->session()->get('captcha.tel'))
            return json_encode(array('result' => 'false', 'msg' => 'distorted telephone'));
        if (env('APP_ENV') != 'testing') {
            //Validate text captcha
            if (!isset($request->captcha) || !$request->session()->has('captcha.text') ||
                !$request->captcha == $request->session()->get('captcha.text')
            )
                return json_encode(array('result' => 'false', 'msg' => 'invalid captcha'));
            if (!$request->session()->has('captcha.timestamp') ||
                time() - intval($request->session()->get('captcha.timestamp')) > 5 * 60
            )
                return json_encode(array('result' => 'false', 'msg' => 'expired captcha'));
        }
        //Check if tel exists
        if (User::where('tel', $request->tel)->count() == 0)
            return json_encode(array('result' => 'false', 'msg' => 'wrong telephone'));
        //Generate random salt
        $salt = base64_encode(random_bytes(24));
        //Update user
        $user = User::where('tel', $request->tel)->first();
        $user->tel = $request->tel;
        $user->password = Hash::make($salt . $request->password);
        $user->salt = $salt;
        $user->token = $user->tel . strval(time());
        //Save new user
        $user->save();

        return response(json_encode(array('result' => 'true', 'msg' => 'success')));
    }
}