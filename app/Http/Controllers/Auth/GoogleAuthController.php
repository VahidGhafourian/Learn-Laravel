<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Psy\Util\Str;

class GoogleAuthController extends Controller
{
    use TwoFactorAuthenticate;

    public function redirect() {
        return Socialite::driver('google')->redirect();
    }
    public function callback() {
        try {
            $googleUser = Socialite::driver('google')->user();
            $user = User::where('email', $googleUser->email)->first();

            if (! $user){
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'password' => bcrypt(\Str::random(16)),
                    'two_factor_type' => 'off'
                ]);
            }

            if(! $user->hasVerifiedEmail()) {
                $user->markEmailAsVerified();
            }

            auth()->loginUsingId($user->id);

            return $this->loggedin(\request(), $user) ?: $this->redirect('/');

        } catch (\Exception $e){
//            dd($e)

            alert()->error('Login with Google faild', 'Message')->persistent('Ok');
            return redirect('/login');
        }
    }
}
