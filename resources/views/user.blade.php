
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
