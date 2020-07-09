<style>
    body {
        margin: 0;
    }
    #myCanvas {
        width: 100%;  /* let our container decide our size */
        height: 100%;
        display: block;
    }
    .canvas_wrapper {
        position: relative;  /* makes this the origin of its children */
        width: 400px;
        height: 350px;
        overflow: hidden;
    }
    #labels {
        position: absolute;  /* let us position ourself inside the container */
        left: 0;             /* make our position the top left of the container */
        top: 0;
        color: white;
    }
    #labels>div {
        position: absolute;  /* let us position them inside the container */
        left: 0;             /* make their default position the top left of the container */
        top: 0;
        cursor: pointer;     /* change the cursor to a hand when over us */
        font-size: large;
        user-select: none;   /* don't let the text get selected */
        text-shadow:         /* create a black outline */
            -1px -1px 0 #000,
            0   -1px 0 #000,
            1px -1px 0 #000,
            1px  0   0 #000,
            1px  1px 0 #000,
            0    1px 0 #000,
            -1px  1px 0 #000,
            -1px  0   0 #000;
    }
    #labels>div:hover {
        color: red;
    }

</style>
<div class="col-12">
    Welcome
    <div class="row">
        <div class="col-2"></div>
        <div class="col-8 canvas_wrapper">
            <canvas id="myCanvas" >
            </canvas>
            <div id="labels"></div>
        </div>
        <div class="col-2"></div>
    </div>
</div>
<script>
    var path_url = "<?php echo base_url(); ?>";
</script>
<script src="<?php echo base_url('third_party/assets/extra-libs/threejs/three.js'); ?>"></script>
<script src="<?php echo base_url('third_party/assets/extra-libs/threejs/GLTFLoader.js'); ?>"></script>
<script src="<?php echo base_url('third_party/assets/extra-libs/threejs/src/loaders/FontLoader.js'); ?>"></script>
<script src="<?php echo base_url('third_party/assets/extra-libs/threejs/OrbitControls.js'); ?>"></script>
<script src="<?php echo base_url('third_party/assets/extra-libs/threejs/threex.domevents.js'); ?>"></script>