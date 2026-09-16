<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><?= esc($page_title); ?></h1>
    <a href="<?= base_url('contacts/create'); ?>" class="btn btn-primary">Add Contact</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="get" action="<?= base_url('contacts'); ?>" class="row g-2 align-items-center">
            <div class="col-md-8">
                <input type="text" class="form-control" name="search" value="<?= esc($search_query ?? ''); ?>" placeholder="Search contacts...">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-outline-primary w-100">Search</button>
            </div>
        </form>
    </div>
</div>

<table class="table table-striped table-hover mt-4 align-middle">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>City</th>
            <th class="text-end">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($contacts)): ?>
            <tr>
                <td colspan="5" class="text-center text-muted">No contacts found.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($contacts as $contact): ?>
                <tr>
                    <td>
                        <a href="<?= base_url('contacts/view/' . $contact['id']); ?>" class="text-decoration-none">
                            <?= esc($contact['first_name'] . ' ' . $contact['last_name']); ?>
                        </a>
                    </td>
                    <td><?= esc($contact['email'] ?? ''); ?></td>
                    <td><?= esc($contact['phone'] ?? ''); ?></td>
                    <td><?= esc($contact['city'] ?? ''); ?></td>
                    <td class="text-end">
                        <a href="<?= base_url('contacts/view/' . $contact['id']); ?>" class="btn btn-sm btn-outline-secondary">View</a>
                        <a href="<?= base_url('contacts/edit/' . $contact['id']); ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                        <form method="post" action="<?= base_url('contacts/delete/' . $contact['id']); ?>" class="d-inline">
                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirmDelete();">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>
