<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">ระบบจัดการผู้ใช้งาน</h1>
            <p class="text-xs text-slate-500">จัดการข้อมูลผู้ใช้งาน กำหนดสิทธิ์ และมอบหมายบทบาท (ผู้แจ้งซ่อม, ช่างเทคนิค, ผู้ดูแลระบบ)</p>
        </div>
        <div>
            <button onclick="document.getElementById('add-user-modal').classList.remove('hidden')" 
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-200 transition">
                <span>➕ เพิ่มผู้ใช้งานใหม่</span>
            </button>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 border-b border-slate-200 uppercase font-bold tracking-wider text-slate-500">
                    <tr>
                        <th class="px-6 py-3.5">ID</th>
                        <th class="px-6 py-3.5">ชื่อ-นามสกุล</th>
                        <th class="px-6 py-3.5">อีเมล</th>
                        <th class="px-6 py-3.5">บทบาท</th>
                        <th class="px-6 py-3.5">วันที่สร้าง</th>
                        <th class="px-6 py-3.5 text-right">การจัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($users as $u): 
                        $roleEnum = \App\Enums\UserRole::tryFrom($u['role']);
                    ?>
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="px-6 py-4 font-bold text-slate-900">#<?= $u['id'] ?></td>
                        <td class="px-6 py-4 font-semibold text-slate-800"><?= htmlspecialchars($u['name']) ?></td>
                        <td class="px-6 py-4 text-slate-600"><?= htmlspecialchars($u['email']) ?></td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-full font-semibold <?= $roleEnum ? $roleEnum->badgeClass() : '' ?>">
                                <?= $roleEnum ? $roleEnum->label() : $u['role'] ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-400"><?= date('d/m/Y H:i', strtotime($u['created_at'])) ?></td>
                        <td class="px-6 py-4 text-right whitespace-nowrap space-x-2">
                            <button onclick="openEditUserModal(<?= $u['id'] ?>, '<?= htmlspecialchars(addslashes($u['name'])) ?>', '<?= htmlspecialchars(addslashes($u['email'])) ?>', '<?= $u['role'] ?>')" 
                                    class="px-2.5 py-1 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-100 font-semibold">
                                แก้ไข
                            </button>
                            <?php if ($u['id'] !== (int)$user['id']): ?>
                                <form action="<?= url('/admin/users/' . $u['id'] . '/delete') ?>" method="POST" class="inline" onsubmit="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบผู้ใช้นี้?')">
                                    <input type="hidden" name="_csrf" value="<?= \App\Core\Auth::csrfToken() ?>">
                                    <button type="submit" class="px-2.5 py-1 rounded-lg bg-rose-50 text-rose-700 hover:bg-rose-100 font-semibold">
                                        ลบ
                                    </button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal: Add User -->
<div id="add-user-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 z-50 hidden">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4 text-xs">
        <div class="flex justify-between items-center border-b border-slate-100 pb-3">
            <h3 class="text-base font-bold text-slate-900">➕ เพิ่มผู้ใช้งานใหม่</h3>
            <button onclick="document.getElementById('add-user-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">&times;</button>
        </div>
        <form action="<?= url('/admin/users') ?>" method="POST" class="space-y-3">
            <input type="hidden" name="_csrf" value="<?= \App\Core\Auth::csrfToken() ?>">
            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">ชื่อ-นามสกุล <span class="text-rose-500">*</span></label>
                <input type="text" name="name" required placeholder="สมชาย หมายดี" class="w-full px-3 py-2 rounded-xl border border-slate-200">
            </div>
            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">อีเมล <span class="text-rose-500">*</span></label>
                <input type="email" name="email" required placeholder="somchai@helpdesk.local" class="w-full px-3 py-2 rounded-xl border border-slate-200">
            </div>
            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">บทบาท <span class="text-rose-500">*</span></label>
                <select name="role" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white">
                    <option value="user">ผู้แจ้งซ่อม</option>
                    <option value="technician">ช่างเทคนิค</option>
                    <option value="admin">ผู้ดูแลระบบ</option>
                </select>
            </div>
            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">รหัสผ่าน <span class="text-rose-500">*</span></label>
                <input type="password" name="password" required placeholder="••••••••" class="w-full px-3 py-2 rounded-xl border border-slate-200">
            </div>
            <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('add-user-modal').classList.add('hidden')" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600">ยกเลิก</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold shadow-md">บันทึกผู้ใช้</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Edit User -->
<div id="edit-user-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 z-50 hidden">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4 text-xs">
        <div class="flex justify-between items-center border-b border-slate-100 pb-3">
            <h3 class="text-base font-bold text-slate-900">✏️ แก้ไขข้อมูลผู้ใช้งาน</h3>
            <button onclick="document.getElementById('edit-user-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">&times;</button>
        </div>
        <form id="edit-user-form" action="" method="POST" class="space-y-3">
            <input type="hidden" name="_csrf" value="<?= \App\Core\Auth::csrfToken() ?>">
            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">ชื่อ-นามสกุล</label>
                <input type="text" name="name" id="edit-user-name" required class="w-full px-3 py-2 rounded-xl border border-slate-200">
            </div>
            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">อีเมล</label>
                <input type="email" name="email" id="edit-user-email" required class="w-full px-3 py-2 rounded-xl border border-slate-200">
            </div>
            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">บทบาท</label>
                <select name="role" id="edit-user-role" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white">
                    <option value="user">ผู้แจ้งซ่อม</option>
                    <option value="technician">ช่างเทคนิค</option>
                    <option value="admin">ผู้ดูแลระบบ</option>
                </select>
            </div>
            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">เปลี่ยนรหัสผ่าน (เว้นว่างไว้ถ้าไม่ต้องการเปลี่ยน)</label>
                <input type="password" name="password" placeholder="••••••••" class="w-full px-3 py-2 rounded-xl border border-slate-200">
            </div>
            <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('edit-user-modal').classList.add('hidden')" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600">ยกเลิก</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold shadow-md">บันทึกการแก้ไข</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditUserModal(id, name, email, role) {
        document.getElementById('edit-user-form').action = `<?= url('/admin/users') ?>/${id}`;
        document.getElementById('edit-user-name').value = name;
        document.getElementById('edit-user-email').value = email;
        document.getElementById('edit-user-role').value = role;
        document.getElementById('edit-user-modal').classList.remove('hidden');
    }
</script>
