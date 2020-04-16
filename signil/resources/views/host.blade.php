@extends('layouts.app')
    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->

    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-size: 25px;
            font-weight: 100;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .takeAnswer {
            background-color: #4CAF50; /* Green */
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
        }

        .clearField {
            background-color: #ccbc31; /* yellow */
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
        }

        .showQuestion {
            background-color: #495057; /* yellow */
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
        }

        .m-b-md {
            margin-bottom: 30px;
        }

        .answers {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<div class="flex-center position-ref">
    <div class="content">
        <div class="title m-b-md">
            SiGnil
        </div>
    </div>
</div>
</body>
<h3>Choose the local(s) zip file(s)</h3>
<p class="note">Note : your browser will process the zip file, don't choose a file too big !</p>
<input type="file" id="file" name="file" multiple /><br />

<div id="result_block" class="hidden">
    <h3>Content :</h3>
    <div id="result"></div>
</div>
</html>
