<?php

namespace Service\Candidate\CandidateFieldPolicies\Interfaces;

interface CandidateFieldPoliciesInterface
{
    public function getPolicies(): array;
    public function areAllRequiredFieldsCompleted(int $candidateId): bool;
}
