<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-receipt"></i> Manage Expenses</h2>
    <a href="<?= base_url('expenses/create') ?>" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Add New Expense
    </a>
</div>

<div class="card">
    <div class="card-body">
        <?php if (!empty($expenses)): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Category</th>
                            <th>Amount</th>
                            <th>Converted (PHP)</th>
                            <?php if (session()->get('role') === 'admin'): ?>
                            <th>User</th>
                            <?php endif; ?>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($expenses as $expense): ?>
                        <tr>
                            <td><?= date('M d, Y', strtotime($expense['expense_date'])) ?></td>
                            <td><?= esc($expense['description']) ?></td>
                            <td><span class="badge bg-info"><?= esc($expense['category_name']) ?></span></td>
                            <td><?= esc($expense['currency']) ?> <?= number_format($expense['amount'], 2) ?></td>
                            <td>₱ <?= number_format($expense['converted_amount'], 2) ?></td>
                            <?php if (session()->get('role') === 'admin'): ?>
                            <td><?= esc($expense['username']) ?></td>
                            <?php endif; ?>
                            <td class="text-center">
                                <?php if (session()->get('role') === 'admin' || $expense['user_id'] == session()->get('user_id')): ?>
                                <a href="<?= base_url('expenses/edit/' . $expense['id']) ?>" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="<?= base_url('expenses/delete/' . $expense['id']) ?>" 
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Are you sure you want to delete this expense?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="bi bi-inbox" style="font-size: 4rem; color: #ccc;"></i>
                <p class="text-muted mt-3">No expenses found. Click "Add New Expense" to get started.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
