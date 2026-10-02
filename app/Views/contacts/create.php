<div class="card shadow-sm">
    <div class="card-body">
        <h2><?= esc($page_title); ?></h2>

        <?= form_open('contacts/create', ['class' => 'needs-validation', 'novalidate' => true]); ?>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">First Name</label>
                    <input type="text" name="first_name" class="form-control" value="<?= old('first_name'); ?>" required>
                    <?= isset($validation) ? $validation->showError('first_name') : ''; ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Last Name</label>
                    <input type="text" name="last_name" class="form-control" value="<?= old('last_name'); ?>" required>
                    <?= isset($validation) ? $validation->showError('last_name') : ''; ?>
                </div>

                <div class="col-12">
                    <h4 class="mt-3">Phone numbers</h4>
                    <?php $phones = old('phones') ?: [
                        ['phone_number' => '', 'phone_type' => 'Mobile', 'is_primary' => '1'],
                        ['phone_number' => '', 'phone_type' => 'Home', 'is_primary' => '0'],
                    ]; ?>
                    <?php foreach ($phones as $index => $phone): ?>
                        <div class="row g-2 mb-2 align-items-end">
                            <div class="col-md-5">
                                <label class="form-label">Number</label>
                                <input type="text" name="phones[<?= $index ?>][phone_number]" class="form-control" value="<?= esc(old('phones.' . $index . '.phone_number', $phone['phone_number'] ?? '')); ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Type</label>
                                <select name="phones[<?= $index ?>][phone_type]" class="form-select">
                                    <?php foreach (['Mobile', 'Home', 'Work', 'Other'] as $type): ?>
                                        <option value="<?= esc($type); ?>" <?= (old('phones.' . $index . '.phone_type', $phone['phone_type'] ?? 'Mobile') === $type) ? 'selected' : ''; ?>><?= esc($type); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="checkbox" name="phones[<?= $index ?>][is_primary]" value="1" <?= !empty(old('phones.' . $index . '.is_primary', $phone['is_primary'] ?? false)) ? 'checked' : ''; ?>>
                                    <label class="form-check-label">Primary</label>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="col-12">
                    <h4 class="mt-3">Email addresses</h4>
                    <?php $emails = old('emails') ?: [
                        ['email_address' => '', 'email_type' => 'Personal', 'is_primary' => '1'],
                        ['email_address' => '', 'email_type' => 'Work', 'is_primary' => '0'],
                    ]; ?>
                    <?php foreach ($emails as $index => $email): ?>
                        <div class="row g-2 mb-2 align-items-end">
                            <div class="col-md-5">
                                <label class="form-label">Email</label>
                                <input type="email" name="emails[<?= $index ?>][email_address]" class="form-control" value="<?= esc(old('emails.' . $index . '.email_address', $email['email_address'] ?? '')); ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Type</label>
                                <select name="emails[<?= $index ?>][email_type]" class="form-select">
                                    <?php foreach (['Personal', 'Work', 'Other'] as $type): ?>
                                        <option value="<?= esc($type); ?>" <?= (old('emails.' . $index . '.email_type', $email['email_type'] ?? 'Personal') === $type) ? 'selected' : ''; ?>><?= esc($type); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="checkbox" name="emails[<?= $index ?>][is_primary]" value="1" <?= !empty(old('emails.' . $index . '.is_primary', $email['is_primary'] ?? false)) ? 'checked' : ''; ?>>
                                    <label class="form-check-label">Primary</label>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="col-12">
                    <h4 class="mt-3">Postal addresses</h4>
                    <?php $addresses = old('addresses') ?: [
                        ['street_address' => '', 'city' => '', 'state_province' => '', 'postal_code' => '', 'country' => '', 'address_type' => 'Home', 'is_primary' => '1'],
                    ]; ?>
                    <?php foreach ($addresses as $index => $address): ?>
                        <div class="border rounded p-3 mb-3">
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label class="form-label">Street</label>
                                    <input type="text" name="addresses[<?= $index ?>][street_address]" class="form-control" value="<?= esc(old('addresses.' . $index . '.street_address', $address['street_address'] ?? '')); ?>">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">City</label>
                                    <input type="text" name="addresses[<?= $index ?>][city]" class="form-control" value="<?= esc(old('addresses.' . $index . '.city', $address['city'] ?? '')); ?>">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">State/Province</label>
                                    <input type="text" name="addresses[<?= $index ?>][state_province]" class="form-control" value="<?= esc(old('addresses.' . $index . '.state_province', $address['state_province'] ?? '')); ?>">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Postal code</label>
                                    <input type="text" name="addresses[<?= $index ?>][postal_code]" class="form-control" value="<?= esc(old('addresses.' . $index . '.postal_code', $address['postal_code'] ?? '')); ?>">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Country</label>
                                    <input type="text" name="addresses[<?= $index ?>][country]" class="form-control" value="<?= esc(old('addresses.' . $index . '.country', $address['country'] ?? '')); ?>">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Type</label>
                                    <select name="addresses[<?= $index ?>][address_type]" class="form-select">
                                        <?php foreach (['Home', 'Work', 'Billing', 'Shipping', 'Other'] as $type): ?>
                                            <option value="<?= esc($type); ?>" <?= (old('addresses.' . $index . '.address_type', $address['address_type'] ?? 'Home') === $type) ? 'selected' : ''; ?>><?= esc($type); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" type="checkbox" name="addresses[<?= $index ?>][is_primary]" value="1" <?= !empty(old('addresses.' . $index . '.is_primary', $address['is_primary'] ?? false)) ? 'checked' : ''; ?>>
                                        <label class="form-check-label">Primary</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Legacy Email</label>
                    <input type="email" name="email" class="form-control" value="<?= old('email'); ?>">
                    <?= isset($validation) ? $validation->showError('email') : ''; ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Legacy Phone</label>
                    <input type="text" name="phone" class="form-control" value="<?= old('phone'); ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Legacy Address</label>
                    <input type="text" name="address" class="form-control" value="<?= old('address'); ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">City</label>
                    <input type="text" name="city" class="form-control" value="<?= old('city'); ?>">
                </div>
                <div class="col-md-12">
                    <label class="form-label">Country</label>
                    <input type="text" name="country" class="form-control" value="<?= old('country'); ?>">
                </div>
                <div class="col-md-12">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control" rows="4"><?= old('notes'); ?></textarea>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">Save Contact</button>
                <a href="<?= base_url('contacts'); ?>" class="btn btn-outline-secondary">Cancel</a>
            </div>
        <?= form_close(); ?>
    </div>
</div>
