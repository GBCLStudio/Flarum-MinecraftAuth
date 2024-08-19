<?php

namespace Arffornia\MinecraftOauth\Exceptions;

class GameOwnershipCheckException extends MinecraftOauthException
{
    public function __construct()
    {
        parent::__construct('User does not own Minecraft');
    }
}
