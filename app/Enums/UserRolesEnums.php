<?php

namespace App\Enums;

enum UserRolesEnums: string
{
  case ADMIN = 'Administrator';
  case MANAGER = 'Manager';
  case USER = 'User';

}
