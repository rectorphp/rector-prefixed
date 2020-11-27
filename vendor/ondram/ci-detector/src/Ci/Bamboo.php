<?php

declare (strict_types=1);
namespace _PhpScoperbd5d0c5f7638\OndraM\CiDetector\Ci;

use _PhpScoperbd5d0c5f7638\OndraM\CiDetector\CiDetector;
use _PhpScoperbd5d0c5f7638\OndraM\CiDetector\Env;
use _PhpScoperbd5d0c5f7638\OndraM\CiDetector\TrinaryLogic;
class Bamboo extends \_PhpScoperbd5d0c5f7638\OndraM\CiDetector\Ci\AbstractCi
{
    public static function isDetected(\_PhpScoperbd5d0c5f7638\OndraM\CiDetector\Env $env) : bool
    {
        return $env->get('bamboo_buildKey') !== \false;
    }
    public function getCiName() : string
    {
        return \_PhpScoperbd5d0c5f7638\OndraM\CiDetector\CiDetector::CI_BAMBOO;
    }
    public function isPullRequest() : \_PhpScoperbd5d0c5f7638\OndraM\CiDetector\TrinaryLogic
    {
        return \_PhpScoperbd5d0c5f7638\OndraM\CiDetector\TrinaryLogic::createFromBoolean($this->env->get('bamboo_repository_pr_key') !== \false);
    }
    public function getBuildNumber() : string
    {
        return $this->env->getString('bamboo_buildNumber');
    }
    public function getBuildUrl() : string
    {
        return $this->env->getString('bamboo_resultsUrl');
    }
    public function getGitCommit() : string
    {
        return $this->env->getString('bamboo_planRepository_revision');
    }
    public function getGitBranch() : string
    {
        $prBranch = $this->env->getString('bamboo_repository_pr_sourceBranch');
        if ($this->isPullRequest()->no() || empty($prBranch)) {
            return $this->env->getString('bamboo_planRepository_branch');
        }
        return $prBranch;
    }
    public function getRepositoryName() : string
    {
        return $this->env->getString('bamboo_planRepository_name');
    }
    public function getRepositoryUrl() : string
    {
        return $this->env->getString('bamboo_planRepository_repositoryUrl');
    }
}