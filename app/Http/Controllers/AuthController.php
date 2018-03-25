<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use GuzzleHttp\Client;

use \App\Http\Resources\UserResource;

use App\Models\Users;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['loginUser', 'registerUser', 'registerViaTwitch', 'getTwitchCode']]);
    }

    public function loginUser(Request $request) {
        $usersArray = $request->all();
        $credentials = [
            'email'     => $usersArray['email'],
            'password'  => $usersArray['password']
        ];
        if (! $token = Auth::attempt($credentials)) {
            return $this->returnDefault(true, 401);
        }
        return $this->respondWithToken($token);
    }

    public function registerUser(Request $request) {
        try {
            $usersArray = $request->all();
            if ($this->checkTwitchUser($usersArray['username'], $usersArray['email'])) {
                $modelArray = [
                    'name'              => $usersArray['username'],
                    'slug'              => str_slug($usersArray['username']),
                    'about'             => 'No About Text',
                    'email'             => $usersArray['email'],
                    'password'          => Hash::make($usersArray['password']),
                    'password_raw'      => '--Hidden--',
                    'active'            => '0',
                ];
                $model = new Users($modelArray);
                $model->save();
                $model->assignRole('Viewer');
                return response($model, 200);
            } else {
                return $this->returnDefault();
            }
        } catch (\Exception $e) {
            return response($e, 400);
        }
    }

    public function me() {
        if (Auth::check()) {
            $authUser = Auth::user();
            $collection = new UserResource($authUser);
            return $collection;
        } else {
            return $this->returnDefault(true);
        }
    }

    public function registerViaTwitch() {
        $client_id = "z24l30mfptjb9087170vfozkrkhymb";
        $client_secret = "s0g4924sph1ggz9kw74vvxjvmfylfa";
        $scopes = ['user_read', 'channel_read'];
        $scopeString = implode("+", $scopes);
        $base_url = "http://toxicblog.local/api/auth/register/twitch/code/";
        $url = "https://api.twitch.tv/kraken/oauth2/authorize?response_type=code&client_id=".$client_id."&redirect_uri=".$base_url."&scope=".$scopeString;
        return [
            'data' => $url
        ];
    }

    public function getTwitchCode(Request $request) {
        $array = [];
        $code = $request->query('code');
        if (!empty($code)) {
            $tokenArray = $this->getTwitchAccessToken($code);
            if (!empty($tokenArray)) {
                $token = $tokenArray['access_token'];
                $userObject = $this->getTwitchUserObject($token);
                if (!empty($userObject)) {
                    $array = $this->getTwitchUserData($userObject, $token);
                    //
                    $password = str_random(8);
                    $userArray = [
                        'name'          => $array['display_name'],
                        'slug'          => $array['name'],
                        'about'         => (!empty($array['bio'])) ? $array['bio'] : 'No About Text',
                        'email'         => $array['email'],
                        'password'      => Hash::make($password),
                        'password_raw'  => $password,
                        'active'        => '1',
                    ];
                    //
                    if ($this->checkTwitchUser($userArray['name'], $userArray['email'])) {
                        $model = new Users($userArray);
                        $model->save();
                        $model->assignRole('Viewer');
                    }
                    return redirect('http://localhost:4200/#/blog');
                } else {
                    return $this->returnDefault();
                }
            } else {
                return $this->returnDefault();
            }
        } else {
            return $this->returnDefault();
        }
    }

    private function returnDefault($error = true, $code = 404) {
        if ($error) {
            return abort($code);
        } else {
            return [
                'data' => [],
                'count' => 0
            ];
        }
    }

    private function respondWithToken($token) {
        $response = [
            'data' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => 3600
            ]
        ];
        return response()->json( $response );
    }

    private function getTwitchAccessToken($code) {
        $client_id = "z24l30mfptjb9087170vfozkrkhymb";
        $client_secret = "s0g4924sph1ggz9kw74vvxjvmfylfa";
        //
        $ch = curl_init("https://api.twitch.tv/kraken/oauth2/token");
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        $fields = array(
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'grant_type' => 'authorization_code',
            'redirect_uri' => "http://toxicblog.local/api/auth/register/twitch/code/",
            'code' => $code
        );
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        $data = curl_exec($ch);
        $response = json_decode($data, true);
        return $response;
    }

    private function getTwitchUserObject($token) {
        $client = new Client();
        $response = $client->get('https://api.twitch.tv/kraken?oauth_token=' . $token);
        return json_decode($response->getBody()->getContents(), true);
    }

    private function getTwitchUserData($user, $token) {
        $link = $user['_links']['user'];
        //
        $client = new Client();
        $response = $client->get($link . "?oauth_token=" . $token);
        return json_decode($response->getBody()->getContents(), true);
    }

    private function checkTwitchUser($username, $email) {
        $validArray = [
            'username'  => true,
            'email'     => true
        ];
        //
        $model = Users::where('name', '=', $username)
        ->first();
        if (!empty($model->name)) {
            $validArray['username'] = false;
        }
        //
        $model = Users::where('email', '=', $email)
        ->first();
        if (!empty($model->email)) {
            $validArray['email'] = false;
        }
        //
        return $validArray['username'] && $validArray['email'];
    }
}
