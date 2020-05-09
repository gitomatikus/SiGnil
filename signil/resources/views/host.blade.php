@extends('layouts.app')
    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">

    <title>SiGnil</title>

    <script>
        window.onload = function () {
            var context = new AudioContext();
        }
    </script>
    <script src="{{ asset('js/host.js') }}" defer></script>

    <!-- Fonts -->

    <!-- Styles -->
    <style>
        html, body {
            background-color: #1E5BAA!important;
            color: #fff;
            font-family: 'Nunito', sans-serif;
            font-size: 25px;
            font-weight: 500;
            height: 100vh;
            margin: 0;
        }
        body {
            background-color: #1E5BAA!important;
            color: #fff!important;
            font-family: 'Nunito', sans-serif;
            font-size: 25px;
            font-weight: 500;
            height: 100vh;
            margin: 0;
            overflow-x: hidden;
        }
        table {
            color: #fff!important;
        }
        span {
            color: #fff!important;
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

        .showQuestion {
            background-color: #4CAF50; /* Green */
            border-radius: 10px;
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
        }

        .clearField {
            background-color: #7b85b7; /* yellow. nope */
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            border-radius: 10px;
        }

        .showAnswer {
            background-color: #848484; /* yellow. not */
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            border-radius: 10px;
        }

        .showQuestion {
            background-color: rgb(208, 131, 34); /* Green. Not actually */
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            border-radius: 10px
        }

        .m-b-md {
            margin-bottom: 30px;
        }

        .answers {
            margin-bottom: 10px;
        }

        .bg {
            background-color: #6796ad !important;
            cursor: pointer
        }
        .playerPhoto {
            max-width: 300px!important;
            margin-left: auto;
            margin-right: auto;
        }
        .photo {
            max-height: 150px;
            min-width: 130px;
        }
        .playersNames {
            overflow-wrap: break-word;
        }
        .host-control {
            display: none;
        }


    </style>
</head>
<body class='host-mode'>
<div class="flex-center position-ref">
    <div class="content">
        <div class="title">
            SiGnil
        </div>
    </div>
</div>
</body>
<input class="pack-selector" type="file" id="file" name="file"/>
<input type="button" class="clearField host-control" value="Finish Round" onclick=Questions.hideQuestions(true)>
<input type="button" class="showQuestion host-control" value="Show Question" onclick=Questions.showToPlayers()>
<input type="button" class="showAnswer host-control" value="Show Answer" onclick=Questions.showAnswerToPlayers()>
<div class="flex-center position-ref gamefield" style="display:none">
    <div style="min-width:80%">
        <h3 style="position: relative; text-align: center">
            <a href=# id="previousRound" onclick="ChangeRound('previous')"><</a>
            <span id="roundName">Round name</span>
            <a href=# id="nextRound" onclick="ChangeRound('next')">></a>
        </h3>
        <table id="gamefield" class="table-hover-cells"></table>
    </div>
</div>
<div class="flex-center position-ref">
    <div id="timer" style="display: none; font-size:40px;">
        <span id="countdown">10</span>
    </div>
</div>

<div class="flex-center position-ref">
    <div id="question" style="display:none"></div>
</div>
<hr>
<div class="flex-center position-ref">
    <div id="playersAnswers" style="">
        <div>Players:</div>
    </div>
</div>
<hr>
Answers:
<div class="flex-center position-ref">
    <div id="answers" style="display:none; max-width:90%; margin: auto;"></div>
</div>
<div class="players">
    <div class="row">
        <div class="col-md-1" style=""></div>
        <div class="col-md-10" style="">
            <div class="row playersList" style="margin: auto"></div>
        </div>
        <div class="col-md-1" style=""></div>
    </div>
</div>
</html>
