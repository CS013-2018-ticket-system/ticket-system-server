<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class JaccountController extends Controller
{
    public function auth()
    {
        $provider = new \League\OAuth2\Client\Provider\GenericProvider([
            'clientId'                => env('JACCOUNT_APPID'),
            'clientSecret'            => env('JACCOUNT_APPSECRET'),
            'redirectUri'             => url("/auth/jaccount"),
            'urlAuthorize'            => 'https://jaccount.sjtu.edu.cn/oauth2/authorize',
            'urlAccessToken'          => 'https://jaccount.sjtu.edu.cn/oauth2/token',
            'urlResourceOwnerDetails' => 'https://api.sjtu.edu.cn/v1/me/profile'
        ]);

        if (!isset($_GET['code'])) {
            $authorizationUrl = $provider->getAuthorizationUrl();
            $_SESSION['oauth2state'] = $provider->getState();
            header('Location: ' . $authorizationUrl);
            exit;

        } elseif (empty($_GET['state']) || (isset($_SESSION['oauth2state']) && $_GET['state'] !== $_SESSION['oauth2state'])) {

            if (isset($_SESSION['oauth2state'])) {
                unset($_SESSION['oauth2state']);
            }

            exit('Invalid state');

        } else {

            try {
                $accessToken = $provider->getAccessToken('authorization_code', [
                    'code' => $_GET['code']
                ]);

                $response = file_get_contents('https://api.sjtu.edu.cn/v1/me/profile?access_token=' . $accessToken->getToken());
                $user = json_decode($response)->entities[0];

                if (!Auth::attempt(['jaccount' => $user->account, 'password' => md5($user->name)])) {
                    $new_user = new User(array(
                        "name" => $user->name,
                        "jaccount" => $user->account,
                        "password" => Hash::make(md5($user->name)),
                        "college" => $user->organize->name,
                        "student_id" => $user->code,
                    ));
                    $new_user->save();
                    Auth::attempt(['jaccount' => $user->account, 'password' => md5($user->name)]);
                    return redirect()->intended("/home");
                }

            } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {

                // Failed to get the access token or user details.
                exit($e->getMessage());

            }

        }
    }
}
