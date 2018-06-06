<!DOCTYPE html>
<html>
  <head>
    <title>QCodeDecoder - Camera</title>
  </head>
  <body>

  <video autoplay></video>
  
  <div id="result" style="border:solid 1px #333; padding:10px; width:100%;"></div>

  <script src="{{ asset('/plugins/jquery-1.8.3.min.js') }}" type="text/javascript"></script> 
  <script src="{{ asset('/plugins/qr/qcode-decoder.min.js') }}"></script>
  <script type="text/javascript">

  $(function () {
    
    var qr = new QCodeDecoder();

    if (!(qr.isCanvasSupported() && qr.hasGetUserMedia())) {
      alert('Your browser doesn\'t match the required specs.');
      throw new Error('Canvas and getUserMedia are required');
    }

    var video = document.querySelector('video');
    resultHandler = function(err, result) {
      if (err)
        return console.log(err.message);


        $.getJSON('{{ url("/qr-send") }}', {data : result}, function(json){
          console.log(json);
          $('#result').html(json.result);
          
        });

        qr.stop();
      

    }

    // prepare a canvas element that will receive
    // the image to decode, sets the callback for
    // the result and then prepares the
    // videoElement to send its source to the
    // decoder.

    qr.decodeFromCamera(video, resultHandler);

    
  });
  </script>
</body>
</html>
