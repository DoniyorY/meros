<?php
return [
    'adminEmail' => 'info@merosedu.uz',
    'supportEmail' => 'support@merosedu.uz',
    'senderEmail' => 'noreply@merosedu.uz',
    'senderName' => 'Meros Edu mailer',
    'telegramBotLink' => 'https://t.me/meros_info_bot',

    'coursePlatformUrl' => 'https://slc-campus.avallainmagnet.com/merosinternationalinstitute',
    'user.passwordResetTokenExpire' => 3600,
    'user.passwordMinLength' => 6,
    'user_status' => [
        10 => 'Active',
        9 => 'Inactive',
        0 => 'Deleted',
    ],
    'user_subscription_status' => [
        0 => 'Inactive',
        1 => 'Active',
    ],
    'status' => [
        0 => 'Inactive',
        1 => 'Active',
    ],
    'billing_status' => [
        'en' => [
            0 => 'Pending',
            1 => 'Success',
            2 => 'Failed',
            3 => 'Cancelled'
        ],
        'ru' => [
            0 => 'В процессе',
            1 => 'Успешно',
            2 => 'Ошибка',
            3 => 'Отменен'
        ]
    ],
    'billing_status_class' => [
        0 => 'badge bg-warning',
        1 => 'badge bg-success',
        2 => 'badge bg-danger',
        3 => 'badge bg-danger'
    ],
    'page_type' => [
        0 => 'B2B',
        1 => 'B2C',
    ]
];
