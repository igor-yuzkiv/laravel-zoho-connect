<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>
    <title>Zoho Connection</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <meta name="are-token" content="{{config('are.ext.areToken')}}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2"
            crossorigin="anonymous"></script>
    <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,800,900|Material+Icons' rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Roboto:300' rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <style>
        body {
            height: 100vh;
            font-family: Roboto, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgb(7, 71, 166);
        }

        .block {
            background: #fff;
            flex-direction: column;
            font-size: 14px;
            display: flex;
            border-radius: 2px;
            box-shadow: 0 0 70px -15px;
            justify-content: space-between;
            padding: 2%;
            height: 90vh;
        }

        form i {
            font-size: 100px !important;
            margin: 10px auto 0;
            color: rgb(7, 71, 166);
            justify-content: center;
        }

        .scoresBlock {
            max-height: 90vh;
            overflow-y: scroll;
            background: #fff;
            font-size: 14px;
            border-radius: 2px;
            box-shadow: 20px 0px 70px -15px;
            padding: 2%;
        }

        .scoresBlock h1 {
            text-align: center;
        }

        h2 {
            text-align: center;
        }

        select {
            width: 30%;
        }

        label {
            margin: 0 10px
        }

        input[type=submit] {
            width: 100px;
            margin: 10px auto;
        }

        .results tr[visible='false'],
        .no-result {
            display: none;
        }

        .results tr[visible='true'] {
            display: table-row;
        }

        .counter {
            padding: 8px;
            color: #ccc;
        }
    </style>

</head>

<body>
<div class="container-fluid">
    <div class="row">
        <div class="row">
            <form action="/zoho/connection/authorization" method="post">
                <div class="col-10 offset-1">
                    <div class="row">
                        <div class="col-4">
                            <div class="block">
                                {{ csrf_field() }}

                                <i class="material-icons">account_circle</i>
                                <h2>Zoho OAuth Setup</h2><br>
                                <div class="row">
                                    <div class="col">
                                        <label>
                                            Open the link you need:<br>
                                            For US:<a href="https://accounts.zoho.com/developerconsole"
                                                      target="_blank">instruction</a><br>
                                            For EU:<a href="https://accounts.zoho.eu/developerconsole"
                                                      target="_blank">instruction</a><br>
                                            For IN:<a href="https://accounts.zoho.in/developerconsole"
                                                      target="_blank">instruction</a><br>
                                            For CN:<a href="https://accounts.zoho.com.cn/developerconsole"
                                                      target="_blank">instruction</a><br>
                                            For AU:<a href="https://accounts.zoho.com.com.ua/developerconsole"
                                                      target="_blank">instruction</a><br>
                                            and enter the following details:
                                        </label>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col">
                                        <label>
                                            <b>Client Name</b><br>
                                            YOUR_APP_NAME
                                        </label>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col">
                                        <label>
                                            <b>Client Domain</b><br>
                                            {{ config('app.url') }}
                                        </label>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col">
                                        <label>
                                            <b>Authorized Redirect URIs</b><br>
                                            {{ $callback_url }}
                                        </label>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col">
                                        <label>
                                            After click <b>Create</b>, you will receive the following credentials.<br>
                                            You need copied this details to fields:
                                        </label>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col">
                                        <label for="id" class="form-label"><strong>Client ID</strong></label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" name="id" required
                                                   aria-describedby="Client ID">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col">
                                        <label for="secret" class="form-label"><strong>Client Secret</strong></label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" name="secret" required
                                                   aria-describedby="Client Secret">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col">
                                        <label for="data_center" class="form-label"><strong>Data Center
                                                (Domain)</strong></label>
                                        <div class="input-group mb-3">
                                            <select name="data_center" class="form-select">
                                                @foreach($data_center as $key => $item)
                                                    <option value="{{$key}}">{{$item['domain']}}
                                                        - {{$item['title']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-grid gap-2 col-6 mx-auto">
                                    <button type="submit" class="btn btn-outline-success">Proceed</button>
                                </div>

                                <div class="row mt-2">
                                    <div class="col">
                                        <label>
                                            <a href="https://www.zoho.com/crm/developer/docs/api/register-client.html"
                                               target="_blank">Official
                                                documentation</a>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-8 scoresBlock">
                            <div class="row">
                                <div class="col-12">
                                    <h1>Scores</h1>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-12">
                                    <div class="form-group pull-right">
                                        <input type="text" class="search form-control"
                                               placeholder="Start taping for search...">
                                    </div>
                                    <span class="counter pull-right"></span>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <table class="table table-hover table-bordered results">
                                        <thead>
                                        <tr>
                                            <th style="width: 5%"></th>
                                            <th style="width: 45%">
                                                Display Name
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr class="warning no-result">
                                            <td></td>
                                            <td>No result</td>
                                        </tr>
                                        @foreach($scopes as $key => $item)
                                            <tr>
                                                <td scope="row">
                                                    <input class="form-check-input" type="checkbox"
                                                           value="{{$item['name']}}" name="scopes[{{$key}}]">
                                                </td>
                                                <td>{{$item['name']}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</body>

<script>
    $(document).ready(function () {
        $(".search").keyup(function () {
            var searchTerm = $(".search").val();
            var listItem = $('.results tbody').children('tr');
            var searchSplit = searchTerm.replace(/ /g, "'):containsi('")

            $.extend($.expr[':'], {
                'containsi': function (elem, i, match, array) {
                    return (elem.textContent || elem.innerText || '').toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
                }
            });

            $(".results tbody tr").not(":containsi('" + searchSplit + "')").each(function (e) {
                $(this).attr('visible', 'false');
            });

            $(".results tbody tr:containsi('" + searchSplit + "')").each(function (e) {
                $(this).attr('visible', 'true');
            });

            var jobCount = $('.results tbody tr[visible="true"]').length;
            $('.counter').text(jobCount + ' item');

            if (jobCount == '0') {
                $('.no-result').show();
            } else {
                $('.no-result').hide();
            }
        });
    });
</script>

</html>
