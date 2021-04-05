<?php declare(strict_types=1);

namespace Badge\Application\PortOut;

use Badge\Application\Domain\Model\Service\ContextProducer\DailyDownloadsReader;
use Badge\Application\Domain\Model\Service\ContextProducer\DependentsReader;
use Badge\Application\Domain\Model\Service\ContextProducer\MonthlyDownloadsReader;
use Badge\Application\Domain\Model\Service\ContextProducer\SuggestersReader;
use Badge\Application\Domain\Model\Service\ContextProducer\TotalDownloadsReader;

interface ContextValueReader extends SuggestersReader, DependentsReader, TotalDownloadsReader, MonthlyDownloadsReader, DailyDownloadsReader
{
}
