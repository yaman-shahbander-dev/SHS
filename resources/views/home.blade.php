@extends('layoutschat.app')

<style>
    .search-bar {
        height: 44px;
        width: 425px;
        border-radius: 40px;
        display: flex;
        align-items: center;
        padding: 0 0 0 20px;
        position: relative;
        background: #fff;
        margin-bottom: 10px
    }

    .input {
        border: none;
        height: 25px;
        width: 150px;
        color: #1b1b1b;
        font-size: 15px;
        outline: none;
    }

    .input:not(:placeholder-shown)+.label {
        font-size: 9px;
        top: 3px;
        color: #00c853;
    }

    .input:focus~.label {
        font-size: 9px;
        top: 3px;
        color: #4279a3;
        transition: all 0.5s ease;
    }

    .label {
        color: #aaaaaa;
        position: absolute;
        top: 13px;
        pointer-events: none;
        transition: all 0.5s ease;
    }

    .highlight {
        width: 0px;
        height: 1px;
        background: #4279a3;
        position: absolute;
        bottom: 8px;
        transition: all 1s ease;
    }

    .input:focus~.highlight {
        width: 250px;
        transition: all 1s ease;
    }

    .input {
        margin: 0 25px 0 0 !important
    }



    .file-upload {
        display: block;
        text-align: center;
        font-family: Helvetica, Arial, sans-serif;
        font-size: 12px;
    }

    .file-upload .file-select {
        display: block;
        border: 2px solid #dce4ec;
        color: #34495e;
        cursor: pointer;
        height: 40px;
        line-height: 40px;
        text-align: left;
        background: #FFFFFF;
        overflow: hidden;
        position: relative;
    }

    .file-upload .file-select .file-select-button {
        background: #dce4ec;
        padding: 0 10px;
        display: inline-block;
        height: 40px;
        line-height: 40px;
    }

    .file-upload .file-select .file-select-name {
        line-height: 40px;
        display: inline-block;
        padding: 0 10px;
    }

    .file-upload .file-select:hover {
        border-color: #34495e;
        transition: all .2s ease-in-out;
        -moz-transition: all .2s ease-in-out;
        -webkit-transition: all .2s ease-in-out;
        -o-transition: all .2s ease-in-out;
    }

    .file-upload .file-select:hover .file-select-button {
        background: #34495e;
        color: #FFFFFF;
        transition: all .2s ease-in-out;
        -moz-transition: all .2s ease-in-out;
        -webkit-transition: all .2s ease-in-out;
        -o-transition: all .2s ease-in-out;
    }

    .file-upload.active .file-select {
        border-color: #3fa46a;
        transition: all .2s ease-in-out;
        -moz-transition: all .2s ease-in-out;
        -webkit-transition: all .2s ease-in-out;
        -o-transition: all .2s ease-in-out;
    }

    .file-upload.active .file-select .file-select-button {
        background: #3fa46a;
        color: #FFFFFF;
        transition: all .2s ease-in-out;
        -moz-transition: all .2s ease-in-out;
        -webkit-transition: all .2s ease-in-out;
        -o-transition: all .2s ease-in-out;
    }

    .file-upload .file-select input[type=file] {
        z-index: 100;
        cursor: pointer;
        position: absolute;
        height: 100%;
        width: 100%;
        top: 0;
        left: 0;
        opacity: 0;
        filter: alpha(opacity=0);
    }

    .file-upload .file-select.file-select-disabled {
        opacity: 0.65;
    }

    .file-upload .file-select.file-select-disabled:hover {
        cursor: default;
        display: block;
        border: 2px solid #dce4ec;
        color: #34495e;
        cursor: pointer;
        height: 40px;
        line-height: 40px;
        margin-top: 5px;
        text-align: left;
        background: #FFFFFF;
        overflow: hidden;
        position: relative;
    }

    .file-upload .file-select.file-select-disabled:hover .file-select-button {
        background: #dce4ec;
        color: #666666;
        padding: 0 10px;
        display: inline-block;
        height: 40px;
        line-height: 40px;
    }

    .file-upload .file-select.file-select-disabled:hover .file-select-name {
        line-height: 40px;
        display: inline-block;
        padding: 0 10px;
    }
</style>

@section('content')



<div class="container-fluid">
    <div class="row">

        <div class="col-md-4">

            <div class="search-bar">
                <input type="text" id="search" class="input" placeholder="Search..." onkeyup="searchFunction()">
                <span class="highlight"></span>
            </div>

            <div class="user-wrapper" id="user-wrapper">
                <ul class="users" id="test">
                    @foreach($users as $user)
                    <li class="user" id="{{ $user->device_token }}">
                        {{--will show unread count notification--}}
                        @if($user->unread)
                        <span class="pending">{{ $user->unread }}</span>
                        @endif

                        <div class="media">
                            <div class="media-left">
                                <img src="{{ asset('storage/Avatar/' . $user->avatar) }}" alt="" class="media-object">
                            </div>

                            <div class="media-body">
                                <p class="name">{{ $user->name }}</p>
                                <p class="email">{{ $user->email }}</p>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>

        </div>

        <div class="col-md-8">
            <div id="ggg"></div>
            <div class=" inputselement row" style="display: none;">
                <div class="input-text col-md-6" id="textInputId" style="display: none">
                    <input type="text" name="message" id="inputmessage" class="submit" autofocus>




                    <!-- <input type="submit" id="submit" value="Send"> -->
                </div>

                <div class="col-md-4">
                    <!-- <input type="file" name="image" id="image" class="image uploader newimg btn btn-primary" style="width: 100%;" > -->

                    <div class="file-upload" id="file-upload">
                        <div class="file-select" style="width: 211px;margin-top: 18px;">
                            <div class="file-select-button" id="fileName" style="position: absolute;">Choose File</div>
                            <div class="file-select-name" id="noFile" style="text-indent: 77px;">No file chosen...</div>
                            <input type="file" name="image" id="image" accept=".png,.gif,.jpeg,.jpg,.mp4,.wma,.webm,.mov,.wmv,.mpeg,.mpg" onchange="pathchange()">
                        </div>
                    </div>

                    <audio id="audiovioctest" controls="" style="display:none"></audio>


                    <input type="file" id="auduoFileRecording" value="" hidden>
                </div>
                <div class="col-md-2">
                    <div data-role="controls" id="recordAudio" style="display: none;">
                        <button class="voiceRecord" style="margin-top: 28px;">Record</button>

                    </div>
                    <div data-role="recordings">
                        <div class="row">
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection


<script>
    function searchFunction() {
        var elemVal = document.getElementById("search").value;
        if (elemVal.trim() == "") {
            elemVal = "emptyValue"
        }
        $.ajax({
            url: "{{ url('api/searchInChatDashboard') }}",
            type: "POST",
            data: {
                searchValue: elemVal
            },
            success: function(Users) {
                $div = $("#user-wrapper");
                $ul = $("#user-wrapper").find('ul');

                $ul.empty();

                Users.forEach(function(User) {
                    var li = document.createElement('li');
                    li.className = "user";
                    li.id = User["device_token"];

                    var divMedia = document.createElement('div');
                    divMedia.className = "media";

                    var mediaLeft = document.createElement('div');
                    mediaLeft.className = "media-left";

                    var img = document.createElement('img');
                    img.className = "media-object";
                    img.src = User["avatar"];

                    var mediaBody = document.createElement('div');
                    mediaBody.className = "media-body";

                    var pName = document.createElement('p');
                    pName.className = "name";
                    pName.innerHTML = User['name'];


                    var pEmail = document.createElement('p');
                    pEmail.className = "email";
                    pEmail.innerHTML = User['email'];

                    mediaBody.appendChild(pName);

                    mediaBody.appendChild(pEmail);

                    mediaLeft.appendChild(img);

                    divMedia.appendChild(mediaLeft);

                    divMedia.appendChild(mediaBody);

                    li.appendChild(divMedia);

                    // $ul.insertBefore(li, $ul.firstChild);

                    $ul.prepend(li);

                }); // foreach end
                $div.append($ul);
            } // success fundtion end
        });
    }


    document.addEventListener("click", function(event) {
        var targetElement = event.target || event.srcElement;

        if (targetElement.className == "name" || targetElement.className == "email") {
            var parentID = targetElement.parentElement.parentElement.parentElement.id;
        } else if (targetElement.className == "media-object") {
            var parentID = targetElement.parentElement.parentElement.parentElement.id;
        } else if (targetElement.className == "media-body") {
            var parentID = targetElement.parentElement.parentElement.id;
        } else if (targetElement.className == "user") {
            var parentID = targetElement.id; //Not parent but the same element  
        }

        if (parentID != null) {
            receiver_id = parentID;

            $.ajax({
                type: "get",
                url: "message/" + receiver_id, // need to create this route
                data: "",
                cache: false,
                success: function(messages) {
                    $('#messages').html(messages);
                    scrollToBottomFunc();
                }
            });
        }
    });
</script>

<script>
    function pathchange() {
        var filename = document.getElementById("image");
        var fileupload = document.getElementById("file-upload");
        var noFile = document.getElementById("noFile");
        if (filename.value == "/^\s*$/") {
            fileupload.classList.remove('active');
            noFile.innerHTML = "No file chosen...";
        } else {
            fileupload.classList.add('active');
            noFile.innerHTML =  filename.value.replace("C:\\fakepath\\", "");
        }
    }
</script>
