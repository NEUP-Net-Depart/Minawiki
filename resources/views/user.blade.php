<!DOCTYPE html>
<html>
    <head>
        <title>Laravel</title>

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
                font-size: 20px;
            }
        </style>
    </head>
    <body>
        <div class="container">
                @foreach($message['commentId'] as $value)

                       content:{{$message['content'][$value]}}<br/>
                        @for($i=0;$i<count($message['replyId'][$value]);$i++)
                            replyId:{{$message['replyId'][$value][$i]}}reply:{{$message['reply'][$value][$i]}}<br/>
                        @endfor
                        starNum:{{$message['starNum'][$value]}}
                        @for($i=0;$i<count($message['starUser'][$value]);$i++)
                            starUser{{$message['starUser'][$value][$i]}}
                        @endfor
               <br><br> @endforeach
        </div>
                <p></p>
            </div>
        </div>
    </body>
</html>
