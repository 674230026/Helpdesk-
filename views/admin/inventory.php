<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">คลังอะไหล่และอุปกรณ์</h1>
            <p class="text-xs text-slate-500">จัดการรายการวัสดุ/อุปกรณ์และอะไหล่คงคลังสำหรับการซ่อมแซมและบำรุงรักษา</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="<?= url('/admin/parts-requests') ?>" class="px-4 py-2.5 rounded-xl bg-orange-50 hover:bg-orange-100 text-orange-800 font-bold text-xs border border-orange-200 transition">
                📦 ตรวจสอบคำขอเบิกอะไหล่
            </a>
            <button onclick="document.getElementById('add-part-modal').classList.remove('hidden')" 
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-200 transition">
                <span>➕ เพิ่มอะไหล่ใหม่</span>
            </button>
        </div>
    </div>

    <!-- Inventory Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 border-b border-slate-200 uppercase font-bold tracking-wider text-slate-500">
                    <tr>
                        <th class="px-6 py-3.5">รหัสอะไหล่</th>
                        <th class="px-6 py-3.5">ชื่อรายการ</th>
                        <th class="px-6 py-3.5">จำนวนคงเหลือ</th>
                        <th class="px-6 py-3.5">ราคาต่อหน่วย (บาท)</th>
                        <th class="px-6 py-3.5 text-right">การจัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($parts as $p): 
                        $isLowStock = $p['stock_quantity'] <= 5;
                    ?>
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="px-6 py-4 font-mono font-bold text-slate-900"><?= htmlspecialchars($p['part_code']) ?></td>
                        <td class="px-6 py-4 font-semibold text-slate-800"><?= htmlspecialchars($p['name']) ?></td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold <?= $isLowStock ? 'bg-rose-100 text-rose-800 border border-rose-200 animate-pulse' : 'bg-emerald-100 text-emerald-800' ?>">
                                <?= $p['stock_quantity'] ?> ชิ้น <?= $isLowStock ? '(ใกล้หมด)' : '' ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-700 font-medium">
                            <?= number_format((float)$p['unit_price'], 2) ?> ฿
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap space-x-2">
                            <button onclick="openEditPartModal(<?= $p['id'] ?>, '<?= htmlspecialchars(addslashes($p['part_code'])) ?>', '<?= htmlspecialchars(addslashes($p['name'])) ?>', <?= $p['stock_quantity'] ?>, <?= $p['unit_price'] ?>)" 
                                    class="px-2.5 py-1 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-100 font-semibold">
                                แก้ไข
                            </button>
                            <form action="<?= url('/admin/inventory/' . $p['id'] . '/delete') ?>" method="POST" class="inline" onsubmit="return confirm('ยืนยันลบอะไหล่นี้?')">
                                <input type="hidden" name="_csrf" value="<?= \App\Core\Auth::csrfToken() ?>">
                                <button type="submit" class="px-2.5 py-1 rounded-lg bg-rose-50 text-rose-700 hover:bg-rose-100 font-semibold">
                                    ลบ
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal: Add Part -->
<div id="add-part-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 z-50 hidden">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4 text-xs">
        <div class="flex justify-between items-center border-b border-slate-100 pb-3">
            <h3 class="text-base font-bold text-slate-900">➕ เพิ่มอะไหล่เข้าคลัง</h3>
            <button onclick="document.getElementById('add-part-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">&times;</button>
        </div>
        <form action="<?= url('/admin/inventory') ?>" method="POST" class="space-y-3">
            <input type="hidden" name="_csrf" value="<?= \App\Core\Auth::csrfToken() ?>">
            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">รหัสอะไหล่ <span class="text-rose-500">*</span></label>
                <input type="text" name="part_code" required placeholder="เช่น RAM-DDR4-8G" class="w-full px-3 py-2 rounded-xl border border-slate-200">
            </div>
            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">ชื่อรายการอะไหล่ <span class="text-rose-500">*</span></label>
                <input type="text" name="name" required placeholder="เช่น RAM DDR4 8GB Kingston" class="w-full px-3 py-2 rounded-xl border border-slate-200">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">จำนวนตั้งต้น</label>
                    <input type="number" name="stock_quantity" min="0" value="10" required class="w-full px-3 py-2 rounded-xl border border-slate-200">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">ราคาต่อหน่วย (บาท)</label>
                    <input type="number" step="0.01" name="unit_price" value="0.00" required class="w-full px-3 py-2 rounded-xl border border-slate-200">
                </div>
            </div>
            <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('add-part-modal').classList.add('hidden')" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600">ยกเลิก</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold shadow-md">บันทึก</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Edit Part -->
<div id="edit-part-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 z-50 hidden">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4 text-xs">
        <div class="flex justify-between items-center border-b border-slate-100 pb-3">
            <h3 class="text-base font-bold text-slate-900">✏️ แก้ไขข้อมูลอะไหล่</h3>
            <button onclick="document.getElementById('edit-part-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">&times;</button>
        </div>
        <form id="edit-part-form" action="" method="POST" class="space-y-3">
            <input type="hidden" name="_csrf" value="<?= \App\Core\Auth::csrfToken() ?>">
            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">รหัสอะไหล่</label>
                <input type="text" name="part_code" id="edit-part-code" required class="w-full px-3 py-2 rounded-xl border border-slate-200">
            </div>
            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">ชื่อรายการอะไหล่</label>
                <input type="text" name="name" id="edit-part-name" required class="w-full px-3 py-2 rounded-xl border border-slate-200">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">จำนวนคงเหลือ</label>
                    <input type="number" name="stock_quantity" id="edit-part-stock" min="0" required class="w-full px-3 py-2 rounded-xl border border-slate-200">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">ราคาต่อหน่วย (บาท)</label>
                    <input type="number" step="0.01" name="unit_price" id="edit-part-price" required class="w-full px-3 py-2 rounded-xl border border-slate-200">
                </div>
            </div>
            <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('edit-part-modal').classList.add('hidden')" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600">ยกเลิก</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold shadow-md">บันทึกการแก้ไข</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditPartModal(id, code, name, stock, price) {
        document.getElementById('edit-part-form').action = `<?= url('/admin/inventory') ?>/${id}`;
        document.getElementById('edit-part-code').value = code;
        document.getElementById('edit-part-name').value = name;
        document.getElementById('edit-part-stock').value = stock;
        document.getElementById('edit-part-price').value = price;
        document.getElementById('edit-part-modal').classList.remove('hidden');
    }
</script>
