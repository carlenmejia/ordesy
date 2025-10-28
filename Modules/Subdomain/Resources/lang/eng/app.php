<?php

return [
    'core' => [
        'subdomain' => 'Subdomain',
        'domain' => 'Domain',
        'customDomain' => 'Custom Domain',
        'domainType' => 'Domain Type',
        'continue' => 'Continue',
        'backToSignin' => 'Go Back to Sign In page',
        'alreadyKnow' => 'Oh, I just remembered the URL!',
        'workspaceTitle' => 'Sign in to your restaurant url',
        'forgotRestaurantTitle' => 'Find your restaurant login url',
        'signInTitle' => 'Don\'t know your restaurant\'s login URL?',
        'signInTitleDescription' => 'Welcome to the login page! Please enter your credentials to access your account and start using the platform\'s features. If you don\'t have an account yet, you can easily sign up for one.',
        'bannedSubdomains' => 'Enter the list of subdomains you want to restrict from getting registered',
        'sendDomainNotification' => 'Send Domain Notification',
        'enterYourSubdomain' => 'Enter your subdomain to get started',
        'dontHaveAccount' => 'Don\'t have account? <b>Click to Sign up</b>',
        'restaurantNotFound' => 'RESTAURANT DOES NOT EXISTS FOR THAT URL',
        'customDomainPlaceholder' => 'Enter your custom domain',
        'customDomainPlaceholderDescription' => 'This will be your restaurant\'s unique web address',
        'enterSubdomain' => 'Enter subdomain',
        'allowedCharacters' => 'Only lowercase letters, numbers, hyphens (-), underscores (_), asterisk (*) or percent (%) are allowed',
        'bannedSubdomainList' => 'Banned Subdomain List',
        'allowedCharactersDomain' => 'Only valid domain name (e.g., example.com) is allowed',
    ],
    'messages' => [
        'forgetMailSuccess' => 'Please check your email. We have sent an email with your login url',
        'forgetMailFail' => 'Your provided email is not found. Please provide a valid email address.',
        'forgotPageMessage' => 'We will send a confirmation email to you in order to verify your email address and determine the presence of a pre-existing restaurant URL.',
        'findRestaurantUrl' => 'Find your restaurant\'s login URL',
        'deleteSubdomain' => 'Are you sure you want to delete this subdomain?',
        'notAllowedToUseThisSubdomain' => 'Sorry, You are not allowed to use this subdomain',
        'noRestaurantLined' => 'No restaurant linked with this email',
        'notifyAllAdmins' => 'This will notify all admins their domain urls',
        'bannedSubdomainAdded' => 'Banned subdomain added successfully',
        'bannedSubdomainDeleted' => 'Banned subdomain deleted successfully',
        'subdomainAlreadyExists' => 'Subdomain with this name already exists. Try a different subdomain.',
    ],
    'email' => [
        'subject' => 'Important Update: New Login URL for Your Restaurant',
        'line2' => 'Welcome ',
        'line3' => 'We would like to inform you that the login URL for your restaurant has been changed. Please take note of the new login URL and use it going forward.',
        'line4' => 'We apologize for any inconvenience this may have caused, but rest assured that the new URL has been implemented for enhanced security and easier access to your account.',
        'line5' => 'If you have any questions or concerns, please don\'t hesitate to reach out to our support team. We are always here to help. ',
        'noteLoginUrlChanged' => 'Login URL: ',
        'noteLoginUrl' => 'Please note your Login URL ',
        'thankYou' => 'Thank you for your continued business. ',
        'goHome' => 'Go to Home',
        'greeting' => 'Hello!',
        'line1' => 'We received a request to help you find your restaurant information.',
        'instructions' => 'Click the button below to log in to your account.',
        'support' => 'If you need any assistance, please contact our support team.',
    ],
    'emailSuperAdmin' => [
        'subject' => 'New Superadmin Login URL- Subdomain Module Activation',
        'line3' => 'We would like to inform you that the Superadmin Login URL has been updated due to the activation of the **Subdomain Module**. Your new URL is ',
        'noteLoginUrlChanged' => 'Superadmin Login URL: ',
        'noteLoginUrl' => 'Please note your Superadmin Login URL ',
    ],
    'match' => [
        'title' => 'You can even follow below pattern',
        'pattern' => '<p>1. <b>test</b> (exact match)</p>
                            <p>2. <b>%test%</b> (match anywhere in the string)</p>
                            <p>3. <b>%test</b> (match anywhere but must end with \'test\')</p>'
    ]
];
