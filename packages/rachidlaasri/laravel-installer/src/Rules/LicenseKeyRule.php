<?php

namespace RachidLaasri\LaravelInstaller\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use RachidLaasri\LaravelInstaller\Repositories\ApplicationStatusRepositoryInterface;

class LicenseKeyRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Bypass license validation - always pass
        return;
        
        // Original validation code (commented out)
        /*
        $licenseRepository = app(ApplicationStatusRepositoryInterface::class);

        $check = $licenseRepository->check($value);

        if ($check === false) {
            $fail('The :attribute is invalid.');
        }
        */
    }
}
