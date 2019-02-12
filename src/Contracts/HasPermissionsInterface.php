<?php

namespace ZablockiBros\Jetpack\Contracts;

interface HasPermissionsInterface
{
    /**
     * @return mixed
     */
    public function permissions();

    /**
     * @param $role
     *
     * @return mixed
     */
    public function attachPermission($role);

    /**
     * @param $permission
     *
     * @return bool
     */
    public function can($permission): bool;
}
