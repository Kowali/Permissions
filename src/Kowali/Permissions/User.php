<?php namespace Kowali\Permissions;

use Illuminate\Auth\UserTrait;

class User {

    protected $user;

    public function __construct($user = null, Manager $manager = null)
    {
        $this->user = $user ?: \App::make('auth')->user();
        $this->manager = $manager ?: \App::make('permissions.manager');
    }

    public function __call($method, $args)
    {
        if(strpos($method, 'can') === 0)
        {
            $action = mb_strtolower(implode('.', preg_split('/(?=\p{Lu})/u', lcfirst(substr($method, 3)))));

            switch(count($args)){
            case 0:
                return $this->manager->check($this->user, $action);
            case 1:
                return $this->manager->check($this->user, $action, $args[0]);
            }
        }
    }
}
