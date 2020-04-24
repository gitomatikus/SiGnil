@extends('layouts.app')
    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">

    <title>Laravel</title>

    <script>
        window.onload = function () {
            var context = new AudioContext();
        }
    </script>
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

        .showQuestion {
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

        .showAnswer {
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

        .bg {
            background-color: #cccccc !important;
            cursor: pointer
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
<input type="file" id="file" name="file" multiple/><br/>
<br>
<input type="button" class="clearField" value="Finish Round" onclick=Questions.hideQuestions(true)>
<input type="button" class="showQuestion" value="Show Question" onclick=Questions.showToPlayers()>
<input type="button" class="showAnswer" value="Show Answer" onclick=Questions.showAnswerToPlayers()>

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

</html>
