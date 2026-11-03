module.exports = function (grunt) {
    // Load all grunt tasks
    require('load-grunt-tasks')(grunt);

    // Project configuration
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        // Clean dist folder
        clean: {
            dist: ['dist'],
            temp: ['dist/buddypress-member-review']
        },

        // Copy files to dist
        copy: {
            dist: {
                files: [{
                    expand: true,
                    src: [
                        '**',
                        // Dev-only tooling (PHPStan + stubs). Never ship these.
                        '!vendor/**',
                        '!composer.json',
                        '!composer.lock',
                        '!phpstan.neon',
                        '!phpstan-baseline.neon',
                        '!node_modules/**',
                        '!audit/**',
                        '!dist/**',
                        '!.git/**',
                        '!.github/**',
                        '!.gitignore',
                        '!.gitattributes',
                        '!.editorconfig',
                        '!.eslintrc',
                        '!.eslintrc.json',
                        '!.prettierrc',
                        '!.stylelintrc',
                        '!package.json',
                        '!package-lock.json',
                        '!gruntfile.js',
                        '!Gruntfile.js',
                        '!composer.json',
                        '!composer.lock',
                        '!phpcs.xml',
                        '!phpcs.xml.dist',
                        '!phpunit.xml',
                        '!phpunit.xml.dist',
                        '!*.log',
                        '!*.zip',
                        '!tests/**',
                        '!bin/**',
                        '!docs/**',
                        '!plan/**',
                        '!CAPABILITIES.md',
                        '!marketing/**',
                        '!**/*.md',
                        '!*.md',
                        '!CLAUDE.md',
                        '!BUILD.md',
                        '!CONTRIBUTING.md',
                        '!**/*.map',
                        '!.wordpress-org/**',
                        '!screenshot-*.png',
                        '!.contract-audit-baseline.json'
                    ],
                    dest: 'dist/buddypress-member-review/'
                }]
            }
        },

        // Create zip file
        compress: {
            dist: {
                options: {
                    archive: 'dist/buddypress-member-review-<%= pkg.version %>.zip',
                    mode: 'zip'
                },
                files: [{
                    expand: true,
                    cwd: 'dist/',
                    src: ['buddypress-member-review/**'],
                    dest: ''
                }]
            }
        },

        // Task for checking text domain
        checktextdomain: {
            options: {
                text_domain: ['bp-member-reviews', 'buddypress'],
                keywords: [
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
                ],
            },
            files: {
                src: [
                    '*.php',
                    '**/*.php',
                    '!node_modules/**',
                    '!options/framework/**',
                    '!tests/**',
                    '!dist/**',
                    // Dev dependency, never shipped. wordpress-stubs.php declares
                    // WordPress core's own gettext calls against the "default"
                    // domain, which this task correctly flags and which we must not
                    // change. Scanning it made `grunt dist` fail with 14 false
                    // positives, so the zip could not be built without --force.
                    '!vendor/**'
                ],
                expand: true
            },
        },

        // Task for CSS minification
        cssmin: {
            public: {
                files: [{
                    expand: true,
                    cwd: 'assets/css',
                    src: ['*.css', '!*.min.css', '!vendor/*.css'],
                    dest: 'assets/css/min',
                    ext: '.min.css',
                }],
            },
            admin: {
                files: [{
                    expand: true,
                    cwd: 'admin/assets/css',
                    src: ['*.css', '!*.min.css', '!vendor/*.css'],
                    dest: 'admin/assets/css/min',
                    ext: '.min.css',
                }],
            },
        },

        // Task for JavaScript minification
        uglify: {
            public: {
                options: {
                    mangle: false,
                },
                files: [{
                    expand: true,
                    cwd: 'assets/js',
                    src: ['*.js', '!*.min.js', '!vendor/*.js'],
                    dest: 'assets/js/min',
                    ext: '.min.js',
                }],
            },
            admin: {
                options: {
                    mangle: false,
                },
                files: [{
                    expand: true,
                    cwd: 'admin/assets/js',
                    src: ['*.js', '!*.min.js', '!vendor/*.js'],
                    dest: 'admin/assets/js/min',
                    ext: '.min.js',
                }],
            },
        },

        // Task for watching file changes
        watch: {
            css: {
                files: ['assets/css/*.css', '!assets/css/*.min.css'],
                tasks: ['cssmin:public'],
            },
            adminCss: {
                files: ['admin/assets/css/*.css', '!admin/css/*.min.css'],
                tasks: ['cssmin:admin'],
            },
            js: {
                files: ['assets/js/*.js', '!assets/js/*.min.js'],
                tasks: ['uglify:public'],
            },
            adminJs: {
                files: ['admin/assets/js/*.js', '!admin/assets/js/*.min.js'],
                tasks: ['uglify:admin'],
            },
            php: {
                files: ['**/*.php'],
                tasks: ['checktextdomain'],
            },
        },

        // Task for generating RTL CSS
        rtlcss: {
            myTask: {
                options: {
                    map: { inline: false },
                    opts: {
                        clean: false
                    },
                    plugins: [],
                    saveUnmodified: true,
                },
                files: [
                    {
                        expand: true,
                        cwd: 'assets/css',
                        src: ['*.css', '!**/*.min.css', '!vendor/**/*.css'],
                        dest: 'assets/css/rtl/',
                        ext: '.rtl.css',
                        flatten: true
                    },
                    {
                        expand: true,
                        cwd: 'admin/assets/css',
                        src: ['*.css', '!**/*.min.css', '!vendor/**/*.css'],
                        dest: 'admin/assets/css/rtl/',
                        ext: '.rtl.css',
                        flatten: true
                    }
                ]
            }
        },

        // Shell commands
        shell: {
            wpcli: {
                // --domain is explicit rather than inferred from the plugin header, and
                // the excludes keep dev-only trees (vendor stubs, audit, docs) out of
                // the scan. Keep this in step with .wbcom-i18n.json.
                command: 'wp i18n make-pot . languages/bp-member-reviews.pot --domain=bp-member-reviews --exclude=node_modules,vendor,dist,tests,audit,docs',
            }
        }
    });

    // Register build task (minify assets)
    grunt.registerTask('build', [
        'cssmin',
        'uglify',
        'rtlcss',
        'checktextdomain',
        'shell'
    ]);

    // Register dist task (create distribution zip)
    grunt.registerTask('dist', [
        'build',
        'clean:dist',
        'copy:dist',
        'compress:dist',
        'clean:temp'
    ]);

    // Register default task
    grunt.registerTask('default', ['build', 'watch']);
};
