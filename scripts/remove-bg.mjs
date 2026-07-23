import sharp from 'sharp';
import path from 'node:path';
import { unlink } from 'node:fs/promises';

const imagesDir = path.resolve('public/images');
const files = ['250-gm.png', '500-gm.png', '1-kg.png'];

function isBackgroundPixel(r, g, b) {
  const saturation = Math.max(r, g, b) - Math.min(r, g, b);
  const brightness = (r + g + b) / 3;

  return saturation <= 14 && brightness >= 238;
}

function idx(x, y, width, channels) {
  return (y * width + x) * channels;
}

async function removeBackground(inputPath, outputPath) {
  const { data, info } = await sharp(inputPath)
    .ensureAlpha()
    .raw()
    .toBuffer({ resolveWithObject: true });

  const { width, height, channels } = info;
  const visited = new Uint8Array(width * height);
  const queue = [];

  const enqueue = (x, y) => {
    const pos = y * width + x;
    if (visited[pos]) {
      return;
    }

    const i = idx(x, y, width, channels);
    const r = data[i];
    const g = data[i + 1];
    const b = data[i + 2];

    if (!isBackgroundPixel(r, g, b)) {
      return;
    }

    visited[pos] = 1;
    queue.push(pos);
  };

  for (let x = 0; x < width; x++) {
    enqueue(x, 0);
    enqueue(x, height - 1);
  }

  for (let y = 0; y < height; y++) {
    enqueue(0, y);
    enqueue(width - 1, y);
  }

  while (queue.length) {
    const pos = queue.pop();
    const x = pos % width;
    const y = (pos - x) / width;
    const alphaIndex = pos * channels + 3;
    data[alphaIndex] = 0;

    if (x > 0) enqueue(x - 1, y);
    if (x < width - 1) enqueue(x + 1, y);
    if (y > 0) enqueue(x, y - 1);
    if (y < height - 1) enqueue(x, y + 1);
  }

  await sharp(data, { raw: { width, height, channels } })
    .png({ compressionLevel: 9, adaptiveFiltering: true })
    .toFile(outputPath);
}

for (const file of files) {
  const input = path.join(imagesDir, file);
  const temp = path.join(imagesDir, `.tmp-${file}`);

  await removeBackground(input, temp);
  await sharp(temp).toFile(input);
  await unlink(temp);

  console.log(`Updated ${file}`);
}

console.log('All product images now have transparent backgrounds.');
