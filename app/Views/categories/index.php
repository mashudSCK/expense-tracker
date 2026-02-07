<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-tags"></i> Manage Categories</h2>
    <a href="<?= base_url('categories/create') ?>" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Add New Category
    </a>
</div>

<div class="card">
    <div class="card-body">
        <?php if (!empty($categories)): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Category Name</th>
                            <th>Created Date</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $category): ?>
                        <tr>
                            <td><?= $category['id'] ?></td>
                            <td><i class="bi bi-tag"></i> <?= esc($category['name']) ?></td>
                            <td><?= date('M d, Y', strtotime($category['created_at'])) ?></td>
                            <td class="text-center">
                                <a href="<?= base_url('categories/edit/' . $category['id']) ?>" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <a href="<?= base_url('categories/delete/' . $category['id']) ?>" 
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Are you sure you want to delete this category? This will also delete all expenses in this category.')">
                                    <i class="bi bi-trash"></i> Delete
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="bi bi-inbox" style="font-size: 4rem; color: #ccc;"></i>
                <p class="text-muted mt-3">No categories found. Click "Add New Category" to get started.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
