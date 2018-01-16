'use strict';

const secrets = require('./secrets');
const path = require('path');

module.exports = function (options) {
  // @see https://www.npmjs.com/package/ssh-deploy-release
  return {
    // Common configuration
    // These options will be merged with those specific to the environment
    common: {
      localPath: '.',

      share: {
        storage:             'storage',
        'config.php':        'api/config.php',
        'configuration.php': 'api/configuration.php',
        'api-logs':          'api/logs',
      },

      exclude: [
        'api/config.php',
        'api/configuration.php',
        'api/config_sample.php',
        'api/configuration_sample.php',

        'api/logs/**',
        '_deployment/**',
        'storage/**',
        'thumbnail/**',
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
        host:       'gag.yohann-bianchi.ovh',
        username:   secrets.preproduction.username,
        password:   secrets.preproduction.password,
        mode:       'synchronize',
        deployPath: `/home/${secrets.preproduction.username}/webapps/gag_backend`,

        onBeforeLink: (context, done) => {
          context.logger.subhead('Hard link custom php CGI');

          const targetPath = path.join(context.options.deployPath, context.options.sharedFolder, 'php71.cgi');
          const linkPath = path.join(context.release.path, 'php71.cgi');

          const command = `ln ${targetPath} ${linkPath}`;
          const showLog = true;

          context.remote.exec(command, () => {
            done();
          }, showLog);
        },
      },
    },
  };
};
