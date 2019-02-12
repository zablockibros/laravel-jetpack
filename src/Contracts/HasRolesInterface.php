<?php

namespace ZablockiBros\Jetpack\Contracts;

interface HasRolesInterface
{
    /**
     * @return mixed
     */
    public function roles();

    /**
     * @param $role
     *
     * @return mixed
     */
    public function attachRole($role);

    /**
     * @param $role
     *
     * @return bool
     */
    public function hasRole($role): bool;

    /**
     * @param $role
     *
     * @return mixed
     */
    public function withRole($role);

    /**
     * @param $role
     * @param $permission
     *
     * @return bool
     */
    public function ability($role, $permission): bool;
}
