<?php

namespace Service\Candidate\CandidateFieldPolicies\Policies;

use Common\Interfaces\SAL\DataMapperInterface;
use SAL\DataMapper\User;

class CandidateFieldPolicySocialLinks extends CandidateFieldPolicy
{
    public const NAME = 'social_links';
    public const PERCENT = 5;
    public const REQUIRED = false;

    private DataMapperInterface $userDataMapper;

    public function __construct(DataMapperInterface $userDataMapper)
    {
        parent::__construct(self::NAME, self::PERCENT, self::REQUIRED);

        $this->userDataMapper = $userDataMapper;
    }

    public function isCompletedByCandidate(int $candidateId): bool
    {
        $userData = $this->userDataMapper->getData($candidateId, [User::KEY_USER_RESOURCE]);
        $userResourceLinks = $userData[User::KEY_USER_RESOURCE]['link'] ?? [];

        return !empty($userResourceLinks);
    }
}
