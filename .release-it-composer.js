const fs = require('fs');
const path = require('path');

class ComposerPlugin {
  static isEnabled() {
    return fs.existsSync('composer.json');
  }

  async beforeBump() {
    // Read composer.json
    const composerPath = path.join(process.cwd(), 'composer.json');
    this.composerData = JSON.parse(fs.readFileSync(composerPath, 'utf8'));
    this.composerPath = composerPath;
  }

  async bump(version) {
    // Update version in composer.json if it exists
    if (this.composerData && this.composerData.version !== undefined) {
      this.composerData.version = version;
      fs.writeFileSync(
        this.composerPath,
        JSON.stringify(this.composerData, null, 4) + '\n'
      );
    }
  }

  async afterRelease() {
    this.log.log('Composer package ready for release');
  }
}

module.exports = ComposerPlugin;
