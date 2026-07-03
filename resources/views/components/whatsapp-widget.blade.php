@props([
    'phone'      => null,
    'heading'    => 'Get Exclusive Updates on WhatsApp!',
    'body'       => 'Sign up for Oasis Mint Status to receive the latest discussions, news, and exclusive content directly to your phone. Stay in the loop and never miss out!',
    'buttonText' => 'Join Now',
    'message'    => 'Subscribe. My name is: ',
])

@php
    // Use the passed prop if provided, otherwise the admin-configured number (with default fallback).
    $waDigits = $phone ? preg_replace('/\D/', '', $phone) : \App\Models\Setting::whatsappNumber();
    $waLink = 'https://wa.me/' . $waDigits;
    if (!empty($message)) {
        $waLink .= '?text=' . rawurlencode($message);
    }
@endphp

<div class="wa-modal-overlay" id="waModalOverlay" role="dialog" aria-modal="true" aria-labelledby="waModalHeading" hidden>
    <div class="wa-modal">
        <button type="button" class="wa-modal-close" id="waModalClose" aria-label="Close">&times;</button>
        <h3 class="wa-modal-heading" id="waModalHeading">{{ $heading }}</h3>
        <hr class="wa-modal-divider">
        <p class="wa-modal-body">{{ $body }}</p>
        <div class="wa-modal-actions">
            <a href="{{ $waLink }}" class="wa-modal-cta" id="waModalCta" target="_blank" rel="noopener noreferrer">{{ $buttonText }}</a>
        </div>
    </div>
</div>

<style>
    .wa-modal-overlay {
        position: fixed;
        inset: 0;
        z-index: 10000;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        background: rgba(0, 0, 0, 0.55);
        opacity: 0;
        transition: opacity 0.25s ease;
        font-family: 'Lato', sans-serif;
    }
    .wa-modal-overlay[hidden] { display: none; }
    .wa-modal-overlay.wa-show { opacity: 1; }

    .wa-modal {
        position: relative;
        width: 100%;
        max-width: 600px;
        background: #fff;
        border-radius: 6px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        padding: 28px 32px 32px;
        text-align: center;
        transform: translateY(-16px);
        transition: transform 0.25s ease;
    }
    .wa-modal-overlay.wa-show .wa-modal { transform: translateY(0); }

    .wa-modal-close {
        position: absolute;
        top: 14px;
        right: 18px;
        width: 32px;
        height: 32px;
        padding: 0;
        border: none;
        background: transparent;
        color: #222;
        font-size: 28px;
        line-height: 1;
        cursor: pointer;
        opacity: 0.75;
        transition: opacity 0.2s ease;
    }
    .wa-modal-close:hover { opacity: 1; }

    .wa-modal-heading {
        margin: 4px 24px 0;
        font-family: 'Lato', sans-serif;
        font-size: 24px;
        font-weight: 700;
        color: #1c1c1c;
        line-height: 1.3;
    }
    .wa-modal-divider {
        margin: 18px 0 22px;
        border: 0;
        border-top: 1px solid #e5e5e5;
    }
    .wa-modal-body {
        margin: 0 0 22px;
        font-size: 15px;
        line-height: 1.6;
        color: #555;
    }
    .wa-modal-actions { margin-top: 4px; }
    .wa-modal-cta {
        display: inline-block;
        font-size: 16px;
        font-weight: 700;
        color: #a01e8f;
        text-decoration: underline;
        text-underline-offset: 3px;
        transition: color 0.2s ease;
    }
    .wa-modal-cta:hover { color: #7d1670; }

    @media (max-width: 480px) {
        .wa-modal { padding: 24px 20px 26px; }
        .wa-modal-heading { margin-left: 12px; margin-right: 12px; font-size: 20px; }
    }

    @media (prefers-reduced-motion: reduce) {
        .wa-modal-overlay,
        .wa-modal { transition: none; }
    }
</style>

<script>
    (function () {
        var overlay = document.getElementById('waModalOverlay');
        var closeBtn = document.getElementById('waModalClose');
        var cta = document.getElementById('waModalCta');
        if (!overlay || !closeBtn) { return; }

        function open() {
            overlay.hidden = false;
            // Force reflow so the opacity/transform transition runs.
            void overlay.offsetWidth;
            overlay.classList.add('wa-show');
        }
        function close() {
            overlay.classList.remove('wa-show');
            setTimeout(function () { overlay.hidden = true; }, 250);
        }

        // Show on every page load.
        open();

        closeBtn.addEventListener('click', close);
        // Close when clicking the dark backdrop (but not the card itself).
        overlay.addEventListener('click', function (e) {
            if (e.target === overlay) { close(); }
        });
        // Close on Escape.
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && !overlay.hidden) { close(); }
        });
        // Clicking "Join Now" opens WhatsApp and dismisses the modal.
        if (cta) { cta.addEventListener('click', close); }
    })();
</script>
