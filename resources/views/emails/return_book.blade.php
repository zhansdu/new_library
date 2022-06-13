<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Library of Suleyman Demirel University</title>
    <style>
        html {
            font-size: 10px;
            font-family: sans-serif;
        }

        body {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        .container {
            padding: 15px;
        }

        .media_wrapper {
            margin: 20px 0;
        }

        .media-type__title {
            font-size: 1.4rem;
        }

        .media {
            margin-top: 10px;
            padding-left: 10px;
        }

        .media_table {
            border-collapse: collapse;
        }

        .media_table, .media_table__title th, .media_table__item td {
            border: 1px solid #ddd;
        }

        .media_table__title th, .media_table__item td {
            padding: 5px 15px;
            white-space: nowrap;
            width: auto;
        }

        .media_table__item td:nth-child(1),
        .media_table__item td:nth-child(4),
        .media_table__item td:nth-child(5) {
            text-align: left;
        }

        .media_table__item td:nth-child(2),
        .media_table__item td:nth-child(3) {
            text-align: center;
        }

        .media_table__item td:nth-child(6) {
            text-align: right;
        }

        .media_table__title th {
            background-color: #4caf50;
            color: #ffffff;
            font-weight: 700;
            text-align: center;
        }

        .media_table__item:nth-child(even) {
            background-color: #bebebe;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Dear {{ ucfirst($fullname) }}</h1>
    <div class="return-media_block media_wrapper">
        <p class="media-type__title">This is to remind you that the following book(s) are due
            tomorrow, {{ \Carbon\Carbon::tomorrow()->format('d.m.Y') }}</p>
        <div class="media">
            <table class="media_table">
                <tr class="media_table__title">
                    <th>Type of publication</th>
                    <th>Date of issues</th>
                    <th>Date of return</th>
                    <th>Author(s)</th>
                    <th>Title</th>
                    <th>Barcode</th>
                </tr>
                @foreach ($returns as $item)
                    <tr class="media_table__item">
                        <td>{{ $item->type }}</td>
                        <td>{{ $item->issue_date }}</td>
                        <td>{{ $item->due_date }}</td>
                        <td>{{ $item->author }}</td>
                        <td>{{ $item->title }}</td>
                        <td>{{ $item->barcode }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
    @if(!empty($others))
        <div class="others-media_block media_wrapper">
            <p class="media-type__title">Just to inform you that you have owed the book</p>
            <div class="media">
                <table class="media_table">
                    <tr class="media_table__title">
                        <th>Type of publication</th>
                        <th>Date of issues</th>
                        <th>Date of return</th>
                        <th>Author(s)</th>
                        <th>Title</th>
                        <th>Barcode</th>
                    </tr>
                    @foreach ($others as $item)
                        <tr class="media_table__item">
                            <td>{{ $item->type }}</td>
                            <td>{{ $item->issue_date }}</td>
                            <td>{{ $item->due_date }}</td>
                            <td>{{ $item->authors }}</td>
                            <td>{{ $item->title }}</td>
                            <td>{{ $item->barcode }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    @endif
</div>
</body>
</html>
