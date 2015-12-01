<!DOCTYPE html>
<html>
<head>
    <title>Boilerplate Package for Laravel by Leelam</title>

    <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

    <style>
        html, body {
            height: 100%;
        }

        body {
            margin: 0;
            padding: 0;
            width: 100%;
            display: table;
            font-weight: 100;
            font-family: 'Lato';
        }

        .container {
            text-align: center;
            display: table-cell;
            vertical-align: middle;
        }

        .content {
            text-align: center;
            display: inline-block;
        }

        .title {
            font-size: 96px;
        }
        .subclass{
            font-size: 46px;
        }
        a{
            text-decoration: none;
            color: inherit;
        }
        #testCloudsms{
        }
        #testCloudsms input.form-control{
            height: 40px;
            border: 1px solid #e1f1f1;
            font-size: 16px;
            text-align: center;
            color: #999999;
            width: 50%;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="content">
        <div class="title">Cloudsms</div>
        <div class="subclass">Made for Laravel by <a href="http://www.leelam.com?package=ref" target="">Leelam</a></div>
        <a href="">Learn more about configuring thing in proper way</a>
    </div>
    <div id="testCloudsms">
        <form action="/sendCloudsms" method="post">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
        <input name="mobiles" type="number" maxlength="10" class="form-control" placeholder="Write your mobile number to test and hit enter" >
        </form>
    </div>
</div>
</body>
</html>
