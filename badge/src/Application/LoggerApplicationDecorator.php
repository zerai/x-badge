<?php declare(strict_types=1);

namespace Badge\Application;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class LoggerApplicationDecorator implements BadgeApplicationInterface
{
    private BadgeApplicationInterface $application;

    private LoggerInterface $logger;

    public function __construct(BadgeApplicationInterface $application, LoggerInterface $logger = null)
    {
        $this->application = $application;
        $this->logger = $logger ?: new NullLogger();
    }

    public function createComposerLockBadge(string $packageName): Image
    {
        try {
            $badgeImage = $this->application->createComposerLockBadge($packageName);
        } catch (\Throwable $exception) {
            $this->logger->info($exception->getMessage());

            throw $exception;
        }

        return $badgeImage;
    }

    public function createGitattributesBadge(string $packageName): Image
    {
        try {
            $badgeImage = $this->application->createGitattributesBadge($packageName);
        } catch (\Throwable $exception) {
            $this->logger->info($exception->getMessage());

            throw $exception;
        }

        return $badgeImage;
    }

    public function createSuggestersBadge(string $packageName): Image
    {
        try {
            $badgeImage = $this->application->createSuggestersBadge($packageName);
        } catch (\Throwable $exception) {
            $this->logger->info($exception->getMessage());

            throw $exception;
        }

        return $badgeImage;
    }

    public function createDependentsBadge(string $packageName): Image
    {
        try {
            $badgeImage = $this->application->createDependentsBadge($packageName);
        } catch (\Throwable $exception) {
            $this->logger->info($exception->getMessage());

            throw $exception;
        }

        return $badgeImage;
    }

    public function createTotalDownloadsBadge(string $packageName): Image
    {
        try {
            $badgeImage = $this->application->createTotalDownloadsBadge($packageName);
        } catch (\Throwable $exception) {
            $this->logger->info($exception->getMessage());

            throw $exception;
        }

        return $badgeImage;
    }

    public function createMonthlyDownloadsBadge(string $packageName): Image
    {
        try {
            $badgeImage = $this->application->createMonthlyDownloadsBadge($packageName);
        } catch (\Throwable $exception) {
            $this->logger->info($exception->getMessage());

            throw $exception;
        }

        return $badgeImage;
    }

    public function createDailyDownloadsBadge(string $packageName): Image
    {
        try {
            $badgeImage = $this->application->createDailyDownloadsBadge($packageName);
        } catch (\Throwable $exception) {
            $this->logger->info($exception->getMessage());

            throw $exception;
        }

        return $badgeImage;
    }

    public function createStableVersionBadge(string $packageName): Image
    {
        try {
            $badgeImage = $this->application->createStableVersionBadge($packageName);
        } catch (\Throwable $exception) {
            $this->logger->info($exception->getMessage());

            throw $exception;
        }

        return $badgeImage;
    }

    public function CreateUnstableVersionBadge(string $packageName): Image
    {
        try {
            $badgeImage = $this->application->CreateUnstableVersionBadge($packageName);
        } catch (\Throwable $exception) {
            $this->logger->info($exception->getMessage());

            throw $exception;
        }

        return $badgeImage;
    }
}
