<!DOCTYPE html>
<html>
  <head>
    <title>Welcome Email</title>
  </head>
  <body>
    
  

    <form role="form" method="post" action="{{ route('sendmail') }}" enctype="multipart/form-data">
            @csrf
            <div class="row">
            <div class="col-sm-9 form-group">
            <label for="email">Email to: </label>
            <input type="email" class="form-control" name="email" style="border: 1px solid black;">
            </div>
        <div class="row">
            <div class="col-sm-9 form-group">
            <label for="subject">Subject: </label>
            <input type="text" class="form-control" name="subject" style="border: 1px solid black;">
            </div>
        </div>
        <div class="row">
            <div class="col-sm-9 form-group">
            <label for="name">Message: </label>
            <textarea class="form-control" type="textarea" name="message" style="border: 1px solid black;"></textarea>
            </div>
        </div>
            <div class="row">
            <div class="col-sm-9 form-group">
                    <button type="submit" name="sendmail" class="btn btn-primary">Send</button>
            </div>
            </div>
        </form>

  </body>
</html>