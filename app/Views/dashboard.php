<h1><?= esc($page_title); ?></h1>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h3>Overview</h3>
                <p class="mb-2"><strong>Total contacts:</strong> <?= esc($total_contacts ?? 0); ?></p>
                <p class="mb-2"><strong>Total anniversaries:</strong> <?= esc($total_anniversaries ?? 0); ?></p>
                <p class="mb-0"><strong>Upcoming anniversaries:</strong> <?= esc(count($upcoming_anniversaries ?? [])); ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h3>Recent contacts</h3>
                <?php if (empty($recent_contacts)): ?>
                    <p class="text-muted mb-0">No contacts yet.</p>
                <?php else: ?>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($recent_contacts as $contact): ?>
                            <li class="list-group-item">
                                <a href="<?= base_url('contacts/view/' . $contact['id']); ?>" class="text-decoration-none">
                                    <?= esc($contact['first_name'] . ' ' . $contact['last_name']); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
