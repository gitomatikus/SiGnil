@extends('layouts.app')
    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <script src="{{ asset('js/player.js') }}" defer></script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <script>
        window.onload = function () {
            var context = new AudioContext();
        }
    </script>


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

        .progress-circle {
            height: 300px;
        }

        .progress-circle > svg {
            height: 100%;
            display: block;
        }

        .bg {
            background-color: #cccccc !important;
        }

        .answerButton {
            width: 80%;
            margin-left: 10%;
            min-width: 200px
        }
        .answerInput {
            width: 100%;
            max-width: 400px;
        }

    </style>
</head>
<body>
<div class="flex-center position-ref">
    <div class="content">
        <div class="title m-b-md">
            SiGnil
        </div>
        <div class="name" style="display: none">
            <input id="username" class="form-control form-control-lg" type="text" placeholder="Username" id="username">
            <input type="button" class="takeAnswer" value="Submit" onclick="SubmitName()">

            <div class="alert-danger" style="display:none" id="name-error"><label name="username">User Name is
                    required</label></div>
        </div>
    </div>
</div>

<div class="container pack-progress">
    <div>
        <div class="flex-center position-ref"><span id="pack-status">Waiting For Pack </span></div>
        <div class="progress-line" id="progress"></div>
    </div>
</div>

<div class="flex-center position-ref gamefield" style="display:none">
    <div style="min-width:80%">
        <h3 style="position: relative; text-align: center">
            <span id="roundName">Round name</span>
        </h3>
        <table id="gamefield" class="table-hover-cells"></table>
    </div>
</div>
<div class="flex-center position-ref">
    <div id="question" style="display:none"></div>
</div>
<div class="flex-center position-ref">
    <div id="answers" style="display:none;"></div>
</div>
<hr>
<div class="flex-center position-ref playersAnswers" style="display:none">
    <div>
        <div class="answerButton">
            <input type="button" class="takeAnswer answerInput" value="Submit" onclick="SiGnil.askForAnswer()">
        </div>
        <div id="playersAnswers" style="">
        </div>
    </div>
</div>

</body>
</html>
