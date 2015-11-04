<?php

namespace app\components;

class Roles
{
    /**
     * Has access to REST API
     */
    const API_USER = 'API_USER';

    /**
     * Has access to manage any partitions in admin panel
     */
    const MANAGER = 'MANAGER';

    /**
     * Has full access to application
     */
    const ADMIN = 'ADMIN';
}