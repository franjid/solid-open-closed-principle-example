# Open/Closed principle example (SOLID)

Here's a practical example demonstrating the Open/Closed principle in action.

In a project featuring candidate profiles, each candidate may have various optional information such as phone numbers, social links, years of experience, etc.

The objective of the following code snippet is to verify if all required fields for a candidate profile are filled.

Within the codebase, there exists a [CandidateFieldPoliciesService](CandidateFieldPolicies/CandidateFieldPoliciesService.php) with a `areAllRequiredFieldsCompleted` method.

Every time we want to add or remove a new policy, we just need to add a new one in [CandidateFieldPolicies/Policies](CandidateFieldPolicies/Policies) and update the [CandidateFieldPoliciesServiceProvider](CandidateFieldPolicies/CandidateFieldPoliciesServiceProvider.php).

This design ensures that the system remains open for extension while closed for modification, adhering to the Open/Closed principle.
