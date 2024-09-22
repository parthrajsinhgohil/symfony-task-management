<?php

namespace App\Components;

enum TaskStatusEnum: string
{
    case PENDING = 'pending';
    case INPROGRESS = 'in_progress';
    case COMPLETED = 'completed';
}