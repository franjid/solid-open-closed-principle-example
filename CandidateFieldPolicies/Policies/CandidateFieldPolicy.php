<?php

namespace Service\Candidate\CandidateFieldPolicies\Policies;

use SAL\Model\UserCustomFormTemplate;
use Service\Candidate\CandidateFieldPolicies\Exception\CandidateFieldPoliciesException;
use Service\Candidate\CandidateFieldPolicies\Interfaces\CandidateFieldPolicyInterface;
use User\Interfaces\UserCustomFormHandlerInterface;
use ValueObject\Types\Enum\EnumValue;

abstract class CandidateFieldPolicy implements CandidateFieldPolicyInterface
{
    private string $name;
    private int $percent;
    private bool $required;

    public function __construct(
        string $name,
        int $percent,
        bool $required
    )
    {
        $this->name = $name;
        $this->percent = $percent;
        $this->required = $required;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPercent(): int
    {
        return $this->percent;
    }

    public function isRequired(): bool
    {
        return $this->required;
    }

    abstract public function isCompletedByCandidate(int $candidateId): bool;

    protected function getUserCustomFormDataContent(
        int $candidateId,
        UserCustomFormHandlerInterface $userCustomFormHandler,
        string $customFormType
    ): ?array
    {
        if (!\in_array($customFormType, UserCustomFormTemplate::getValidTypes(), true)) {
            throw new CandidateFieldPoliciesException('Not valid custom form type');
        }

        $customFormTemplates = new EnumValue(
            [
                UserCustomFormTemplate::VALUE_TYPE_CANDIDATE_ONBOARDING,
                UserCustomFormTemplate::VALUE_TYPE_CANDIDATE_ONBOARDING_FAST_TRACK,
            ],
            $customFormType
        );

        $userCustomFormDataObject = $userCustomFormHandler->getUserCustomFromDataAsUser(
            $candidateId, $customFormTemplates
        );

        if ($userCustomFormDataObject === null) {
            return null;
        }

        return json_decode($userCustomFormDataObject->getContent(), true);
    }
}
