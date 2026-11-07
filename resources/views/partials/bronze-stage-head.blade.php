<script type="importmap">
{
  "imports": {
    "three": "https://unpkg.com/three@0.160.0/build/three.module.js",
    "three/examples/jsm/loaders/GLTFLoader.js": "https://unpkg.com/three@0.160.0/examples/jsm/loaders/GLTFLoader.js"
  }
}
</script>
<style>
    .bronze-stage-canvas {
        position: fixed;
        inset: 0;
        z-index: 1;
        width: 100%;
        height: 100%;
        pointer-events: none;
        opacity: .24;
        mix-blend-mode: screen;
    }

    @media (max-width: 767.98px) {
        .bronze-stage-canvas { opacity: .36; }
    }
</style>
