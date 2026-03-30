<?php $layout = 'public'; $title = 'Features'; ?>

<div class="py-4">
    <div class="text-center mb-5">
        <h1 class="fw-bold">Platform Features</h1>
        <p class="lead text-muted">Discover everything QR Order has to offer for your restaurant.</p>
    </div>

    <!-- QR Code Ordering -->
    <div class="row align-items-center mb-5 pb-4 border-bottom">
        <div class="col-lg-6 mb-4 mb-lg-0">
            <span class="badge bg-primary mb-2">Ordering</span>
            <h3 class="fw-bold">QR Code Table Ordering</h3>
            <p class="text-muted">Generate unique QR codes for each table in your restaurant. Customers simply scan the code with their smartphone to view your menu and place orders directly — no app download required.</p>
            <ul class="list-unstyled">
                <li class="mb-2">&#10003; Unique QR code per table</li>
                <li class="mb-2">&#10003; No app required for customers</li>
                <li class="mb-2">&#10003; Instant menu access</li>
                <li class="mb-2">&#10003; Downloadable and printable QR codes</li>
            </ul>
        </div>
        <div class="col-lg-6 text-center">
            <div class="bg-light rounded-4 p-5">
                <svg xmlns="http://www.w3.org/2000/svg" width="120" height="120" fill="#0d6efd" viewBox="0 0 16 16"><path d="M0 .5A.5.5 0 0 1 .5 0h3a.5.5 0 0 1 0 1H1v2.5a.5.5 0 0 1-1 0v-3Zm12 0a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0V1h-2.5a.5.5 0 0 1-.5-.5ZM.5 12a.5.5 0 0 1 .5.5V15h2.5a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5v-3a.5.5 0 0 1 .5-.5Zm15 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1 0-1H15v-2.5a.5.5 0 0 1 .5-.5ZM4 4h1v1H4V4Zm2 0h1v1H6V4Zm3 0h1v1H9V4Zm2 0h1v1h-1V4ZM4 6h1v1H4V6Zm6 0h1v1h-1V6Zm-3 1h1v1H7V7Zm2 0h1v1H9V7ZM4 9h1v1H4V9Zm2 0h1v1H6V9Zm3 0h1v1H9V9Zm2 0h1v1h-1V9Z"/></svg>
            </div>
        </div>
    </div>

    <!-- Real-Time Order Management -->
    <div class="row align-items-center mb-5 pb-4 border-bottom">
        <div class="col-lg-6 order-lg-2 mb-4 mb-lg-0">
            <span class="badge bg-success mb-2">Management</span>
            <h3 class="fw-bold">Real-Time Order Management</h3>
            <p class="text-muted">Orders appear on your waiter dashboard in real time via MQTT push notifications. Track order status from pending to completed and never miss a single order.</p>
            <ul class="list-unstyled">
                <li class="mb-2">&#10003; Instant push notifications</li>
                <li class="mb-2">&#10003; Order status tracking</li>
                <li class="mb-2">&#10003; Waiter assignment to tables</li>
                <li class="mb-2">&#10003; Order history and logs</li>
            </ul>
        </div>
        <div class="col-lg-6 order-lg-1 text-center">
            <div class="bg-light rounded-4 p-5">
                <svg xmlns="http://www.w3.org/2000/svg" width="120" height="120" fill="#198754" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3.5a.5.5 0 0 1-.5-.5v-3.5A.5.5 0 0 1 8 4z"/></svg>
            </div>
        </div>
    </div>

    <!-- Custom Order Forms -->
    <div class="row align-items-center mb-5 pb-4 border-bottom">
        <div class="col-lg-6 mb-4 mb-lg-0">
            <span class="badge bg-warning text-dark mb-2">Customization</span>
            <h3 class="fw-bold">Custom Order Forms</h3>
            <p class="text-muted">Build custom order forms with our drag-and-drop form builder. Add text fields, dropdowns, checkboxes, and more to capture exactly what you need from each order.</p>
            <ul class="list-unstyled">
                <li class="mb-2">&#10003; Drag-and-drop form builder</li>
                <li class="mb-2">&#10003; Multiple field types</li>
                <li class="mb-2">&#10003; Assign forms to specific tables</li>
                <li class="mb-2">&#10003; Preview before publishing</li>
            </ul>
        </div>
        <div class="col-lg-6 text-center">
            <div class="bg-light rounded-4 p-5">
                <svg xmlns="http://www.w3.org/2000/svg" width="120" height="120" fill="#ffc107" viewBox="0 0 16 16"><path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/><path d="M10.97 4.97a.75.75 0 0 1 1.071 1.05l-3.992 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.236.236 0 0 1 .02-.022z"/></svg>
            </div>
        </div>
    </div>

    <!-- Location Restriction -->
    <div class="row align-items-center mb-5">
        <div class="col-lg-6 order-lg-2 mb-4 mb-lg-0">
            <span class="badge bg-info mb-2">Security</span>
            <h3 class="fw-bold">Location-Based Restrictions</h3>
            <p class="text-muted">Ensure orders only come from customers physically present at your restaurant. Set a radius limit and let GPS verification handle the rest.</p>
            <ul class="list-unstyled">
                <li class="mb-2">&#10003; GPS-based order verification</li>
                <li class="mb-2">&#10003; Configurable radius limit</li>
                <li class="mb-2">&#10003; Prevents remote abuse</li>
                <li class="mb-2">&#10003; Toggle on/off per restaurant</li>
            </ul>
        </div>
        <div class="col-lg-6 order-lg-1 text-center">
            <div class="bg-light rounded-4 p-5">
                <svg xmlns="http://www.w3.org/2000/svg" width="120" height="120" fill="#0dcaf0" viewBox="0 0 16 16"><path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/></svg>
            </div>
        </div>
    </div>

    <!-- CTA -->
    <div class="text-center py-5">
        <h3 class="fw-bold mb-3">Ready to get started?</h3>
        <p class="text-muted mb-4">Create your free account and start taking orders in minutes.</p>
        <a href="<?= url('/register') ?>" class="btn btn-primary btn-lg px-5">Sign Up Free</a>
    </div>
</div>
