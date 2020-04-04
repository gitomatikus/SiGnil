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
        <div class="answers">
            <input class="form-control form-control-lg" type="text" placeholder="Username" id="username">
            <div class="alert-danger" style="display:none" id="name-error"><label name="username">User Name is
                    required</label></div>
        </div>
    </div>
</div>

<div class="container">
    <div>
        <div class="flex-center position-ref">
            <div class="content">
                <div class="title m-b-md">
                    <input type="button" class="clearField" value="Clear Field" onclick=SiGnil.askForClear()>
                </div>
            </div>
            <div class="content">
                <div class="title m-b-md">
                    <input type="button" class="takeAnswer" value="I know Answer" onclick=SiGnil.askForAnswer()>
                </div>
            </div>
            <div class="content">
                <div class="title m-b-md">
                    <input type="button" class="showQuestion" value="ShowQuestion" onclick=SiGnil.askForQuestion(1)>
                </div>
            </div>
        </div>
            <div class="flex-center position-ref"> TIMER</div>
        <div class="row">
            <div class="col-7">
                <span style="display:none" id="question">Какой-то вопрос</span>
            </div>
            <div class="col-5 m-b-md">
                <div class="content-answers"></div>
            </div>
        </div>
    </div>
</div>
</body>
</html>

