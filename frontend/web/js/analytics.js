/**
 * Meros GA4 Analytics
 *
 * Требуется подключенный Google tag / gtag.js.
 *
 * GA4 Measurement ID подключается отдельно в layout:
 * G-BGGS96YQZ5
 */
const GA_MEASUREMENT_ID = 'G-BGGS96YQZ5';

(function () {
    'use strict';

    /**
     * Проверяем наличие Google Analytics.
     */
    function hasGtag() {
        return typeof window.gtag === 'function';
    }

    /**
     * Отправка события.
     */
    function track(eventName, params = {}) {
        if (!hasGtag()) {
            console.debug('[Meros Analytics] gtag not available:', eventName, params);
            return;
        }

        const data = {
            page_location: window.location.href,
            page_path: window.location.pathname,
            page_title: document.title,
            ...params
        };

        window.gtag('event', eventName, data);

        console.debug('[Meros Analytics]', eventName, data);
    }

    /**
     * Преобразует:
     *
     * "1 300 000 uzs"
     * "1,300,000 UZS"
     *
     * в:
     *
     * 1300000
     */
    function parsePrice(value) {
        if (!value) {
            return null;
        }

        const number = String(value)
            .replace(/[^\d]/g, '');

        return number
            ? parseInt(number, 10)
            : null;
    }

    /**
     * Информация о тарифе из .meros-plan-card.
     */
    function getPlanData(element) {
        const card = element.closest('.meros-plan-card');

        if (!card) {
            return {};
        }

        const name =
            card.querySelector('header h3')
                ?.textContent
                ?.trim() || null;

        const priceText =
            card.querySelector('.price')
                ?.textContent
                ?.trim() || null;

        const price = parsePrice(priceText);

        return {
            plan_name: name,
            value: price,
            currency: 'UZS'
        };
    }

    /**
     * ID тарифа из URL:
     *
     * /get-plan?id=123
     */
    function getPlanId(link) {
        try {
            const url = new URL(link.href, window.location.origin);

            return url.searchParams.get('id');
        } catch (e) {
            return null;
        }
    }

    /**
     * Billing ID из action формы:
     *
     * /payment/click-pay?id=123
     * /payment/payme?id=123
     */
    function getBillingId(form) {
        try {
            const url = new URL(
                form.action,
                window.location.origin
            );

            return url.searchParams.get('id');
        } catch (e) {
            return null;
        }
    }


    /*
     |--------------------------------------------------------------------------
     | CLICK EVENTS
     |--------------------------------------------------------------------------
     */

    document.addEventListener('click', function (event) {

        /*
         * PHONE
         *
         * Работает сразу для обоих твоих вариантов:
         *
         * .navigation-phone
         * и обычного <a href="tel:...">
         */
        const phoneLink = event.target.closest('a[href^="tel:"]');

        if (phoneLink) {
            track('click_phone', {
                phone_number:
                    phoneLink
                        .getAttribute('href')
                        ?.replace(/^tel:/i, '') || null
            });

            return;
        }


        /*
         * LOGIN
         */
        const loginLink = event.target.closest(
            'a[href*="/login"]'
        );

        if (loginLink) {
            track('login_click', {
                link_url: loginLink.href
            });

            return;
        }


        /*
         * SIGNUP
         *
         * Если ссылка на signup есть на другой странице,
         * тоже автоматически поймается.
         */
        const signupLink = event.target.closest(
            'a[href*="/signup"]'
        );

        if (signupLink) {
            track('signup_click', {
                link_url: signupLink.href
            });

            return;
        }


        /*
         * BUY NOW / SELECT PLAN
         *
         * /get-plan?id=123
         */
        const planLink = event.target.closest(
            'a[href*="get-plan"]'
        );

        if (planLink) {
            const plan = getPlanData(planLink);
            const planId = getPlanId(planLink);

            track('begin_checkout', {
                plan_id: planId,
                plan_name: plan.plan_name,
                value: plan.value,
                currency: plan.currency,
                link_url: planLink.href
            });

            return;
        }
    });


    /*
     |--------------------------------------------------------------------------
     | FORM EVENTS
     |--------------------------------------------------------------------------
     */

    document.addEventListener('submit', function (event) {

        const form = event.target;

        if (!(form instanceof HTMLFormElement)) {
            return;
        }


        /*
         * HOMEPAGE CONSULTATION
         */
        if (form.id === 'homepage-consultation-form') {
            track('consultation_submit', {
                form_id: form.id,
                form_location: 'homepage'
            });

            return;
        }


        /*
         * CONTACT PAGE
         */
        if (form.id === 'contact-form') {
            track('contact_submit', {
                form_id: form.id,
                form_location: 'contact_page'
            });

            return;
        }


        /*
         * CLICK PAYMENT
         */
        if (form.id === 'click_form') {
            const billingId = getBillingId(form);

            const amountInput =
                form.querySelector(
                    'input[name="amount"]'
                );

            const amount = parsePrice(
                amountInput?.value
            );

            track('payment_attempt', {
                payment_type: 'click',
                billing_id: billingId,
                value: amount,
                currency: 'UZS'
            });

            /*
             * Дополнительно стандартное
             * ecommerce событие GA4.
             */
            track('add_payment_info', {
                payment_type: 'click',
                value: amount,
                currency: 'UZS'
            });

            return;
        }


        /*
         * PAYME PAYMENT
         */
        if (form.id === 'payme-form') {
            const billingId = getBillingId(form);

            track('payment_attempt', {
                payment_type: 'payme',
                billing_id: billingId,
                currency: 'UZS'
            });

            track('add_payment_info', {
                payment_type: 'payme',
                currency: 'UZS'
            });

            return;
        }
    });


    /*
     |--------------------------------------------------------------------------
     | PURCHASE
     |--------------------------------------------------------------------------
     |
     | Эту функцию вызываем ТОЛЬКО после подтвержденной оплаты.
     |
     | Пример:
     |
     | MerosAnalytics.purchase({
     |     transaction_id: 12345,
     |     plan_id: 3,
     |     plan_name: '3 months subscription',
     |     value: 1300000,
     |     payment_type: 'click'
     | });
     |
     */

    window.MerosAnalytics = {

        track: track,

        purchase: function ({
                                transaction_id,
                                plan_id = null,
                                plan_name = null,
                                value,
                                payment_type = null
                            }) {

            if (!transaction_id) {
                console.error(
                    '[Meros Analytics] purchase requires transaction_id'
                );

                return;
            }

            track('purchase', {
                transaction_id: String(transaction_id),
                value: Number(value) || 0,
                currency: 'UZS',
                payment_type: payment_type,

                items: [
                    {
                        item_id: plan_id
                            ? String(plan_id)
                            : undefined,

                        item_name:
                            plan_name || 'Meros subscription',

                        price: Number(value) || 0,

                        quantity: 1
                    }
                ]
            });
        }
    };
    function setGaCookie(name, value, maxAge) {
        if (!value) {
            return;
        }

        document.cookie =
            name + '=' + encodeURIComponent(value) +
            '; path=/' +
            '; max-age=' + maxAge +
            '; SameSite=Lax' +
            '; Secure';
    }
    if (typeof gtag === 'function') {
        gtag(
            'get',
            GA_MEASUREMENT_ID,
            'client_id',
            function (clientId) {
                setGaCookie(
                    'meros_ga_client_id',
                    clientId,
                    60 * 60 * 24 * 365
                );
            }
        );

        gtag(
            'get',
            GA_MEASUREMENT_ID,
            'session_id',
            function (sessionId) {
                setGaCookie(
                    'meros_ga_session_id',
                    sessionId,
                    60 * 60
                );
            }
        );
    }
})();