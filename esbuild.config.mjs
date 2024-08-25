import * as esbuild from 'esbuild';

await esbuild.build({
  entryPoints: [
    "./scripts/clock.js"
  ],
  bundle: true,
  outdir: "./app/public/scripts"
})
