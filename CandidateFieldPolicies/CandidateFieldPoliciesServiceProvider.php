<?php

namespace Service\Candidate\CandidateFieldPolicies;

use Candidate\Interfaces\CandidateFieldPoliciesInterface;
use Common\Interfaces\SAL\DataMapperInterface;
use Common\Interfaces\SAL\GetterInterface;
use Service\Candidate\CandidateFieldPolicies\Policies\CandidateFieldPolicyAdditionalQuestions;
use Service\Candidate\CandidateFieldPolicies\Policies\CandidateFieldPolicyBasicInformation;
use Service\Candidate\CandidateFieldPolicies\Policies\CandidateFieldPolicyEducation;
use Service\Candidate\CandidateFieldPolicies\Policies\CandidateFieldPolicyInterestedIn;
use Service\Candidate\CandidateFieldPolicies\Policies\CandidateFieldPolicyPhoneNumberVerified;
use Service\Candidate\CandidateFieldPolicies\Policies\CandidateFieldPolicyPitch;
use Service\Candidate\CandidateFieldPolicies\Policies\CandidateFieldPolicySocialLinks;
use Service\Candidate\CandidateFieldPolicies\Policies\CandidateFieldPolicyTechExperience;
use Service\Candidate\CandidateFieldPolicies\Policies\CandidateFieldPolicyWorkExperience;
use Service\Candidate\CandidateFieldPolicies\Policies\CandidateFieldPolicyYearsOfExperience;
use User\Interfaces\UserCustomFormHandlerInterface;
use LogicException;

class CandidateFieldPoliciesServiceProvider
{
    private CandidateFieldPoliciesService $candidateFieldPoliciesService;

    public function __construct(
        DataMapperInterface $userDataMapper,
        DataMapperInterface $roleDataMapper,
        GetterInterface $skillModel,
        UserCustomFormHandlerInterface $userCustomFormHandler
    )
    {
        $policies = [
            new CandidateFieldPolicyPitch($userDataMapper),
            new CandidateFieldPolicyBasicInformation($userDataMapper, $userCustomFormHandler),
            new CandidateFieldPolicyInterestedIn($userDataMapper, $userCustomFormHandler),
            new CandidateFieldPolicySocialLinks($userDataMapper),
            new CandidateFieldPolicyTechExperience($userDataMapper),
            new CandidateFieldPolicyYearsOfExperience($userDataMapper),
            new CandidateFieldPolicyAdditionalQuestions($userCustomFormHandler),
            new CandidateFieldPolicyPhoneNumberVerified($userDataMapper),
        ];

        $this->candidateFieldPoliciesService = new CandidateFieldPoliciesService($policies);
        $this->checkTotalPoliciesPercentage();
    }

    public function getService(): CandidateFieldPoliciesInterface
    {
        return $this->candidateFieldPoliciesService;
    }

    private function checkTotalPoliciesPercentage(): void
    {
        $totalPercentage = 0;
        $policies = $this->candidateFieldPoliciesService->getPolicies();

        foreach ($policies as $policy) {
            $totalPercentage += $policy->getPercent();
        }

        if ($totalPercentage !== 100) {
            throw new LogicException(
                'Policies percentage is not 100% (actual value is ' . $totalPercentage . '). Check again'
            );
        }
    }
}
