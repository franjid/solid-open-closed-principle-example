<?php

namespace Service\Candidate\CandidateFieldPolicies\Policies;

use Common\Interfaces\SAL\DataMapperInterface;
use SAL\DataMapper\User;
use User\Repository\UserCandidateDataRepository;

class CandidateFieldPolicyPitch extends CandidateFieldPolicy
{
    public const NAME = 'pitch';
    public const PERCENT = 10;
    public const REQUIRED = false;

    private DataMapperInterface $userDataMapper;

    public function __construct(DataMapperInterface $userDataMapper)
    {
        parent::__construct(self::NAME, self::PERCENT, self::REQUIRED);

        $this->userDataMapper = $userDataMapper;
    }

    public function isCompletedByCandidate(int $candidateId): bool
    {
        $userData = $this->userDataMapper->getData($candidateId, [User::KEY_USER_CANDIDATE_DATA]);
        $pitch = $userData[User::KEY_USER_CANDIDATE_DATA][UserCandidateDataRepository::FIELD_PITCH] ?? 0;

        return $pitch && strlen($pitch) > 0;
    }
}
