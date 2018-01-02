'use strict';

const fs   = require('fs');
const path = require('path');
const secrets = require('./secrets');

function onBeforeDeployPreproduction(context, done) {
  context.logger.subhead('Backup current configuration');
  //@formatter:off
  fs.renameSync(path.join(__dirname, '../api/config.php'),        path.join(__dirname, '../api/config.php.local'));
  fs.renameSync(path.join(__dirname, '../api/configuration.php'), path.join(__dirname, '../api/configuration.php.local'));

  context.logger.subhead('Copy preproduction configuration');
  fs.copyFileSync(path.join(__dirname, 'environments/preproduction/config.php'),        path.join(__dirname, '../api/config.php'));
  fs.copyFileSync(path.join(__dirname, 'environments/preproduction/configuration.php'), path.join(__dirname, '../api/configuration.php'));
  //@formatter:on

  done();
}

function onAfterDeployPreproduction(context, done) {
  context.logger.subhead('Restore configuration');

  //@formatter:off
  fs.unlink(path.join(__dirname, '../api/config.php'));
  fs.unlink(path.join(__dirname, '../api/configuration.php'));

  fs.renameSync(path.join(__dirname, '../api/config.php.local'),        path.join(__dirname, '../api/config.php'));
  fs.renameSync(path.join(__dirname, '../api/configuration.php.local'), path.join(__dirname, '../api/configuration.php'));
  //@formatter:on

  done();
}

module.exports = function (options) {
  // @see https://www.npmjs.com/package/ssh-deploy-release
  return {
    // Common configuration
    // These options will be merged with those specific to the environment
    common: {
      localPath: '.',

      share: {
        storage:      'storage',
        thumbnail:    'thumbnail',
      },

      exclude: [
        'api/config.php.local',
        'api/configuration.php.local',
      ],

      create: [
        'storage/uploads',
      ],

      makeWritable: [
        'storage',
      ],
    },

    // Environment specific configuration
    environments: {
      preproduction: {
        host:           'gag.yohann-bianchi.ovh',
        username:       secrets.preproduction.username,
        password:       secrets.preproduction.password,
        mode:           'synchronize',
        deployPath:     `/home/${secrets.preproduction.username}/webapps/gag_backend`,
        onBeforeDeploy: onBeforeDeployPreproduction,
        onAfterDeploy:  onAfterDeployPreproduction,
      },
    },
  };
};
