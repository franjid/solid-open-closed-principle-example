<?php

namespace Service\Candidate\CandidateFieldPolicies\Policies;

use Common\Interfaces\SAL\DataMapperInterface;
use SAL\DataMapper\User;
use User\Repository\UserRepository;

class CandidateFieldPolicyPhoneNumberVerified extends CandidateFieldPolicy
{
    public const NAME = 'phone_number_verified';
    public const PERCENT = 5;
    public const REQUIRED = false;

    private DataMapperInterface $userDataMapper;

    public function __construct(
        DataMapperInterface $userDataMapper
    )
    {
        parent::__construct(self::NAME, self::PERCENT, self::REQUIRED);

        $this->userDataMapper = $userDataMapper;
    }

    public function isCompletedByCandidate(int $candidateId): bool
    {
        $userData = $this->userDataMapper->getData($candidateId);
        $phoneNumberVerified = $userData[User::KEY_USER][UserRepository::FIELD_NAME_PHONE_NUMBER_VERIFIED];

        return (bool) $phoneNumberVerified;
    }
}
