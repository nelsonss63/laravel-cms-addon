<?php

namespace Cednet\Cms;

use Cms\Models\User;
use Former\Facades\Former;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Lang;

class UsersController extends \Cednet\Cms\CmsAdminBaseController
{

    protected $layout = 'cms::layouts.edit';
    protected $nav = array();

    public function __construct()
    {
        parent::__construct();
        View::share("title", "Users");
    }

    public function users()
    {
        return View::make('cms::edit.users.users', array(
            "users" => User::all()
        ));
    }

    public function createUser()
    {
        return View::make('cms::edit.users.user', array(
            "user" => new User()
        ));
    }

    public function editUser($userId)
    {
        $user = User::find($userId);
        $populate = $user->getAttributes();
        unset($populate['password']);
        Former::populate($populate);
        return View::make('cms::edit.users.user', array(
            "user" => $user
        ));
    }

    public function saveUser($userId = 0)
    {
        $data = array(
            "username" => Input::get('username'),
            "edit" => Input::get('edit') ? true : false,
            "admin" => Input::get('admin') ? true : false,
        );
        if(Input::get('password')) {
            $data['password'] = Hash::make(Input::get('password'));
        }
        if($userId) {
            $user = User::where("id", "=", $userId)->update($data);
        } else {
            $user = User::create($data);
        }

        return Redirect::route('users')->with('flash_notice', Lang::get('cms::m.saved'));
    }

    /**
     * Removing a User must also reset all pages to user_id = 0 (put in Unsorted Pages)
     * @param $userId
     * @return mixed
     */
    public function removeUser($userId)
    {
        if($userId == 1) return Redirect::back()->with('flash_error', 'Cannot remove main user');

        $user = User::find($userId);
        if($user) $user->delete();

        return Redirect::route('users')->with('flash_notice', 'User removed');
    }

}