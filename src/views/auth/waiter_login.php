<?php $layout = 'public'; $title = 'Waiter Login'; ?>

<div class="row justify-content-center py-5">
    <div class="col-md-6 col-lg-5">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:64px;height:64px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="#198754" viewBox="0 0 16 16"><path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4Zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10Z"/></svg>
                    </div>
                    <h3 class="fw-bold">Waiter Login</h3>
                    <p class="text-muted">Sign in to access your orders and tables.</p>
                </div>

                <form method="POST" action="<?= url('/login/waiter') ?>">
                    <?= CSRF::field() ?>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control form-control-lg" id="email" name="email" value="<?= h(old('email')) ?>" placeholder="waiter@restaurant.com" required autofocus>
                    </div>
                    <div class="mb-4">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="Enter your password" required>
                    </div>
                    <button type="submit" class="btn btn-success btn-lg w-100 mb-3">Sign In</button>
                </form>

                <div class="text-center">
                    <a href="<?= url('/login') ?>" class="text-muted text-decoration-none">&larr; Back to login options</a>
                </div>
            </div>
        </div>
    </div>
</div>
