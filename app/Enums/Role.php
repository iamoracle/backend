<?php

namespace App\Enums;

enum RoleKey: string
{
    case SUPER_ADMIN = 'super-admin';
    case ADMIN = 'admin';
    case STUDENT = 'student';
}
