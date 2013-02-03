<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function has_role($role_map, $role)
{
	return isset($role_map) && isset($role_map[$role]) && $role_map[$role] === TRUE;
}

function has_any_roles($role_map, $roles)
{
	foreach ($roles as $role)
	{
		if (has_role($role_map, $role)) return true;
	}
	return false;
}

function has_all_roles($role_map, $roles)
{
	foreach ($roles as $role)
	{
		if (has_role($role_map, $role) == false) return false;
	}
	return true;
}