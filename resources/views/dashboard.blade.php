<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>داشبورد محتوای استخراج‌شده</title>

    <style>
        * {
            box-sizing: border-box;
        }

        :root {
            --bg: #f4f7fb;
            --bg-accent-1: rgba(59, 130, 246, 0.10);
            --bg-accent-2: rgba(139, 92, 246, 0.08);

            --card-bg: rgba(255, 255, 255, 0.88);
            --card-border: rgba(255, 255, 255, 0.85);
            --card-shadow: 0 20px 60px rgba(15, 23, 42, 0.10);

            --text: #0f172a;
            --text-soft: #475569;
            --text-muted: #64748b;

            --line: #e2e8f0;
            --line-soft: #edf2f7;

            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --primary-soft: #dbeafe;

            --success: #166534;
            --success-soft: #dcfce7;

            --warning: #92400e;
            --warning-soft: #fef3c7;

            --danger: #991b1b;
            --danger-soft: #fee2e2;

            --neutral: #374151;
            --neutral-soft: #e5e7eb;
        }

        body {
            margin: 0;
            font-family: Tahoma, "Vazirmatn", sans-serif;
            color: var(--text);
            background:
                radial-gradient(circle at top right, var(--bg-accent-1), transparent 25%),
                radial-gradient(circle at top left, var(--bg-accent-2), transparent 20%),
                var(--bg);
            min-height: 100vh;
        }

        a {
            text-decoration: none;
        }

        .container {
            max-width: 1450px;
            margin: 0 auto;
            padding: 36px 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 20px;
            flex-wrap: wrap;
            margin-bottom: 24px;
        }

        .header-content {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .header-badge {
            display: inline-flex;
            align-items: center;
            width: fit-content;
            padding: 7px 14px;
            border-radius: 999px;
            background: rgba(37, 99, 235, 0.08);
            color: var(--primary);
            border: 1px solid rgba(37, 99, 235, 0.12);
            font-size: 13px;
            font-weight: 700;
        }

        .header-title {
            margin: 0;
            font-size: 38px;
            line-height: 1.2;
            font-weight: 800;
            letter-spacing: -0.4px;
            color: #0b1324;
        }

        .header-subtitle {
            margin: 0;
            color: var(--text-muted);
            font-size: 14px;
        }

        .stats-box {
            min-width: 200px;
            padding: 16px 18px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.75);
            border: 1px solid rgba(255, 255, 255, 0.95);
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
            backdrop-filter: blur(10px);
        }

        .stats-label {
            display: block;
            font-size: 13px;
            color: var(--text-muted);
            margin-bottom: 8px;
        }

        .stats-value {
            display: block;
            font-size: 28px;
            line-height: 1;
            font-weight: 800;
            color: var(--text);
        }

        .card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 26px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
            backdrop-filter: blur(14px);
        }

        .card-top {
            padding: 20px 24px;
            border-bottom: 1px solid var(--line);
            background: linear-gradient(to left, rgba(255,255,255,.75), rgba(248,250,252,.95));
        }

        .card-title {
            margin: 0;
            font-size: 17px;
            font-weight: 800;
            color: #1e293b;
        }

        .card-subtitle {
            margin: 6px 0 0;
            font-size: 13px;
            color: var(--text-muted);
        }

        .table-wrapper {
            overflow-x: auto;
            padding: 10px 14px 0;
        }

        .content-table {
            width: 100%;
            min-width: 1250px;
            border-collapse: separate;
            border-spacing: 0 12px;
        }

        .content-table thead th {
            padding: 0 14px 12px;
            font-size: 12px;
            font-weight: 800;
            color: var(--text-soft);
            white-space: nowrap;
            text-align: right;
        }

        .content-table tbody tr {
            filter: drop-shadow(0 10px 25px rgba(15, 23, 42, 0.05));
        }

        .content-table tbody td {
            background: rgba(255, 255, 255, 0.95);
            padding: 18px 14px;
            vertical-align: top;
            font-size: 14px;
            color: #334155;
            border-top: 1px solid #f1f5f9;
            border-bottom: 1px solid #f1f5f9;
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }

        .content-table tbody tr td:first-child {
            border-right: 1px solid #f1f5f9;
            border-top-right-radius: 18px;
            border-bottom-right-radius: 18px;
        }

        .content-table tbody tr td:last-child {
            border-left: 1px solid #f1f5f9;
            border-top-left-radius: 18px;
            border-bottom-left-radius: 18px;
        }

        .content-table tbody tr:hover td {
            background: #ffffff;
            transform: translateY(-1px);
        }

        .title-column {
            min-width: 380px;
            max-width: 460px;
        }

        .post-title {
            margin: 0;
            color: var(--text);
            font-size: 15px;
            font-weight: 700;
            line-height: 1.9;
        }

        .author-text,
        .date-text,
        .plain-text {
            color: var(--text-soft);
            font-weight: 500;
        }

        .domain-chip {
            display: inline-flex;
            align-items: center;
            padding: 8px 12px;
            border-radius: 999px;
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            border: 1px solid #e2e8f0;
            color: #334155;
            font-size: 13px;
            font-weight: 700;
            white-space: nowrap;
        }

        .meta-chip {
            display: inline-block;
            max-width: 220px;
            padding: 8px 12px;
            border-radius: 12px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #475569;
            font-size: 12px;
            line-height: 1.8;
            word-break: break-word;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 100px;
            padding: 8px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 800;
            line-height: 1;
            border: 1px solid transparent;
            white-space: nowrap;
        }

        .badge-success {
            background: var(--success-soft);
            color: var(--success);
            border-color: rgba(22, 101, 52, 0.10);
        }

        .badge-warning {
            background: var(--warning-soft);
            color: var(--warning);
            border-color: rgba(146, 64, 14, 0.10);
        }

        .badge-danger {
            background: var(--danger-soft);
            color: var(--danger);
            border-color: rgba(153, 27, 27, 0.10);
        }

        .badge-secondary {
            background: var(--neutral-soft);
            color: var(--neutral);
            border-color: rgba(55, 65, 81, 0.10);
        }

        .action-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 14px;
            border-radius: 12px;
            background: linear-gradient(180deg, #eff6ff 0%, #dbeafe 100%);
            border: 1px solid rgba(37, 99, 235, 0.10);
            color: var(--primary);
            font-weight: 800;
            font-size: 13px;
            transition: all 0.2s ease;
        }

        .action-link:hover {
            background: linear-gradient(180deg, #dbeafe 0%, #bfdbfe 100%);
            color: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 8px 18px rgba(37, 99, 235, 0.15);
        }

        .empty-state {
            text-align: center;
            padding: 48px 20px;
            color: var(--text-muted);
            font-size: 15px;
            background: white;
            border-radius: 18px;
            border: 1px solid #f1f5f9;
        }

        .pagination-wrapper {
            padding: 24px;
            border-top: 1px solid var(--line);
            background: linear-gradient(to left, rgba(248,250,252,.98), rgba(255,255,255,.95));
        }

        /* Laravel pagination custom styles */
        .pagination-wrapper nav {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 14px;
        }

        .pagination-wrapper nav > div:first-child {
            display: none;
        }

        .pagination-wrapper nav > div:last-child {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 14px;
            width: 100%;
        }

        .pagination-wrapper p {
            margin: 0;
            font-size: 14px;
            color: var(--text-muted);
            text-align: center;
        }

        .pagination-wrapper span[aria-current="page"] span,
        .pagination-wrapper a,
        .pagination-wrapper span.relative.inline-flex.items-center {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 42px;
            height: 42px;
            padding: 0 14px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            color: #334155;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.04);
        }

        .pagination-wrapper a:hover {
            background: #eff6ff;
            border-color: #bfdbfe;
            color: var(--primary);
            transform: translateY(-1px);
        }

        .pagination-wrapper span[aria-current="page"] span {
            background: linear-gradient(180deg, #2563eb 0%, #1d4ed8 100%);
            color: #ffffff;
            border-color: #2563eb;
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.22);
        }

        .pagination-wrapper span[aria-disabled="true"] span,
        .pagination-wrapper span[aria-disabled="true"] {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 42px;
            height: 42px;
            padding: 0 14px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            color: #94a3b8;
            font-size: 14px;
            font-weight: 700;
            cursor: not-allowed;
            box-shadow: none;
        }

        .pagination-wrapper .hidden.sm\\:flex-1.sm\\:flex.sm\\:items-center.sm\\:justify-between {
            display: flex !important;
            flex-direction: column;
            align-items: center;
            gap: 16px;
            width: 100%;
        }

        .pagination-wrapper .hidden.sm\\:flex-1.sm\\:flex.sm\\:items-center.sm\\:justify-between > div:first-child {
            order: 2;
        }

        .pagination-wrapper .hidden.sm\\:flex-1.sm\\:flex.sm\\:items-center.sm\\:justify-between > div:last-child {
            order: 1;
        }

        .pagination-wrapper .relative.z-0.inline-flex {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 8px;
            direction: ltr;
        }

        .pagination-wrapper svg {
            width: 16px;
            height: 16px;
        }

        @media (max-width: 992px) {
            .header-title {
                font-size: 30px;
            }

            .container {
                padding: 24px 14px;
            }
        }

        @media (max-width: 640px) {
            .header {
                align-items: stretch;
            }

            .stats-box {
                width: 100%;
            }

            .header-title {
                font-size: 24px;
            }

            .card-top,
            .pagination-wrapper {
                padding: 18px 16px;
            }

            .table-wrapper {
                padding: 8px 10px 0;
            }

            .pagination-wrapper a,
            .pagination-wrapper span[aria-current="page"] span,
            .pagination-wrapper span[aria-disabled="true"] span,
            .pagination-wrapper span.relative.inline-flex.items-center {
                min-width: 38px;
                height: 38px;
                font-size: 13px;
                border-radius: 10px;
            }
        }
    </style>
</head>
<body>
@php
    $statusMap = [
        'published' => ['label' => 'منتشر شده', 'class' => 'badge-success'],
        'pending' => ['label' => 'در انتظار', 'class' => 'badge-warning'],
        'failed' => ['label' => 'ناموفق', 'class' => 'badge-danger'],
        'draft' => ['label' => 'پیش‌نویس', 'class' => 'badge-secondary'],
        'successful' => ['label' => 'موفق', 'class' => 'badge-success'],
    ];
@endphp

<div class="container">
    <div class="header">
        <div class="header-content">
            <span class="header-badge">پنل مدیریت محتوا</span>
            <h1 class="header-title">داشبورد محتوای استخراج‌شده</h1>
            <p class="header-subtitle">نمایش، بررسی و مدیریت رکوردهای جمع‌آوری‌شده از منابع مختلف</p>
        </div>

        <div class="stats-box">
            <span class="stats-label">مجموع رکوردها</span>
            <span class="stats-value">{{ $scrapedContents->total() }}</span>
        </div>
    </div>

    <div class="card">
        <div class="card-top">
            <h2 class="card-title">فهرست محتوا</h2>
            <p class="card-subtitle">اطلاعات کامل محتواهای استخراج‌شده همراه با وضعیت، منبع و تاریخ انتشار</p>
        </div>

        <div class="table-wrapper">
            <table class="content-table">
                <thead>
                <tr>
                    <th>عنوان</th>
                    <th>نویسنده</th>
                    <th>دامنه</th>
                    <th>دسته‌بندی‌ها</th>
                    <th>برچسب‌ها</th>
                    <th>وضعیت</th>
                    <th>تاریخ انتشار</th>
                    <th>لینک</th>
                </tr>
                </thead>

                <tbody>
                @forelse($scrapedContents as $item)
                    @php
                        $status = $statusMap[$item->status] ?? [
                            'label' => $item->status ?: 'نامشخص',
                            'class' => 'badge-secondary',
                        ];
                    @endphp

                    <tr>
                        <td class="title-column">
                            <p class="post-title">{{ $item->title ?: '-' }}</p>
                        </td>

                        <td class="author-text">
                            {{ $item->author ?: 'نامشخص' }}
                        </td>

                        <td>
                            <span class="domain-chip">{{ $item->domain ?: '-' }}</span>
                        </td>

                        <td>
                            <span class="meta-chip">{{ $item->categories ?: '-' }}</span>
                        </td>

                        <td>
                            <span class="meta-chip">{{ $item->tags ?: '-' }}</span>
                        </td>

                        <td>
                            <span class="badge {{ $status['class'] }}">{{ $status['label'] }}</span>
                        </td>

                        <td class="date-text">
                            @if($item->published_at)
                                {{ \Carbon\Carbon::parse($item->published_at)->translatedFormat('j F Y') }}
                            @else
                                -
                            @endif
                        </td>

                        <td>
                            <a href="{{ $item->url }}" target="_blank" rel="noopener noreferrer" class="action-link">
                                مشاهده
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                هیچ رکوردی یافت نشد.
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-wrapper">
            {{ $scrapedContents->links() }}
        </div>
    </div>
</div>

</body>
</html>
