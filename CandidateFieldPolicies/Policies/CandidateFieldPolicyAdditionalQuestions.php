<?php

namespace Service\Candidate\CandidateFieldPolicies\Policies;

use SAL\Model\UserCustomFormTemplate;
use User\Interfaces\UserCustomFormHandlerInterface;

class CandidateFieldPolicyAdditionalQuestions extends CandidateFieldPolicy
{
    public const NAME = 'additional_questions';
    public const PERCENT = 5;
    public const REQUIRED = false;

    private UserCustomFormHandlerInterface $userCustomFormHandler;

    public function __construct(UserCustomFormHandlerInterface $userCustomFormHandler)
    {
        parent::__construct(self::NAME, self::PERCENT, self::REQUIRED);

        $this->userCustomFormHandler = $userCustomFormHandler;
    }

    public function isCompletedByCandidate(int $candidateId): bool
    {
        $userCustomFormData = $this->getUserCustomFormDataContent(
            $candidateId,
            $this->userCustomFormHandler,
            UserCustomFormTemplate::VALUE_TYPE_CANDIDATE_ONBOARDING_FAST_TRACK
        );

        if ($userCustomFormData === null) {
            return false;
        }

        $motivation = $userCustomFormData['motivation'] ?? [];
        $currentSalary = $userCustomFormData['current_salary'] ?? [];

        return !empty($motivation) && !empty($currentSalary);
    }
}
