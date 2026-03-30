<?php $layout = 'public'; $title = 'Smart QR Ordering for Restaurants'; ?>

<!-- Hero Section -->
<div class="bg-primary text-white py-5 mb-5" style="margin-top: -1.5rem; margin-left: -0.75rem; margin-right: -0.75rem;">
    <div class="container">
        <div class="row align-items-center py-5">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h1 class="display-4 fw-bold mb-3">Smart QR Ordering for Restaurants</h1>
                <p class="lead mb-4">Streamline your restaurant operations with digital QR code menus and real-time order management. Let your customers order from their table with ease.</p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="<?= url('/register') ?>" class="btn btn-light btn-lg px-4">Get Started Free</a>
                    <a href="<?= url('/features') ?>" class="btn btn-outline-light btn-lg px-4">Learn More</a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <div class="bg-white rounded-4 p-5 d-inline-block shadow">
                    <svg width="200" height="200" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                        <rect width="200" height="200" fill="white"/>
                        <rect x="20" y="20" width="60" height="60" rx="4" fill="#0d6efd"/>
                        <rect x="30" y="30" width="40" height="40" rx="2" fill="white"/>
                        <rect x="40" y="40" width="20" height="20" rx="1" fill="#0d6efd"/>
                        <rect x="120" y="20" width="60" height="60" rx="4" fill="#0d6efd"/>
                        <rect x="130" y="30" width="40" height="40" rx="2" fill="white"/>
                        <rect x="140" y="40" width="20" height="20" rx="1" fill="#0d6efd"/>
                        <rect x="20" y="120" width="60" height="60" rx="4" fill="#0d6efd"/>
                        <rect x="30" y="130" width="40" height="40" rx="2" fill="white"/>
                        <rect x="40" y="140" width="20" height="20" rx="1" fill="#0d6efd"/>
                        <rect x="90" y="90" width="20" height="20" fill="#0d6efd"/>
                        <rect x="120" y="120" width="20" height="20" fill="#0d6efd"/>
                        <rect x="150" y="120" width="30" height="15" fill="#0d6efd"/>
                        <rect x="120" y="150" width="15" height="30" fill="#0d6efd"/>
                        <rect x="150" y="160" width="30" height="20" fill="#0d6efd"/>
                    </svg>
                    <p class="text-muted mt-3 mb-0 small">Scan &middot; Order &middot; Enjoy</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Features Grid -->
<section class="py-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold">Why Choose QR Order?</h2>
        <p class="text-muted">Everything you need to modernize your restaurant ordering process.</p>
    </div>
    <div class="row g-4">
        <div class="col-md-6 col-lg-3">
            <div class="card h-100 border-0 shadow-sm text-center p-4">
                <div class="card-body">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:64px;height:64px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="#0d6efd" viewBox="0 0 16 16"><path d="M0 .5A.5.5 0 0 1 .5 0h3a.5.5 0 0 1 0 1H1v2.5a.5.5 0 0 1-1 0v-3Zm12 0a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0V1h-2.5a.5.5 0 0 1-.5-.5ZM.5 12a.5.5 0 0 1 .5.5V15h2.5a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5v-3a.5.5 0 0 1 .5-.5Zm15 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1 0-1H15v-2.5a.5.5 0 0 1 .5-.5ZM4 4h1v1H4V4Zm2 0h1v1H6V4Zm3 0h1v1H9V4Zm2 0h1v1h-1V4ZM4 6h1v1H4V6Zm6 0h1v1h-1V6Zm-3 1h1v1H7V7Zm2 0h1v1H9V7ZM4 9h1v1H4V9Zm2 0h1v1H6V9Zm3 0h1v1H9V9Zm2 0h1v1h-1V9Z"/></svg>
                    </div>
                    <h5 class="card-title">QR Code Menus</h5>
                    <p class="card-text text-muted">Generate unique QR codes for every table. Customers scan and browse your menu instantly.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card h-100 border-0 shadow-sm text-center p-4">
                <div class="card-body">
                    <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:64px;height:64px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="#198754" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588z"/><circle cx="8" cy="4.5" r="1"/></svg>
                    </div>
                    <h5 class="card-title">Real-Time Orders</h5>
                    <p class="card-text text-muted">Orders appear instantly on waiter dashboards. No more missed orders or long waits.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card h-100 border-0 shadow-sm text-center p-4">
                <div class="card-body">
                    <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:64px;height:64px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="#ffc107" viewBox="0 0 16 16"><path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zM7 6.5C7 7.328 6.552 8 6 8s-1-.672-1-1.5S5.448 5 6 5s1 .672 1 1.5zM4.285 9.567a.5.5 0 0 1 .683.183A3.498 3.498 0 0 0 8 11.5a3.498 3.498 0 0 0 3.032-1.75.5.5 0 1 1 .866.5A4.498 4.498 0 0 1 8 12.5a4.498 4.498 0 0 1-3.898-2.25.5.5 0 0 1 .183-.683zM10 8c-.552 0-1-.672-1-1.5S9.448 5 10 5s1 .672 1 1.5S10.552 8 10 8z"/></svg>
                    </div>
                    <h5 class="card-title">Easy Management</h5>
                    <p class="card-text text-muted">Manage tables, menus, waiters, and orders all from one intuitive dashboard.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card h-100 border-0 shadow-sm text-center p-4">
                <div class="card-body">
                    <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:64px;height:64px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="#0dcaf0" viewBox="0 0 16 16"><path d="M11 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h6zM5 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H5z"/><path d="M8 14a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/></svg>
                    </div>
                    <h5 class="card-title">Mobile Friendly</h5>
                    <p class="card-text text-muted">Fully responsive design works perfectly on any smartphone or tablet.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works -->
<section class="py-5 bg-light rounded-4 my-5 p-4">
    <div class="text-center mb-5">
        <h2 class="fw-bold">How It Works</h2>
        <p class="text-muted">Get started in three simple steps.</p>
    </div>
    <div class="row g-4">
        <div class="col-md-4 text-center">
            <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:56px;height:56px;font-size:1.5rem;font-weight:700;">1</div>
            <h5>Set Up Your Restaurant</h5>
            <p class="text-muted">Register your account, add your tables, and upload your menu items in minutes.</p>
        </div>
        <div class="col-md-4 text-center">
            <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:56px;height:56px;font-size:1.5rem;font-weight:700;">2</div>
            <h5>Generate QR Codes</h5>
            <p class="text-muted">Create unique QR codes for each table and print them out for your customers.</p>
        </div>
        <div class="col-md-4 text-center">
            <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:56px;height:56px;font-size:1.5rem;font-weight:700;">3</div>
            <h5>Receive Orders</h5>
            <p class="text-muted">Customers scan, order, and your waiters get notified in real time. It is that easy!</p>
        </div>
    </div>
</section>

<!-- Pricing Preview -->
<?php if (!empty($plans)): ?>
<section class="py-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold">Simple, Transparent Pricing</h2>
        <p class="text-muted">Start free and scale as you grow.</p>
    </div>
    <div class="row g-4 justify-content-center">
        <?php foreach ($plans as $index => $plan): ?>
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm <?= $index === 1 ? 'border-primary border-2' : '' ?>">
                <?php if ($index === 1): ?>
                    <div class="card-header bg-primary text-white text-center py-2 fw-bold">Most Popular</div>
                <?php endif; ?>
                <div class="card-body text-center p-4">
                    <h4 class="fw-bold"><?= h($plan['name']) ?></h4>
                    <div class="display-5 fw-bold text-primary my-3">
                        <?= format_price($plan['price_monthly']) ?>
                        <span class="fs-6 fw-normal text-muted">/month</span>
                    </div>
                    <ul class="list-unstyled text-start mb-4">
                        <li class="py-2 border-bottom"><strong><?= (int) $plan['max_tables'] ?></strong> Tables</li>
                        <li class="py-2 border-bottom"><strong><?= (int) $plan['max_waiters'] ?></strong> Waiters</li>
                        <li class="py-2 border-bottom">QR Code Generation</li>
                        <li class="py-2">Real-Time Orders</li>
                    </ul>
                    <a href="<?= url('/register') ?>" class="btn <?= $index === 1 ? 'btn-primary' : 'btn-outline-primary' ?> w-100">Get Started</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <div class="text-center mt-4">
        <a href="<?= url('/pricing') ?>" class="text-decoration-none">View full pricing details &rarr;</a>
    </div>
</section>
<?php endif; ?>

<!-- CTA Section -->
<section class="bg-primary text-white rounded-4 p-5 my-5 text-center">
    <h2 class="fw-bold mb-3">Ready to Modernize Your Restaurant?</h2>
    <p class="lead mb-4">Join hundreds of restaurants already using QR Order to serve customers faster and smarter.</p>
    <a href="<?= url('/register') ?>" class="btn btn-light btn-lg px-5">Start Your Free Trial</a>
</section>
