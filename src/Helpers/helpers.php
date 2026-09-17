<?php

if (! function_exists('acl_menus')) {
    function acl_menus($user = null, bool $activeOnly = true)
    {
        return \Tahmid\AclManager\Facades\Acl::getMenus($user, $activeOnly);
    }
}

if (! function_exists('acl_menu_tree')) {
    function acl_menu_tree($user = null, bool $activeOnly = true)
    {
        return \Tahmid\AclManager\Facades\Acl::getMenuTree($user, $activeOnly);
    }
}
