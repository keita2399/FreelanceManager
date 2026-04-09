<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>請求書 {{ $invoice->invoice_number }}</title>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
        font-family: 'Hiragino Kaku Gothic ProN', 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
        font-size: 11pt;
        color: #1a1a1a;
        background: #fff;
    }
    .page {
        width: 210mm;
        min-height: 297mm;
        margin: 0 auto;
        padding: 20mm 20mm 15mm;
        background: #fff;
    }
    /* 印刷ボタン（印刷時は非表示） */
    .print-actions {
        position: fixed;
        top: 16px;
        right: 16px;
        display: flex;
        gap: 8px;
        z-index: 100;
    }
    .btn-print {
        padding: 10px 24px;
        background: #4f46e5;
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        cursor: pointer;
        font-family: inherit;
    }
    .btn-back {
        padding: 10px 20px;
        background: #fff;
        color: #374151;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 13px;
        cursor: pointer;
        text-decoration: none;
        font-family: inherit;
        display: inline-block;
        line-height: 1;
    }
    @media print {
        .print-actions { display: none; }
        body { background: #fff; }
        .page { margin: 0; padding: 15mm 20mm; width: 100%; }
    }

    /* 請求書本体 */
    .doc-title {
        font-size: 24pt;
        font-weight: bold;
        color: #4f46e5;
        text-align: center;
        letter-spacing: 0.3em;
        margin-bottom: 6px;
    }
    .doc-number {
        text-align: center;
        font-size: 10pt;
        color: #6b7280;
        margin-bottom: 28px;
    }
    .meta-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 28px;
    }
    .bill-to {
        flex: 1;
    }
    .bill-to .company {
        font-size: 16pt;
        font-weight: bold;
        border-bottom: 2px solid #1a1a1a;
        padding-bottom: 4px;
        margin-bottom: 4px;
    }
    .bill-to .contact {
        font-size: 10pt;
        color: #374151;
    }
    .issuer {
        text-align: right;
        font-size: 10pt;
        color: #374151;
    }
    .issuer .name {
        font-size: 13pt;
        font-weight: bold;
        color: #1a1a1a;
        margin-bottom: 4px;
    }
    .section { margin-bottom: 20px; }
    .section-label {
        font-size: 9pt;
        font-weight: bold;
        color: #4f46e5;
        border-bottom: 1px solid #e5e7eb;
        padding-bottom: 3px;
        margin-bottom: 10px;
        letter-spacing: 0.1em;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th {
        background: #f3f4f6;
        padding: 8px 12px;
        font-size: 9pt;
        text-align: left;
        border: 1px solid #e5e7eb;
        color: #374151;
    }
    td {
        padding: 9px 12px;
        font-size: 10pt;
        border: 1px solid #e5e7eb;
    }
    .total-row td {
        background: #eef2ff;
        font-weight: bold;
        font-size: 13pt;
        color: #4f46e5;
    }
    .tax-row td {
        font-size: 9pt;
        color: #6b7280;
        background: #f9fafb;
    }
    .align-right { text-align: right; }
    .status-badge {
        display: inline-block;
        padding: 3px 12px;
        border-radius: 20px;
        font-size: 10pt;
        font-weight: bold;
    }
    .s-paid { background: #d1fae5; color: #065f46; }
    .s-sent { background: #dbeafe; color: #1e40af; }
    .s-unsent { background: #f3f4f6; color: #374151; }
    .s-overdue { background: #fee2e2; color: #991b1b; }
    .footer {
        margin-top: 32px;
        text-align: center;
        font-size: 9pt;
        color: #9ca3af;
        border-top: 1px solid #e5e7eb;
        padding-top: 12px;
    }
    .info-table td:first-child { width: 120px; color: #6b7280; font-size: 9pt; }
    .info-table td:last-child { font-weight: 500; }
</style>
</head>
<body>

<div class="print-actions">
    <a href="{{ route('invoices.index') }}" class="btn-back">← 戻る</a>
    <button class="btn-print" onclick="window.print()">PDFとして保存</button>
</div>

<div class="page">
    <div class="doc-title">請　求　書</div>
    <div class="doc-number">No. {{ $invoice->invoice_number }}</div>

    <div class="meta-row">
        <div class="bill-to">
            <div class="company">{{ $invoice->project->client->name }}　御中</div>
            @if($invoice->project->client->contact_person)
            <div class="contact">{{ $invoice->project->client->contact_person }} 様</div>
            @endif
        </div>
        <div class="issuer">
            <div class="name">{{ $user->name }}</div>
            <div>{{ $user->email }}</div>
            <div style="margin-top:6px; color:#9ca3af;">発行日: {{ $invoice->issue_date->format('Y年n月j日') }}</div>
            @if($invoice->due_date)
            <div style="color:#9ca3af;">支払期限: {{ $invoice->due_date->format('Y年n月j日') }}</div>
            @endif
        </div>
    </div>

    <div class="section">
        <div class="section-label">請求内容</div>
        @php
            $tax = round($invoice->amount * 0.1);
            $total = $invoice->amount + $tax;
        @endphp
        <table>
            <thead>
                <tr>
                    <th style="width:60%">内容</th>
                    <th class="align-right" style="width:40%">金額</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $invoice->project->name }}</td>
                    <td class="align-right">¥{{ number_format($invoice->amount) }}</td>
                </tr>
                <tr class="tax-row">
                    <td class="align-right">消費税（10%）</td>
                    <td class="align-right">¥{{ number_format($tax) }}</td>
                </tr>
                <tr class="total-row">
                    <td class="align-right">合計（税込）</td>
                    <td class="align-right">¥{{ number_format($total) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-label">ステータス</div>
        @php
            $cls = match($invoice->status) {
                '入金済' => 's-paid', '送付済' => 's-sent',
                '未入金期限超過' => 's-overdue', default => 's-unsent'
            };
        @endphp
        <span class="status-badge {{ $cls }}">{{ $invoice->status }}</span>
    </div>

    @if($invoice->notes)
    <div class="section">
        <div class="section-label">備考</div>
        <p style="font-size:10pt; color:#374151;">{{ $invoice->notes }}</p>
    </div>
    @endif

    <div class="footer">
        ご不明な点は {{ $user->email }} までお問い合わせください。
    </div>
</div>

<script>
    // 直接アクセスした場合のみ自動印刷（iframeからはindex側でprint()を呼ぶ）
    window.addEventListener('load', function () {
        if (window.parent === window) {
            window.print();
        }
    });
</script>

</body>
</html>
