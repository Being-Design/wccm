'use strict';
module.exports = function ( grunt ) {

    /*
     * Grunt Tasks
     * load all grunt tasks matching the `grunt-*` pattern
     * Ref. https://npmjs.org/package/load-grunt-tasks
     */
    require( 'load-grunt-tasks' )( grunt );
    /*
     * Grunt Config
     */
    grunt.initConfig( {
        /*
         * Uglify
         * Compress and Minify JS files
         * Ref. https://npmjs.org/package/grunt-contrib-uglify
         */
        uglify: {
            options: {
                banner: '/*! \n * BuddyBoss Location Fields JavaScript Library \n * @package BuddyBoss Location Fields \n */'
            },
            frontend: {
                files: {
                    'assets/js/bp-location-autocomplete.min.js': [ 'assets/js/bp-location-autocomplete.js' ],
                    'assets/js/bp-group-autocomplete.min.js': [ 'assets/js/bp-group-autocomplete.js' ]
                }
            }
        },
        /*
         * Check text domain
         * Check your code for missing or incorrect text-domain in gettext functions
         * Ref. https://github.com/stephenharris/grunt-checktextdomain
         */
        checktextdomain: {
            options: {
                text_domain: ['buddypress', 'bbpress', 'bp-location-autocomplete'], //Specify allowed domain(s)
                keywords: [ //List keyword specifications
                    '__:1,2d',
                    '_e:1,2d',
                    '_x:1,2c,3d',
                    'esc_html__:1,2d',
                    'esc_html_e:1,2d',
                    'esc_html_x:1,2c,3d',
                    'esc_attr__:1,2d',
                    'esc_attr_e:1,2d',
                    'esc_attr_x:1,2c,3d',
                    '_ex:1,2c,3d',
                    '_n:1,2,4d',
                    '_nx:1,2,4c,5d',
                    '_n_noop:1,2,3d',
                    '_nx_noop:1,2,3c,4d'
                ]
            },
            target: {
                files: [
                    {
                        src: [
                            '*.php',
                            '**/*.php',
                            '!node_modules/**',
                            '!tests/**',
                            '!admin/tgm/**'
                        ], //all php
                        expand: true
                    }
                ]
            }
        },
        /*
         * Makepot
         * Generate a POT file for translators to use when translating your plugin or theme.
         * Ref. https://github.com/cedaro/grunt-wp-i18n/blob/develop/docs/makepot.md
         */
        makepot: {
            target: {
                options: {
                    cwd: '.', // Directory of files to internationalize.
                    domainPath: 'languages/', // Where to save the POT file.
                    exclude: [ 'node_modules/*', 'admin/ReduxFramework/*' ], // List of files or directories to ignore.
                    mainFile: 'index.php', // Main project file.
                    potFilename: 'bp-location-autocomplete.pot', // Name of the POT file.
                    potHeaders: { // Headers to add to the generated POT file.
                        poedit: true, // Includes common Poedit headers.
                        'Last-Translator': 'BuddyBoss <support@buddyboss.com>',
                        'Language-Team': 'BuddyBoss <support@buddyboss.com>',
                        'report-msgid-bugs-to': 'https://www.buddyboss.com/contact/',
                        'x-poedit-keywordslist': true // Include a list of all possible gettext functions.
                    },
                    type: 'wp-plugin', // Type of project (wp-plugin or wp-theme).
                    updateTimestamp: true, // Whether the POT-Creation-Date should be updated without other changes.
                    updatePoFiles: true // Whether to update PO files in the same directory as the POT file.
                }
            }
        },
        /*
         * .Po to .Mo
         * Grunt plug-in to compile .po files into binary .mo files with msgfmt.
         * Ref. https://github.com/axisthemes/grunt-potomo
         */
        potomo: {
            dist: {
                options: {
                    poDel: false
                },
                files: [
                    {
                        expand: true,
                        cwd: 'languages/',
                        src: [ '*.po' ],
                        dest: 'languages/',
                        ext: '.mo',
                        nonull: true
                    }
                ]
            }
        }
    } );

    // register task
    grunt.registerTask( 'default', [ 'uglify', 'checktextdomain', 'makepot', 'potomo' ] );
};