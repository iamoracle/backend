<?php

// source: https://medium.com/@mahdibagheri71/stop-email-aliases-in-their-tracks-advanced-email-validation-for-laravel-applications-e70ff5cd42e0

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EmailValidationRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $fail('Invalid :attribute format.');
            return;
        }

        $parts = explode('@', $value);
        [$localPart, $domain] = $parts;

        $this->strictEmailValidation($value, $fail);
        $this->allowedDomain($domain, $fail);
        $this->checkCommonAliasPatterns($localPart, $domain, $fail);
        $this->checkGmailDotAliases($localPart, $domain, $fail);
        $this->checkSuspiciousPatterns($localPart, $fail);
        $this->checkCommonAliasServices($value, $fail);
        $this->validateDns($domain, $fail);
    }

    private function checkCommonAliasPatterns(string $localPart, string $domain, Closure $fail): void
    {
        $aliasPatterns = [
            '/\+.*/',
            '/\.{2,}/',
            '/^\./',
            '/\.$/',
        ];

        foreach ($aliasPatterns as $pattern) {
            if (preg_match($pattern, $localPart)) {
                $fail('Email aliases are not allowed. Please use your main :attribute.');
                return;
            }
        }

        if (preg_match('/\d{3,}\+/', $localPart)) {
            $fail('This :attribute format appears to be an alias.');
            return;
        }

        $aliasDomains = [
            'tempmail.org', 'guerrillamail.com', 'mailinator.com',
            'yopmail.com', '10minutemail.com', 'temp-mail.org',
            'throwaway.email', 'getnada.com',
        ];

        if (in_array(strtolower($domain), $aliasDomains)) {
            $fail('Temporary or alias :attribute services are not allowed.');
            return;
        }
    }

    private function checkGmailDotAliases(string $localPart, string $domain, Closure $fail): void
    {
        $gmailDomains = ['gmail.com', 'googlemail.com'];

        if (in_array(strtolower($domain), $gmailDomains)) {
            if (strpos($localPart, '.') !== false) {
                $fail('Gmail addresses with dots are not allowed. Please use your :attribute without dots.');
                return;
            }

            $segments = explode('.', $localPart);
            if (count($segments) > 1) {
                foreach ($segments as $segment) {
                    if (strlen($segment) === 1) {
                        $fail('This Gmail :attribute format is not allowed.');
                        return;
                    }
                }
            }

            if (strlen($localPart) < 3) {
                $fail('Gmail :attribute is too short.');
                return;
            }

            $suspiciousGmailPatterns = [
                '/^[a-z]{1,2}\d+$/',
                '/^\d+[a-z]{1,2}$/',
                '/^[a-z]\d[a-z]\d/',
            ];

            foreach ($suspiciousGmailPatterns as $pattern) {
                if (preg_match($pattern, strtolower($localPart))) {
                    $fail('This Gmail :attribute format appears to be an alias.');
                    return;
                }
            }
        }
    }

    private function checkCommonAliasServices(string $email, Closure $fail): void
    {
        $aliasServices = [
            'simplelogin.io', 'anonaddy.com', 'relay.firefox.com',
            'hide-my-email.com', 'icloud.com',
        ];

        $domain = strtolower(explode('@', $email)[1]);

        if ($domain === 'icloud.com' && preg_match('/^[a-z0-9]{10,}@/', strtolower($email))) {
            $fail('Generated :attribute addresses are not allowed.');
            return;
        }

        foreach ($aliasServices as $service) {
            if ($domain === $service) {
                $fail('Email forwarding services are not allowed for :attribute.');
                return;
            }
        }
    }

    private function checkSuspiciousPatterns(string $localPart, Closure $fail): void
    {
        $suspiciousPatterns = [
            '/^[a-z]\d{8,}$/',
            '/^\d{10,}$/',
            '/^test\d*$/',
            '/^temp\d*$/',
            '/^[a-z]{1,2}\d{6,}$/',
            '/^noreply/',
            '/^no-reply/',
            '/^admin\d*$/',
            '/^support\d*$/',
        ];

        foreach ($suspiciousPatterns as $pattern) {
            if (preg_match($pattern, strtolower($localPart))) {
                $fail('This :attribute format appears to be temporary or invalid.');
                return;
            }
        }
    }

    protected function strictEmailValidation(string $email, Closure $fail): void
    {
        if (! preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email)) {
            $fail('The :attribute must be a valid RFC compliant email address.');
        }
    }

    protected function validateDns(string $domain, Closure $fail): void
    {
        if (! checkdnsrr($domain, 'MX') && ! checkdnsrr($domain, 'A')) {
            $fail('The :attribute domain does not exist.');
        }
    }


    protected function allowedDomain($domain, $fail)
    {
        $allowedDomains = [
            'gmail.com',
            'googlemail.com',
            'outlook.com',
            'hotmail.com',
            'live.com',
            'msn.com',
            'icloud.com',
            'me.com',
            'mac.com',
            'yahoo.com',
            'yahoo.co.uk',
            'yahoo.ca',
            'yahoo.com.au',
            'yahoo.de',
            'yahoo.fr',
            'yahoo.es',
            'yahoo.it',
            'ymail.com',
            'rocketmail.com',
            'aol.com',
            'aim.com',
        ];

        if (! in_array($domain, $allowedDomains)) {
            $fail('Please use an :attribute from a supported provider: '.implode(', ', $allowedDomains));
        }
    }
}