<?php

namespace Service\Candidate\CandidateFieldPolicies\Policies;

use Common\Interfaces\SAL\DataMapperInterface;
use SAL\DataMapper\User;
use SAL\Model\UserCustomFormTemplate;
use User\Interfaces\UserCustomFormHandlerInterface;
use User\Repository\UserCandidateDataRepository;

class CandidateFieldPolicyBasicInformation extends CandidateFieldPolicy
{
    public const NAME = 'basic_information';
    public const PERCENT = 15;
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
        $userData = $this->userDataMapper->getData($candidateId, [User::KEY_USER_CANDIDATE_DATA]);
        $willFreelance = $userData[User::KEY_USER_CANDIDATE_DATA][UserCandidateDataRepository::FIELD_WILL_FREELANCE] ?? 0;
        $willFulltime = $userData[User::KEY_USER_CANDIDATE_DATA][UserCandidateDataRepository::FIELD_WILL_FULL_TIME] ?? 0;
        $lookingState = $userData[User::KEY_USER_CANDIDATE_DATA][UserCandidateDataRepository::FIELD_LOOKING_STATE] ?? null;

        return (bool) ($willFreelance + $willFulltime) &&
            $lookingState &&
            $this->getCustomFormsConditions($candidateId) ;
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

        $noticePeriod = $userCustomFormData['notice_period'] ?? [];
        $remoteWorking = $userCustomFormData['remote_working'] ?? [];

        return !empty($noticePeriod) && !empty($remoteWorking);
    }
}
