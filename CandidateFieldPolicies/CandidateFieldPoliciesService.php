<?php

namespace Service\Candidate\CandidateFieldPolicies;

use Service\Candidate\Interfaces\CandidateFieldPoliciesInterface;
use Service\Candidate\CandidateFieldPolicies\Exception\CandidateFieldPoliciesException;
use Service\Candidate\CandidateFieldPolicies\Interfaces\CandidateFieldPolicyInterface;
use Service\Candidate\CandidateFieldPolicies\Policies\CandidateFieldPolicy;

class CandidateFieldPoliciesService implements CandidateFieldPoliciesInterface
{
    private array $policies;

    public function __construct(array $policies)
    {
        foreach ($policies as $policy) {
            if (!$policy instanceof CandidateFieldPolicyInterface) {
                throw new CandidateFieldPoliciesException('Not valid policy');
            }
        }

        $this->policies = $policies;
    }

    /**
     * @return CandidateFieldPolicy[]
     */
    public function getPolicies(): array
    {
        return $this->policies;
    }

    public function areAllRequiredFieldsCompleted(int $candidateId): bool
    {
        $policies = $this->getPolicies();

        foreach ($policies as $policy) {
            if (!$policy->isRequired()) {
                continue;
            }

            if (!$policy->isCompletedByCandidate($candidateId)) {
                return false;
            }
        }

        return true;
    }
}
