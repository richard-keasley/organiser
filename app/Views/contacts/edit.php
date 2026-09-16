<div class="card shadow-sm">
    <div class="card-body">
        <h2><?= esc($page_title); ?></h2>

        <?= form_open('contacts/edit/' . $contact['id'], ['class' => 'needs-validation', 'novalidate' => true]); ?>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">First Name</label>
                    <input type="text" name="first_name" class="form-control" value="<?= old('first_name', $contact['first_name'] ?? ''); ?>" required>
                    <?= isset($validation) ? $validation->showError('first_name') : ''; ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Last Name</label>
                    <input type="text" name="last_name" class="form-control" value="<?= old('last_name', $contact['last_name'] ?? ''); ?>" required>
                    <?= isset($validation) ? $validation->showError('last_name') : ''; ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= old('email', $contact['email'] ?? ''); ?>">
                    <?= isset($validation) ? $validation->showError('email') : ''; ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" value="<?= old('phone', $contact['phone'] ?? ''); ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Address</label>
                    <input type="text" name="address" class="form-control" value="<?= old('address', $contact['address'] ?? ''); ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">City</label>
                    <input type="text" name="city" class="form-control" value="<?= old('city', $contact['city'] ?? ''); ?>">
                </div>
                <div class="col-md-12">
                    <label class="form-label">Country</label>
                    <input type="text" name="country" class="form-control" value="<?= old('country', $contact['country'] ?? ''); ?>">
                </div>
                <div class="col-md-12">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control" rows="4"><?= old('notes', $contact['notes'] ?? ''); ?></textarea>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">Update Contact</button>
                <a href="<?= base_url('contacts/view/' . $contact['id']); ?>" class="btn btn-outline-secondary">Cancel</a>
            </div>
        <?= form_close(); ?>
    </div>
</div>
