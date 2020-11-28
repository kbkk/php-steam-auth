<?php

namespace SteamAuth;

interface SteamApiInterface
{
    public function getProfile($sid);
}