<?php $layout = 'public'; $title = 'Login'; ?>

<div class="py-5">
    <div class="text-center mb-5">
        <h1 class="fw-bold">Sign In</h1>
        <p class="lead text-muted">Choose your account type to continue.</p>
    </div>

    <div class="row g-4 justify-content-center">
        <!-- Super Admin -->
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100 text-center">
                <div class="card-body p-5">
                    <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:72px;height:72px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#dc3545" viewBox="0 0 16 16"><path d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872l-.1-.34zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z"/></svg>
                    </div>
                    <h4 class="fw-bold">Super Admin</h4>
                    <p class="text-muted mb-4">System administrator with full platform access.</p>
                    <a href="<?= url('/login/admin') ?>" class="btn btn-outline-danger w-100">Sign In</a>
                </div>
            </div>
        </div>

        <!-- Customer Admin -->
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100 text-center">
                <div class="card-body p-5">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:72px;height:72px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#0d6efd" viewBox="0 0 16 16"><path d="M14.763.075A.5.5 0 0 1 15 .5v15a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5V14h-1v1.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V10a.5.5 0 0 1 .342-.474L6 7.64V4.5a.5.5 0 0 1 .276-.447l8-4a.5.5 0 0 1 .487.022zM6 8.694 1 10.36V15h5V8.694zM7 15h2v-1.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5V15h2V1.309l-7 3.5V15z"/><path d="M2 11h1v1H2v-1zm2 0h1v1H4v-1zm-2 2h1v1H2v-1zm2 0h1v1H4v-1zm4-4h1v1H8V9zm2 0h1v1h-1V9zm-2 2h1v1H8v-1zm2 0h1v1h-1v-1zm2-2h1v1h-1V9zm0 2h1v1h-1v-1zM8 7h1v1H8V7zm2 0h1v1h-1V7zm2 0h1v1h-1V7zM8 5h1v1H8V5zm2 0h1v1h-1V5zm2 0h1v1h-1V5zm0-2h1v1h-1V3z"/></svg>
                    </div>
                    <h4 class="fw-bold">Restaurant Admin</h4>
                    <p class="text-muted mb-4">Manage your restaurant, menu, tables, and orders.</p>
                    <a href="<?= url('/login/customer') ?>" class="btn btn-outline-primary w-100">Sign In</a>
                </div>
            </div>
        </div>

        <!-- Waiter -->
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100 text-center">
                <div class="card-body p-5">
                    <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:72px;height:72px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#198754" viewBox="0 0 16 16"><path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4Zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10Z"/></svg>
                    </div>
                    <h4 class="fw-bold">Waiter</h4>
                    <p class="text-muted mb-4">View your tables and manage incoming orders.</p>
                    <a href="<?= url('/login/waiter') ?>" class="btn btn-outline-success w-100">Sign In</a>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center mt-5">
        <p class="text-muted">Don't have an account? <a href="<?= url('/register') ?>">Register your restaurant</a></p>
    </div>
</div>
