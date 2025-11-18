<div class="wrap tocc-admin-dashboard">
    <h1>Pending Payments</h1>
    
    <?php
    require_once(__DIR__ . '/../includes/registration-handler.php');
    global $wpdb;
    
    $page = isset($_GET['paged']) ? intval($_GET['paged']) : 1;
    $per_page = 20;
    $offset = ($page - 1) * $per_page;
    
    // Get pending payments
    $query = "
        SELECT m.*, u.user_email, u.user_login, p.status as payment_status, p.payment_method, p.amount, p.created_at as payment_date
        FROM {$wpdb->prefix}tocc_members m
        LEFT JOIN {$wpdb->users} u ON m.user_id = u.ID
        LEFT JOIN {$wpdb->prefix}tocc_payments p ON m.user_id = p.user_id
        WHERE p.status = 'pending'
        ORDER BY m.created_at DESC
        LIMIT %d OFFSET %d
    ";
    
    $members = $wpdb->get_results($wpdb->prepare($query, $per_page, $offset));
    
    // Count total pending
    $total_query = "
        SELECT COUNT(*) FROM {$wpdb->prefix}tocc_members m
        LEFT JOIN {$wpdb->prefix}tocc_payments p ON m.user_id = p.user_id
        WHERE p.status = 'pending'
    ";
    $total = $wpdb->get_var($total_query);
    $total_pages = ceil($total / $per_page);
    ?>

    <div class="tocc-stats">
        <div class="stat-card">
            <div class="stat-number"><?php echo esc_html($total); ?></div>
            <div class="stat-label">Pending Payments</div>
        </div>
    </div>

    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th>Member Name</th>
                <th>Email</th>
                <th>Company</th>
                <th>Sector</th>
                <th>Payment Method</th>
                <th>Amount</th>
                <th>Payment Date</th>
                <th>Registered</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($members)) : ?>
                <?php foreach ($members as $member) : ?>
                    <tr>
                        <td>
                            <strong>
                                <?php echo esc_html($member->user_login); ?>
                            </strong>
                            <br>
                            <small><?php echo esc_html($member->company_name); ?></small>
                        </td>
                        <td><?php echo esc_html($member->user_email); ?></td>
                        <td><?php echo esc_html($member->company_name); ?></td>
                        <td><?php echo esc_html($member->sector); ?></td>
                        <td>
                            <span class="payment-method">
                                <?php echo esc_html(ucfirst(str_replace('_', ' ', $member->payment_method))); ?>
                            </span>
                        </td>
                        <td>£<?php echo esc_html(number_format($member->amount, 2)); ?></td>
                        <td><?php echo esc_html($member->payment_date ? date('M d, Y', strtotime($member->payment_date)) : 'N/A'); ?></td>
                        <td><?php echo esc_html(date('M d, Y', strtotime($member->created_at))); ?></td>
                        <td>
                            <button class="button button-small tocc-view-details" data-member-id="<?php echo esc_attr($member->user_id); ?>">
                                View Details
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="9" style="text-align: center; padding: 40px;">
                        No pending payments found.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <?php if ($total_pages > 1) : ?>
        <div class="tablenav bottom">
            <div class="tablenav-pages">
                <span class="displaying-num"><?php echo esc_html(sprintf('Showing %d-%d of %d', $offset + 1, min($offset + $per_page, $total), $total)); ?></span>
                <span class="pagination-links">
                    <?php if ($page > 1) : ?>
                        <a class="prev-page" href="?page=tocc_pending_payments&paged=1">&laquo;</a>
                        <a class="prev-page" href="?page=tocc_pending_payments&paged=<?php echo $page - 1; ?>">‹</a>
                    <?php endif; ?>
                    
                    <?php echo esc_html(sprintf('Page %d of %d', $page, $total_pages)); ?>
                    
                    <?php if ($page < $total_pages) : ?>
                        <a class="next-page" href="?page=tocc_pending_payments&paged=<?php echo $page + 1; ?>">›</a>
                        <a class="next-page" href="?page=tocc_pending_payments&paged=<?php echo $total_pages; ?>">&raquo;</a>
                    <?php endif; ?>
                </span>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
.tocc-admin-dashboard {
    margin: 20px;
}

.tocc-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.stat-number {
    font-size: 2em;
    font-weight: bold;
    color: #1a3a52;
}

.stat-label {
    color: #666;
    margin-top: 8px;
}

.payment-method {
    background: #e3f2fd;
    color: #1976d2;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.9em;
}

.payment-status {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.9em;
    font-weight: 600;
}

.payment-status.status-pending {
    background: #fff3cd;
    color: #856404;
}

.payment-status.status-completed {
    background: #d4edda;
    color: #155724;
}

.payment-status.status-failed {
    background: #f8d7da;
    color: #721c24;
}

.tocc-view-details {
    background: #1a3a52;
    color: white;
    border: none;
}

.tocc-view-details:hover {
    background: #0f2437;
}
</style>
