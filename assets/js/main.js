/**
 * NextGen Farming Incubation Hub - Interactive JavaScript (jQuery)
 * Implements smooth navigation, animated counters, dynamic form category logic,
 * and AJAX form submissions.
 */

$(document).ready(function () {
    'use strict';

    // -------------------------------------------------------------
    // 1. Sticky Navigation with Glassmorphism
    // -------------------------------------------------------------
    const $navbar = $('#main-navbar');
    $(window).on('scroll', function () {
        if ($(this).scrollTop() > 30) {
            $navbar.addClass('shadow-md bg-white/95 backdrop-blur-md').removeClass('bg-transparent py-5').addClass('py-3');
        } else {
            $navbar.removeClass('shadow-md bg-white/95 backdrop-blur-md').addClass('bg-transparent py-5').removeClass('py-3');
        }
    });

    // -------------------------------------------------------------
    // 2. Mobile Menu Toggle
    // -------------------------------------------------------------
    $('#mobile-menu-btn').on('click', function (e) {
        e.stopPropagation();
        $('#mobile-menu').slideToggle(250);
        $(this).find('i').toggleClass('fa-bars fa-xmark');
    });

    $(document).on('click', function (e) {
        if (!$(e.target).closest('#mobile-menu, #mobile-menu-btn').length) {
            if ($('#mobile-menu').is(':visible')) {
                $('#mobile-menu').slideUp(200);
                $('#mobile-menu-btn').find('i').removeClass('fa-xmark').addClass('fa-bars');
            }
        }
    });

    // -------------------------------------------------------------
    // 3. Smooth Scrolling for Anchor Links
    // -------------------------------------------------------------
    $('a[href^="#"]').on('click', function (e) {
        const targetId = $(this).attr('href');
        if (targetId && targetId !== '#' && $(targetId).length) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: $(targetId).offset().top - 80
            }, 600);

            // Close mobile menu if open
            if ($('#mobile-menu').is(':visible')) {
                $('#mobile-menu').slideUp(200);
                $('#mobile-menu-btn').find('i').removeClass('fa-xmark').addClass('fa-bars');
            }
        }
    });

    // -------------------------------------------------------------
    // 4. Animated Number Counters
    // -------------------------------------------------------------
    let countersStarted = false;
    function checkCounters() {
        const $counters = $('.counter-value');
        if (!$counters.length || countersStarted) return;

        const windowTop = $(window).scrollTop();
        const windowBottom = windowTop + $(window).height();

        $counters.each(function () {
            const $counter = $(this);
            const counterTop = $counter.offset().top;

            if (counterTop >= windowTop && counterTop <= windowBottom) {
                countersStarted = true;
                $counters.each(function () {
                    const $this = $(this);
                    const target = parseInt($this.data('target'), 10);
                    const prefix = $this.data('prefix') || '';
                    const suffix = $this.data('suffix') || '';
                    const duration = 1800; // ms
                    const stepTime = 30;
                    const steps = duration / stepTime;
                    const increment = target / steps;
                    let current = 0;

                    const timer = setInterval(function () {
                        current += increment;
                        if (current >= target) {
                            $this.text(prefix + target.toLocaleString() + suffix);
                            clearInterval(timer);
                        } else {
                            $this.text(prefix + Math.floor(current).toLocaleString() + suffix);
                        }
                    }, stepTime);
                });
                return false; // Break loop
            }
        });
    }

    $(window).on('scroll load', checkCounters);

    // -------------------------------------------------------------
    // 5. Offerings Interactive Tabs (Home & Programs)
    // -------------------------------------------------------------
    $('.offering-tab-btn').on('click', function () {
        const targetTab = $(this).data('tab');

        // Toggle active button states
        $('.offering-tab-btn').removeClass('active bg-emerald-800 text-white shadow-lg')
                              .addClass('bg-emerald-50 text-emerald-900 hover:bg-emerald-100');
        $(this).addClass('active bg-emerald-800 text-white shadow-lg')
               .removeClass('bg-emerald-50 text-emerald-900 hover:bg-emerald-100');

        // Fade in target content
        $('.offering-content-panel').hide();
        $('#' + targetTab).fadeIn(300);
    });

    // -------------------------------------------------------------
    // 6. Contact / Application Dynamic Category Selection
    // -------------------------------------------------------------
    const categoryConfigs = {
        'Entrepreneur': {
            interests: [
                'AgriTech Ignite Accelerator (Early Stage)',
                'HarvestScale Growth Cohort (Series Seed/A)',
                'Seed Fundraising & Investor Matching',
                'Farmland Pilot Testing & Lab Access',
                'Export Market & Value Chain Linkages'
            ],
            extraFieldLabel: 'Pitch Deck URL or Venture Website',
            extraFieldPlaceholder: 'https://krishisense.bd or Google Drive link',
            extraFieldHelp: 'Link to your deck, MVP demo, or company website',
            messagePlaceholder: 'Please tell us about your agribusiness model, the farming challenge you solve in Bangladesh or emerging markets, your traction, and how the hub can support you...',
            icon: 'fa-seedling'
        },
        'Investor': {
            interests: [
                'Angel Syndicate & Co-Investment Deals',
                'Curated Pre-Vetted AgriTech Deal Flow',
                'ESG & Carbon Impact Portfolio Scouting',
                'Emerging Market Due Diligence Access'
            ],
            extraFieldLabel: 'Investment Firm / Portfolio Website or LinkedIn',
            extraFieldPlaceholder: 'https://bengalventures.vc or LinkedIn profile',
            extraFieldHelp: 'Where can we learn about your investment focus or fund?',
            messagePlaceholder: 'Tell us about your fund or angel profile, typical ticket size ($25k - $500k+), stage preferences (Pre-seed, Seed, Series A), and specific agricultural sectors of interest...',
            icon: 'fa-chart-line'
        },
        'Support Organisation': {
            interests: [
                'Ecosystem Development Partnership',
                'CSR & Climate Grant Co-Implementation',
                'Government / University Research Commercialization',
                'International Ag-Tech Demonstration Trials'
            ],
            extraFieldLabel: 'Organization Website & Headquarter Location',
            extraFieldPlaceholder: 'https://agroinnovate-nl.org, Wageningen, Netherlands',
            extraFieldHelp: 'Institutional website and country/city',
            messagePlaceholder: 'Describe your organization mandate, geographical focus (e.g., Bangladesh, South Asia, Africa), and proposed areas of strategic collaboration...',
            icon: 'fa-building-columns'
        },
        'Mentor': {
            interests: [
                'Domain Mentor Residency (Agronomy / Bio-Tech)',
                'Founder Mentorship & Advisory Board',
                'Masterclasses & Technical Workshops',
                'Commercial Scale-Up & Supply Chain Mentoring'
            ],
            extraFieldLabel: 'LinkedIn Profile / Academic Profile',
            extraFieldPlaceholder: 'https://linkedin.com/in/expert-profile',
            extraFieldHelp: 'Public profile highlighting your track record',
            messagePlaceholder: 'Share your background, industry experience (e.g. cold-chain, hydroponics, ag-fintech, supply chain), and how you would like to guide emerging founders...',
            icon: 'fa-user-tie'
        }
    };

    function updateCategoryUI(category) {
        const config = categoryConfigs[category] || categoryConfigs['Entrepreneur'];

        // Update Sub-interest select box
        const $interestSelect = $('#sub_interest');
        if ($interestSelect.length) {
            $interestSelect.empty();
            $interestSelect.append('<option value="" disabled selected>-- Select Specific Program / Area of Interest --</option>');
            $.each(config.interests, function (idx, item) {
                $interestSelect.append($('<option></option>').attr('value', item).text(item));
            });
        }

        // Update dynamic fields
        $('#extra-field-label').text(config.extraFieldLabel);
        $('#pitch_url').attr('placeholder', config.extraFieldPlaceholder);
        $('#extra-field-help').text(config.extraFieldHelp);
        $('#message').attr('placeholder', config.messagePlaceholder);

        // Highlight selected category pill
        $('.category-pill-btn').removeClass('border-emerald-600 bg-emerald-50 text-emerald-800 ring-2 ring-emerald-500')
                               .addClass('border-gray-200 bg-white text-gray-700 hover:border-emerald-300');
        
        $(`.category-pill-btn[data-category="${category}"]`)
            .removeClass('border-gray-200 bg-white text-gray-700')
            .addClass('border-emerald-600 bg-emerald-50 text-emerald-800 ring-2 ring-emerald-500');
        
        $('#selected-category-input').val(category);
    }

    // Category pill click handler
    $('.category-pill-btn').on('click', function () {
        const category = $(this).data('category');
        updateCategoryUI(category);
    });

    // Also support query param pre-selection (?category=Investor)
    const urlParams = new URLSearchParams(window.location.search);
    const categoryParam = urlParams.get('category');
    if (categoryParam && categoryConfigs[categoryParam]) {
        updateCategoryUI(categoryParam);
    } else {
        updateCategoryUI('Entrepreneur');
    }

    // -------------------------------------------------------------
    // 7. Toast Notification Helper
    // -------------------------------------------------------------
    function showToast(type, title, message) {
        const isSuccess = type === 'success';
        const bgColor = isSuccess ? 'bg-emerald-900 border-emerald-600 text-white' : 'bg-red-900 border-red-600 text-white';
        const icon = isSuccess ? 'fa-circle-check text-emerald-400' : 'fa-circle-exclamation text-red-400';

        const toastHtml = `
            <div id="dynamic-toast" class="fixed bottom-6 right-6 z-50 flex items-start space-x-3 p-5 rounded-2xl shadow-2xl border ${bgColor} max-w-md animate-bounce-in">
                <i class="fa-solid ${icon} text-2xl mt-0.5"></i>
                <div class="flex-1">
                    <h4 class="font-bold text-base leading-snug">${title}</h4>
                    <p class="text-sm text-gray-200 mt-1 leading-relaxed">${message}</p>
                </div>
                <button type="button" class="text-gray-300 hover:text-white ml-2 text-lg" onclick="$(this).parent().fadeOut(300, function(){ $(this).remove(); });">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        `;

        $('#dynamic-toast').remove();
        $('body').append(toastHtml);

        setTimeout(function () {
            $('#dynamic-toast').fadeOut(400, function () {
                $(this).remove();
            });
        }, 7000);
    }

    // -------------------------------------------------------------
    // 8. Contact & Application AJAX Submission
    // -------------------------------------------------------------
    $('#hub-contact-form').on('submit', function (e) {
        e.preventDefault();

        const $form = $(this);
        const $submitBtn = $form.find('#btn-submit-form');
        const $btnText = $submitBtn.find('.btn-text');
        const $btnSpinner = $submitBtn.find('.btn-spinner');
        const $alertBox = $('#form-alert-container');

        // Client-side quick check
        const fullName = $.trim($('#full_name').val());
        const email = $.trim($('#email').val());
        const message = $.trim($('#message').val());

        if (!fullName || !email || !message) {
            showToast('error', 'Required Fields Missing', 'Please provide your Full Name, Email, and Message before submitting.');
            return;
        }

        // Email regex validation
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(email)) {
            showToast('error', 'Invalid Email', 'Please enter a valid email address.');
            return;
        }

        // Disable button & show spinner
        $submitBtn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed');
        $btnSpinner.removeClass('hidden');
        $btnText.text('Transmitting Application...');
        $alertBox.empty().hide();

        // Perform AJAX Request
        $.ajax({
            url: 'api/submit.php',
            type: 'POST',
            data: $form.serialize(),
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    showToast('success', 'Application Received!', response.message);
                    
                    // Show in-page confirmation banner
                    const successBanner = `
                        <div class="p-6 bg-emerald-50 border-2 border-emerald-500 rounded-2xl text-emerald-900 mb-6 flex items-start space-x-4">
                            <i class="fa-solid fa-circle-check text-emerald-600 text-3xl mt-1"></i>
                            <div>
                                <h3 class="font-bold text-lg">Thank You for Connecting with NextGen Hub!</h3>
                                <p class="text-sm text-emerald-800 mt-1 leading-relaxed">
                                    Your profile has been routed to our ecosystem review committee. A member of our acceleration or investment team will reach out within 2 business days.
                                </p>
                                <span class="inline-block mt-3 px-3 py-1 bg-emerald-200 text-emerald-900 text-xs font-semibold rounded-full">
                                    Submission ID: #${response.id || 'NXT-' + Math.floor(1000 + Math.random() * 9000)}
                                </span>
                            </div>
                        </div>
                    `;
                    $alertBox.html(successBanner).fadeIn(300);

                    // Reset form fields
                    $form[0].reset();
                    updateCategoryUI('Entrepreneur');

                    // Scroll to alert container
                    $('html, body').animate({
                        scrollTop: $alertBox.offset().top - 100
                    }, 400);

                } else {
                    showToast('error', 'Submission Issue', response.message || 'An unexpected error occurred. Please try again.');
                }
            },
            error: function (xhr) {
                let errorMsg = 'Could not establish connection to the incubation server. Please check your network or try again.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                showToast('error', 'Submission Failed', errorMsg);
            },
            complete: function () {
                // Restore button state
                $submitBtn.prop('disabled', false).removeClass('opacity-75 cursor-not-allowed');
                $btnSpinner.addClass('hidden');
                $btnText.text('Submit Application / Inquiry');
            }
        });
    });

    // -------------------------------------------------------------
    // 9. Newsletter Subscription AJAX
    // -------------------------------------------------------------
    $('#newsletter-form').on('submit', function (e) {
        e.preventDefault();
        const $input = $(this).find('input[type="email"]');
        const emailVal = $.trim($input.val());

        if (!emailVal || !emailVal.includes('@')) {
            showToast('error', 'Invalid Email', 'Please enter a valid email address to subscribe.');
            return;
        }

        const $btn = $(this).find('button');
        $btn.prop('disabled', true).text('Subscribing...');

        setTimeout(function () {
            showToast('success', 'Subscribed to Agri-Dispatch!', 'You will receive our monthly emerging markets AgriTech report and cohort updates.');
            $input.val('');
            $btn.prop('disabled', false).html('<i class="fa-solid fa-paper-plane mr-2"></i>Subscribe');
        }, 800);
    });

    // -------------------------------------------------------------
    // 10. FAQ Accordion Interaction
    // -------------------------------------------------------------
    $('.faq-question-btn').on('click', function () {
        const $answer = $(this).next('.faq-answer-content');
        const $icon = $(this).find('.faq-chevron');

        $('.faq-answer-content').not($answer).slideUp(250);
        $('.faq-chevron').not($icon).removeClass('rotate-180');

        $answer.slideToggle(250);
        $icon.toggleClass('rotate-180');
    });

});
