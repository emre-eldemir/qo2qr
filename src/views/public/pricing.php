<?php $layout = 'public'; $title = 'Pricing'; ?>

<div class="py-4">
    <div class="text-center mb-5">
        <h1 class="fw-bold">Choose Your Plan</h1>
        <p class="lead text-muted">Simple pricing that grows with your business. Start free, upgrade anytime.</p>
    </div>

    <?php if (!empty($plans)): ?>
    <div class="row g-4 justify-content-center mb-5">
        <?php foreach ($plans as $index => $plan): ?>
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 <?= $index === 1 ? 'border-primary border-2 shadow' : 'border-0 shadow-sm' ?>">
                <?php if ($index === 1): ?>
                    <div class="card-header bg-primary text-white text-center py-2 fw-bold">Most Popular</div>
                <?php endif; ?>
                <div class="card-body d-flex flex-column text-center p-4">
                    <h3 class="fw-bold mb-1"><?= h($plan['name']) ?></h3>
                    <div class="display-4 fw-bold text-primary my-3">
                        <?= format_price($plan['price_monthly']) ?>
                        <span class="fs-6 fw-normal text-muted">/month</span>
                    </div>
                    <hr>
                    <ul class="list-unstyled text-start flex-grow-1 mb-4">
                        <li class="py-2 d-flex align-items-center">
                            <span class="text-success me-2">&#10003;</span>
                            Up to <strong class="ms-1"><?= (int) $plan['max_tables'] ?></strong>&nbsp;tables
                        </li>
                        <li class="py-2 d-flex align-items-center">
                            <span class="text-success me-2">&#10003;</span>
                            Up to <strong class="ms-1"><?= (int) $plan['max_waiters'] ?></strong>&nbsp;waiters
                        </li>
                        <li class="py-2 d-flex align-items-center">
                            <span class="text-success me-2">&#10003;</span>
                            QR code generation
                        </li>
                        <li class="py-2 d-flex align-items-center">
                            <span class="text-success me-2">&#10003;</span>
                            Real-time order notifications
                        </li>
                        <li class="py-2 d-flex align-items-center">
                            <span class="text-success me-2">&#10003;</span>
                            Custom order forms
                        </li>
                        <li class="py-2 d-flex align-items-center">
                            <span class="text-success me-2">&#10003;</span>
                            Location-based restrictions
                        </li>
                        <?php if ($plan['price_monthly'] > 0): ?>
                        <li class="py-2 d-flex align-items-center">
                            <span class="text-success me-2">&#10003;</span>
                            Priority support
                        </li>
                        <?php endif; ?>
                    </ul>
                    <a href="<?= url('/register') ?>" class="btn <?= $index === 1 ? 'btn-primary' : 'btn-outline-primary' ?> btn-lg w-100">
                        <?= $plan['price_monthly'] == 0 ? 'Start Free' : 'Get Started' ?>
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="alert alert-info text-center">
        <p class="mb-0">Pricing plans are currently being updated. Please check back soon.</p>
    </div>
    <?php endif; ?>

    <!-- FAQ -->
    <div class="py-5">
        <div class="text-center mb-4">
            <h2 class="fw-bold">Frequently Asked Questions</h2>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion" id="pricingFaq">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                Can I change my plan later?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#pricingFaq">
                            <div class="accordion-body text-muted">
                                Yes! You can upgrade or downgrade your plan at any time from your dashboard. Changes take effect immediately.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                Is there a free trial?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#pricingFaq">
                            <div class="accordion-body text-muted">
                                Our Free plan is available forever with no credit card required. You can start using QR Order right away and upgrade when you need more capacity.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                Do customers need to download an app?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#pricingFaq">
                            <div class="accordion-body text-muted">
                                No. Customers simply scan the QR code with their smartphone camera and the ordering page opens in their browser. No app download is needed.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
