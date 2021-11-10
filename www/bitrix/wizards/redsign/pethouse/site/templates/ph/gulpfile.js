'use strict';

var gulp = require('gulp');
var runSequence = require('run-sequence');
var sass = require('gulp-sass');
var sourcemaps = require('gulp-sourcemaps');
var args = require('yargs').argv;
var pxtorem = require('gulp-pxtorem');
var autoprefixer = require('gulp-autoprefixer');
var cleanCSS = require('gulp-clean-css');
var spritesmith = require('gulp.spritesmith');
var spritesmash = require('gulp-spritesmash');
var uglify = require('gulp-uglify');
var rename = require('gulp-rename');

// svg sprite
var svgSprite = require('gulp-svg-sprite');
var svgmin = require('gulp-svgmin');
var cheerio = require('gulp-cheerio');
var replace = require('gulp-replace');
var path = require('path');

var paths = {
  src: {
    styles: './sass',
    js: './',
    images: './img',
  },
  build: {
    styles: './assets/css',
    images: './assets/img',
    js: './',
  },
  watch: {
    styles: './sass/**/*.scss',
    sprite: {
      png: '/icons/png',
      svg: '/icons/svg',
    },
    js: [
      './**/*.js',
      '!./node_modules/*.js',
      '!./node_modules/**/*.js',
      '!./gulpfile.js',
      '!./**/*.min.js',
      '!./**/*.map.js',
      '!*.min.js',
      '!*.map.js',
    ]
  }
};

var pxtoremOptions = {
  rootValue: 12,
  propWhiteList: [
    'font',
    'font-size',
    'line-height',
    'letter-spacing',
    'padding',
    'padding-top',
    'padding-right',
    'padding-bottom',
    'padding-left',
    'margin',
    'margin-top',
    'margin-right',
    'margin-bottom',
    'margin-left',
    'height',
    'max-height',
    'min-height',
    'width',
    'max-width',
    'min-width',
    'top',
    'right',
    'bottom',
    'left',
  ],
  replace: false,
  mediaQuery: false,
	minPixelValue: 2,
};

var themes = [
  'activelife',
  'everyday',
  'fashionshow',
  'homeware',
  'lovekids',
  'mediamart',
  'officespace',
  'pethouse',
  'stroymart',
]

gulp.task('default', ['build', 'watch']);

// gulp.task('build', [
    // 'js:build',
    // 'sprite:build',
    // 'styles:build',
// ]);


gulp.task('build', function(){

  var theme = (args.theme ? args.theme : themes[0]);

  if(args.theme == 'all') {

    for (var key in themes) {
      runSequence(
        'sprite.svg:build --theme=' + themes[key],
        'sprite.png:build --theme=' + themes[key],
        'styles:build --theme=' + themes[key]
      );
    }

  } else {
    runSequence('sprite.svg:build', 'sprite.png:build', 'styles:build');
  }

});

gulp.task('watch', function(){

  var theme = (args.theme ? args.theme : themes[0]);

  //gulp.watch([paths.watch.js], ['js:build']);
  // gulp.watch([
      // paths.build.images + paths.watch.sprite.png + '/*.png',
      // paths.build.images + '/themes/' + theme + paths.watch.sprite.png + '/*.png'
    // ], ['sprite.png:build']
  // );
  // gulp.watch([
      // paths.build.images + paths.watch.sprite.svg + '/*.svg',
      // paths.build.images + '/themes/' + theme + paths.watch.sprite.svg + '/*.svg'
    // ], ['sprite.svg:build']
  // );
  gulp.watch([paths.watch.styles], ['styles:build']);

});

gulp.task('js:build', function () {

  if(args.production) {

    gulp.src(paths.watch.js)
      .pipe(uglify())
      .pipe(rename({suffix: '.min'}))
      .pipe(sourcemaps.write('./'))
      .pipe(gulp.dest(paths.build.js));

  } else {

    gulp.src(paths.watch.js)
      .pipe(uglify().on('error', function(e){
        console.log(e);
      }))
      .pipe(rename({suffix: '.min'}))
      .pipe(sourcemaps.write('./'))
      .pipe(gulp.dest(paths.build.js));

  }
});

gulp.task('styles:build', function() {

  var sDestPath = './build/' + themes[0] + '/'+ paths.build.styles;
  if (args.theme == 'all') {
  } else if (args.theme) {
    sDestPath = paths.build.styles;
  } else {
    sDestPath = paths.build.styles;
  }

	gulp.src(paths.watch.styles)
		.pipe(sourcemaps.init())
		.pipe(sass().on('error', sass.logError))
		.pipe(pxtorem(pxtoremOptions))
		.pipe(autoprefixer({
				browsers: ['last 2 versions'],
				cascade: false
		}))
		.pipe(sourcemaps.write('./'))
		.pipe(gulp.dest(sDestPath));


  if (args.production) {

    gulp.src(paths.watch.styles)
      .pipe(sass().on('error', sass.logError))
      .pipe(pxtorem(pxtoremOptions))
      .pipe(autoprefixer({
          browsers: ['last 2 versions'],
          cascade: false
      }))
      .pipe(gulp.dest(sDestPath))
      .pipe(cleanCSS({compatibility: 'ie8'}))
      .pipe(rename({suffix: '.min'}))
      .pipe(gulp.dest(sDestPath));
  }

});

gulp.task('sprite:build', [
  'sprite.png:build',
  'sprite:svg:build',
]);


gulp.task('sprite.png:build', function () {

  var fileName = 'icons.png';
  var theme = (args.theme ? args.theme : themes[0]);

  var spriteData = gulp.src([
      paths.build.images + paths.watch.sprite.png + '/*.png',
      paths.build.images + '/themes/' + theme + paths.watch.sprite.png + '/*.png'
    ])
    .pipe(spritesmith({
      imgName: fileName,
      cssName: './_sprite_png.scss',
      cssFormat: 'scss',
      imgPath: '../img/' + fileName
    }));

  spriteData.img
    .pipe(gulp.dest(paths.build.images))

  spriteData.css
    .pipe(spritesmash())
    .pipe(gulp.dest(paths.src.styles))

});

gulp.task('sprite.svg:build', function () {

  var theme = (args.theme ? args.theme : themes[0]);

	return gulp.src([
      paths.build.images + paths.watch.sprite.svg + '/*.svg',
      paths.build.images + '/themes/' + theme + paths.watch.sprite.svg + '/*.svg'
    ])
    // minify svg
		.pipe(svgmin({
			js2svg: {
				pretty: true
			}
		}))
		// remove all fill, style and stroke declarations in out shapes
		.pipe(cheerio({
			run: function ($) {
        $('style').remove();
				$('[class]').removeAttr('class');
				$('[fill]').removeAttr('fill');
				$('[stroke]').removeAttr('stroke');
				$('[style]').removeAttr('style');
			},
			parserOptions: {xmlMode: true}
		}))
		// cheerio plugin create unnecessary string '&gt;', so replace it.
		.pipe(replace('&gt;', '>'))
		// build svg sprite
		.pipe(svgSprite({
      shape: {
        id: {
          generator: function(name) {
            return 'svg-' + path.basename(name, '.svg')
          }
        }
      },
			mode: {
				symbol: {
					sprite: '../icons.svg',
					render: {
						scss: {
							dest: '../../../sass/_sprite_svg.scss',
              template: paths.src.styles + "/_sprite_svg_template.scss"
						}
					},
          symbol: true,

				},
			},
      svg: {
        namespaceClassnames : false,
      },
		}))
		.pipe(gulp.dest(paths.build.images));
});