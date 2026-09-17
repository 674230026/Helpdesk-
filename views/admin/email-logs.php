<?php
$mailerMode = \App\Core\Config::get('MAIL_MAILER', 'log');
$mailHost = \App\Core\Config::get('MAIL_HOST', 'smtp.gmail.com');
$mailPort = \App\Core\Config::get('MAIL_PORT', '465');
$mailUser = \App\Core\Config::get('MAIL_USERNAME', 'watweera82@gmail.com');
$mailPass = \App\Core\Config::get('MAIL_PASSWORD', '');
$mailEnc = \App\Core\Config::get('MAIL_ENCRYPTION', 'ssl');
$mailFrom = \App\Core\Config::get('MAIL_FROM_ADDRESS', 'watweera82@gmail.com');
$mailFromName = \App\Core\Config::get('MAIL_FROM_NAME', 'Smart IT Helpdesk');
$smtpDebug = $_SESSION['smtp_debug'] ?? null;
unset($_SESSION['smtp_debug']);
?>

<div class="space-y-6 max-w-6xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">ระบบตั้งค่าและประวัติการส่งอีเมลจริง (Live SMTP & Mail Logs)</h1>
            <p class="text-xs text-slate-500 mt-1">กำหนดค่าเชื่อมต่อเซิร์ฟเวอร์อีเมล (Gmail / SMTP) เพื่อส่งการแจ้งเตือนถึงอีเมลลูกค้าจริง</p>
        </div>
        <div class="flex items-center gap-2">
            <?php if ($mailerMode === 'smtp'): ?>
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    🟢 โหมดส่งอีเมลจริง (SMTP Active)
                </span>
            <?php else: ?>
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                    🟡 โหมดบันทึกในเครื่อง (Local Log Only)
                </span>
            <?php endif; ?>
        </div>
    </div>

    <!-- Alert / Flash Messages -->
    <?php if (!empty($flash['success'])): ?>
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-start gap-3 shadow-xs">
            <span class="text-xl">✅</span>
            <div class="space-y-1">
                <p class="font-bold text-emerald-900">สำเร็จ!</p>
                <p><?= htmlspecialchars($flash['success']) ?></p>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($flash['error'])): ?>
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-start gap-3 shadow-xs">
            <span class="text-xl">⚠️</span>
            <div class="space-y-1">
                <p class="font-bold text-rose-900">เกิดข้อผิดพลาดในการส่ง</p>
                <p><?= htmlspecialchars($flash['error']) ?></p>
            </div>
        </div>
    <?php endif; ?>

    <!-- Diagnostic Debug Transcript (if available) -->
    <?php if (!empty($smtpDebug)): ?>
        <div class="bg-slate-900 text-slate-200 rounded-2xl p-4 text-xs font-mono border border-slate-800 shadow-sm">
            <div class="flex items-center justify-between pb-2 mb-2 border-b border-slate-800">
                <span class="text-slate-400 font-bold">🔍 SMTP Server Handshake Transcript:</span>
                <span class="text-[10px] text-slate-500">บันทึกการคุยกับเซิร์ฟเวอร์ SMTP แบบสด</span>
            </div>
            <pre class="overflow-x-auto max-h-48 text-[11px] leading-relaxed text-emerald-400 whitespace-pre-wrap"><?= htmlspecialchars($smtpDebug) ?></pre>
        </div>
    <?php endif; ?>

    <!-- Gmail App Password Guide Card -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-2xl p-5 text-xs text-slate-700 shadow-xs">
        <div class="flex items-start gap-3.5">
            <div class="w-9 h-9 rounded-xl bg-blue-600 text-white flex items-center justify-center text-lg shrink-0 shadow-xs font-bold">
                M
            </div>
            <div class="space-y-2 flex-1">
                <h3 class="font-bold text-sm text-blue-950 flex items-center gap-2">
                    <span>คู่มือการเชื่อมต่อส่งอีเมลจริงผ่าน Gmail (ใช้เวลาเพียง 30 วินาที)</span>
                    <span class="px-2 py-0.5 rounded-md bg-blue-100 text-blue-800 text-[10px] font-semibold">แนะนำสำหรับ Gmail</span>
                </h3>
                <p class="text-blue-900 leading-relaxed">
                    Google ปิดระบบรับรหัสผ่านปกติของอีเมลเพื่อความปลอดภัย ในการส่งอีเมลจริงจากโปรแกรมภายนอก ต้องใช้ <strong>"รหัสผ่านสำหรับแอป (App Password 16 ตัวอักษร)"</strong> โดยทำตาม 3 ขั้นตอนด้านล่าง:
                </p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 pt-1">
                    <div class="bg-white/80 backdrop-blur rounded-xl p-3 border border-blue-100">
                        <span class="inline-block w-5 h-5 rounded-full bg-blue-100 text-blue-800 text-center font-bold text-[11px] leading-5 mb-1.5">1</span>
                        <p class="font-semibold text-slate-800">เปิดการยืนยันแบบ 2 ขั้นตอน</p>
                        <p class="text-[11px] text-slate-500 mt-1">ใน Google Account ตรวจสอบให้แน่ใจว่าเปิด 2-Step Verification ไว้แล้ว</p>
                    </div>
                    <div class="bg-white/80 backdrop-blur rounded-xl p-3 border border-blue-100">
                        <span class="inline-block w-5 h-5 rounded-full bg-blue-100 text-blue-800 text-center font-bold text-[11px] leading-5 mb-1.5">2</span>
                        <p class="font-semibold text-slate-800">สร้างรหัสผ่านแอป</p>
                        <p class="text-[11px] text-slate-500 mt-1">
                            เข้าไปที่ลิงก์ตรง: <a href="https://myaccount.google.com/apppasswords" target="_blank" class="text-indigo-600 font-bold underline hover:text-indigo-800">myaccount.google.com/apppasswords</a> พิมพ์ชื่อว่า "Helpdesk" แล้วกด <strong>สร้าง</strong>
                        </p>
                    </div>
                    <div class="bg-white/80 backdrop-blur rounded-xl p-3 border border-blue-100">
                        <span class="inline-block w-5 h-5 rounded-full bg-blue-100 text-blue-800 text-center font-bold text-[11px] leading-5 mb-1.5">3</span>
                        <p class="font-semibold text-slate-800">กรอกรหัส 16 ตัวอักษร</p>
                        <p class="text-[11px] text-slate-500 mt-1">นำรหัส 16 ตัว (เช่น <code>abcd efgh ijkl mnop</code>) มาใส่ในช่อง <strong>SMTP Password</strong> ด้านล่าง</p>
                    </div>
                </div>
                <div class="text-[11px] text-blue-800/90 pt-1">
                    ⚡ <strong>หมายเหตุเครือข่าย:</strong> ระบบตั้งค่าให้ใช้ <strong>Port 465 (SSL)</strong> เพื่อให้สามารถส่งทะลุผ่านระบบไฟร์วอลล์และ Wi-Fi มหาวิทยาลัยได้ 100%
                </div>
            </div>
        </div>
    </div>

    <!-- Configuration & Live Tester 2-Column Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Column 1: SMTP Settings Form (7 cols) -->
        <div class="lg:col-span-7 bg-white rounded-2xl border border-slate-200 shadow-xs p-6">
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
                <div>
                    <h2 class="font-bold text-slate-900 text-base">⚙️ ตั้งค่าเซิร์ฟเวอร์ SMTP (บันทึกลง .env)</h2>
                    <p class="text-xs text-slate-500">ข้อมูลบัญชีสำหรับเชื่อมต่อส่งอีเมลจริง</p>
                </div>
            </div>

            <form action="<?= url('/admin/email-settings/save') ?>" method="POST" class="space-y-4 text-xs">
                <?= csrf_field() ?>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">โหมดการส่งอีเมล (Driver)</label>
                        <select name="mail_mailer" class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 text-xs font-semibold bg-slate-50">
                            <option value="smtp" <?= $mailerMode === 'smtp' ? 'selected' : '' ?>>smtp (ส่งออกไปยังกล่องจดหมายจริง)</option>
                            <option value="log" <?= $mailerMode === 'log' ? 'selected' : '' ?>>log (จำลองบันทึกไฟล์ในเครื่องเท่านั้น)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">SMTP Host</label>
                        <input type="text" name="mail_host" value="<?= htmlspecialchars($mailHost) ?>" required
                               class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 text-xs font-mono">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">SMTP Port (แนะนำ 465)</label>
                        <input type="number" name="mail_port" value="<?= htmlspecialchars($mailPort) ?>" required
                               class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 text-xs font-mono">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">การเข้ารหัส (Encryption)</label>
                        <select name="mail_encryption" class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 text-xs font-mono bg-slate-50">
                            <option value="ssl" <?= $mailEnc === 'ssl' ? 'selected' : '' ?>>ssl (สำหรับ Port 465 - ปลอดภัยสูงสุด)</option>
                            <option value="tls" <?= $mailEnc === 'tls' ? 'selected' : '' ?>>tls (สำหรับ Port 587)</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">SMTP Username (อีเมลผู้ส่ง เช่น Gmail)</label>
                        <input type="email" name="mail_username" value="<?= htmlspecialchars($mailUser) ?>" placeholder="your_email@gmail.com" required
                               class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 text-xs">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">
                            SMTP Password (รหัสผ่านแอป 16 หลัก)
                            <?php if (!empty($mailPass)): ?>
                                <span class="text-emerald-600 text-[10px] font-normal font-sans">(มีรหัสผ่านบันทึกอยู่แล้ว)</span>
                            <?php endif; ?>
                        </label>
                        <input type="password" name="mail_password" placeholder="<?= !empty($mailPass) ? '••••••••••••••••' : 'ใส่รหัสแอป 16 หลักจาก Google' ?>"
                               class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 text-xs font-mono">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">อีเมลผู้ส่ง (From Address)</label>
                        <input type="email" name="mail_from_address" value="<?= htmlspecialchars($mailFrom) ?>" required
                               class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 text-xs">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">ชื่อผู้ส่ง (From Name)</label>
                        <input type="text" name="mail_from_name" value="<?= htmlspecialchars($mailFromName) ?>" required
                               class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 text-xs">
                    </div>
                </div>

                <div class="pt-2 flex justify-end">
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold transition shadow-xs">
                        💾 บันทึกการตั้งค่าลงไฟล์ .env
                    </button>
                </div>
            </form>
        </div>

        <!-- Column 2: Live SMTP Tester Card (5 cols) -->
        <div class="lg:col-span-5 bg-gradient-to-br from-indigo-900 to-slate-900 rounded-2xl text-white p-6 shadow-sm flex flex-col justify-between">
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-500/20 border border-indigo-400/30 flex items-center justify-center text-xl">
                        🚀
                    </div>
                    <div>
                        <h2 class="font-bold text-base text-white">ทดสอบส่งอีเมลจริงทันที</h2>
                        <p class="text-[11px] text-indigo-200">ตรวจสอบการส่งเข้า Inbox ของคุณแบบเรียลไทม์</p>
                    </div>
                </div>

                <p class="text-xs text-slate-300 leading-relaxed">
                    กรอกอีเมลปลายทางที่ต้องการทดสอบ แล้วกดปุ่ม ระบบจะส่งอีเมลทดสอบเชื่อมต่อจริงผ่าน SMTP และแสดงผลลัพธ์การจับมือกับเซิร์ฟเวอร์ทันที
                </p>

                <form action="<?= url('/admin/email-settings/test') ?>" method="POST" class="space-y-3.5">
                    <?= csrf_field() ?>

                    <div>
                        <label class="block text-xs font-semibold text-indigo-200 mb-1">อีเมลปลายทางที่ต้องการรับข้อความ</label>
                        <input type="email" name="test_email" value="watweera82@gmail.com" required
                               class="w-full px-3.5 py-2.5 rounded-xl bg-white/10 border border-white/20 text-white placeholder-white/40 focus:outline-hidden focus:ring-2 focus:ring-indigo-400 text-xs font-medium">
                    </div>

                    <button type="submit" class="w-full py-3 px-4 rounded-xl bg-indigo-500 hover:bg-indigo-600 text-white font-bold text-xs transition shadow-lg shadow-indigo-500/30 flex items-center justify-center gap-2 cursor-pointer">
                        <span>ส่งอีเมลทดสอบจริงทันที</span>
                        <span>✉️ ↗</span>
                    </button>
                </form>

                <div class="p-3.5 rounded-xl bg-white/5 border border-white/10 text-[11px] text-slate-300 space-y-1">
                    <p class="font-bold text-white flex items-center gap-1.5">
                        <span>💡 ระบบอัตโนมัติเมื่อปิดงานซ่อม:</span>
                    </p>
                    <p class="text-slate-300">
                        เมื่อเปิดใช้งานโหมด SMTP ทุกครั้งที่ช่างกดเปลี่ยนสถานะงานซ่อมเป็น <strong>"เสร็จสิ้น (Resolved)"</strong> หรือปิดงาน ระบบจะส่งอีเมลสรุปและลิงก์ให้คะแนนตรงไปยังกล่องจดหมายของลูกค้าจริงโดยอัตโนมัติ
                    </p>
                </div>
            </div>

            <div class="pt-4 border-t border-white/10 text-[10px] text-slate-400 text-center">
                Smart IT Helpdesk Email Notification Engine
            </div>
        </div>
    </div>

    <!-- Email Logs Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-900 text-sm">📜 ประวัติและสำเนาเทมเพลตอีเมลที่ถูกสร้าง (storage/mail/)</h3>
            <span class="text-xs font-semibold px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600">
                <?= count($files) ?> ฉบับ
            </span>
        </div>

        <?php if (empty($files)): ?>
            <div class="p-12 text-center text-slate-400 text-xs">
                <div class="text-3xl mb-2">📭</div>
                ยังไม่มีการสร้างอีเมลในระบบ (ทดลองสร้าง Ticket ใหม่ หรือ Assign ช่าง เพื่อทดสอบ)
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 border-b border-slate-200 uppercase font-bold tracking-wider text-slate-500">
                        <tr>
                            <th class="px-6 py-3.5">ชื่อไฟล์บันทึก</th>
                            <th class="px-6 py-3.5">ขนาด</th>
                            <th class="px-6 py-3.5">เวลาที่ส่ง</th>
                            <th class="px-6 py-3.5 text-right">เปิดดูหน้าตาอีเมล</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php foreach ($files as $f): ?>
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-6 py-4 font-mono font-medium text-slate-800">
                                ✉️ <?= htmlspecialchars($f['filename']) ?>
                            </td>
                            <td class="px-6 py-4 text-slate-500">
                                <?= round($f['size'] / 1024, 1) ?> KB
                            </td>
                            <td class="px-6 py-4 text-slate-400">
                                <?= date('d/m/Y H:i:s', $f['time']) ?>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="<?= url('/admin/email-logs/view?file=' . urlencode($f['filename'])) ?>" target="_blank" 
                                   class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold transition">
                                    <span>เปิดดู HTML</span> ↗
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
