<?php

namespace App\Enums;

enum TaskStagesEnums: string
{
  case NOT_STARTED = 'Not started';
  case IN_PROGRESS = 'In progress';
  case COMPLETED = 'Completed';

}
