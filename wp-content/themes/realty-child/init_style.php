<style>
/*! CSS Used from: Embedded */
.crellyslider{margin:0 auto;position:relative;white-space:nowrap;overflow:hidden;line-height:1.5;font-size:14px;color:#000;font-family:'Verdana',sans-serif;}
.crellyslider>.cs-controls,.crellyslider>.cs-navigation{z-index:999;-webkit-transition:all .2s;-moz-transition:all .2s;-o-transition:all .2s;-ms-transition:all .2s;transition:all .2s;opacity:0;filter:alpha(opacity=0);}
.crellyslider:hover>.cs-controls,.crellyslider:hover>.cs-navigation{opacity:1;filter:alpha(opacity=100);}
.crellyslider>.cs-slides{list-style:none;margin:0;padding:0;}
.crellyslider>.cs-slides>.cs-slide{margin:0;padding:0;position:absolute;overflow:hidden;-webkit-touch-callout:none;-webkit-user-select:none;-khtml-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;}
.crellyslider>.cs-slides>.cs-slide>*{position:absolute;display:block;cursor:default;}
.crellyslider>.cs-controls{position:absolute;width:100%;top:50%;margin-top:-9px;}
.crellyslider>.cs-controls>.cs-previous,.crellyslider>.cs-controls>.cs-next{display:block;width:35px;height:35px;position:absolute;cursor:pointer;background-color:#fff;box-shadow:0 3px 10px rgba(0,0,0,0.16),0 3px 10px rgba(0,0,0,0.23);background-repeat:no-repeat;background-position:center center;border:10px;border-radius:50%;}
.crellyslider>.cs-controls>.cs-previous{background-image:url(premium-office.net/wp-content/plugins/crelly-slider/images/arrow-left.png);left:30px;}
.crellyslider>.cs-controls>.cs-next{background-image:url(premium-office.net/wp-content/plugins/crelly-slider/images/arrow-right.png);right:30px;}
.crellyslider>.cs-navigation{position:absolute;width:100%;height:0;bottom:40px;text-align:center;}
.crellyslider>.cs-navigation>.cs-slide-link{width:15px;height:15px;display:inline-block;cursor:pointer;margin:6px;background-color:#fff;box-shadow:0 3px 10px rgba(0,0,0,0.16),0 3px 10px rgba(0,0,0,0.23);border:10px;border-radius:50%;opacity:.5;filter:alpha(opacity=50);}
.crellyslider>.cs-navigation>.cs-slide-link.cs-active{opacity:1;filter:alpha(opacity=100);}
.crellyslider>.cs-progress-bar{width:0;height:4px;position:absolute;top:0;background-color:#fff;opacity:.5;filter:alpha(opacity=50);z-index:999;}
.crellyslider>.cs-progress-bar.cs-progress-bar-hidden{opacity:0;filter:alpha(opacity=0);}
.typeahead__container input{font:inherit;margin:0;}
.typeahead__container input{overflow:visible;}
.typeahead__container [type="search"]{-webkit-appearance:textfield;outline-offset:-2px;}
.typeahead__container ::-webkit-input-placeholder{color:inherit;opacity:.54;}
.typeahead__container{position:relative;font:14px Lato,"Helvetica Neue",Arial,Helvetica,sans-serif;}
.typeahead__container *{box-sizing:border-box;outline:0;}
.typeahead__query{position:relative;z-index:2;width:100%;}
.typeahead__field{font-size:0;position:relative;display:table;border-collapse:collapse;width:100%;}
.typeahead__field>*{display:table-cell;vertical-align:top;}
.typeahead__query{font-size:14px;}
.typeahead__field{color:#555;}
.typeahead__field input:focus,.typeahead__field input:active{border-color:#66afe9;}
.typeahead__field input[type="search"]{-webkit-appearance:none;appearance:none;}
.typeahead__field input[type="search"]::-ms-clear{display:none;width:0;height:0;}
.chosen-container{position:relative;display:inline-block;vertical-align:middle;font-size:13px;zoom:1;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;}
.chosen-container .chosen-drop{position:absolute;top:100%;left:-9999px;z-index:1010;box-sizing:border-box;width:100%;border:1px solid #aaa;border-top:0;background:#fff;box-shadow:0 4px 5px rgba(0,0,0,.15);}
.chosen-container a{cursor:pointer;}
.chosen-container-single .chosen-single{position:relative;display:block;overflow:hidden;padding:0 0 0 8px;height:23px;border:1px solid #aaa;border-radius:5px;background-color:#fff;background:linear-gradient(top,#fff 20%,#f6f6f6 50%,#eee 52%,#f4f4f4 100%);background-clip:padding-box;box-shadow:0 0 3px #fff inset,0 1px 1px rgba(0,0,0,.1);color:#444;text-decoration:none;white-space:nowrap;line-height:24px;}
.chosen-container-single .chosen-single span{display:block;overflow:hidden;margin-right:26px;text-overflow:ellipsis;white-space:nowrap;}
.chosen-container-single .chosen-single div{position:absolute;top:0;right:0;display:block;width:18px;height:100%;}
.chosen-container-single .chosen-single div b{display:block;width:100%;height:100%;background:url(premium-office.net/wp-content/themes/realty/assets/css/chosen-sprite.png) 0 2px no-repeat;}
.chosen-container-single .chosen-search{position:relative;z-index:1010;margin:0;padding:3px 4px;white-space:nowrap;}
.chosen-container-single .chosen-search input[type=text]{box-sizing:border-box;margin:1px 0;padding:4px 20px 4px 5px;width:100%;height:auto;outline:0;border:1px solid #aaa;background:url(premium-office.net/wp-content/themes/realty/assets/css/chosen-sprite.png) 100% -20px no-repeat;font-size:1em;font-family:sans-serif;line-height:normal;border-radius:0;}
.chosen-container-single .chosen-drop{margin-top:-1px;border-radius:0 0 4px 4px;background-clip:padding-box;}
.chosen-container .chosen-results{position:relative;overflow-x:hidden;overflow-y:auto;margin:0 4px 4px 0;padding:0 0 0 4px;max-height:240px;-webkit-overflow-scrolling:touch;}
@media only screen and (-webkit-min-device-pixel-ratio:2),only screen and (-webkit-min-device-pixel-ratio:1.5),only screen and (min-resolution:144dpi){
.chosen-container-single .chosen-search input[type=text],.chosen-container-single .chosen-single div b{background-image:url(premium-office.net/wp-content/themes/realty/assets/css/chosen-sprite@2x.png)!important;background-size:52px 37px!important;background-repeat:no-repeat!important;}
}
body{margin:0;}
article,header,nav,section{display:block;}
a{background-color:transparent;}
a:active,a:hover{outline-width:0;}
b{font-weight:inherit;}
b{font-weight:bolder;}
img{border-style:none;}
input,select{font:inherit;}
input,select{overflow:visible;}
input,select{margin:0;}
select{text-transform:none;}
[type="submit"]{cursor:pointer;}
[type="submit"]{-webkit-appearance:button;}
input::-moz-focus-inner{border:0;padding:0;}
input:-moz-focusring{outline:1px dotted ButtonText;}
[type="search"]{-webkit-appearance:textfield;}
.container{margin-right:auto;margin-left:auto;padding-left:15px;padding-right:15px;}
.container:before,.container:after{content:" ";display:table;}
.container:after{clear:both;}
@media (min-width:768px){
.container{width:750px;}
}
@media (min-width:992px){
.container{width:970px;}
}
@media (min-width:1200px){
.container{width:1170px;}
}
.row{margin-left:-15px;margin-right:-15px;}
.row:before,.row:after{content:" ";display:table;}
.row:after{clear:both;}
.col-sm-3,.col-md-3,.col-sm-4,.col-xs-12,.col-sm-12{position:relative;min-height:1px;padding-left:15px;padding-right:15px;}
.col-xs-12{float:left;}
.col-xs-12{width:100%;}
@media (min-width:768px){
.col-sm-3,.col-sm-4,.col-sm-12{float:left;}
.col-sm-3{width:25%;}
.col-sm-4{width:33.33333%;}
.col-sm-12{width:100%;}
}
@media (min-width:992px){
.col-md-3{float:left;}
.col-md-3{width:25%;}
}
body{position:relative;font-family:"Helvetica Neue",Helvetica,Arial,sans-serif;font-size:16px;line-height:1.7;color:#787878;-webkit-font-smoothing:antialiased;-webkit-text-size-adjust:100%;word-wrap:break-word;transition:all .5s;}
*,:after,:before{box-sizing:border-box;}
.clearfix:before,.clearfix:after{content:" ";display:table;}
.clearfix:after{clear:both;}
.hide{display:none!important;}
.border-box{box-shadow:0 1px 2px 1px rgba(0,0,0,.15);transition:all .2s;}
.border-box:hover{box-shadow:0 1px 2px 1px rgba(0,0,0,.3);}
a{outline:0;color:#43becc;text-decoration:none;transition:all .2s;}
a:hover,a:focus{color:#787878;text-decoration:none;}
a[href^=tel]{color:inherit;text-decoration:none;}
a i{transition:all 0s;}
i{display:inline-block;font-style:normal;transition:all .2s;}
img{max-width:100%;height:auto;transition:all .3s;}
section{margin-bottom:30px;}
h4{margin:0 0 1em;font-family:Lato,sans-serif;font-weight:400;line-height:1.3;text-rendering:optimizeLegibility;text-shadow:none;-webkit-font-smoothing:antialiased;}
h4{font-size:1.4em;}
article h4{margin-top:1em;}
p{margin:0 0 1em;}
p:empty{margin:0;}
ul{margin:0 0 1em;padding-left:20px;list-style-type:square;line-height:2;}
.form-group{margin-bottom:15px;}
select:not(.attachment-filters){position:relative;}
select:not(.attachment-filters).form-control{-webkit-appearance:none;-moz-appearance:none;appearance:none;box-shadow:none;}
select:not(.attachment-filters)::after{font-family:FontAwesome;content:"\F107";position:absolute;right:14px;top:14px;line-height:1;}
input,input[type=submit],input:focus,.chosen-container.chosen-container-single .chosen-single,select:not(.attachment-filters){outline:0;width:100%;height:40px;line-height:40px;padding:0 1em;font-size:1em;color:#787878;border-radius:4px;border:1px solid #e6e6e6;-webkit-appearance:none;box-shadow:none;transition:all .2s;}
input[type=submit]{width:auto;color:#fff;border-color:transparent!important;}
input:hover,.form-control:hover{border-color:#e6e6e6;}
input:active,input:focus,.form-control:active,.form-control:focus{border-color:#43becc;}
::-webkit-input-placeholder{color:#bbb!important;}
:-moz-placeholder{color:#bbb!important;}
::-moz-placeholder{color:#bbb!important;}
:-ms-input-placeholder{color:#bbb!important;}
#header{position:relative;z-index:4001;box-shadow:0 2px 4px 0 rgba(0,0,0,.1);background-color:#fff;}
#header .container{position:relative;}
.site-branding{position:relative;padding:20px 0;}
@media (min-width:992px){
.site-branding{padding:30px 0;}
}
.site-title{margin-bottom:0;font-size:2em;font-weight:700;line-height:1;}
.site-title a{color:#42484b;}
.top-header{line-height:40px;color:#999;background-color:#f8f8f8;background-color:rgba(0,0,0,.05);transition:all .3s;}
@media (min-width:992px){
.top-header{line-height:50px;font-size:.9em;}
}
.top-header a{color:#999;}
.top-header-sidebar{text-align:center;}
@media (min-width:992px){
.top-header-sidebar{float:left;text-align:inherit;}
}
.top-header-links{text-align:center;margin:0 -.5em;}
.top-header-links a{display:inline-block;margin:0 .5em;}
@media (min-width:992px){
.top-header-links{float:right;}
}
#toggle-navigation{display:block;position:absolute;z-index:9002;top:30px;right:0;width:24px;height:30px;transition:none;}
#toggle-navigation i{position:absolute;top:8px;width:24px;height:2px;background-color:#999;cursor:pointer;transition:all .2s;}
@media (min-width:992px){
#toggle-navigation i{display:none;}
}
#toggle-navigation i:before{position:absolute;content:"";top:-8px;width:24px;height:2px;background-color:#999;transition:all .2s;}
#toggle-navigation i:after{position:absolute;content:"";top:8px;width:24px;height:2px;background-color:#999;transition:all .2s;}
#content{margin-top:60px;margin-bottom:120px;}
.btn,.btn:focus,input[type="submit"]{display:inline-block;outline:0!important;padding:0 1em;font-size:1em;line-height:40px;color:#fff;border:0;border-radius:4px;background-color:#39424a;transition:all .2s;}
.btn:hover,.btn:focus:hover,input[type="submit"]:hover{background-color:#39424a;}
.btn-primary,.btn-primary:focus,input[type="submit"]{color:#fff;background-color:#43becc;}
.btn-primary:hover,.btn-primary:focus:hover,input[type="submit"]:hover{color:#fff;background-color:#2e353b;}
.btn-block{display:block;width:100%;}
.property-search-form{position:relative;z-index:2;margin-bottom:50px;padding:30px 30px 10px;font-size:.9em;border-radius:4px;background-color:#fff;}
.property-search-form input:not([type=submit]),.property-search-form select,.property-search-form select:focus,.property-search-form .chosen-container{background-color:#fff;}
.property-search-form input[type="submit"]{width:100%;}
.vc_row.container{margin-left:auto;margin-right:auto;}
.vc_row.full-width{margin:0;}
.vc_row.full-width .vc_column-inner{padding:0;}
.chosen-container{font-size:1em;border-radius:4px;}
.chosen-container.chosen-container-single{position:relative;}
.chosen-container.chosen-container-single .chosen-single{display:block;background:inherit;}
.chosen-container.chosen-container-single .chosen-single div b{display:none;}
.chosen-container.chosen-container-single .chosen-search{padding:5px 10px;}
.chosen-container.chosen-container-single .chosen-search input[type="text"]{border-color:#e6e6e6;}
.chosen-container.chosen-container-single::after{font-family:'realty';content:"\75";position:absolute;top:0;right:15px;font-size:.6em;line-height:40px;transform:rotate(90deg);}
.chosen-container.chosen-container-single:hover{cursor:pointer;border-color:#e6e6e6;}
.chosen-container .chosen-drop{border-color:#43becc;border-radius:0;}
.chosen-container .chosen-results{margin:5px 0;padding:0 10px!important;font-size:.9em;}
body{font-size:14px;font-style:normal;font-weight:400;background-color:#fff;line-height:1.4;-webkit-font-smoothing:subpixel-antialiased;}
body.en{font-family:"proxima-nova","Helvetica Neue",Arial,Helvetica,sans-serif;}
body.en #header{font-family:"proxima-nova","Helvetica Neue",Arial,Helvetica,sans-serif;}
body.en .top-header-links{text-transform:uppercase;}
#header{background-color:transparent!important;}
.top-header{background-color:transparent;}
body.home .viewwrap{padding-top:0;}
*{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;}
*:before,*:after{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;}
input,input[type=submit],input:focus,.chosen-container.chosen-container-single .chosen-single,select:not(.attachment-filters){border-radius:0;}
.calling-info{float:right;height:65px;min-width:16.5em;vertical-align:middle;font-size:1em;display:table;}
.calling-info .calling-content{display:table-cell;vertical-align:middle;}
.calling-info .calling-desc{color:#444;padding:0;font-size:1em;line-height:1.5em;font-weight:normal;font-family:"DidotW01-Italic",Hoefler Text,Garamond,Times New Roman,serif;}
body.en .calling-info .calling-desc .jat{font-size:1em;}
.calling-info .calling-desc .jat{font-size:.8em;padding-right:5px;}
.calling-info .calling-desc span a{color:#000;font-size:2em;font-family:"DidotW01-Italic",Hoefler Text,Garamond,Times New Roman,serif;letter-spacing:2px;}
#header .calling-info .calling-desc span a{font-size:1.45em;}
#header .calling-info{float:left;padding:0 40px;min-width:initial;}
.top-header-sidebar.lang-switch{line-height:30px;}
.top-header a:hover,.top-header a:focus,.top-header .lang-item a:hover,.top-header .lang-item a:focus{color:#000;}
.main-navigation{z-index:4000;}
nav#navigation .primary-menu a{border-bottom:0 solid transparent;padding:0 15px;line-height:inherit!important;font-family:a-otf-ryumin-pr6n,"Roboto Slab",Garamond,"Times New Roman",游明朝,"Yu Mincho",游明朝体,YuMincho,"ヒラギノ明朝 Pro W3","Hiragino Mincho Pro",HiraMinProN-W3,HGS明朝E,"ＭＳ Ｐ明朝","MS PMincho",serif;position:relative;height:100%;}
nav#navigation .primary-menu a:before{content:"";display:inline-block;vertical-align:middle;height:100%;}
body.en nav#navigation .primary-menu a{font-family:Didot,"DidotW01-Italic","Didot LT STD","Hoefler Text",Garamond,"Times New Roman",serif;font-size:1.2em;font-style:italic;}
@media all and (-ms-high-contrast:none){
body.en nav#navigation .primary-menu a{font-family:"adobe-caslon-pro",Times,serif;}
}
@media all and (-ms-high-contrast:none){
body.en nav#navigation .primary-menu a{font-family:"adobe-caslon-pro",Times,serif;}
}
body.en nav#navigation .primary-menu a{font-family:"adobe-caslon-pro",Times,serif;}
nav#navigation .primary-menu a:hover{border-bottom:2px solid #000;}
nav#navigation .primary-menu a span{line-height:28px;display:inline-block;vertical-align:middle;}
.site-title{position:relative;}
.site-branding:before,.site-branding:after{content:" ";display:table;}
.site-branding:after{clear:both;}
.full-width>#slidersec>.wpb_column>.vc_column-inner>.wpb_wrapper>.container{width:100%;margin:0;}
.crellyslider,.crellyslider>.cs-slides>.cs-slide{height:100%!important;}
.cs-slide>.sword-wrap.container{top:initial!important;left:initial!important;height:100%;position:relative!important;}
#content .wpb_text_column .crellyslider>.cs-navigation>.cs-slide-link,#content .wpb_text_column .crellyslider>.cs-navigation>.cs-slide-link:last-child{margin:6px;}
.cs-slide.active-slide>.sword-wrap.container{display:block!important;}
.crellyslider .non-top{top:initial!important;}
.crellyslider .non-left{left:initial!important;}
.crellyslider .sl02{top:22%!important;}
.crellyslider .sl04{top:22%!important;}
@media (min-width:1200px){
.crellyslider .sl03{left:-20px!important;}
}
.sword-wrap .sword{position:absolute;}
.sword.center-cap{width:100%;text-align:center;left:0!important;}
.sword.non-ls{letter-spacing:0!important;font-size:30px!important;}
.sword.little-ls{letter-spacing:2px!important;}
.adobe-calson{font-family:"adobe-caslon-pro",Didot,"DidotW01-Italic","Didot LT STD","Hoefler Text",Garamond,"Times New Roman",serif;}
.ja-txt{font-size:26px;font-family:"Roboto Slab",Garamond,"Times New Roman",游明朝,"Yu Mincho",游明朝体,YuMincho,"ヒラギノ明朝 Pro W3","Hiragino Mincho Pro",HiraMinProN-W3,HGS明朝E,"ＭＳ Ｐ明朝","MS PMincho",serif;font-weight:normal;letter-spacing:4px;}
.crellyslider>.cs-controls>.cs-previous,.crellyslider>.cs-controls>.cs-next{display:block;width:60px;height:60px;position:absolute;cursor:pointer;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale;background-color:rgba(0,0,0,.3);box-shadow:0 0 0 rgba(0,0,0,0),0 0 0 rgba(0,0,0,0);background-repeat:no-repeat;background-position:center center;border:0;border-radius:0;}
.crellyslider>.cs-controls>.cs-next,.crellyslider>.cs-controls>.cs-previous{background-image:none;font-family:'arrowicons'!important;speak:none;font-style:normal;font-weight:normal;font-variant:normal;text-transform:none;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale;color:#FFF;font-size:30px;line-height:60px;}
.crellyslider>.cs-controls>.cs-previous:before{content:"\e902";width:60px;text-align:center;display:block;}
.crellyslider>.cs-controls>.cs-next:before{content:"\e903";width:60px;text-align:center;display:block;}
.inline-container>div,.inline-container>nav{background:#FFF;}
@media (min-width:992px){
.top-header{line-height:35px;}
#header .container{position:relative;width:100%;padding:0;}
.top-header-sidebar.lang-switch{float:right;background:#000;}
#header .top-header .lang-item.current-lang a{color:#FFF;}
#header .top-header .lang-item a:hover{color:#FFF;}
#header .top-header-links{float:left;margin:0;}
#header .top-header-links a{margin:0;}
.site-branding{max-width:70px;padding:0 0 0 20px;display:table;}
.top-header{padding:0 20px;}
nav#navigation{float:left;padding-left:4%;}
.primary-menu li{float:left;margin:0;height:65px;line-height:65px;}
#toggle-navigation{display:none;}
}
@media (min-width:768px){
.site-title{width:39px;}
#toggle-navigation{top:20px;}
}
@media (min-width:992px){
.site-title{width:auto;display:table-cell;height:65px;vertical-align:middle;}
body.en nav#navigation .primary-menu a{padding:0 15px;letter-spacing:0;}
}
@media only screen and (max-width:991px){
.calling-info{display:none;}
.main-navigation{padding:0;z-index:9999;}
nav#navigation .primary-menu a,nav#navigation .sp-second-menu a{color:#000;padding:10px;border-bottom:1px solid #eee;text-transform:initial;}
body.en nav#navigation .sp-second-menu a,body.en .ask-staff .btn{font-family:Didot,"DidotW01-Italic","Didot LT STD","Hoefler Text",Garamond,"Times New Roman",serif;}
nav#navigation .sp-second-menu,ul#menu-sp-bottom-menu-en{padding:0;margin:0;list-style:none;}
nav#navigation .sp-second-menu a{display:block;line-height:inherit!important;font-family:a-otf-ryumin-pr6n,"Roboto Slab",Garamond,"Times New Roman",游明朝,"Yu Mincho",游明朝体,YuMincho,"ヒラギノ明朝 Pro W3","Hiragino Mincho Pro",HiraMinProN-W3,HGS明朝E,"ＭＳ Ｐ明朝","MS PMincho",serif;position:relative;}
.ask-staff{padding:10px;}
.ask-staff a{display:block;font-family:a-otf-ryumin-pr6n,"Roboto Slab",Garamond,"Times New Roman",游明朝,"Yu Mincho",游明朝体,YuMincho,"ヒラギノ明朝 Pro W3","Hiragino Mincho Pro",HiraMinProN-W3,HGS明朝E,"ＭＳ Ｐ明朝","MS PMincho",serif;}
.ask-staff .btn{display:block;}
ul#menu-sp-bottom-menu-en{padding:0 10px;text-align:left;}
ul#menu-sp-bottom-menu-en li{display:inline-block;}
ul#menu-sp-bottom-menu-en li:first-child{margin-right:10px;}
ul#menu-sp-bottom-menu-en li a{font-size:12px;}
nav#navigation .primary-menu a span:before{font-family:'howcons'!important;speak:none;font-style:normal;font-weight:normal;font-variant:normal;text-transform:none;line-height:inherit;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale;font-size:1.4em;vertical-align:middle;margin-right:8px;display:inline-block;}
nav#navigation .primary-menu li.menu-search>a>span:before{content:"\e933";}
nav#navigation .primary-menu li.menu-fav a span:before{content:"\e92a";}
nav#navigation .primary-menu li.menu-account a span:before{content:"\e927";}
}
@media only screen and (max-width:767px){
.site-title{width:32px;}
#toggle-navigation{top:20px;}
}
.viewwrap{position:relative;}
@media (min-width:992px){
.viewwrap{padding-top:106px;}
}
@media (max-width:991px){
.viewwrap{padding-top:146px;}
}
@media (max-width:767px){
.viewwrap{padding-top:140px;}
}
header#header{position:absolute;top:0;left:0;width:100%;box-shadow:none;}
.home div#content{margin-top:0;padding-top:0;}
.container.vc_row.wpb_row.vc_inner.vc_row-fluid{margin-left:-15px;margin-right:-15px;}
.bg-ltgray{background:rgba(247,247,247,1);}
div#content{overflow-x:hidden;}
.top-header-sidebar ul{padding:0;margin:0;line-height:inherit;}
.top-header-sidebar ul li{position:relative;display:inline-block;margin:0 .5em;line-height:inherit;vertical-align:middle;}
.top-header-sidebar li.lang-item-en{text-transform:uppercase;}
.top-header-sidebar ul li:first-child:after{position:absolute;content:"|";display:inline-block;right:-.7em;color:#787878;}
.top-header .lang-item.current-lang a{color:#787878;}
.top-header .lang-item a{color:#bbb;}
.link-items{background:#FFF;border:1px solid #eee;}
#links h4{color:#000;margin:0;padding:10px 15px;font-size:14px;font-family:a-otf-ryumin-pr6n,"Roboto Slab",Garamond,"Times New Roman",游明朝,"Yu Mincho",游明朝体,YuMincho,"ヒラギノ明朝 Pro W3","Hiragino Mincho Pro",HiraMinProN-W3,HGS明朝E,"ＭＳ Ｐ明朝","MS PMincho",serif;}
#links{padding:80px 0;}
.row.link-list .col-sm-3{padding:0 5px;}
@media (min-width:992px){
.hidden-pc{display:none;}
}
@media (min-width:768px){
.row.link-list{margin-left:-5px;margin-right:-5px;}
}
@media (max-width:991px){
.sword-wrap>.sword{display:none!important;}
.top-header-sidebar.site-desc{width:100%!important;}
.hidden-sm{display:none;}
.site-title{float:left;}
.site-branding{padding:5px 0;}
#toggle-navigation{top:12px;}
.header-navi-wrap{background:#FFF;}
.top-header-links.primary-tooltips.hidden-pc{float:right;padding-right:40px;vertical-align:middle;margin:0;height:43px;line-height:43px;display:none;}
}
#links .container{padding:0;}
.main-slider{position:relative;height:736px;}
.main-slider>.vc_column-inner>.wpb_wrapper{position:relative;}
.slider-content{position:absolute;top:219px;width:100%;}
.main-slider .crellyslider-slider{display:block!important;}
.main-slider .crellyslider-slider ul li{display:none;}
.main-slider .property-search-form{margin:0;border-radius:0;box-shadow:none;background:none;padding:0;}
.slider-searchbox .search-form-row .form-group{margin:0;padding:0;}
.row.search-form-row{margin:0;}
.slider-searchbox .search-form-row .keyword-div{width:36%;}
.slider-searchbox .search-form-row .form-group.select{width:22%;}
.slider-searchbox .search-form-row .form-group.search-btn-div{width:20%;}
.slider-searchbox input,.slider-searchbox .chosen-container.chosen-container-single .chosen-single{height:58px;line-height:58px;}
.slider-searchbox .chosen-container.chosen-container-single::after{line-height:58px;}
.entry-content .container{padding:0;}
.property-search-form input[type="submit"]{font-weight:bold;font-size:14px;}
.chosen-container.chosen-container-single .chosen-single{font-size:14px;color:#787878;}
@media (min-width:768px){
#toggle-navigation{top:14px;}
.slider-content .search-col .slider-searchbox{max-width:980px;margin:0 auto;}
}
.btn.btn-square{border-radius:0;}
#hometoplinks #links{background:none;padding:20px 0;}
#hometoplinks .wpb_content_element.wpb_raw_html{margin:0;}
.thum-links{height:120px;overflow:hidden;}
.sword-wrap{text-transform:initial;}
@media (min-width:992px){
body.en nav#navigation .primary-menu a span{font-size:20px;}
.site-desc{min-width:760px;}
.slider-searchbox input,.slider-searchbox .chosen-container.chosen-container-single .chosen-single{height:38px;line-height:38px;}
.slider-searchbox .chosen-container.chosen-container-single::after{line-height:38px;}
}
body.page-template-default div#content{margin-bottom:0;}
@media only screen and (max-width:991px) and (min-width:768px){
#links h4{padding:10px 10px;font-size:13px;}
}
@media (max-width:991px){
#slidersec>.vc_column_container>.vc_column-inner{padding:0;}
#slidersec .container.vc_row.wpb_row.vc_inner.vc_row-fluid{margin:0;padding:0;}
.main-slider.vc_column_container>.vc_column-inner{padding:0;}
#links{padding:80px 30px;}
.container{width:100%;}
.entry-content .container{padding:0 15px;}
.vc_row{margin-left:0;margin-right:0;}
.vc_column_container>.vc_column-inner{box-sizing:border-box;padding-left:0;padding-right:0;width:100%;}
.flex-cell .hidden-xs{display:none;}
.flex-column{-webkit-flex-direction:column;-ms-flex-direction:column;flex-direction:column;display:-webkit-flex;display:-ms-flexbox;display:flex;margin:0;padding:0;list-style:none;-webkit-flex-wrap:nowrap;-ms-flex-wrap:nowrap;flex-wrap:nowrap;}
.fill-height{height:100%;}
.flex-cell{position:relative;min-width:0;-webkit-flex:1;-ms-flex:1;flex:1;}
.flex-cell-auto{position:relative;min-width:0;-webkit-flex:0 1 auto;-ms-flex:0 1 auto;flex:0 1 auto;}
.side-panel-top-row{overflow-x:hidden;overflow-y:auto;height:100%;-webkit-overflow-scrolling:touch;}
.row.search-form-row{display:table;width:100%;margin:0;}
}
.single-search{background:rgba(245,245,245,1);border-bottom:1px solid #eee;}
.top-header-links.primary-tooltips a{vertical-align:top;}
#header .top-header .lang-item.current-lang a{color:#FFF;}
@media (max-width:767px){
.top-header-sidebar ul{position:relative;}
.top-header-sidebar.lang-switch ul li{display:block;margin:0;}
.top-header-sidebar.lang-switch ul li{position:absolute;right:0;top:40px;width:100%;z-index:9999;display:none;}
.top-header-sidebar.lang-switch ul li.current-lang{position:relative;top:initial;right:initial;display:block;}
.top-header-sidebar.lang-switch ul li a{padding:0 .5em;line-height:inherit;display:block;background:#000;}
.top-header .lang-item.current-lang a:before{content:"\e904";display:inline-block;font-family:'arrowicons'!important;speak:none;font-style:normal;font-weight:normal;font-variant:normal;text-transform:none;padding-right:5px;}
#header>.top-header>.container:before,#header>.top-header>.container:after{display:table;content:'';}
#header>.top-header>.container:after{clear:both;}
#header>.top-header>.container{padding:0;background:#000;}
.top-header-sidebar.site-desc{font-size:12px;text-align:left;display:inline-block;width:auto!important;padding-left:10px;color:#bbb;}
.top-header-sidebar.lang-switch{line-height:40px;display:inline-block;float:right;border-left:1px solid #4a4a4a;}
#links h4{font-size:12px;}
.top-header-sidebar{font-size:90%;}
.slider-searchbox .search-form-row .form-group,.slider-searchbox .search-form-row .form-group.search-btn-div{width:100%;}
.slider-searchbox .search-form-row .form-group.select{width:50%;}
.entry-content{overflow-x:hidden;}
.page.type-page .entry-content .container{padding:0 15px;}
.hidden-xs{display:none;}
body.home section .vc_row.boxed{padding:0 35px;}
.home div#content{overflow-x:hidden;}
.main-slider{height:566px;}
.main-slider>div,.main-slider>div>div{height:100%;}
}
@media (max-width:767px){
.slider-content{width:100%;}
.row.link-list .col-sm-3{padding:0;margin-bottom:10px;}
#links{padding:80px 15px;border-top:1px solid rgba(0,0,0,0.1);}
.row.link-list{margin-left:0;margin-right:0;}
.top-header-links.primary-tooltips.hidden-pc{height:36px;line-height:36px;}
}
@media (max-width:767px) and (min-width:481px){
.row.link-list{margin-left:-5px;margin-right:-5px;}
.row.link-list .col-sm-3{width:50%;float:left;padding:0 5px;}
}
@media (max-width:480px){
body.home section .vc_row.boxed{padding:0 10px;}
}
@media (max-width:345px){
.top-header-sidebar{font-size:12px;}
}
.vc_clearfix:after,.vc_column-inner::after,.vc_row:after{clear:both;}
.vc_row:after,.vc_row:before{content:" ";display:table;}
.vc_column_container{width:100%;}
.vc_row{margin-left:-15px;margin-right:-15px;}
.vc_col-sm-12{position:relative;min-height:1px;padding-left:15px;padding-right:15px;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;}
@media (min-width:768px){
.vc_col-sm-12{float:left;}
}
.vc_clearfix:after,.vc_clearfix:before{content:" ";display:table;}
#content .wpb_text_column :last-child,.wpb_text_column :last-child{margin-bottom:0;}
.wpb_content_element{margin-bottom:35px;}
.vc_column-inner::after,.vc_column-inner::before{content:" ";display:table;}
.vc_column_container{padding-left:0;padding-right:0;}
.vc_column_container>.vc_column-inner{box-sizing:border-box;padding-left:15px;padding-right:15px;width:100%;}
.vc_section{padding-left:15px;padding-right:15px;margin-left:-15px;margin-right:-15px;}
.vc_section[data-vc-full-width]{-webkit-transition:opacity .5s ease;-o-transition:opacity .5s ease;transition:opacity .5s ease;overflow:hidden;}
.top-header,.top-header a,.site-branding,.site-title a,.primary-menu a{color:#000;}
#header{font-family:"Source Sans Pro";opacity:1;visibility:visible;-webkit-transition:opacity .24s ease-in-out;-moz-transition:opacity .24s ease-in-out;transition:opacity .24s ease-in-out;}
.btn-primary,.btn-primary:focus,input[type='submit']{background-color:#000;}
.top-header a{color:#787878;}
html{height:100%;font-size:62.5%;margin:0!important;}
.top-header-sidebar{text-align:center;}
@media (min-width:992px){
.top-header-sidebar{float:left;text-align:inherit;}
}
@media (min-width:992px){
.top-header-links{float:right;}
}
.main-navigation{opacity:1;min-width:270px;max-width:80%;position:fixed;z-index:9002;top:0;bottom:0;left:0;padding:30px 60px;text-transform:uppercase;background-color:#fff;transition:transform .2s ease-in;transform:translateX(-100%);-webkit-transform:translateX(-100%);-moz-transform:translateX(-100%);-o-transform:translateX(-100%);-ms-transform:translateX(-100%);}
.primary-menu{padding:0;list-style-type:none;}
.primary-menu li{position:relative;}
.primary-menu a{display:block;color:#42484b;}
.primary-menu a:hover{color:#42484b;}
@media (max-width:991px){
.primary-menu a{position:relative;line-height:50px!important;}
.primary-menu a:before{transform:rotate(0) scale(1,2)!important;}
.primary-menu li:hover{color:#42484b;}
.primary-menu li:hover:before{opacity:1;transform:translateX(10px);}
}
.mobile-menu-overlay{position:fixed;z-index:-1;top:0;right:0;bottom:0;left:0;background-color:#000;opacity:0;transition:all .2s;}
@media (min-width:992px){
.mobile-menu-overlay{display:none;}
}
@media (min-width:992px){
.site-branding{float:left;}
.main-navigation{min-width:0;max-width:none;float:right;position:relative;padding:0;transform:none;text-transform:none;background-color:transparent;transition:all 0s;transform:translateX(0);-webkit-transform:translateX(0);-moz-transform:translateX(0);-o-transform:translateX(0);-ms-transform:translateX(0);}
.primary-menu{margin:0 -15px;}
.primary-menu li {float: left;margin: 0;height: 65px;line-height: 65px;}
}
html{font-size:10px;-webkit-tap-highlight-color:rgba(0,0,0,0);}
body{font-size:14px;font-style:normal;font-weight:400;background-color:#fff;line-height:1.4;-webkit-font-smoothing:subpixel-antialiased;}
body.en{font-family:"proxima-nova","Helvetica Neue",Arial,Helvetica,sans-serif;}
body.en #header{font-family:"proxima-nova","Helvetica Neue",Arial,Helvetica,sans-serif;}
body.en .top-header-links{text-transform:uppercase;}
.top-header{background-color:transparent;}
body.home .viewwrap{padding-top:0;}
.sticky .main-navigation{z-index:9999;}
header#header.sticky{position:fixed;width:100%;top:0;left:0;}
.sticky .top-header{display:none;}
.entry-content p:last-child{margin-bottom:0;}
input[type="submit"]{display:inline-block;outline:0!important;padding:0 1em;font-size:1em;line-height:40px;color:#fff;border:0;border-radius:4px;background-color:#39424a;transition:all .2s;}
input[type="submit"]:hover{background-color:#39424a;}
input[type="submit"]{color:#fff;background-color:#43becc;}
input[type="submit"]:hover{color:#fff;background-color:#2e353b;}
.section-title{margin-top:0;}
[class^="topicon-"]{font-family:'topicons'!important;speak:none;font-style:normal;font-weight:normal;font-variant:normal;text-transform:none;line-height:1;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale;}
.topicon-icon-topnav03:before{content:"\e902";}
input,input[type=submit],input:focus{border-radius:0;}
div#content{overflow-x:hidden;}
@media (min-width:1200px){
.container{width:1170px;}
}
.entry-content .container{padding:0;}
.section-title span{line-height:inherit;}
body.page-template-default div#content{margin-bottom:0;}
@media (max-width:991px){
.container{width:100%;}
.entry-content .container{padding:0 15px;}
.vc_row{margin-left:0;margin-right:0;}
.vc_column_container>.vc_column-inner{box-sizing:border-box;padding-left:0;padding-right:0;width:100%;}
}
@media (max-width:767px){
.entry-content{overflow-x:hidden;}
.page.type-page .entry-content .container{padding:0 15px;}
}
.vc_column-inner::after,.vc_row:after{clear:both;}
.vc_row:after,.vc_row:before{content:" ";display:table;}
.vc_column_container{width:100%;}
.vc_row{margin-left:-15px;margin-right:-15px;}
.vc_col-sm-12,.vc_col-sm-6{position:relative;min-height:1px;padding-left:15px;padding-right:15px;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;}
@media (min-width:768px){
.vc_col-sm-12,.vc_col-sm-6{float:left;}
.vc_col-sm-12{width:100%;}
.vc_col-sm-6{width:50%;}
}
#content .wpb_text_column :last-child,#content .wpb_text_column p:last-child,.wpb_text_column :last-child,.wpb_text_column p:last-child{margin-bottom:0;}
.wpb_content_element{margin-bottom:35px;}
.vc_column-inner::after,.vc_column-inner::before{content:" ";display:table;}
.vc_column_container{padding-left:0;padding-right:0;}
.vc_column_container>.vc_column-inner{box-sizing:border-box;padding-left:15px;padding-right:15px;width:100%;}
.section-title span{background-color:#fff;}
label{font-weight:400;color:#999;}
.section-title{overflow:hidden;position:relative;z-index:0;margin-top:1em;margin-bottom:1em;font-weight:300;color:#787878;}
.section-title::after{position:absolute;z-index:-2;top:50%;left:0;margin-top:-1px;margin-left:1em;content:"";height:2px;width:9999px;background-color:#e6e6e6;}
.section-title span{position:relative;display:inline-block;padding-right:1em;line-height:1;}
div.wpcf7 input:not([type="submit"]),div.wpcf7 textarea{width:100%;}
div.wpcf7 label{margin:0;}
div.wpcf7 .wpcf7-textarea{height:153px;}
div.wpcf7 .wpcf7-response-output{margin:0 0 30px;padding:0;font-size:.9em;}
div.wpcf7 .screen-reader-response{position:absolute;overflow:hidden;clip:rect(1px,1px,1px,1px);height:1px;width:1px;margin:0;padding:0;border:0;}
div.wpcf7 .wpcf7-form-control-wrap{display:block!important;margin-bottom:1em;}
div.wpcf7{margin:0;padding:0;}
div.wpcf7-response-output{margin:2em .5em 1em;padding:.2em 1em;}
div.wpcf7 .screen-reader-response{position:absolute;overflow:hidden;clip:rect(1px,1px,1px,1px);height:1px;width:1px;margin:0;padding:0;border:0;}
.wpcf7-form-control-wrap{position:relative;}
.wpcf7-display-none{display:none;}
div.wpcf7 .ajax-loader{visibility:hidden;display:inline-block;background-image:url(/wp-content/plugins/contact-form-7/includes/css/../../images/ajax-loader.gif);width:16px;height:16px;border:0;padding:0;margin:0 0 0 4px;vertical-align:middle;}
[class^="topicon-"]{font-family:'topicons'!important;speak:none;font-style:normal;font-weight:normal;font-variant:normal;text-transform:none;line-height:1;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale;}
.topicon-icon-topnav03:before{content:"\e902";}
*{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;}
*:before,*:after{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;}
input,input[type=submit],input:focus,textarea{border-radius:0;}
.entry-content h1,.entry-content h4{color:#000;}
div#content{overflow-x:hidden;}
@media (min-width:1200px){
.container{width:1170px;}
}
.entry-content .container{padding:0;}
.section-title span{line-height:inherit;}
.send-contact input[type='submit']{width:100%;}
.lb-title{margin-bottom:10px;}
span.req-label{display:inline-block;background:#000;color:#FFF;font-size:.9em;padding:2px 5px;margin-left:5px;border-radius:3px;}
.big-tel-row{line-height:60px;margin-bottom:10px;}
p.tel-note{font-size:1.2em;}
p.tel-note span.open-hour{margin-left:15px;letter-spacing:1px;}
i.bigtelcon{font-size:3em;margin-right:10px;display:inline-block;vertical-align:middle;}
a#big-tel{line-height:inherit;vertical-align:middle;}
a#big-tel{color:#000;font-size:3em;font-family:"DidotW01-Italic",Hoefler Text,Garamond,Times New Roman,serif;letter-spacing:2px;}
textarea{padding:20px;}
body.page-template-default div#content{margin-bottom:0;}
.h4_didot h4.section-title{font-family:"DidotW01-Italic","Didot LT STD","Hoefler Text",Garamond,"Times New Roman",serif;}
body.en .style_a .big-title{margin-bottom:20px;}
.style_a .big-title{font-family:Didot,"DidotW01-Italic","Didot LT STD","Hoefler Text",Garamond,"Times New Roman",serif;text-transform:uppercase;font-size:4em;line-height:.9em;margin:0;padding:50px 0 10px 0;}
@media (max-width:767px){
i.bigtelcon,a#big-tel{font-size:2.7em;}
.style_a .big-title{font-size:3em;padding-top:20px;}
}
.vc_custom_1495395946927{margin-bottom:60px!important;}






#pagination{text-align:center;}
#pagination ul{display:inline-block;}
#pagination .page-numbers{padding:0;list-style:none;text-align:center;}
#pagination .page-numbers li{width:auto!important;float:left;margin:0 2px;line-height:1;clear:none;}
#pagination .page-numbers li a,#pagination .page-numbers li span,#pagination .page-numbers li i{display:inline-block;height:40px;width:40px;line-height:40px;color:#787878;background-color:#f8f8f8;}
#pagination .page-numbers li a:hover,#pagination .page-numbers li span:hover,#pagination .page-numbers li i:hover{color:#fff;background-color:#43becc;}
#pagination .page-numbers li .current,#pagination .page-numbers li .current:hover{color:#999;background-color:transparent;}
#pagination .page-numbers li .next{font-size:.6em;font-weight:700;}
.page-title{font-size:2em;font-weight:300;color:#999;}
.property-items{position:relative;margin-bottom:50px;}
.property-item{position:relative;margin-bottom:30px;background-color:#fbfbfb;}
.property-item .property-thumbnail{position:relative;overflow:hidden;margin:0;}
.property-item a{color:inherit;}
.property-item .property-excerpt{font-size:.9em;}
.property-item img{display:block;max-width:none;width:100%;transition:all .5s;backface-visibility:hidden;}
@media (min-width:768px){
.property-item img{width:calc(100% + 20px)!important;-webkit-filter:grayscale(0%);filter:none;transform:translate3d(-10px,0,0);}
}
.property-item figcaption{top:0;height:100%;width:100%;transition:all .3s;}
@media (min-width:768px){
.property-item figcaption{position:absolute;}
}
@media (min-width:768px){
.property-item:hover img{filter:gray;-webkit-filter:grayscale(100%);transform:translate3d(0,0,0);}
}
.property-item:hover .property-excerpt{opacity:1;}
.property-item:hover .property-excerpt::after{transform:translate3d(0,0,0);}
.property-item .property-title{position:relative;z-index:0;padding:15px;}
.property-item .property-title::after{position:absolute;z-index:-1;opacity:.75;top:0;left:0;content:"";width:100%;height:100%;background-color:#f0f0f0;}
.property-item .property-title .title{margin-bottom:0;font-size:1.3em;color:#787878;}
.property-item .address{font-size:1.2em;}
.property-item .property-excerpt{display:none;position:absolute;top:0;width:100%;height:100%;padding:15px 15px;opacity:0;background-color:rgba(255,255,255,.9);transition:all .3s;}
@media (min-width:768px){
.property-item .property-excerpt{display:block;}
}
.property-item .property-excerpt::after{position:absolute;opacity:.5;top:0;left:0;width:100%;height:3px;background:#43becc;content:'';transition:all .3s;transform:translate3d(-100%,0,0);}
.property-item .property-meta>div{width:50%;}
.property-item .property-price{position:relative;padding:0 15px;color:#787878;text-align:left;border-top:1px solid #eee;}
@media (min-width:768px){
.property-item .property-price{line-height:50px!important;}
}
@media (min-width:992px){
.property-item .property-price{font-size:1.1em;}
}
.property-item .property-price span{font-weight:300;}
.property-item .property-price i{opacity:.3;line-height:inherit;margin-right:10px;}
.property-item .property-price i:hover{opacity:.75;cursor:pointer;}
.property-item .property-price .price-tag{float:left;color:#999;}
.property-item .property-price .property-icons{float:right;}
.property-meta{padding:15px;font-size:.9em;line-height:2;color:#999;text-align:center;}
@media (min-width:768px){
.property-meta{text-align:left;}
}
.property-meta .meta-title{display:inline-block;width:25px;}
.property-meta>div{float:left;}
.property-meta .meta-data{display:inline-block;}
.property-meta .meta-data:hover{cursor:default;}
.add-to-favorites:hover{cursor:pointer;}
.property-item .share-unit{z-index:9999;position:absolute;top:-195px;left:-10px;width:40px;height:40px;line-height:40px;}
.property-item .share-unit::after{border-left:8px solid transparent;border-right:8px solid transparent;border-top:8px solid #c91a22;content:" ";height:0;position:absolute;right:12px;width:0;}
.property-item .share-unit a{color:#fff;display:block;text-align:center;}
.property-item .share-unit .social-facebook{background-color:#3b5998;}
.property-item .share-unit .social-twitter{background-color:#4cc2ff;}
.property-item .share-unit .social-google{background-color:#d23e2b;}
.property-item .share-unit .social-pinterest{background-color:#c91a22;}
.property-item .share-unit i{display:block;margin:0;padding:0;opacity:1;}
.icheckbox_square{display:inline-block;*display:inline;vertical-align:middle;margin:0;padding:0;width:22px;height:22px;background:url(/wp-content/themes/realty-child/js/icheck/skins/square/square.png) no-repeat;border:0;cursor:pointer;}
.icheckbox_square{background-position:0 0;}
@media (-o-min-device-pixel-ratio:5/4),(-webkit-min-device-pixel-ratio:1.25),(min-resolution:120dpi),(min-resolution:1.25dppx){
.icheckbox_square{background-image:url(/wp-content/themes/realty-child/js/icheck/skins/square/square@2x.png);-webkit-background-size:240px 24px;background-size:240px 24px;}
}

.property-items{opacity:1!important;}
@media (min-width:992px){
.property-meta.custom-meta-list{min-height:50px;}
}
i.add-to-contact.origin.fa{font-family:'howcons'!important;speak:none;font-style:normal;font-weight:normal;font-variant:normal;text-transform:none;line-height:inherit;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale;font-size:1.5em;}
i.add-to-contact.origin.fa.howcons-clipboard-add2:before{content:"\e906";}
.property-item .property-price .property-icons i.add-to-contact{font-size:1.5em;}
.property-item .property-price .property-icons i:last-child{margin-right:0;}
.property-item .property-price .property-icons>span{display:inline-block;}
.property-item.border-box{box-shadow:none;border:1px solid #eee;}
.property-item.border-box .property-content{background:#FFF;}
.property-search-form input[type="submit"]{font-weight:bold;font-size:14px;}
.chosen-container.chosen-container-single .chosen-single{font-size:14px;color:#787878;}
.property-item .property-title .title{font-size:1.2em;font-family:a-otf-ryumin-pr6n,"Roboto Slab",Garamond,"Times New Roman",游明朝,"Yu Mincho",游明朝体,YuMincho,"ヒラギノ明朝 Pro W3","Hiragino Mincho Pro",HiraMinProN-W3,HGS明朝E,"ＭＳ Ｐ明朝","MS PMincho",serif;}
body.en .property-item .property-title .title{font-family:"adobe-caslon-pro",Times,serif;}
.property-item .property-title h3.title{min-height:42px;}
#viewed_property_box .property-item .property-thumbnail{max-height:120px;}
.widget_view_listing_image{position:relative;float:left;width:40%;background:none;overflow:hidden;border-radius:3px;}
.listing_name_right{display:inline;margin-left:0;float:left;line-height:20px;position:relative;width:60%;padding-left:13px;}
#viewed_property_box .property-item.border-box{background:none;border:0;}
#viewed_property_box .property-item.border-box .bestview-name{padding:0;}
#viewed_property_box .property-item.border-box .bestview-name h3.title{min-height:1px;margin-bottom:10px;position:relative;padding-left:25px;}
div#viewed_property_box>ul>li{border-bottom:1px solid #eee;margin-bottom:20px;}
div#viewed_property_box>ul>li h3:before{content:"";position:absolute;left:0;top:0;color:#FFF;width:18px;height:18px;line-height:18px;text-align:center;z-index:2;}
div#viewed_property_box>ul>li h3:after{content:"";position:absolute;left:0;top:0;background:#000;width:18px;height:18px;-webkit-transform:rotate(-45deg);-moz-transform:rotate(-45deg);-ms-transform:rotate(-45deg);-o-transform:rotate(-45deg);transform:rotate(-45deg);}
div#viewed_property_box>ul>li:nth-child(1) h3:before{content:"1";display:inline-block;}
div#viewed_property_box>ul>li:nth-child(2) h3:before{content:"2";display:inline-block;}
div#viewed_property_box>ul>li:nth-child(3) h3:before{content:"3";display:inline-block;}
body.en h2.page-title{color:#000;font-family:Didot,"DidotW01-Italic","Didot LT STD","Hoefler Text",Garamond,"Times New Roman",serif;}
@media (max-width:1199px) and (min-width:768px){
#property-search-results .property-item .property-price .price-tag,#property-search-results .property-item .property-price .property-icons{float:none;}
}
.search-result-container .page-title,.col-md-4.search-container h2{font-family:Didot,"DidotW01-Italic","Didot LT STD","Hoefler Text",Garamond,"Times New Roman",serif;color:#000;}
.property-excerpt .address{background:#000;color:#FFF;display:inline-block;padding:3px 5px;line-height:1;font-size:1em;}
.property-item .property-meta.custom-meta-list>div{width:auto;margin-right:10px;}
body.en .property-item .property-meta.custom-meta-list>div{float:none;width:100%;}
.property-item .property-meta.custom-meta-list>div:last-child{margin-right:0;}
.property-meta.custom-meta-list .meta-title{display:inline-block;width:12px;}
.property-item .property-title{position:relative;z-index:0;padding:10px 15px 0;}
.property-meta.custom-meta-list{padding:0 15px;color:#000;text-align:left;}
.property-item .property-price .price-tag{color:#000;}
.property-item .property-price i{color:#393939;}
.property-item .property-price .property-icons{font-size:1em;}
.property-item .property-price .property-icons i{font-size:inherit;display:inline-block;}
.property-item .property-price .property-icons>span,.property-item .property-price .property-icons i{vertical-align:middle;margin-right:5px;}
.property-item .property-price .property-icons i:last-child{margin-right:0;}
.property-item .property-price i:hover{color:#000;}
.check-field label{padding-left:10px;line-height:22px;display:inline-block;vertical-align:middle;}
.property-search-form .check-field{margin-bottom:10px;}
#viewed_property_box{z-index:1;}
@media only screen and (max-width:991px) and (min-width:768px){
#viewed_property_box .property-item .property-meta.custom-meta-list>div{display:block;float:none;margin:0;}
div#viewed_property_box>ul>li{border-bottom:0;margin-bottom:0;}
#viewed_property_box .widget_view_listing_image{width:35%;}
#viewed_property_box .listing_name_right{width:65%;padding-left:10px;}
#viewed_property_box .property-item .property-price .price-tag{float:none;}
#viewed_property_box .property-item .property-price .property-icons{float:none;}
}
@media (max-width:767px){
.property-item .property-title h3.title{min-height:initial;}
.property-item .property-price .price-tag{line-height:42px;}
}
@media only screen and (max-width:767px) and (min-width:500px){
#viewed_property_box .widget_view_listing_image{width:100%;}
#viewed_property_box .listing_name_right{display:block;width:100%;padding-left:0;}
#viewed_property_box .property-meta.custom-meta-list{padding:0;text-align:left;}
#viewed_property_box .property-item .property-meta.custom-meta-list>div{display:block;float:none;margin:0;}
#viewed_property_box .property-item .property-price{padding:0;}
#viewed_property_box .property-item .property-price .price-tag{float:none;}
#viewed_property_box .property-item .property-price .property-icons{float:none;}
}
@media only screen and (max-width:499px){
.col-ss-12{width:100%;}
}
@media (max-width:480px){
.property-item .property-price .price-tag,.property-item .property-price .property-icons{display:block;float:none;}
.property-item .property-price .property-icons>span,.property-item .property-price .property-icons i{margin-right:10px;}
.property-item .property-price .property-icons i:last-child{margin-right:0;}
}
body.page-template-page-lgray {
    background: #fafafa;
}

@media (min-width:992px){
.top-header-links{float:right;}
}
.top-header-links.primary-tooltips a{vertical-align:top;}
.top-header-links .desktop{display:none;}
.howcons-mail-checked2:before{content:"\e902";}
.jktCD a,.jktCD li,.jktCD ul,.jktCD span,.jktCD div{margin:0;padding:0;}
.jktCD{display:inline;}
.jktCD-click{cursor:pointer;display:inline;color:#66a;font-size:14px;}
.jktCD-click:hover{color:orange;}
.jktCD-main{clear:both;position:absolute;z-index:1500;background-color:#fff;padding:5px 0!important;display:none;}
.jktCD-main:after{content:'';display:block;width:0;height:0;position:absolute;border-left:10px solid transparent;border-right:10px solid transparent;border-bottom:10px solid #ccc;margin-left:-10px;}
.jktCD-main li>a,.jktCD-main li{text-align:left;color:#333;text-decoration:none;display:-moz-inline-stack;display:inline-block;zoom:1;*display:inline;margin:0;float:none!important;}
.jktCD-main li:hover>a{display:block;color:orange;}
.fa{display:inline-block;}
.fa{font:normal normal normal 14px/1 FontAwesome;font-size:inherit;text-rendering:auto;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale;}
.fa-star:before{content:"\f005";}
.fa-sign-out:before{content:"\f08b";}
.top-header-links .jktCD-click{color:#000;text-decoration:underline;text-transform:initial;}
.top-header-links .jktCD-main{-webkit-box-shadow:3px 3px 15px 0 rgba(0,0,0,0.75);-moz-box-shadow:3px 3px 15px 0 rgba(0,0,0,0.75);box-shadow:3px 3px 15px 0 rgba(0,0,0,0.75);}
.jktCD-style-one:after{left:20%;top:-10px;border-bottom:10px solid #FFF;}
.top-header-links .jktCD-main li{width:180px;}
.top-header-links .jktCD-main li>a{padding:5px 10px;display:block;}
.top-header-links .jktCD-main li>a:hover,.top-header-links .jktCD-main li>a:focus{color:#FFF;background:#000;}
.top-header a:hover,.top-header a:focus{color:#000;}
.contact-list-header{display:none!important;}
.top-header-links .jktCD-click:after{content:"\e904";display:inline-block;font-family:'arrowicons'!important;speak:none;font-style:normal;font-weight:normal;font-variant:normal;text-transform:none;padding-left:4px;}
.top-header-links.primary-tooltips a span i{margin-right:5px;font-size:1.1em;}
.top-header-links.primary-tooltips a.contact-list-header span i{font-size:1.4em;vertical-align:middle;}
@media (max-width:991px){
.hidden-sm{display:none;}
.top-header-links .jktCD-main{right:0;}
.jktCD-style-one:after{left:85%;}
.top-header-links.primary-tooltips a span i{font-size:24px;display:block;}
.top-header-links.primary-tooltips a.contact-list-header span i{font-size:30px;vertical-align:top;line-height:1;display:inline-block;}
.top-header-links span.mobile{display:block;}
}
.top-header-links.primary-tooltips a{vertical-align:top;}
@media (max-width:767px){
.hidden-xs,.top-header-links a.hidden-xs{display:none;}
}
/*! CSS Used from: https://use.fontawesome.com/d543855e1a.css */
/*! @import //use.fontawesome.com/releases/v4.7.0/css/font-awesome-css.min.css */
.fa-user-circle:before{content:"\f2bd";}
/*! CSS Used fontfaces */
#page-user-profile ul.widget-user-menu {
    display: none;
}
#registerform ul, li.wppb-form-field {
    list-style-type: none;
    padding-left: 0;
}
.breadcrumbs {
    font-size: 12px;
    line-height: 40px;
    background: #fafafa;
}

.top-header a, .breadcrumbs a {
    color: #787878;
}

.btn-primary, .btn-primary:focus, input[type='submit'], .acf-button.blue, .property-item .property-excerpt::after, .property-item.featured .property-title::after, #pagination .page-numbers li a:hover, #pagination .page-numbers li span:hover, #pagination .page-numbers li i:hover, #page-banner .banner-title:after, .map-wrapper .map-controls .control.active, .map-wrapper .map-controls .control:hover, .datepicker table tr td.active.active, .datepicker table tr td.active:hover.active, .noUi-connect {
    background-color: #000;
}

.entry-header .header-content a {
    color: #42484b;
}
.news .entry-title a {
    color: #000;
}
.has-post-thumbnail .entry-header .entry-title {
    color: #fff;
}

.entry-header .entry-title {
    margin: 0;
    display: inline-block;
    font-size: 2em;
}

.news .entry-title {
    line-height: 1.4;
    margin-bottom: 15px;
    font-size: 1.6em;
    font-weight: 500;
    font-family: "heisei-mincho-stdn","Roboto Slab",Garamond,"Times New Roman",游明朝,"Yu Mincho",游明朝体,YuMincho,"ヒラギノ明朝 Pro W3","Hiragino Mincho Pro",HiraMinProN-W3,HGS明朝E,"ＭＳ Ｐ明朝","MS PMincho",serif;
    font-family: "Roboto Slab",Garamond,"Times New Roman",游明朝,"Yu Mincho",游明朝体,YuMincho,"ヒラギノ明朝 Pro W3","Hiragino Mincho Pro",HiraMinProN-W3,HGS明朝E,"ＭＳ Ｐ明朝","MS PMincho",serif;
}
</style>