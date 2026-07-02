<!-- Page Header -->
<div class="page-header">
    <div>
        <div class="page-title">Quản lý Người dùng</div>
        <div class="page-subtitle"><?= count($user_list) ?> tài khoản đã đăng ký</div>
    </div>
</div>

<?php if(isset($_GET['error']) && $_GET['error'] == 'cannot_delete_user'): ?>
<div style="margin-bottom:16px; padding:14px 18px; background:rgba(239,68,68,0.12); border:1px solid var(--danger); border-radius:var(--radius-md); color:var(--danger); display:flex; align-items:center; gap:10px;">
    <i class="fas fa-ban"></i>
    <span>Không thể xóa người dùng. Chức năng này đã bị vô hiệu hóa để bảo vệ dữ liệu.</span>
</div>
<?php endif; ?>

<!-- Users Table -->
<div class="panel">
    <div class="panel-header">
        <span class="panel-title"><i class="fas fa-users" style="color:var(--success); margin-right:8px;"></i>Danh sách người dùng</span>
    </div>
    <div style="overflow-x: auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên đăng nhập</th>
                    <th>Email</th>
                    <th>Họ tên</th>
                    <th>Vai trò</th>
                    <th style="text-align:right;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($user_list as $u): ?>
                <tr>
                    <td><span class="id-chip">#<?= $u['id'] ?></span></td>
                    <td>
                        <div style="display:flex; align-items:center; gap:10px;">
                            <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,var(--accent),#a78bfa);display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;color:#fff;flex-shrink:0;">
                                <?= strtoupper(substr($u['username'], 0, 1)) ?>
                            </div>
                            <span style="font-weight:600;"><?= htmlspecialchars($u['username']) ?></span>
                        </div>
                    </td>
                    <td style="color:var(--text-secondary);"><?= htmlspecialchars($u['email']) ?></td>
                    <td><?= htmlspecialchars($u['fullname'] ?: '—') ?></td>
                    <td>
                        <?php if($u['role'] == 1): ?>
                            <span class="badge-custom badge-admin"><i class="fas fa-shield-alt" style="font-size:9px;"></i> Admin</span>
                        <?php else: ?>
                            <span class="badge-custom badge-user"><i class="fas fa-user" style="font-size:9px;"></i> User</span>
                        <?php endif; ?>
                    </td>
                    <td style="text-align:right;">
                        <span style="font-size:11px; color:var(--text-muted);" title="Chức năng xóa đã bị vô hiệu hóa">
                            <i class="fas fa-lock" style="margin-right:4px;"></i>Không thể xóa
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php if(empty($user_list)): ?>
        <div style="text-align:center; padding:48px 20px; color:var(--text-muted);">
            <i class="fas fa-users" style="font-size:36px; margin-bottom:12px; display:block;"></i>
            Chưa có người dùng nào.
        </div>
        <?php endif; ?>
    </div>
</div>
