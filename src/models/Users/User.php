<?php

namespace Cms\Models;

use CmsPassword;
use CmsUsername;
use Illuminate\Support\Facades\Session;

class User extends \Eloquent
{

    protected $table = 'cms_users';
    protected $fillable = array('username', 'password', 'edit', 'admin', 'session_token');
    protected $hidden = array('password');

    static $sessionTokenName = "cms_session";

    //Check if logged in and has user
    public static function check() {
        return (Session::has(self::$sessionTokenName) AND self::getUser());
    }

    public static function guest() {
        return !Session::has(self::$sessionTokenName);
    }

    public static function getUser() {
        if(self::guest()) return false;
        if($user = self::where("session_token", "=", Session::get(self::$sessionTokenName))->first()) {
            return $user;
        }
        return false;
    }

    /**
     * Make login attempt
     * Store to session if successful
     * @param CmsUsername $username
     * @param CmsPassword $password
     * @return bool
     */
    public static function attempt(CmsUsername $username, CmsPassword $password)
    {
        if($user = User::where("username", "=", $username)->where("password", "=", md5(md5($password)))->first()) {

            //Add token
            $user->update(array("session_token" => md5(time())));

            //Login user
            self::loginUser($user);

            return $user;
        }

        return false;
    }

    /**
     * Adds user to session
     * @param User $user
     */
    public static function loginUser(User $user)
    {
        Session::put(self::$sessionTokenName, $user->session_token);
    }

    public static function logout()
    {
        Session::forget(self::$sessionTokenName);
    }

}