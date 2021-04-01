<?php declare(strict_types=1);

namespace Badge\Tests\Integration;

use Badge\Adapter\Out\DefaultBranchDetector;
use Badge\Application\Domain\Model\RepositoryDetail;
use Bitbucket\Api\Workspaces;
use Bitbucket\Client as BitbucketClient;
use Github\Api\Repo;
use Github\Client as GithubClient;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class DefaultBranchDetectorTest extends TestCase
{
    /**
     * @var MockObject & GithubClient
     */
    private $githubClient;

    /**
     * @var MockObject & BitbucketClient
     */
    private $bitbucketClient;

    private DefaultBranchDetector $defaultBranchDetector;

    protected function setUp(): void
    {
        $this->githubClient = $this->getMockBuilder(GithubClient::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['api'])
            ->addMethods(['show', 'get'])
            ->getMock();

        $this->bitbucketClient = $this->getMockBuilder(BitbucketClient::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['workspaces', 'getHttpClient'])
            ->getMock();

        $this->defaultBranchDetector = new DefaultBranchDetector(
            $this->githubClient,
            $this->bitbucketClient
        );
    }

    /**
     * @test
     */
    public function shouldReadFromGithub(): void
    {
        $expectedDefaultBranch = 'irrelevant-branch';

        $repo = $this->createMockWithoutInvokingTheOriginalConstructor(Repo::class);

        $repo
            ->expects($this->once())
            ->method('show')
            ->with('irrelevantowner', 'irrelevant-repo-name')
            ->willReturn([
                'default_branch' => $expectedDefaultBranch,
            ]);

        $this->githubClient
            ->expects($this->once())
            ->method('api')
            ->withAnyParameters()
            ->willReturn($repo);

        $result = $this->defaultBranchDetector->getDefaultBranch(
            RepositoryDetail::fromRepositoryUrl('https://github.com/irrelevantowner/irrelevant-repo-name')
        );

        self::assertEquals($expectedDefaultBranch, $result);
    }

    /**
     * @test
     */
    public function shouldReadFromBitbucket(): void
    {
        self::markTestIncomplete();
        $expectedDefaultBranch = 'irrelevant-branch';

        $workspaces = $this->createMockWithoutInvokingTheOriginalConstructor(Workspaces::class, ['show']);

        $workspaces
            ->expects($this->once())
            ->method('show')
            ->willReturn([
                'mainbranch' => [
                    'name' => $expectedDefaultBranch,
                ],
            ]);

        $this->bitbucketClient
            ->expects($this->once())
            ->method('workspaces')
            ->withAnyParameters()
            ->willReturn($workspaces);

        $result = $this->defaultBranchDetector->getDefaultBranch(
            RepositoryDetail::fromRepositoryUrl('https://bitbucket.org/irrelevantowner/irrelevant-repo-name')
        );

        self::assertEquals($expectedDefaultBranch, $result);
    }

    /**
     * @param array<string> $methods
     * @psalm-param class-string $className
     */
    private function createMockWithoutInvokingTheOriginalConstructor(string $className, array $methods = []): MockObject
    {
        return $this->getMockBuilder($className)
            ->disableOriginalConstructor()
            ->setMethods($methods)
            ->getMock();
    }
}
