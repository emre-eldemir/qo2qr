<?php $layout = 'public'; $title = 'Register Your Restaurant'; ?>

<div class="row justify-content-center py-5">
    <div class="col-md-8 col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <h3 class="fw-bold">Create Your Account</h3>
                    <p class="text-muted">Register your restaurant and start taking orders today.</p>
                </div>

                <form method="POST" action="<?= url('/register') ?>">
                    <?= CSRF::field() ?>
                    <div class="mb-3">
                        <label for="name" class="form-label">Your Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-lg" id="name" name="name" value="<?= h(old('name')) ?>" placeholder="John Smith" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label for="restaurant_name" class="form-label">Restaurant Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-lg" id="restaurant_name" name="restaurant_name" value="<?= h(old('restaurant_name')) ?>" placeholder="My Restaurant" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                        <input type="email" class="form-control form-control-lg" id="email" name="email" value="<?= h(old('email')) ?>" placeholder="you@restaurant.com" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="Minimum 8 characters" minlength="8" required>
                    </div>
                    <div class="mb-4">
                        <label for="password_confirm" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control form-control-lg" id="password_confirm" name="password_confirm" placeholder="Re-enter your password" minlength="8" required>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100 mb-3">Create Account</button>

                    <p class="text-center text-muted small mb-0">
                        By registering, you agree to start with our <strong>Free Plan</strong>.
                        You can upgrade anytime from your dashboard.
                    </p>
                </form>
            </div>
        </div>

        <div class="text-center mt-4">
            <p class="text-muted">Already have an account? <a href="<?= url('/login/customer') ?>">Sign in here</a></p>
        </div>
    </div>
</div>
