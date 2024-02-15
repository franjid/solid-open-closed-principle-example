<?php

namespace Service\Candidate\CandidateFieldPolicies\Policies;

use Common\Interfaces\SAL\DataMapperInterface;
use SAL\DataMapper\User;

class CandidateFieldPolicyTechExperience extends CandidateFieldPolicy
{
    public const NAME = 'technology_experience';
    public const PERCENT = 10;
    public const REQUIRED = true;

    private DataMapperInterface $userDataMapper;

    public function __construct(DataMapperInterface $userDataMapper)
    {
        parent::__construct(self::NAME, self::PERCENT, self::REQUIRED);

        $this->userDataMapper = $userDataMapper;
    }

    public function isCompletedByCandidate(int $candidateId): bool
    {
        $userData = $this->userDataMapper->getData($candidateId, [User::KEY_USER_SKILL]);
        $userSkill = $userData[User::KEY_USER_SKILL] ?? [];

        return !empty($userSkill);
    }
}
