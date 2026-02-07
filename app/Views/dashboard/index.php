<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-speedometer2"></i> Dashboard</h2>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="stat-card">
            <h5><i class="bi bi-cash-stack"></i> Total Expenses</h5>
            <h2>₱ <?= number_format($total_expenses, 2) ?></h2>
            <p class="mb-0">In Philippine Peso (PHP)</p>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-pie-chart"></i> Expenses by Category</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($expenses_by_category)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th class="text-end">Total Amount (PHP)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($expenses_by_category as $category): ?>
                                <tr>
                                    <td><i class="bi bi-tag"></i> <?= esc($category['name']) ?></td>
                                    <td class="text-end">₱ <?= number_format($category['total'], 2) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center">No expenses recorded yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0"><i class="bi bi-clock-history"></i> Recent Expenses (Last 10)</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($recent_expenses)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Description</th>
                                    <th>Category</th>
                                    <th>Amount</th>
                                    <th class="text-end">Converted (PHP)</th>
                                    <?php if (session()->get('role') === 'admin'): ?>
                                    <th>User</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_expenses as $expense): ?>
                                <tr>
                                    <td><?= date('M d, Y', strtotime($expense['expense_date'])) ?></td>
                                    <td><?= esc($expense['description']) ?></td>
                                    <td><span class="badge bg-info"><?= esc($expense['category_name']) ?></span></td>
                                    <td><?= esc($expense['currency']) ?> <?= number_format($expense['amount'], 2) ?></td>
                                    <td class="text-end">₱ <?= number_format($expense['converted_amount'], 2) ?></td>
                                    <?php if (session()->get('role') === 'admin'): ?>
                                    <td><?= esc($expense['username']) ?></td>
                                    <?php endif; ?>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center">No expenses recorded yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
