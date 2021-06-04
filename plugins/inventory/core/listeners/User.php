<?php namespace Inventory\Core\Listeners;

use Flash;
use Cms\Classes\Page;
use ApplicationException;
use RainLab\User\Models\User as UserModel;
use RainLab\User\Models\UserGroup;
use Inventory\Core\Classes\Subdomain;

class User
{
    public function checkUserType($account, $credentials)
    {
        $user = UserModel::findByEmail($credentials['login']);

        if (!$user) throw new ApplicationException('User not found.');
    }

    public function afterLogin(UserModel $user)
    {
        if ($user) {
            return redirect(Page::url('dashboard'));
        } 
    }

    public function handleRegisteredUser(UserModel $user, array $data)
    {
        $user->groups()->save(UserGroup::whereCode('registered')->first());
    }
}
