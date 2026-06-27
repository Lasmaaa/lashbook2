const test = require('node:test');
const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');
const http = require('node:http');
const { spawn } = require('node:child_process');

const ROOT = path.resolve(__dirname, '..');

function read(file) {
  return fs.readFileSync(file, 'utf8');
}

function exists(file) {
  return fs.existsSync(file);
}

function request(port, urlPath) {
  return new Promise((resolve, reject) => {
    const req = http.get({ hostname: '127.0.0.1', port, path: urlPath }, (res) => {
      const chunks = [];
      res.on('data', (chunk) => chunks.push(chunk));
      res.on('end', () => {
        resolve({
          status: res.statusCode,
          headers: res.headers,
          body: Buffer.concat(chunks),
        });
      });
    });
    req.on('error', reject);
    req.setTimeout(10000, () => req.destroy(new Error('timeout')));
  });
}

function waitForServer(port, attempts = 40) {
  return new Promise(async (resolve, reject) => {
    for (let i = 0; i < attempts; i += 1) {
      try {
        const res = await request(port, '/health');
        if (res.status === 200) {
          resolve();
          return;
        }
      } catch {
        // retry
      }
      await new Promise((r) => setTimeout(r, 250));
    }
    reject(new Error(`server did not start on port ${port}`));
  });
}

test('required deployment files exist', () => {
  const required = [
    'Dockerfile',
    'render.yaml',
    'docker/start.sh',
    'docker/sync-env.php',
    'docker/laravel-env.conf',
    'package.json',
    'public/build/manifest.json',
    'resources/views/layouts/app.blade.php',
    'resources/views/layouts/user/index.blade.php',
    'resources/views/auth/login.blade.php',
  ];

  for (const file of required) {
    assert.ok(exists(path.join(ROOT, file)), `missing file: ${file}`);
  }
});

test('Dockerfile installs PHP extensions and retries composer', () => {
  const dockerfile = read(path.join(ROOT, 'Dockerfile'));
  assert.match(dockerfile, /mbstring/);
  assert.match(dockerfile, /composer:2\.8/);
  assert.match(dockerfile, /COMPOSER_MEMORY_LIMIT=-1/);
  assert.match(dockerfile, /composer install --no-dev/);
  assert.match(dockerfile, /attempt \$\{attempt\}\/5/);
});

test('render.yaml defines docker web service with auto deploy', () => {
  const yaml = read(path.join(ROOT, 'render.yaml'));
  assert.match(yaml, /type:\s*web/);
  assert.match(yaml, /runtime:\s*docker/);
  assert.match(yaml, /dockerfilePath:\s*\.\/Dockerfile/);
  assert.match(yaml, /healthCheckPath:\s*\/health/);
  assert.match(yaml, /autoDeploy:\s*true/);
  assert.match(yaml, /branch:\s*lashbook2/);
  assert.doesNotMatch(yaml, /key:\s*APP_KEY/, 'APP_KEY must be managed by container, not Render');
});

test('blade templates expose required data-testid hooks', () => {
  const appLayout = read(path.join(ROOT, 'resources/views/layouts/app.blade.php'));
  const home = read(path.join(ROOT, 'resources/views/layouts/user/index.blade.php'));
  const login = read(path.join(ROOT, 'resources/views/auth/login.blade.php'));
  const sidebar = read(path.join(ROOT, 'resources/views/layouts/sidebar.blade.php'));

  const requiredIds = [
    'app-shell',
    'sidebar',
    'main-content',
    'home-carousel',
    'home-actions',
    'login-form',
    'brand-title',
  ];

  const combined = `${appLayout}\n${home}\n${login}\n${sidebar}`;
  for (const id of requiredIds) {
    assert.match(combined, new RegExp(`data-testid="${id}"`), `missing data-testid="${id}"`);
  }

  assert.match(home, /Lashbook2|carousel-slide/);
  assert.match(login, /route\('login'\)/);
});

test('built vite assets are present', () => {
  const manifest = JSON.parse(read(path.join(ROOT, 'public/build/manifest.json')));
  const cssEntry = Object.values(manifest).find((entry) => entry.file && entry.file.endsWith('.css'));
  const jsEntry = Object.values(manifest).find((entry) => entry.file && entry.file.endsWith('.js'));

  assert.ok(cssEntry, 'missing css build entry');
  assert.ok(jsEntry, 'missing js build entry');
  assert.ok(exists(path.join(ROOT, 'public/build', cssEntry.file)), 'missing built css file');
  assert.ok(exists(path.join(ROOT, 'public/build', jsEntry.file)), 'missing built js file');
  assert.ok(fs.statSync(path.join(ROOT, 'public/build', cssEntry.file)).size > 1000);
});

test('laravel server serves health, login and static assets', async (t) => {
  const port = 8765 + Math.floor(Math.random() * 500);
  const server = spawn('php', ['artisan', 'serve', '--port=' + port, '--host=127.0.0.1'], {
    cwd: ROOT,
    env: { ...process.env, APP_ENV: 'local' },
    stdio: ['ignore', 'pipe', 'pipe'],
  });

  t.after(() => new Promise((resolve) => {
    server.kill('SIGTERM');
    server.on('exit', resolve);
    setTimeout(resolve, 1000);
  }));

  await waitForServer(port);

  const routes = [
    ['/health', /text\/plain/],
    ['/login', /text\/html/],
    ['/up', /text\/html|application\/json/],
  ];

  for (const [route, contentTypePattern] of routes) {
    const res = await request(port, route);
    assert.equal(res.status, 200, `expected 200 for ${route}, got ${res.status}`);
    assert.match(String(res.headers['content-type']), contentTypePattern, `unexpected content-type for ${route}`);
    assert.ok(res.body.length > 0, `empty body for ${route}`);
  }

  const loginRes = await request(port, '/login');
  const loginHtml = loginRes.body.toString('utf8');
  assert.match(loginHtml, /data-testid="login-form"/);
  assert.match(loginHtml, /Lashbook2/i);

  const manifest = JSON.parse(read(path.join(ROOT, 'public/build/manifest.json')));
  const cssEntry = Object.values(manifest).find((entry) => entry.file && entry.file.endsWith('.css'));
  const cssRes = await request(port, '/build/' + cssEntry.file);
  assert.equal(cssRes.status, 200);
  assert.match(String(cssRes.headers['content-type']), /text\/css/);
});
