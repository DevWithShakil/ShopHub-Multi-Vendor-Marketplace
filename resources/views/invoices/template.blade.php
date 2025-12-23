<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice #{{ $order->invoice_no }}</title>
    <style>
        @page { margin: 0; padding: 0; }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 14px;
            color: #333;
            margin: 0;
            padding: 0;
            background: #fff;
        }

        :root {
            --primary: #4F46E5;
            --dark: #0B0F19;
            --gray: #6B7280;
        }

        /* --- LAYOUT --- */
        .wrapper { width: 100%; }

        /* 🔥 HEADER (Dark Theme) */
        .header {
            background-color: #0B0F19;
            padding: 40px 50px;
            color: #fff;
            border-bottom: 4px solid #4F46E5;
        }
        .logo-text {
            font-size: 28px;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        .logo-sub {
            font-size: 10px;
            color: #a5b4fc; /* Light Indigo */
            letter-spacing: 4px;
            text-transform: uppercase;
            display: block;
            margin-top: 5px;
        }
        .invoice-label {
            float: right;
            text-align: right;
        }
        .invoice-heading {
            font-size: 30px;
            font-weight: 900;
            color: #4F46E5;
            text-transform: uppercase;
            margin: 0;
        }
        .invoice-no {
            font-size: 14px;
            color: #fff;
            margin-top: 5px;
            font-family: monospace;
        }

        /* --- INFO SECTION --- */
        .info-section {
            padding: 40px 50px;
        }
        .info-table { width: 100%; }
        .info-table td { vertical-align: top; }

        .box-title {
            font-size: 11px;
            text-transform: uppercase;
            color: #4F46E5;
            font-weight: 800;
            margin-bottom: 8px;
            letter-spacing: 1px;
        }
        .box-content {
            font-size: 14px;
            line-height: 1.6;
            color: #1f2937;
        }
        .box-content strong { color: #000; font-size: 15px; }

        /* --- ORDER DETAILS GRID --- */
        .details-box {
            background: #F3F4F6;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #4F46E5;
        }

        /* --- ITEMS TABLE --- */
        .table-wrapper { padding: 0 50px; }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .items-table th {
            background-color: #0B0F19; /* Dark Header */
            color: #fff;
            padding: 12px 15px;
            text-align: left;
            font-weight: bold;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .items-table th.right { text-align: right; }

        .items-table td {
            padding: 15px;
            border-bottom: 1px solid #e5e7eb;
            color: #374151;
        }
        .items-table tr:nth-child(even) { background-color: #F9FAFB; }
        .items-table td.right { text-align: right; }
        .item-name { font-weight: bold; color: #111; display: block; }
        .item-meta { font-size: 11px; color: #6B7280; }

        /* --- TOTALS --- */
        .totals-section {
            padding: 20px 50px;
            float: right;
            width: 40%;
        }
        .totals-table { width: 100%; border-collapse: collapse; }
        .totals-table td { padding: 8px 0; text-align: right; }
        .total-label { color: #6B7280; font-size: 13px; }
        .total-val { font-weight: bold; color: #111; font-size: 14px; }

        .grand-total-row td {
            border-top: 2px solid #0B0F19;
            padding-top: 15px;
            font-size: 18px;
            color: #4F46E5;
            font-weight: 900;
        }

        /* --- STATUS STAMP --- */
        .stamp {
            position: absolute;
            top: 220px;
            right: 50px;
            font-size: 50px;
            font-weight: 900;
            color: {{ $status_color }};
            border: 6px solid {{ $status_color }};
            padding: 10px 40px;
            text-transform: uppercase;
            transform: rotate(-10deg);
            opacity: 0.15;
            border-radius: 12px;
            z-index: 0;
            font-family: 'Courier New', monospace;
        }

        /* --- FOOTER --- */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: #0B0F19;
            color: #9CA3AF;
            text-align: center;
            padding: 30px 0;
            font-size: 12px;
        }
        .footer-link { color: #4F46E5; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>

    <div class="header">
        <table style="width: 100%">
            <tr>
                <td>
                    <div class="logo-text">ShopHub</div>
                    <div class="logo-sub">Premium Store</div>
                </td>
                <td class="invoice-label">
                    <div class="invoice-heading">INVOICE</div>
                    <div class="invoice-no">#{{ $order->invoice_no }}</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="stamp">{{ $status_label }}</div>

    <div class="wrapper">
        <div class="info-section">
            <table class="info-table">
                <tr>
                    <td width="40%">
                        <div class="box-title">Billed To</div>
                        <div class="box-content">
                            <strong>{{ $order->user->name }}</strong><br>
                            {{ $order->shipping_address ?? 'Address not provided' }}<br>
                            {{ $order->phone }}<br>
                            {{ $order->user->email }}
                        </div>
                    </td>
                    <td width="20%"></td> <td width="40%">
                        <div class="details-box">
                            <table style="width: 100%">
                                <tr>
                                    <td class="box-title" style="margin:0;">Date:</td>
                                    <td style="text-align: right; font-weight: bold;">{{ $order->created_at->format('d M, Y') }}</td>
                                </tr>
                                <tr>
                                    <td class="box-title" style="margin:0;">Status:</td>
                                    <td style="text-align: right; font-weight: bold; color: {{ $status_color }};">
                                        {{ ucfirst($order->status) }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="table-wrapper">
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="border-top-left-radius: 6px; border-bottom-left-radius: 6px;">Description</th>
                        <th class="right">Quantity</th>
                        <th class="right">Unit Price</th>
                        <th class="right" style="border-top-right-radius: 6px; border-bottom-right-radius: 6px;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>
                            <span class="item-name">{{ $item->product->name }}</span>
                            </td>
                        <td class="right">{{ $item->quantity }}</td>
                        <td class="right">BDT {{ number_format($item->unit_price) }}</td>
                        <td class="right">BDT {{ number_format($item->total_price) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="clear: both;"></div>

        <div class="totals-section">
            <table class="totals-table">
                <tr>
                    <td class="total-label">Subtotal</td>
                    <td class="total-val">BDT {{ number_format($order->total_amount) }}</td>
                </tr>
                <tr>
                    <td class="total-label">Shipping & Handling</td>
                    <td class="total-val">Free</td>
                </tr>
                <tr class="grand-total-row">
                    <td>Total Due</td>
                    <td>BDT {{ number_format($order->total_amount) }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="footer">
        <p style="margin-bottom: 5px;">Thank you for your business!</p>
        <p>
            www.shophub.com &nbsp;|&nbsp; support@shophub.com &nbsp;|&nbsp; +880 1234 567890
        </p>
    </div>

</body>
</html>
