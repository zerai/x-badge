<?php declare(strict_types=1);

namespace Badge\Core;

interface Image
{
    public function getContent(): string;

    public function getFileName(): string;
}
