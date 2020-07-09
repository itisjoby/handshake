
//https://threejsfundamentals.org/threejs/lessons/threejs-align-html-elements-to-3d.html
function main() {
    const canvas = document.querySelector('#myCanvas');
    const renderer = new THREE.WebGLRenderer({canvas});

    const fov = 75;
    const aspect = 2;  // the canvas default
    const near = 1.1;
    const far = 20;
    const camera = new THREE.PerspectiveCamera(fov, aspect, near, far);
    camera.position.z = 7;

    const controls = new THREE.OrbitControls(camera, canvas);
    controls.target.set(0, 0, 0);
    controls.update();

    const scene = new THREE.Scene();

    {
        const color = 0xFFFFFF;
        const intensity = 1;
        const light = new THREE.DirectionalLight(color, intensity);
        light.position.set(-1, 2, 4);
        scene.add(light);
    }

    const boxWidth = 1;
    const boxHeight = 1;
    const boxDepth = 1;
    const geometry = new THREE.BoxGeometry(boxWidth, boxHeight, boxDepth);

    const labelContainerElem = document.querySelector('#labels');

    function makeInstance(geometry, color, x, name) {
        const material = new THREE.MeshPhongMaterial({color});

        const cube = new THREE.Mesh(geometry, material);
        scene.add(cube);

        cube.position.x = x;

        const elem = document.createElement('div');
        elem.textContent = name;
        labelContainerElem.appendChild(elem);

        return {cube, elem};
    }

    const cubes = [
        makeInstance(geometry, 0x44aa88, 0, 'Aqua Colored Box'),
        makeInstance(geometry, 0x8844aa, -2, 'Purple Colored Box'),
        makeInstance(geometry, 0xaa8844, 2, 'Gold Colored Box'),
    ];

    function resizeRendererToDisplaySize(renderer) {
        const canvas = renderer.domElement;
        const width = canvas.clientWidth;
        const height = canvas.clientHeight;
        const needResize = canvas.width !== width || canvas.height !== height;
        if (needResize) {
            renderer.setSize(width, height, false);
        }
        return needResize;
    }

    const tempV = new THREE.Vector3();
    const raycaster = new THREE.Raycaster();

    function render(time) {
        time *= 0.001;

        if (resizeRendererToDisplaySize(renderer)) {
            const canvas = renderer.domElement;
            camera.aspect = canvas.clientWidth / canvas.clientHeight;
            camera.updateProjectionMatrix();
        }

        cubes.forEach((cubeInfo, ndx) => {
            const {cube, elem} = cubeInfo;
            const speed = 1 + ndx * .1;
            const rot = time * speed;
            cube.rotation.x = rot;
            cube.rotation.y = rot;

            // get the position of the center of the cube
            cube.updateWorldMatrix(true, false);
            cube.getWorldPosition(tempV);

            // get the normalized screen coordinate of that position
            // x and y will be in the -1 to +1 range with x = -1 being
            // on the left and y = -1 being on the bottom
            tempV.project(camera);

            // ask the raycaster for all the objects that intersect
            // from the eye toward this object's position
            raycaster.setFromCamera(tempV, camera);
            const intersectedObjects = raycaster.intersectObjects(scene.children);
            // We're visible if the first intersection is this object.
            const show = intersectedObjects.length && cube === intersectedObjects[0].object;

            if (!show || Math.abs(tempV.z) > 1) {
                // hide the label
                elem.style.display = 'none';
            } else {
                // unhide the label
                elem.style.display = '';

                // convert the normalized position to CSS coordinates
                const x = (tempV.x * .5 + .5) * canvas.clientWidth;
                const y = (tempV.y * -.5 + .5) * canvas.clientHeight;

                // move the elem to that position
                elem.style.transform = `translate(-50%, -50%) translate(${x}px,${y}px)`;

                // set the zIndex for sorting
                elem.style.zIndex = (-tempV.z * .5 + .5) * 100000 | 0;
            }
        });

        renderer.render(scene, camera);

        requestAnimationFrame(render);
    }

    requestAnimationFrame(render);
}

main();








// Three.js - Canvas Textured Labels One Canvas
// from https://threejsfundamentals.org/threejs/threejs-canvas-textured-labels-one-canvas.html

import * as THREE from 'https://threejsfundamentals.org/threejs/resources/threejs/r108/build/three.module.js';
import {OrbitControls} from 'https://threejsfundamentals.org/threejs/resources/threejs/r108/examples/jsm/controls/OrbitControls.js';

function main() {
    const canvas = document.querySelector('#c');
    const renderer = new THREE.WebGLRenderer({canvas});

    const fov = 75;
    const aspect = 2;  // the canvas default
    const near = 0.1;
    const far = 50;
    const camera = new THREE.PerspectiveCamera(fov, aspect, near, far);
    camera.position.set(0, 2, 5);

    const controls = new OrbitControls(camera, canvas);
    controls.target.set(0, 2, 0);
    controls.update();

    const scene = new THREE.Scene();
    scene.background = new THREE.Color('white');

    function addLight(position) {
        const color = 0xFFFFFF;
        const intensity = 1;
        const light = new THREE.DirectionalLight(color, intensity);
        light.position.set(...position);
        scene.add(light);
        scene.add(light.target);
    }
    addLight([-3, 1, 1]);
    addLight([2, 1, .5]);

    const bodyRadiusTop = .4;
    const bodyRadiusBottom = .2;
    const bodyHeight = 2;
    const bodyRadialSegments = 6;
    const bodyGeometry = new THREE.CylinderBufferGeometry(
            bodyRadiusTop, bodyRadiusBottom, bodyHeight, bodyRadialSegments);

    const headRadius = bodyRadiusTop * 0.8;
    const headLonSegments = 12;
    const headLatSegments = 5;
    const headGeometry = new THREE.SphereBufferGeometry(
            headRadius, headLonSegments, headLatSegments);

    const labelGeometry = new THREE.PlaneBufferGeometry(1, 1);

    const ctx = document.createElement('canvas').getContext('2d');

    function makeLabelCanvas(baseWidth, size, name) {
        const borderSize = 2;
        const font = `${size}px bold sans-serif`;
        ctx.font = font;
        // measure how long the name will be
        const textWidth = ctx.measureText(name).width;

        const doubleBorderSize = borderSize * 2;
        const width = baseWidth + doubleBorderSize;
        const height = size + doubleBorderSize;
        ctx.canvas.width = width;
        ctx.canvas.height = height;

        // need to set font again after resizing canvas
        ctx.font = font;
        ctx.textBaseline = 'middle';
        ctx.textAlign = 'center';

        ctx.fillStyle = 'blue';
        ctx.fillRect(0, 0, width, height);

        // scale to fit but don't stretch
        const scaleFactor = Math.min(1, baseWidth / textWidth);
        ctx.translate(width / 2, height / 2);
        ctx.scale(scaleFactor, 1);
        ctx.fillStyle = 'white';
        ctx.fillText(name, 0, 0);

        return ctx.canvas;
    }

    const forceTextureInitialization = function () {
        const material = new THREE.MeshBasicMaterial();
        const geometry = new THREE.PlaneBufferGeometry();
        const scene = new THREE.Scene();
        scene.add(new THREE.Mesh(geometry, material));
        const camera = new THREE.Camera();

        return function forceTextureInitialization(texture) {
            material.map = texture;
            renderer.render(scene, camera);
        };
    }();

    function makePerson(x, labelWidth, size, name, color) {
        const canvas = makeLabelCanvas(labelWidth, size, name);
        const texture = new THREE.CanvasTexture(canvas);
        // because our canvas is likely not a power of 2
        // in both dimensions set the filtering appropriately.
        texture.minFilter = THREE.LinearFilter;
        texture.wrapS = THREE.ClampToEdgeWrapping;
        texture.wrapT = THREE.ClampToEdgeWrapping;
        forceTextureInitialization(texture);

        const labelMaterial = new THREE.MeshBasicMaterial({
            map: texture,
            side: THREE.DoubleSide,
            transparent: true,
        });
        const bodyMaterial = new THREE.MeshPhongMaterial({
            color,
            flatShading: true,
        });

        const root = new THREE.Object3D();
        root.position.x = x;

        const body = new THREE.Mesh(bodyGeometry, bodyMaterial);
        root.add(body);
        body.position.y = bodyHeight / 2;

        const head = new THREE.Mesh(headGeometry, bodyMaterial);
        root.add(head);
        head.position.y = bodyHeight + headRadius * 1.1;

        const label = new THREE.Mesh(labelGeometry, labelMaterial);
        root.add(label);
        label.position.y = bodyHeight * 4 / 5;
        label.position.z = bodyRadiusTop * 1.01;

        // if units are meters then 0.01 here makes size
        // of the label into centimeters.
        const labelBaseScale = 0.01;
        label.scale.x = canvas.width * labelBaseScale;
        label.scale.y = canvas.height * labelBaseScale;

        scene.add(root);
        return root;
    }

    makePerson(-3, 150, 32, 'Purple People Eater', 'purple');
    makePerson(-0, 150, 32, 'Green Machine', 'green');
    makePerson(+3, 150, 32, 'Red Menace', 'red');

    function resizeRendererToDisplaySize(renderer) {
        const canvas = renderer.domElement;
        const width = canvas.clientWidth;
        const height = canvas.clientHeight;
        const needResize = canvas.width !== width || canvas.height !== height;
        if (needResize) {
            renderer.setSize(width, height, false);
        }
        return needResize;
    }

    function render() {
        if (resizeRendererToDisplaySize(renderer)) {
            const canvas = renderer.domElement;
            camera.aspect = canvas.clientWidth / canvas.clientHeight;
            camera.updateProjectionMatrix();
        }

        renderer.render(scene, camera);

        requestAnimationFrame(render);
    }

    requestAnimationFrame(render);
}

main();
