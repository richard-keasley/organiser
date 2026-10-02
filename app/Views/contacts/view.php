<div class="card shadow-sm">
    <div class="card-body">
        <h2><?= esc($contact['first_name'] . ' ' . $contact['last_name']); ?></h2>
        <div class="row mt-3">
            <div class="col-md-6">
                <?php if (! empty($contact['emails'])): ?>
                    <?php foreach ($contact['emails'] as $email): ?>
                        <p>
                            <strong>Email:</strong> <?= esc($email['email_address'] ?? ''); ?>
                            <?= !empty($email['is_primary']) ? '<span class="badge bg-primary">Primary</span>' : ''; ?>
                            <?php if (! empty($email['email_type'])): ?>
                                <small class="text-muted">(<?= esc($email['email_type']); ?>)</small>
                            <?php endif; ?>
                        </p>
                    <?php endforeach; ?>
                <?php elseif (! empty($contact['email'])): ?>
                    <p><strong>Email:</strong> <?= esc($contact['email']); ?></p>
                <?php endif; ?>

                <?php if (! empty($contact['phones'])): ?>
                    <?php foreach ($contact['phones'] as $phone): ?>
                        <p>
                            <strong>Phone:</strong> <?= esc($phone['phone_number'] ?? ''); ?>
                            <?= !empty($phone['is_primary']) ? '<span class="badge bg-primary">Primary</span>' : ''; ?>
                            <?php if (! empty($phone['phone_type'])): ?>
                                <small class="text-muted">(<?= esc($phone['phone_type']); ?>)</small>
                            <?php endif; ?>
                        </p>
                    <?php endforeach; ?>
                <?php elseif (! empty($contact['phone'])): ?>
                    <p><strong>Phone:</strong> <?= esc($contact['phone']); ?></p>
                <?php endif; ?>

                <?php if (! empty($contact['addresses'])): ?>
                    <?php foreach ($contact['addresses'] as $address): ?>
                        <p>
                            <strong>Address:</strong> <?= esc($address['street_address'] ?? ''); ?>,
                            <?= esc($address['city'] ?? ''); ?>
                            <?= !empty($address['state_province']) ? ', ' . esc($address['state_province']) : ''; ?>
                            <?= !empty($address['postal_code']) ? ' ' . esc($address['postal_code']) : ''; ?>
                            <?= !empty($address['country']) ? ', ' . esc($address['country']) : ''; ?>
                            <?= !empty($address['is_primary']) ? '<span class="badge bg-primary">Primary</span>' : ''; ?>
                            <?php if (! empty($address['address_type'])): ?>
                                <small class="text-muted">(<?= esc($address['address_type']); ?>)</small>
                            <?php endif; ?>
                        </p>
                    <?php endforeach; ?>
                <?php elseif (! empty($contact['address'])): ?>
                    <p><strong>Address:</strong> <?= esc($contact['address']); ?></p>
                <?php endif; ?>
            </div>
            <div class="col-md-6">
                <p><strong>City:</strong> <?= esc($contact['city'] ?? ''); ?></p>
                <p><strong>Country:</strong> <?= esc($contact['country'] ?? ''); ?></p>
                <p><strong>Notes:</strong> <?= esc($contact['notes'] ?? ''); ?></p>
            </div>
        </div>
        <div class="mt-4">
            <a href="<?= base_url('contacts/edit/' . $contact['id']); ?>" class="btn btn-primary">Edit</a>
            <a href="<?= base_url('contacts'); ?>" class="btn btn-outline-secondary">Back to list</a>
        </div>
    </div>
</div>

<?php if (! empty($anniversaries)): ?>
    <div class="mt-4">
        <h3>Anniversaries</h3>
        <ul class="list-group">
            <?php foreach ($anniversaries as $anniversary): ?>
                <li class="list-group-item">
                    <?= esc($anniversary['title'] ?? 'Anniversary'); ?> - <?= esc($anniversary['event_date'] ?? ''); ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
