<?php

namespace Biskuit\User;

use Biskuit\Application as App;
use Biskuit\Module\Module;
use Biskuit\User\Model\Role;
use Biskuit\User\Model\User;

class UserModule extends Module
{
    protected $perms = [];

    /**
     * {@inheritdoc}
     */
    public function main(App $app)
    {
        $app['user'] = function ($app) {

            if (!$user = $app['auth']->getUser()) {
                $user = User::create(['roles' => [Role::ROLE_ANONYMOUS]]);
            }

            return $user;
        };
    }

    /**
     * @return array
     */
    public function getPermissions()
    {
        if (!$this->perms) {

            foreach (App::module() as $module) {
                if ($perms = $module->get('permissions')) {
                    $this->registerPermissions($module->get('name'), $perms);
                }
            }

            App::trigger('user.permission', [$this]);
        }

        return $this->perms;
    }

    /**
     * Register permissions.
     *
     * @param string $extension
     * @param array  $permissions
     */
    public function registerPermissions($extension, array $permissions = [])
    {
        $this->perms[$extension] = $permissions;
    }
}
