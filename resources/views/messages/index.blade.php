<div class="message-wrapper">
    <ul class="messages">
        @foreach($messages as $message)
        <li class="message clearfix">
            {{--if message from id is equal to auth id then it is sent by logged in user --}}
            <div class="{{ ($message->from == Auth::user()->device_token) ? 'sent' : 'received' }}">
                <p>{{ $message->message }}</p>

                @if($message->type == 'image')
                <a href="{{ asset('storage/chat/image') . '/' . $message->fileName }}" download>
                    <img src="{{ asset('storage/chat/image') . '/' . $message->fileName }}" alt="" width="250px" height="100px">
                </a>

                @elseif($message->type == 'video')

                <video width="320" height="240" controls>
                    <source src="{{ asset('storage/chat/video') . '/' . $message->fileName }}" type="video/mp4">

                </video>

                @elseif($message->type == 'audio')

                    <audio  controls=""  src="{{ asset('storage/chat/audio') . '/' . $message->fileName }}"></audio>


                @endif

                <p class="date">{{ date('d M y, h:i a', strtotime($message->created_at)) }}</p>
            </div>
        </li>
        @endforeach


    </ul>
</div>


<script>
    $("#image").change(function() {
        $('#inputmessage').focus();
    });
</script>
<script>
    $(document).on('keyup', '.input-text #inputmessage', function(e) {
        var message = $(this).val();

        var image = document.getElementById('image').files;

       var recordAudio = $('#auduoFileRecording').attr('value');


        // alert(recordAudio);

        // check if enter key is pressed and message is not null also receiver is selected

        if((e.keyCode == 13 && recordAudio!=""   && message == '' && receiver_id != ''))
        {
            $('#auduoFileRecording').attr('value',"");
            console.log("World!");
            recordAudiobool=0;
            // recordAudio = $('#auduoFileRecording').attr('value');
            var newr=recordAudio;
            recordAudio="";

            //alert($(this).val());

            document.getElementById('auduoFileRecording').value ="";
            // alert(recordAudio);
            fetch(newr).then(response => response.blob())
                .then(blob => {
                    const data = new FormData();
                    data.append("file", blob, "firecord.wav"); // where `.ext` matches file `MIME` type
                    data.append('message', message);
                    data.append('receiver_id', receiver_id);
                    // console.log(data);
                    axios.post('message', data).then(function(response) {
                        scrollToBottomFunc();
                    });

                    recordAudio="";
                })
                .then(response => response.ok)
                .then(res => console.log(res))
                .catch(err => console.log(err));

                recordAudio="";
            $('#audiovioctest').attr('src','');
            $('#audiovioctest').attr('style','display:none;');
                    document.getElementById('auduoFileRecording').value ="";
                    document.getElementById('auduoFileRecording').style.display ="none";
            recordAudiobool=1;
            $('#file-upload').css('display','inline');


        }


        else if ((e.keyCode == 13 && message != '' && receiver_id != '') || (e.keyCode == 13 && image.length != 0 && receiver_id != '')) {
            $(this).val(''); // while pressed enter text box will be empty
            $('#auduoFileRecording').attr('value','');
            $('#audiovioctest').attr('src','');
            $('#audiovioctest').attr('style','display:none;');

            var data = new FormData();

            data.append('file', image[0]);

            data.append('message', message);

            data.append('receiver_id', receiver_id);

            document.getElementById('image').value = ''; // while pressed enter text box will be empty
            document.getElementById("noFile").innerHTML = "No file chosen...";
            //var datastr = "receiver_id=" + receiver_id + "&message=" + message;

            axios.post('message', data).then(function(response) {
                scrollToBottomFunc();
            });



        }
    });
</script>

