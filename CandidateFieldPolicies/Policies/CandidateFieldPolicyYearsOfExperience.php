<?php

namespace Service\Candidate\CandidateFieldPolicies\Policies;

use Common\Interfaces\SAL\DataMapperInterface;
use SAL\DataMapper\User;

class CandidateFieldPolicyYearsOfExperience extends CandidateFieldPolicy
{
    public const NAME = 'years_of_experience';
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
        $userData = $this->userDataMapper->getData($candidateId, [User::KEY_USER_ROLE]);
        $userRole = $userData[User::KEY_USER_ROLE] ?? [];

        return !empty($userRole);
    }
}
