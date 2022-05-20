<?php

namespace Hito\Core\Module\DTO;

class NotificationDTO
{
    public function __construct(public string $title,
                                public string $content,
                                public ?string $actionName = null,
                                public ?string $actionUrl = null)
    {
    }
}
