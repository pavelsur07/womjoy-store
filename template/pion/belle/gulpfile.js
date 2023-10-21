const gulp = require('gulp');
const autoprefixer = require('gulp-autoprefixer');
const del = require('del');
const browserSync = require('browser-sync').create();
const cleanCSS = require('gulp-clean-css');
const gulpif = require('gulp-if');
const gcmq = require('gulp-group-css-media-queries');
const sass = require('gulp-sass')(require('sass'));
const fileinclude = require('gulp-file-include');

const isDev = (process.argv.indexOf('--dev') !== -1);
const isProd = !isDev;
const isSync = (process.argv.indexOf('--sync') !== -1);


function clear(){
	return del('build/*');
}



function styles(){
	return gulp.src(['./src/scss/style.scss'])
			   .pipe(sass().on('error', sass.logError))
			   //.pipe(concat('style.css'))
			   .pipe(gcmq())
			   .pipe(autoprefixer({
			   		grid: true,
		            browsers: ['> 0.1%'],
		            // cascade: false
		        }))
			   //.on('error', console.error.bind(console))
			   .pipe(gulp.dest('./build/css'))
			   .pipe(gulpif(isSync, browserSync.stream()));
}
function bs(){
	return gulp.src(['./src/scss/bootstrap.scss'])
			   .pipe(sass({outputStyle: 'compressed'}).on('error', sass.logError))
			   //.pipe(concat('style.css'))
			   //.on('error', console.error.bind(console))
			   .pipe(gulp.dest('./build/css'))
			   .pipe(gulpif(isSync, browserSync.stream()));
}
function img(){
	return gulp.src('./src/img/**/*')
			   .pipe(gulp.dest('./build/img'))
}
function fonts(){
	return gulp.src('./src/fonts/**/*')
			   .pipe(gulp.dest('./build/fonts'))
}
function libs() {
	return gulp.src('./src/libs/**/*')
			   .pipe(gulp.dest('./build/libs'))
}
function js(){
	return gulp.src('./src/js/**/*')
			   .pipe(gulp.dest('./build/js'))
			   .pipe(gulpif(isSync, browserSync.stream()));
}
function html(){
	return gulp.src('./src/**/*.html')
			   .pipe(fileinclude())
			   .pipe(gulp.dest('./build'))
			   .pipe(gulpif(isSync, browserSync.stream()));
}

function watch(){
	if(isSync){
		browserSync.init({
	        server: {
	        	baseDir: "build/"
	        },
	        open: false
	    });
	}

	gulp.watch(['./src/scss/**/*.scss', '!./src/scss/bootstrap.scss'], styles);
	gulp.watch('./src/js/**/*.js', js);
	gulp.watch('./src/**/*.html', html);
}

let build = gulp.series(clear, 
	gulp.parallel(styles, bs, fonts, img, html, js, libs)
);

gulp.task('build', build);
gulp.task('watch', gulp.series(build, watch));