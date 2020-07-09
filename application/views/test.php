
<?php
if ($action == '1') {
    ?>
    <a href="tel:+917012438984">
        Call number!
    </a>

    <a href="sms:+917012438984?body=Hello%20there!">
        Compose SMS!
    </a>
    <video style="width:400px" src="" id="camera-stream" controls>
    </video>
    <button type="button" id="start_rec">Record</button>
    <button type="button" id="pause_rec">Pause</button>
    <button type="button" id="resume_rec">Resume</button>
    <button type="button" id="stop_rec">Stop</button>
    <div id="status_box" style="font-size:2em;">Pending</div>

    <input type="file" accept="image/*" capture="camera">

    <!-- Newly proposed specification -->
    <input type="file" accept="image/*" capture>
    <script>

        window.addEventListener('devicelight', function (event) {
            // Get the ambient light level in lux.
            var lightLevel = event.value;
            console.log(lightLevel)
        });
        console.log("Ram=" + navigator.deviceMemory + "GB");


        //// Get the current power level.
        //    navigator.battery.level;
        //
        //// Get the charging status (boolean).
        //    navigator.battery.charging;
        //
        //// Find out how long until the battery is charged.
        //    navigator.battery.chargingTime;
        //
        //// Find out how long until the battery is empty.
        //    navigator.battery.dischargingTime;



        console.log(window.navigator.appVersion)

        // Vibrate for 1 second (1000 milliseconds).
        //navigator.vibrate(1000);
        //
        //// Vibrate in sequence.
        //navigator.vibrate([500, 250, 500]);

        // Check support
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(success);
        }

        function success(position) {
            console.log('Latitude: ' + position.coords.latitude);
            console.log('Longitude: ' + position.coords.longitude);
        }
        //or
        var watchID = navigator.geolocation.watchPosition(function (position) {
            console.log(position.coords.latitude, position.coords.longitude);
        });
        //navigator.geolocation.clearWatch(watchID);
    </script>
    <script>
        // Request the camera.
        navigator.getUserMedia(
                // Constraints
                        {
                            video: true
                        },
                        // Success Callback
                                function (localMediaStream) {


                                    var mediaRecorder = new MediaRecorder(localMediaStream);


                                    document.getElementById("start_rec").addEventListener("click", function () {
                                        mediaRecorder.start();
                                        document.getElementById("status_box").innerHTML = mediaRecorder.state;
                                    });
                                    document.getElementById("pause_rec").addEventListener("click", function () {
                                        mediaRecorder.pause();
                                        document.getElementById("status_box").innerHTML = mediaRecorder.state;
                                    });
                                    document.getElementById("resume_rec").addEventListener("click", function () {
                                        mediaRecorder.resume();
                                        document.getElementById("status_box").innerHTML = mediaRecorder.state;
                                    });
                                    document.getElementById("stop_rec").addEventListener("click", function () {
                                        mediaRecorder.stop();
                                        document.getElementById("status_box").innerHTML = mediaRecorder.state;
                                    });
                                    document.getElementById("stop_rec").addEventListener("click", function () {
                                        let blob = mediaRecorder.requestData();
                                        document.getElementById("status_box").innerHTML = 'saved';
                                    });





                                    console.log(mediaRecorder.state)
                                    // Get a reference to the video element on the page.
                                    var vid = document.getElementById('camera-stream');
                                    //                                console.log(localMediaStream)
                                    //                                // Create an object URL for the video stream and use this 
                                    //                                // to set the video source.
                                    //                                //vid.src = window.URL.createObjectURL(localMediaStream);
                                    vid.srcObject = localMediaStream;
                                    vid.play();
                                },
                                // Error Callback
                                        function (err) {
                                            // Log the error to the console.
                                            console.log('The following error occurred when trying to use getUserMedia: ' + err);
                                        }
                                );
    </script>

    <script>

                                //                            if (navigator.mediaDevices) {
                                //                                console.log('getUserMedia supported.');
                                //
                                //                                var constraints = {audio: true};
                                //                                var chunks = [];
                                //
                                //                                navigator.mediaDevices.getUserMedia(constraints)
                                //                                        .then(function (stream) {
                                //
                                //                                            var mediaRecorder = new MediaRecorder(stream);
                                //
                                //                                            visualize(stream);
                                //
                                //                                            record.onclick = function () {
                                //                                                mediaRecorder.start();
                                //                                                console.log(mediaRecorder.state);
                                //                                                console.log("recorder started");
                                //                                                record.style.background = "red";
                                //                                                record.style.color = "black";
                                //                                            }
                                //
                                //                                            stop.onclick = function () {
                                //                                                mediaRecorder.stop();
                                //                                                console.log(mediaRecorder.state);
                                //                                                console.log("recorder stopped");
                                //                                                record.style.background = "";
                                //                                                record.style.color = "";
                                //                                            }
                                //
                                //                                            mediaRecorder.onstop = function (e) {
                                //                                                console.log("data available after MediaRecorder.stop() called.");
                                //
                                //                                                var clipName = prompt('Enter a name for your sound clip');
                                //
                                //                                                var clipContainer = document.createElement('article');
                                //                                                var clipLabel = document.createElement('p');
                                //                                                var audio = document.createElement('audio');
                                //                                                var deleteButton = document.createElement('button');
                                //
                                //                                                clipContainer.classList.add('clip');
                                //                                                audio.setAttribute('controls', '');
                                //                                                deleteButton.innerHTML = "Delete";
                                //                                                clipLabel.innerHTML = clipName;
                                //
                                //                                                clipContainer.appendChild(audio);
                                //                                                clipContainer.appendChild(clipLabel);
                                //                                                clipContainer.appendChild(deleteButton);
                                //                                                soundClips.appendChild(clipContainer);
                                //
                                //                                                audio.controls = true;
                                //                                                var blob = new Blob(chunks, {'type': 'audio/ogg; codecs=opus'});
                                //                                                chunks = [];
                                //                                                var audioURL = URL.createObjectURL(blob);
                                //                                                audio.src = audioURL;
                                //                                                console.log("recorder stopped");
                                //
                                //                                                deleteButton.onclick = function (e) {
                                //                                                    evtTgt = e.target;
                                //                                                    evtTgt.parentNode.parentNode.removeChild(evtTgt.parentNode);
                                //                                                }
                                //                                            }
                                //
                                //                                            mediaRecorder.ondataavailable = function (e) {
                                //                                                chunks.push(e.data);
                                //                                            }
                                //                                        })
                                //                                        .catch(function (err) {
                                //                                            console.log('The following error occurred: ' + err);
                                //                                        })
                                //                            }

                                //https://www.w3.org/TR/messaging/
                                //https://www.w3.org/TR/telephony/
    </script>
    <?php
} else
if ($action == 2) {
    ?>

    <!doctype html>
    <head>
        <title>MediaRecorder examples - Record video and audio</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" href="styles.css" type="text/css">

    </head>
    <body>
        <header>
            <h1><a href="index.html">MediaRecorder examples</a></h1>
            <p>Record video and audio.</p>
        </header>
        <main>
            <p><button id="record" disabled>Record</button> <button id="stop" disabled>Stop</button></p>
            <div class="row">
                <figure>
                    <video id="live" width="320"></video><br />
                    <caption>live preview</caption>
                </figure>
                <figure>
                    <video id="recording" controls width="320"></video><br />
                    <caption>recorded clip</caption>
                </figure>
            </div>
        </main>
    </body>

    <script>

                                // This example uses MediaRecorder to record from an audio and video stream, and uses the
                                // resulting blob as a source for a video element.
                                //
                                // The relevant functions in use are:
                                //
                                // navigator.mediaDevices.getUserMedia -> to get the video & audio stream from user
                                // MediaRecorder (constructor) -> create MediaRecorder instance for a stream
                                // MediaRecorder.ondataavailable -> event to listen to when the recording is ready
                                // MediaRecorder.start -> start recording
                                // MediaRecorder.stop -> stop recording (this will generate a blob of data)
                                // URL.createObjectURL -> to create a URL from a blob, which we use as video src

                                var recordButton, stopButton, recorder, liveStream;

                                window.onload = function () {
                                    recordButton = document.getElementById('record');
                                    stopButton = document.getElementById('stop');

                                    // get video & audio stream from user
                                    navigator.mediaDevices.getUserMedia({
                                        audio: true,
                                        video: true
                                    })
                                            .then(function (stream) {
                                                liveStream = stream;

                                                var liveVideo = document.getElementById('live');
                                                //                                                liveVideo.src = URL.createObjectURL(stream);
                                                //                                                liveVideo.play();
                                                liveVideo.srcObject = stream;
                                                liveVideo.play();

                                                recordButton.disabled = false;
                                                recordButton.addEventListener('click', startRecording);
                                                stopButton.addEventListener('click', stopRecording);

                                            });
                                };

                                function startRecording() {
                                    recorder = new MediaRecorder(liveStream);

                                    recorder.addEventListener('dataavailable', onRecordingReady);

                                    recordButton.disabled = true;
                                    stopButton.disabled = false;

                                    recorder.start();
                                }

                                function stopRecording() {
                                    recordButton.disabled = false;
                                    stopButton.disabled = true;

                                    // Stopping the recorder will eventually trigger the 'dataavailable' event and we can complete the recording process
                                    recorder.stop();
                                }

                                function onRecordingReady(e) {
                                    var video = document.getElementById('recording');
                                    // e.data contains a blob representing the recording
                                    video.src = URL.createObjectURL(e.data);
                                    video.play();
                                }
                                //https://mozdevs.github.io/MediaRecorder-examples/
                                //https://girliemac.com/presentation-slides/html5-mobile-approach/deviceAPIs.html#22
    </script>
    <?php
}
?>