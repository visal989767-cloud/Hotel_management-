import * as THREE from 'three';
import { GLTFLoader } from 'three/examples/jsm/loaders/GLTFLoader.js';

const canvas = document.querySelector('#hotel-bronze-stage');
if (!canvas) {
    throw new Error('The bronze stage canvas is missing.');
}

const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
const authPage = document.body.classList.contains('auth-page');
const scene = new THREE.Scene();
scene.background = new THREE.Color('#030608');
scene.fog = new THREE.FogExp2('#030608', 0.015);

const camera = new THREE.PerspectiveCamera(50, innerWidth / innerHeight, 0.1, 100);
camera.position.set(0, 0.15, 4.2);
scene.add(camera);

const renderer = new THREE.WebGLRenderer({
    canvas,
    antialias: true,
    alpha: false,
    powerPreference: 'high-performance'
});
renderer.setPixelRatio(Math.min(devicePixelRatio, 2));
renderer.setSize(innerWidth, innerHeight);
renderer.shadowMap.enabled = true;
renderer.shadowMap.type = THREE.PCFSoftShadowMap;
renderer.toneMapping = THREE.ACESFilmicToneMapping;
renderer.toneMappingExposure = 1.8;

const uniforms = {
    uTime: { value: 0 },
    uResolution: { value: new THREE.Vector2(innerWidth, innerHeight) },
    uScroll: { value: 0 }
};

const background = new THREE.ShaderMaterial({
    uniforms,
    depthWrite: false,
    depthTest: false,
    vertexShader: `
        varying vec2 vUv;
        void main() {
            vUv = uv;
            gl_Position = projectionMatrix * modelViewMatrix * vec4(position, 1.0);
        }
    `,
    fragmentShader: `
        varying vec2 vUv;
        uniform float uTime;
        uniform vec2 uResolution;
        uniform float uScroll;
        void main() {
            vec2 uv = (gl_FragCoord.xy - 0.5 * uResolution.xy) / uResolution.y;
            float t = uTime * 0.08;
            float drift = uScroll * 2.0;
            uv.x += sin(uv.y * 2.5 + t + drift) * 0.3;
            uv.y += cos(uv.x * 2.0 - t * 0.8 - drift) * 0.25;
            float wave = sin(dot(uv, vec2(cos(0.6), sin(0.6))) * 2.4 + t)
                       * 0.5
                       + cos(dot(uv, vec2(cos(-0.7), sin(-0.7))) * 3.2 - t * 1.4) * 0.35
                       + sin(dot(uv, vec2(cos(1.2), sin(1.2))) * 4.0 + t * 1.8) * 0.15;
            float sheen = pow(max(0.0, 1.0 - abs(wave - 0.12)), 5.0);
            float palette = smoothstep(0.0, 1.0, uScroll);
            vec3 bronze = mix(vec3(0.008, 0.003, 0.001), vec3(0.21, 0.105, 0.045), sheen);
            vec3 sapphire = mix(vec3(0.001, 0.003, 0.009), vec3(0.045, 0.15, 0.25), sheen);
            vec3 color = mix(bronze, sapphire, palette);
            color *= 1.0 - dot(uv, uv) * 0.11;
            gl_FragColor = vec4(color, 1.0);
        }
    `
});
const backgroundPlane = new THREE.Mesh(new THREE.PlaneGeometry(30, 30), background);
backgroundPlane.position.z = -8;
backgroundPlane.renderOrder = -10;
camera.add(backgroundPlane);

scene.add(new THREE.AmbientLight('#ffffff', 0.18));
const keyLight = new THREE.SpotLight('#fff0dc', 16);
keyLight.position.set(4, 6, 3);
keyLight.angle = Math.PI / 4;
keyLight.penumbra = 0.9;
keyLight.castShadow = true;
keyLight.shadow.mapSize.set(1024, 1024);
scene.add(keyLight);
const rimLight = new THREE.DirectionalLight('#b7d9ff', 8);
rimLight.position.set(-5, 3, -4);
scene.add(rimLight);
const fillLight = new THREE.DirectionalLight('#f5c990', 0.8);
fillLight.position.set(-2, -4, 2);
scene.add(fillLight);

const sparkCount = 220;
const sparkPositions = new Float32Array(sparkCount * 3);
const sparkColors = new Float32Array(sparkCount * 3);
for (let i = 0; i < sparkCount; i++) {
    sparkPositions[i * 3] = (Math.random() - 0.5) * 8;
    sparkPositions[i * 3 + 1] = (Math.random() - 0.5) * 6;
    sparkPositions[i * 3 + 2] = (Math.random() - 0.5) * 6;
    const blue = Math.random() > 0.6;
    sparkColors[i * 3] = blue ? 0.65 : 1;
    sparkColors[i * 3 + 1] = blue ? 0.84 : 0.48;
    sparkColors[i * 3 + 2] = blue ? 1 : 0.12;
}
const sparkGeometry = new THREE.BufferGeometry();
sparkGeometry.setAttribute('position', new THREE.BufferAttribute(sparkPositions, 3));
sparkGeometry.setAttribute('color', new THREE.BufferAttribute(sparkColors, 3));
const sparks = new THREE.Points(sparkGeometry, new THREE.PointsMaterial({
    size: 0.025,
    vertexColors: true,
    transparent: true,
    opacity: 0.75,
    blending: THREE.AdditiveBlending,
    depthWrite: false
}));
scene.add(sparks);

let pivot = null;
const loader = new GLTFLoader();
loader.load(
    'https://api.getlayers.ai/storage/v1/object/public/public/assets/laocoon-59f84455c6/bronze_horse.glb',
    (gltf) => {
        const model = gltf.scene;
        pivot = new THREE.Group();
        scene.add(pivot);
        pivot.add(model);
        model.traverse((child) => {
            if (!child.isMesh) return;
            child.castShadow = true;
            child.receiveShadow = true;
            if (!child.material) return;
            child.material.roughness = 0.42;
            child.material.metalness = 0.92;
            child.material.flatShading = false;
            if (child.material.map) child.material.map.anisotropy = 16;
        });
        const initialSize = new THREE.Box3().setFromObject(model).getSize(new THREE.Vector3());
        const maxDimension = Math.max(initialSize.x, initialSize.y, initialSize.z);
        model.scale.setScalar(3.2 / (maxDimension || 1));
        model.updateMatrixWorld(true);
        model.position.sub(new THREE.Box3().setFromObject(model).getCenter(new THREE.Vector3()));
        pivot.position.set(
            authPage ? (innerWidth > 767 ? 1.75 : 1.1) : 0,
            -0.3,
            0
        );
    },
    undefined,
    (error) => console.error('Error loading bronze horse model:', error)
);

const clock = new THREE.Clock();
let scrollTarget = 0;
let scrollProgress = 0;
let lastFrame = 0;
function render(timestamp) {
    requestAnimationFrame(render);
    if (reducedMotion && timestamp - lastFrame < 50) return;
    lastFrame = timestamp;

    const maxScroll = document.documentElement.scrollHeight - innerHeight;
    scrollTarget = maxScroll > 0 ? scrollY / maxScroll : 0;
    scrollProgress += (scrollTarget - scrollProgress) * 0.025;
    const time = clock.getElapsedTime();
    uniforms.uTime.value = time;
    uniforms.uScroll.value = scrollProgress;

    const angle = scrollProgress * Math.PI * 2;
    const radius = 4.2 - Math.sin(scrollProgress * Math.PI) * 0.5;
    const position = new THREE.Vector3(
        radius * Math.sin(angle),
        0.2 + Math.sin(scrollProgress * Math.PI) * 0.5,
        radius * Math.cos(angle)
    );
    camera.position.lerp(position, reducedMotion ? 0.04 : 0.025);
    camera.lookAt(0, -0.2, 0);

    const positions = sparkGeometry.attributes.position.array;
    for (let i = 0; i < sparkCount; i++) {
        const offset = i * 3;
        positions[offset + 1] += 0.0015 * (1 + Math.abs(scrollTarget - scrollProgress) * 20);
        positions[offset] += Math.sin(time + i) * 0.0007;
        if (positions[offset + 1] > 3) positions[offset + 1] = -3;
    }
    sparkGeometry.attributes.position.needsUpdate = true;
    if (pivot) pivot.rotation.y += reducedMotion ? 0 : (scrollProgress - pivot.rotation.y / (Math.PI * 2)) * 0.002;
    renderer.render(scene, camera);
}

window.addEventListener('resize', () => {
    camera.aspect = innerWidth / innerHeight;
    camera.updateProjectionMatrix();
    renderer.setPixelRatio(Math.min(devicePixelRatio, 2));
    renderer.setSize(innerWidth, innerHeight);
    uniforms.uResolution.value.set(innerWidth, innerHeight);
    if (pivot && authPage) pivot.position.x = innerWidth > 767 ? 1.75 : 1.1;
});

requestAnimationFrame(render);
