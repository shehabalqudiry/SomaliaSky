@extends('layouts.app')
@section('styles')
    <style>
        aside {
            display: inline-block;
            font-size: 15px;
            vertical-align: top;
        }

        main {
            display: inline-block;
            font-size: 15px;
            vertical-align: top;
        }

        aside header {
            padding: 30px 20px;

        }

        aside ul {
            padding-left: 0;
            padding-right: 0;
            margin: 0;
            list-style-type: none;
            overflow-y: scroll;
            /* height: 690px; */
        }

        aside li {
            padding: 10px 0;

        }


        aside li img {
            border-radius: 50%;
            margin-left: 20px;
            margin-right: 8px;
        }

        aside li div {
            display: inline-block;
            vertical-align: top;
            margin-top: 12px;
        }

        aside li h2 {
            /* font-size: 14px; */
            color: rgb(59, 59, 59);
            font-weight: normal;
            margin-bottom: 5px;
        }

        aside li h3 {
            /* font-size: 12px; */
            color: #7e818a;
            font-weight: normal;
        }

        .status {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 7px;
        }

        .green {
            background-color: #58b666;
        }

        .orange {
            background-color: #ff725d;
        }

        .blue {
            background-color: #6fbced;
            margin-right: 0;
            margin-left: 7px;
        }

        main header {
            height: 110px;
            padding: 30px 20px 30px 40px;
        }

        main header>* {
            display: inline-block;
            vertical-align: top;
        }

        main header img:first-child {
            border-radius: 50%;
        }

        main header img:last-child {
            width: 24px;
            margin-top: 8px;
        }

        main header div {
            margin-left: 10px;
            margin-right: 145px;
        }

        #chat {
            height: 320px;
            padding-left: 0;
            margin: 0;
            list-style-type: none;
            overflow-y: scroll;

        }

        #userMenue {
            height: 320px;
            padding-left: 0;
            margin: 0;
            list-style-type: none;
            overflow-y: scroll;

        }

        #chat li {
            padding: 0;
        }

        #chat h2,
        #chat h3 {
            display: inline-block;
            /* font-size: 13px; */
            font-weight: normal;
        }

        #chat h3 {
            color: #bbb;
        }

        #chat .entete {
            margin-bottom: 5px;
        }

        #chat .message {
            padding: 20px;
            color: rgb(24, 24, 24);
            line-height: 25px;
            max-width: 90%;
            display: inline-block;
            text-align: left;
            border-radius: 5px;
        }

        #chat .me {
            text-align: right;
        }

        #chat .me a {
            color:rgb(240, 240, 240) !important;
        }

        #chat .you {
            text-align: left;
            color:#000 !important;
        }

        #chat .you a{
            color:#000 !important;
        }
    </style>
@endsection
@section('content')
<div class="container my-5">
    <div class="row">
        @if (request()->user_id)
            <livewire:chat :user_id="request()->user_id" />
        @else
            <livewire:chat />
        @endif
    </div>
</div>
@endsection

@section('scripts')
    <script>
        document.getElementById('chat').scroll(0, 100000000000000000000000000000);
        window.livewire.on('boot', () => {
            document.getElementById('chat').scroll(0, 100000000000000000000000000000);
        });
    </script>
@endsection
