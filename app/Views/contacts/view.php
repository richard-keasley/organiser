<div class="card shadow-sm">
    <div class="card-body">
        <h2><?= esc($contact['first_name'] . ' ' . $contact['last_name']); ?></h2>
        <div class="row mt-3">
            <div class="col-md-6">
                <p><strong>Email:</strong> <?= esc($contact['email'] ?? ''); ?></p>
                <p><strong>Phone:</strong> <?= esc($contact['phone'] ?? ''); ?></p>
                <p><strong>Address:</strong> <?= esc($contact['address'] ?? ''); ?></p>
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
