module.exports = function(grunt) {
    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-autoprefixer');
    
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        sass: {
            development: {
                options: {
                    style: 'expanded'
                },
                files: {
                    'front/css/home.css': 'front/sass/home.scss',
                    'front/css/app.css': 'front/sass/app.scss',
                }
            }
        },

        autoprefixer: {
            options: {
                browsers: ['last 5 versions']
            },
            main: {
                expand: true,
                flatten: true,
                src: 'front/css/*.css',
                dest: 'res/css/'
            }
        },
        
        watch: {
            styles: {
                files: ['front/sass/*', 'front/sass/_parts/*'],
                tasks: ['sass', 'autoprefixer']
            }
        }
    });

    grunt.registerTask('auto', ['watch']);
    grunt.registerTask('build', ['sass', 'autoprefixer']);
    grunt.registerTask('default', ['watch']);

};