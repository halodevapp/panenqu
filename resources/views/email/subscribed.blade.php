<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Contact</title>
    <style>
        body {
            padding-top: 30px;
            font-family: sans-serif;
        }

        .card {
            display: grid;
            margin: 0 auto;
            border: 1px solid rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            width: 80%;
        }

        .text-center {
            text-align: center;
        }

        .card-header {
            background-color: rgba(192, 223, 224, 0.1);
            padding: 15px 15px;
        }

        .card-text {
            color: rgba(0, 0, 0, 0.8);
        }

        .card-body {
            max-width: inherit;
            padding: 30px 15px;
        }

        .card-body p {
            display: inline-block;
            word-break: break-all;
        }

        .btn {
            display: inline-block;
            padding: 10px 10px;
            cursor: pointer;
        }

        .btn-primary {
            color: white !important;
            background-color: #48ABDE;
            text-decoration: none;
            border-radius: 5px;
        }

        .card-footer {
            background-color: rgba(192, 223, 224, 0.1);
            padding: 15px 15px;
        }

        .my-10 {
            margin: 10px 0;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .table>thead {
            font-weight: bolder;
        }

        .table tr td {
            padding: 10px;
            word-wrap: break-word
        }

        table,
        td,
        th {
            border: 0 solid rgba(0, 0, 0, 0.2);
        }

        .v-align-top {
            vertical-align: top;
        }

        .w100px {
            width: 100px;
        }

        .w5px {
            width: 5px;
        }

        .text-bolder {
            font-weight: bolder;
        }
    </style>

</head>

<body>
    <div class="card">
        <div class="card-header text-center">
            <h4>Subscriber</h4>
        </div>
        <div class="card-body text-center">
            <p>{{ $subscribe->email }} has been subscribe panenqu.</p>
        </div>
        <div class="card-footer text-center">
            <a href="panenqu.com" target="_blank" style="text-decoration: none">panenqu.com</a>
        </div>
    </div>


</body>

</html>
