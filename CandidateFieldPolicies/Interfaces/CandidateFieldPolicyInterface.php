<?php

namespace Service\Candidate\CandidateFieldPolicies\Interfaces;

interface CandidateFieldPolicyInterface
{
    public function getName(): string;
    public function getPercent(): int;
    public function isRequired(): bool;
    public function isCompletedByCandidate(int $candidateId): bool;
}
