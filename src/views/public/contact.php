<?php $layout = 'public'; $title = 'Contact Us'; ?>

<div class="py-4">
    <div class="text-center mb-5">
        <h1 class="fw-bold">Contact Us</h1>
        <p class="lead text-muted">Have a question or feedback? We would love to hear from you.</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="row g-4">
                <!-- Contact Info -->
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4">Get in Touch</h5>
                            <div class="mb-3">
                                <div class="fw-semibold">Email</div>
                                <p class="text-muted mb-0">support@qrorder.com</p>
                            </div>
                            <div class="mb-3">
                                <div class="fw-semibold">Phone</div>
                                <p class="text-muted mb-0">+1 (555) 123-4567</p>
                            </div>
                            <div>
                                <div class="fw-semibold">Hours</div>
                                <p class="text-muted mb-0">Mon-Fri, 9am-6pm EST</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4">Send Us a Message</h5>
                            <form method="POST" action="<?= url('/contact') ?>">
                                <?= CSRF::field() ?>
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" value="<?= h(old('name')) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?= h(old('email')) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="subject" class="form-label">Subject</label>
                                    <input type="text" class="form-control" id="subject" name="subject" value="<?= h(old('subject')) ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="message" class="form-label">Message <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="message" name="message" rows="5" required><?= h(old('message')) ?></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary px-4">Send Message</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
