<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @font-face {
            font-family: "DejaVu Sans";
            font-style: normal;
            font-weight: 400;
            src: url("/fonts/dejavu-sans/DejaVuSans.ttf");
            /* IE9 Compat Modes */
            src: local("DejaVu Sans"),
            local("DejaVu Sans"),
            url("/fonts/dejavu-sans/DejaVuSans.ttf") format("truetype");
        }

        body {
            font-family: "DejaVu Sans", sans-serif;
            margin: 0;
            padding: 0;
        }

        .page-break {
            page-break-after: always;
        }

        .first-page {
            padding: 25px;
            text-align: center;
        }

        .top-header {
            font-size: 18px;
            text-transform: uppercase;
        }

        .education-name {
            font-size: 24px;
            font-weight: 700;
        }

        .sup-title {
            font-size: 35px;
            font-weight: 700;
        }

        .title {
            margin-bottom: 25px;
            font-size: 45px;
        }

        .number {
            font-size: 36px;
            margin-bottom: 80px;
            font-weight: 700;
        }

        .top-header, .education-name {
            margin-bottom: 50px;
        }

        .sup-title {
            margin-bottom: 70px;
        }

        .from-inventory, .to-inventory {
            text-transform: uppercase;
            font-size: 24px;
            font-weight: 700;
        }

        .to-inventory {
            margin-bottom: 50px;
        }

        .from-date, .to-date {
            font-size: 28px;
            text-transform: uppercase;
        }

        .initial {
            font-size: 16px;
        }

        .underlined {
            text-align: left;
            padding: 0 25px;
            display: flex;
            justify-content: space-between;
        }

        .underline-input {
            margin-left: auto;
            border-bottom: 1px solid #000000;
        }

        .from-inventory .underline-input, .to-inventory .underline-input {
            width: 48%;
        }

        .from-date .underline-input {
            width: 78%;
        }

        .to-date .underline-input {
            width: 73%;
        }

        table {
            border-spacing: 0;
        }

        table {
            border: .01em solid black;
        }

        th {
            border: .002em solid #000000;
            padding: 3px;
            text-align: left;
            font-size: 8px;
        }

        td {
            border: .001em solid #000000;
            padding: 5px;
        }

        table {
            max-width: 595px;
            width: 100%;
            max-height: 842px;
            font-size: 10px;
            margin: 0;
            table-layout: fixed;
        }

        .create_date {
            width: 10%;
        }

        .check {
            width: 8%;
        }

        .check-value {
            width: 4%;
            text-align: center;
        }

        .item_no {
            width: 10%;
        }

        .author_title {
            width: 24%;
        }

        .year_city {
            width: 12%;
        }

        .cost {
            width: 8%;
        }

        .barcode {
            width: 9%;
        }

        .call_number {
            width: 11%;
        }

        .doc_no {
            width: 8%;
        }

        .page {
            text-align: center;
        }

        .library-name, .total-cost_title {
            text-align: right;
        }

        body {
            counter-reset: table;
        }

        .page-number:before {
            counter-increment: table;
            content: counter(table);
        }
    </style>
</head>
<body>
<div class="first-page">
    <div class="top-header">{{ __('prints/inventories.top-header', [], $locale) }}</div>
    <div class="education-name">{{ __('prints/inventories.education-name', [], $locale) }}</div>
    <div class="sup-title">{{ __('prints/inventories.sup-title', [], $locale) }}</div>
    <h1 class="title">{{ __('prints/inventories.title', [], $locale) }}</h1>
    <div class="number">№</div>
    <div class="from-inventory underlined">
        <div>{{ __('prints/inventories.from-inventory', [], $locale) }}</div>
        <div class="underline-input">&nbsp;</div>
    </div>
    <div class="to-inventory underlined">
        <div>{{ __('prints/inventories.to-inventory', [], $locale) }}</div>
        <div class="underline-input">&nbsp;</div>
    </div>
    <div class="from-date underlined">
        <div>{{ __('prints/inventories.from-date', [], $locale) }}</div>
        <div class="underline-input">&nbsp;</div>
    </div>
    <div class="to-date underlined">
        <div>{{ __('prints/inventories.to-date', [], $locale) }}</div>
        <div class="underline-input">&nbsp;</div>
    </div>
    <div class="initial">{{ __('prints/inventories.initial', [], $locale) . ', ' . date('Y') }}</div>
</div>
<div class="page-break"></div>
<div class="item-pages">
    <table class="items">
        <thead>
        <tr class="top">
            <th colspan="4" class="date">{{ date('d.m.Y') }}</th>
            <th colspan="3" class="page">[<span class="page-number"></span>]</th>
            <th colspan="3" class="library-name">{{ __('prints/inventories.library-name', [], $locale) }}</th>
        </tr>
        <tr class="headers">
            <th class="create_date batch_id"><b>{{ __('prints/inventories.create_date', [], $locale) }}
                    , {{ __('prints/inventories.batch_id', [], $locale) }}</b></th>
            <th class="item_no"><b>{{ __('prints/inventories.inventory_no', [], $locale) }}</b></th>
            <th class="check" colspan="2"><b>{{ __('prints/inventories.check', [], $locale) }}</b></th>
            <th class="author_title"><b>{{ __('prints/inventories.author_title', [], $locale) }}</b></th>
            <th class="year_city"><b>{{ __('prints/inventories.year_city', [], $locale) }}</b></th>
            <th class="cost"><b>{{ __('prints/inventories.cost', [], $locale) }}</b></th>
            <th class="barcode"><b>{{ __('prints/inventories.barcode', [], $locale) }}</b></th>
            <th class="call_number"><b>{{ __('prints/inventories.call_number', [], $locale) }}</b></th>
            <th class="doc_no"><b>{{ __('prints/inventories.doc_no', [], $locale) }}</b></th>
        </tr>
        </thead>
        <tbody>
        @foreach($items as $item)
            <tr>
                <td class="create_date create_date-value batch_id batch_id-value">{{ $item->create_date }}
                    <br>{{ $item->batch_id }}</td>
                <td class="item_no item_no-value">{{ $item->item_no }}</td>
                <td class="check-value">-</td>
                <td class="check-value">-</td>
                <td class="author_title author_title-value">{{ $item->author_title }}</td>
                <td class="year_city year_city-value">{{ $item->year_city }}</td>
                <td class="cost cost-value">{{ $item->cost }} {{ $item->currency }}</td>
                <td class="barcode barcode-value">{{ $item->barcode }}</td>
                <td class="call_number call_number-value">{{ $item->call_number }}</td>
                <td class="doc_no doc_no-value">{{ $item->doc_no }}</td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <th colspan="6"
                class="total-cost_title">{{ __('prints/inventories.total-cost_title', ['count' => $count], $locale) }}</th>
            <th colspan="4" class="total-cost">{{ $cost }}</th>
        </tr>
        <tr>
            <th colspan="10">{{ __('prints/inventories.footer-comment', [], $locale) }}</th>
        </tr>
        </tfoot>
    </table>
</div>
</body>
