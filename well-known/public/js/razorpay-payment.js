/**
 * WinkelKart – Razorpay Payment Integration
 *
 * Usage:
 *   WinkelKartPay.initiate({ type, reference_id, amount, onSuccess, onFailure });
 *
 * All three flows (membership, order, booking) share this single helper.
 */
var WinkelKartPay = (function () {
    'use strict';

    var csrfToken = document.querySelector('meta[name="csrf-token"]')
        ? document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        : '';

    /**
     * @param {Object} opts
     * @param {string} opts.type         - 'membership' | 'order' | 'booking'
     * @param {number} opts.reference_id - id of kyc / order / booking row
     * @param {number} opts.amount       - amount in INR (informational – server validates)
     * @param {Function} [opts.onSuccess]
     * @param {Function} [opts.onFailure]
     */
    function initiate(opts) {
        if (!opts.type || !opts.reference_id || !opts.amount) {
            console.error('WinkelKartPay: type, reference_id and amount are required.');
            return;
        }

        // Step 1 – Create Razorpay order on our server
        fetch('/razorpay/create-order', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                type: opts.type,
                reference_id: opts.reference_id,
                amount: opts.amount
            })
        })
        .then(function (res) {
            if (!res.ok) {
                return res.text().then(function (text) {
                    var errorMsg;
                    try {
                        var json = JSON.parse(text);
                        errorMsg = json.error || json.message || ('Server error ' + res.status);
                    } catch (e) {
                        errorMsg = 'Server error ' + res.status + '. Please try again.';
                    }
                    throw new Error(errorMsg);
                });
            }
            return res.json();
        })
        .then(function (data) {
            if (data.error) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'error', title: 'Payment Error', text: data.error });
                } else {
                    alert(data.error);
                }
                if (opts.onFailure) opts.onFailure(data);
                return;
            }

            // Step 2 – Open Razorpay checkout popup
            var razorpayOptions = {
                key: data.key,
                amount: data.amount,
                currency: data.currency,
                name: data.name,
                description: data.description,
                order_id: data.order_id,
                prefill: data.prefill || {},
                theme: { color: '#1d4ed8' },
                handler: function (response) {
                    // Step 3 – Verify on server
                    verifyPayment(response, opts);
                },
                modal: {
                    ondismiss: function () {
                        if (opts.onFailure) opts.onFailure({ dismissed: true });
                    }
                }
            };

            var rzp = new Razorpay(razorpayOptions);

            rzp.on('payment.failed', function (response) {
                handleFailure(data.order_id, opts);
            });

            rzp.open();
        })
        .catch(function (err) {
            console.error('WinkelKartPay error:', err);
            var msg = (err && err.message) ? err.message : 'Could not connect to payment server. Please try again.';
            if (typeof Swal !== 'undefined') {
                Swal.fire({ icon: 'error', title: 'Payment Error', text: msg });
            } else {
                alert(msg);
            }
            if (opts.onFailure) opts.onFailure(err);
        });
    }

    function verifyPayment(response, opts) {
        // Show loading
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Verifying Payment...',
                html: 'Please wait while we confirm your payment ⏳',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: function () { Swal.showLoading(); }
            });
        }

        fetch('/razorpay/verify-payment', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                razorpay_payment_id: response.razorpay_payment_id,
                razorpay_order_id: response.razorpay_order_id,
                razorpay_signature: response.razorpay_signature,
                type: opts.type,
                reference_id: opts.reference_id
            })
        })
        .then(function (res) { return res.json(); })
        .then(function (data) {
            if (data.success) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Payment Successful!',
                        html: '<b>' + (data.message || 'Payment verified.') + '</b>',
                        confirmButtonText: 'Continue',
                        confirmButtonColor: '#2d6a4f',
                        allowOutsideClick: false
                    }).then(function () {
                        if (data.redirect) window.location.href = data.redirect;
                    });
                } else {
                    alert(data.message || 'Payment successful!');
                    if (data.redirect) window.location.href = data.redirect;
                }
                if (opts.onSuccess) opts.onSuccess(data);
            } else {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'error', title: 'Verification Failed', text: data.error || 'Please contact support.' });
                } else {
                    alert(data.error || 'Verification failed.');
                }
                if (opts.onFailure) opts.onFailure(data);
            }
        })
        .catch(function (err) {
            console.error('Verify error:', err);
            if (typeof Swal !== 'undefined') {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Verification request failed. Contact support.' });
            }
            if (opts.onFailure) opts.onFailure(err);
        });
    }

    function handleFailure(orderId, opts) {
        fetch('/razorpay/payment-failed', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                razorpay_order_id: orderId,
                type: opts.type,
                reference_id: opts.reference_id
            })
        })
        .then(function (res) { return res.json(); })
        .then(function (data) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({ icon: 'error', title: 'Payment Failed', text: data.message || 'Payment was not completed.' });
            } else {
                alert(data.message || 'Payment failed.');
            }
            if (opts.onFailure) opts.onFailure(data);
        })
        .catch(function () {
            if (opts.onFailure) opts.onFailure({ error: 'network' });
        });
    }

    return { initiate: initiate };
})();
