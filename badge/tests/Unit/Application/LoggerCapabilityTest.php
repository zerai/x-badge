<?php declare(strict_types=1);

namespace Badge\Tests\Unit\Application;

use Badge\Application\BadgeApplicationInterface;
use Badge\Application\LoggerApplicationDecorator;
use InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

use function PHPUnit\Framework\once;

/** @covers \Badge\Application\LoggerApplicationDecorator */
final class LoggerCapabilityTest extends TestCase
{
    /**
     * @var LoggerInterface&MockObject
     */
    private $logger;

    /**
     * @var BadgeApplicationInterface&MockObject
     */
    private $application;

    private LoggerApplicationDecorator $decorator;

    public function setUp(): void
    {
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->application = $this->createMock(BadgeApplicationInterface::class);

        $this->decorator = new LoggerApplicationDecorator(
            $this->application,
            $this->logger
        );
    }

    /**
     * @test
     */
    public function shouldLogExceptionForComposerLockUsecase(): void
    {
        self::expectException(InvalidArgumentException::class);

        $this->logger
            ->expects(once())
            ->method('info');

        $this->application
            ->expects(once())
            ->method('createComposerLockBadge')
            ->will($this->throwException(new InvalidArgumentException('foo')));

        $this->decorator->createComposerLockBadge('foobar');
    }

    /**
     * @test
     */
    public function shouldLogExceptionForGitattributesUsecase(): void
    {
        self::expectException(InvalidArgumentException::class);

        $this->logger
            ->expects(once())
            ->method('info');

        $this->application
            ->expects(once())
            ->method('createGitattributesBadge')
            ->will($this->throwException(new InvalidArgumentException('foo')));

        $this->decorator->createGitattributesBadge('foobar');
    }

    /**
     * @test
     */
    public function shouldLogExceptionForSuggestersUsecase(): void
    {
        self::expectException(InvalidArgumentException::class);

        $this->logger
            ->expects(once())
            ->method('info');

        $this->application
            ->expects(once())
            ->method('createSuggestersBadge')
            ->will($this->throwException(new InvalidArgumentException('foo')));

        $this->decorator->createSuggestersBadge('foobar');
    }

    /**
     * @test
     */
    public function shouldLogExceptionForDependentsUsecase(): void
    {
        self::expectException(InvalidArgumentException::class);

        $this->logger
            ->expects(once())
            ->method('info');

        $this->application
            ->expects(once())
            ->method('createDependentsBadge')
            ->will($this->throwException(new InvalidArgumentException('foo')));

        $this->decorator->createDependentsBadge('foobar');
    }

    /**
     * @test
     */
    public function shouldLogExceptionForTotalDownloadsUsecase(): void
    {
        self::expectException(InvalidArgumentException::class);

        $this->logger
            ->expects(once())
            ->method('info');

        $this->application
            ->expects(once())
            ->method('createTotalDownloadsBadge')
            ->will($this->throwException(new InvalidArgumentException('foo')));

        $this->decorator->createTotalDownloadsBadge('foobar');
    }

    /**
     * @test
     */
    public function shouldLogExceptionForMonthlyDownloadsUsecase(): void
    {
        self::expectException(InvalidArgumentException::class);

        $this->logger
            ->expects(once())
            ->method('info');

        $this->application
            ->expects(once())
            ->method('createMonthlyDownloadsBadge')
            ->will($this->throwException(new InvalidArgumentException('foo')));

        $this->decorator->createMonthlyDownloadsBadge('foobar');
    }

    /**
     * @test
     */
    public function shouldLogExceptionForDailyDownloadsUsecase(): void
    {
        self::expectException(InvalidArgumentException::class);

        $this->logger
            ->expects(once())
            ->method('info');

        $this->application
            ->expects(once())
            ->method('createDailyDownloadsBadge')
            ->will($this->throwException(new InvalidArgumentException('foo')));

        $this->decorator->createDailyDownloadsBadge('foobar');
    }

    /**
     * @test
     */
    public function shouldLogExceptionForStableVersionDownloadsUsecase(): void
    {
        self::expectException(InvalidArgumentException::class);

        $this->logger
            ->expects(once())
            ->method('info');

        $this->application
            ->expects(once())
            ->method('createStableVersionBadge')
            ->will($this->throwException(new InvalidArgumentException('foo')));

        $this->decorator->createStableVersionBadge('foobar');
    }

    /**
     * @test
     */
    public function shouldLogExceptionForUnstableVersionDownloadsUsecase(): void
    {
        self::expectException(InvalidArgumentException::class);

        $this->logger
            ->expects(once())
            ->method('info');

        $this->application
            ->expects(once())
            ->method('CreateUnstableVersionBadge')
            ->will($this->throwException(new InvalidArgumentException('foo')));

        $this->decorator->CreateUnstableVersionBadge('foobar');
    }
}
