<?php declare(strict_types=1);

namespace Badge\Adapter\Out;

use Badge\Application\Domain\Model\RepositoryDetail;
use Badge\Application\Domain\Model\Service\DetectableBranch;
use Bitbucket\Client as BitbucketClient;
use Github\Client as GithubClient;

class DefaultBranchDetector implements DetectableBranch
{
    private GithubClient $githubClient;

    private BitbucketClient $bitbucketClient;

    public function __construct(GithubClient $githubClient, BitbucketClient $bitbucketClient)
    {
        $this->githubClient = $githubClient;
        $this->bitbucketClient = $bitbucketClient;
    }

    public function getDefaultBranch(RepositoryDetail $repositoryDetail): string
    {
        $result = '';

        if ($repositoryDetail->isGitHub()) {
            $repoGitHubData = $this->githubClient
                ->repo()
                ->show($repositoryDetail->repositoryOwner(), $repositoryDetail->repositoryName());

            $result = (string) $repoGitHubData['default_branch'];
        }

        if ($repositoryDetail->isBitbucket()) {
            $repoBitbucketData = $this->bitbucketClient
                ->repositories()
                ->workspaces($repositoryDetail->repositoryOwner())
                ->show($repositoryDetail->repositoryName());

            $result = (string) $repoBitbucketData['mainbranch']['name'];
        }

        return $result;
    }
}
