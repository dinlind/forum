<?php if (!class_exists('CaptchaConfiguration')) { return; }

return [
    'SignupCaptcha' => [
        'UserInputID' => 'captchaCode',
        'CodeLength' => CaptchaRandomization::GetRandomCodeLength(6, 8),
        'ImageWidth' => 375,
        'ImageHeight' => 50,
        'ReloadEnabled' => false,
        'SoundEnabled' => false,
    ],
];
