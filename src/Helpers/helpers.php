<?php

if (! function_exists('acl_menus')) {
    function acl_menus($user = null, bool $activeOnly = true)
    {
        return \Acl::getMenus($user, $activeOnly);
    }
}

if (! function_exists('acl_menu_tree')) {
    function acl_menu_tree($user = null, bool $activeOnly = true)
    {
        return \Acl::getMenuTree($user, $activeOnly);
    }
}
