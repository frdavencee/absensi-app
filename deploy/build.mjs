import fs from 'fs';
import path from 'path';
import { execSync } from 'child_process';

const root = process.cwd();
const stagingRoot = path.join(root, 'deploy_staging');
const htdocsRoot = path.join(root, 'htdocs_root');
const publicBuild = path.join(root, 'public', 'build');
const publicStorage = path.join(root, 'public', 'storage');
const deployEnvSrc = path.join(root, 'deploy', 'env_production.txt');
const deployIndexSrc = path.join(root, 'deploy', 'index.php');

function rmRf(target) {
  if (fs.existsSync(target)) {
    fs.rmSync(target, { recursive: true, force: true });
  }
}

function mkDir(target) {
  fs.mkdirSync(target, { recursive: true });
}

function copyFile(src, dest) {
  fs.copyFileSync(src, dest);
}

function copyDir(src, dest) {
  mkDir(dest);
  const entries = fs.readdirSync(src, { withFileTypes: true });
  for (const entry of entries) {
    const srcPath = path.join(src, entry.name);
    const destPath = path.join(dest, entry.name);
    if (entry.isDirectory()) {
      copyDir(srcPath, destPath);
    } else {
      copyFile(srcPath, destPath);
    }
  }
}

function exec(cmd, opts) {
  return execSync(cmd, { stdio: 'inherit', ...opts });
}

function execQuiet(cmd) {
  try {
    execSync(cmd, { stdio: 'pipe' });
  } catch (e) {
    // silent
  }
}

console.log('\n🧹 Cleaning old staging files...');
rmRf(stagingRoot);
mkDir(stagingRoot);

console.log('📋 Copying htdocs_root (index.php + .htaccess)...');
copyDir(htdocsRoot, stagingRoot);

console.log('📦 Copying public/build assets...');
const stagingBuild = path.join(stagingRoot, 'public', 'build');
if (fs.existsSync(publicBuild)) {
  copyDir(publicBuild, stagingBuild);
} else {
  console.log('   ⚠️  public/build not found. Run "npm run build" first.');
}

console.log('🖼️  Copying public/storage (uploaded photos)...');
const stagingStorage = path.join(stagingRoot, 'public', 'storage');
if (fs.existsSync(publicStorage)) {
  copyDir(publicStorage, stagingStorage);
}

console.log('🔒 Copying env_production.txt as .env...');
const stagingEnv = path.join(stagingRoot, '.env');
copyFile(deployEnvSrc, stagingEnv);

const stagingIndex = path.join(stagingRoot, 'index.php');
if (fs.existsSync(deployIndexSrc)) {
  console.log('📄 Copying deploy-specific index.php...');
  copyFile(deployIndexSrc, stagingIndex);
}

console.log('\n📦 Creating deployment archive...');
const zipName = 'absensi-app-infinityfree.zip';
rmRf(zipName);
exec(`powershell Compress-Archive -Path "${stagingRoot.replace(/"/g, '""')}\\*" -DestinationPath "${zipName}" -Force`);

console.log('\n✅ Deployment package ready: ' + path.join(root, zipName));
console.log('   Upload the ZIP contents to InfinityFree public_html (htdocs) folder.');
console.log('   Or extract the ZIP and upload the "deploy_staging" folder contents to public_html.\n');
