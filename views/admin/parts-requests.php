<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">คำขอเบิกอะไหล่</h1>
            <p class="text-xs text-slate-500">ตรวจสอบและอนุมัติการเบิกใช้อะไหล่/อุปกรณ์ในงานซ่อมบำรุงโดยช่างเทคนิค</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="<?= url('/admin/inventory') ?>" class="px-4 py-2.5 rounded-xl bg-white border border-slate-200 text-slate-700 font-bold text-xs hover:bg-slate-50 transition">
                📦 ดูคลังอะไหล่ทั้งหมด
            </a>
            <span class="text-xs font-semibold px-3 py-1.5 rounded-xl bg-orange-50 text-orange-700 border border-orange-200">
                รอการพิจารณา <?= count($requests) ?> รายการ
            </span>
        </div>
    </div>

    <!-- Requests Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 border-b border-slate-200 uppercase font-bold tracking-wider text-slate-500">
                    <tr>
                        <th class="px-6 py-3.5">ใบงาน</th>
                        <th class="px-6 py-3.5">สถานที่</th>
                        <th class="px-6 py-3.5">ช่างผู้ขอเบิก</th>
                        <th class="px-6 py-3.5">รายการอะไหล่</th>
                        <th class="px-6 py-3.5">จำนวนที่ขอ</th>
                        <th class="px-6 py-3.5">สต็อกคงเหลือ</th>
                        <th class="px-6 py-3.5">มูลค่ารวม (บาท)</th>
                        <th class="px-6 py-3.5 text-right">การพิจารณา</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if (empty($requests)): ?>
                        <tr>
                            <td colspan="8" class="p-12 text-center text-slate-400">
                                <div class="text-3xl mb-2">🎉</div>
                                ไม่มีรายการขอเบิกอะไหล่ค้างในระบบ
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($requests as $r): 
                            $hasEnoughStock = $r['stock_quantity'] >= $r['quantity'];
                        ?>
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-6 py-4">
                                <a href="<?= url('/tickets/' . $r['ticket_id']) ?>" target="_blank" class="font-bold text-indigo-600 hover:underline">
                                    #<?= $r['ticket_id'] ?>: <?= htmlspecialchars($r['ticket_title']) ?>
                                </a>
                            </td>
                            <td class="px-6 py-4 text-slate-600 font-medium"><?= htmlspecialchars($r['ticket_location']) ?></td>
                            <td class="px-6 py-4 font-semibold text-slate-800"><?= htmlspecialchars($r['technician_name'] ?? 'ช่างเทคนิค') ?></td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900"><?= htmlspecialchars($r['part_name']) ?></div>
                                <div class="text-slate-400 font-mono text-[11px]"><?= htmlspecialchars($r['part_code']) ?></div>
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-900 text-sm"><?= $r['quantity'] ?> ชิ้น</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-0.5 rounded-full font-bold text-xs <?= $hasEnoughStock ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' ?>">
                                    <?= $r['stock_quantity'] ?> ชิ้น
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-700 font-bold">
                                <?= number_format((float)$r['total_price'], 2) ?> ฿
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap space-x-2">
                                <?php if ($hasEnoughStock): ?>
                                    <form action="<?= url('/admin/parts-requests/' . $r['id'] . '/approve') ?>" method="POST" class="inline">
                                        <input type="hidden" name="_csrf" value="<?= \App\Core\Auth::csrfToken() ?>">
                                        <button type="submit" class="px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold transition shadow-xs">
                                            ✓ อนุมัติเบิก
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <span class="text-xs text-rose-500 font-bold mr-2">สต็อกไม่พอ</span>
                                <?php endif; ?>

                                <form action="<?= url('/admin/parts-requests/' . $r['id'] . '/reject') ?>" method="POST" class="inline" onsubmit="return confirm('ยืนยันปฏิเสธการขอเบิกอะไหล่นี้?')">
                                    <input type="hidden" name="_csrf" value="<?= \App\Core\Auth::csrfToken() ?>">
                                    <button type="submit" class="px-3 py-1.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold transition">
                                        ✕ ไม่อนุมัติ
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
