<?php

namespace Service\Candidate\CandidateFieldPolicies\Policies;

use Common\Interfaces\SAL\DataMapperInterface;
use SAL\DataMapper\User;
use SAL\Model\UserCustomFormTemplate;
use User\Interfaces\UserCustomFormHandlerInterface;

class CandidateFieldPolicyInterestedIn extends CandidateFieldPolicy
{
    public const NAME = 'interested_in';
    public const PERCENT = 10;
    public const REQUIRED = true;

    private DataMapperInterface $userDataMapper;
    private UserCustomFormHandlerInterface $userCustomFormHandler;

    public function __construct(
        DataMapperInterface $userDataMapper,
        UserCustomFormHandlerInterface $userCustomFormHandler
    )
    {
        parent::__construct(self::NAME, self::PERCENT, self::REQUIRED);

        $this->userDataMapper = $userDataMapper;
        $this->userCustomFormHandler = $userCustomFormHandler;
    }

    public function isCompletedByCandidate(int $candidateId): bool
    {
        $userData = $this->userDataMapper->getData($candidateId, [User::KEY_USER_INTERESTED_IN_ROLE]);

        $userInterestedInRole = $userData[User::KEY_USER_INTERESTED_IN_ROLE] ?? [];

        return !empty($userInterestedInRole) && $this->getCustomFormsConditions($candidateId);
    }

    private function getCustomFormsConditions(int $candidateId): bool
    {
        $userCustomFormData = $this->getUserCustomFormDataContent(
            $candidateId,
            $this->userCustomFormHandler,
            UserCustomFormTemplate::VALUE_TYPE_CANDIDATE_ONBOARDING
        );

        if ($userCustomFormData === null) {
            return false;
        }

        $preferredCompanies = $userCustomFormData['preferred_companies'] ?? [];
        $preferredSectors = $userCustomFormData['preferred_sectors'] ?? [];

        return !empty($preferredCompanies) && !empty($preferredSectors);
    }
}
