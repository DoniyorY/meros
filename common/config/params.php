<?php
return [
   'adminEmail' => 'info@merosedu.uz',
   'supportEmail' => 'support@merosedu.uz',
   'senderEmail' => 'noreply@merosedu.uz',
   'senderName' => 'Meros Edu mailer',
   'user.passwordResetTokenExpire' => 3600,
   'user.passwordMinLength' => 6,
   'payme' => [
      'merchantId' => '6a353561395419e77320e0dd',
      'login' => 'Paycom',
      'key' => 'oEQzmq&8@iNy20jv6amMsswd&mAHspBv#mUZ',
      
      'checkoutUrl' => 'https://test.paycom.uz', //'https://checkout.paycom.uz'
      
      
      // false: billing.amount хранится в сумах.
      // true: billing.amount уже хранится в тийинах.
      'amountAlreadyInTiyin' => false,
      
      // Укажите ваш числовой код Payme для billing.payment_provider.
      'providerCode' => 2,
      
      // Сейчас на странице указан срок 3 месяца.
      // Если срок зависит от subscription_id, замените вычисление
      // в PaymeController::markBillingSuccess().
      'subscriptionDuration' => '+3 months',
      
      // Записывается в user_subscriptions.currency_code.
      // ISO 4217 numeric code: UZS = 860.
      'currencyCode' => 860,
      
      // Строковое значение для
      // user_subscriptions.payment_provider.
      'subscriptionPaymentProvider' => 'payme',
      
      // Для теста отмены подтверждённой транзакции в песочнице.
      // В продакшене лучше проверять, была ли услуга уже использована.
      'allowCancelPerformed' => true,
      
      // Обнулять даты подписки после возврата успешного платежа.
      'clearSubscriptionDatesOnRefund' => true,
      
      'allowedIps' => [
         '185.234.113.1',
         '185.234.113.2',
         '185.234.113.3',
         '185.234.113.4',
         '185.234.113.5',
         '185.234.113.6',
         '185.234.113.7',
         '185.234.113.8',
         '185.234.113.9',
         '185.234.113.10',
         '185.234.113.11',
         '185.234.113.12',
         '185.234.113.13',
         '185.234.113.14',
         '185.234.113.15',
      ],
   ],
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
