<?php

namespace App\Model;

interface CommandInterface
{
    public function runCommand(array $argv): void;
}