<?php namespace Kowali\Permissions;

use Illuminate\Config\Repository as Configuration;
use App;

class Manager {

    /**
     * A link to laravel config repository
     *
     * @var \Illuminate\Config\Repository
     */
    protected $config;

    public function __construct(Configuration $config = null)
    {
        $this->config = $config ?: App::make('config');
    }

    public function check($user, $action, $object = null)
    {
        $user_roles = array_map('trim', explode(',', $user->roles));

        if(in_array('administrator', $user_roles))
        {
            return true;
        }

        if( ! is_null($object))
        {
            if(is_object($object))
            {
                $action = str_plural(mb_strtolower(class_basename($object))) . '.' . $action;
            }
            else
            {
                $action = "{$object}.{$action}";
            }
        }

        if($groups = $this->config->get("permissions.actions.{$action}"))
        {
            $groups = (array)$groups;
            if(in_array('*', $groups))
            {
                return true;
            }

            foreach($groups as $group)
            {
                if(in_array($group, $user_roles))
                {
                    return true;
                }
            }
        }
        if($permissions = $this->config->get("permissions.users.{$user->slug}"))
        {
            if(in_array($action, $permissions))
            {
                return true;
            }
        }

        if(is_object($object) && method_exists($object, 'getOwnerId'))
        {
            if($object->getOwnerId() == $user->id)
            {
                return true;
            }
        }

        return false;
    }

}
