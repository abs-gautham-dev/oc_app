/*!
 * ParsleyJS-LaraExtras.js
 * Version 0.4.2 - built Sun, Jun 19th 2016, 3:00 pm
 * hhttps://github.com/happyDemon/ParsleyJS-LaraExtras
 * Maxim Kerstens - <maxim.kerstens@gmail.com>
 * MIT Licensed
 */
!function(e,r){"object"==typeof exports&&"undefined"!=typeof module?module.exports=r(require("jquery"),require("moment")):"function"==typeof define&&define.amd?define(["jquery","moment"],r):e.laraParsley=r(e.jQuery,e.moment)}(this,function(e,r){"use strict";window.Parsley.addValidator("in",{requirementType:"string",validateString:function(e,r){var t=r.split(",");return t.indexOf(e)>-1},messages:{en:'The value should be one of the following: "%s".'}}),window.Parsley.addValidator("notIn",{requirementType:"string",validateString:function(e,r){var t=r.split(",");return-1==t.indexOf(e)},messages:{en:'The value should not be one of the following: "%s".'}}),window.Parsley.options.dateFormats=["DD/MM/YY","DD/MM/YYYY","MM/DD/YY","MM/DD/YYYY","YY/MM/DD","YYYY/MM/DD"],window.Parsley.addValidator("date",{requirementType:"boolean",validateString:function(e,n,i){return r(e,t.getDateFormatsOption(i),!0).isValid()},messages:{en:"You should provide a valid date."}}),window.Parsley.addValidator("dateFormat",{requirementType:"string",validateString:function(e,n){return r(e,t.convert(n),!0).isValid()},messages:{en:"The date you entered is not in the right format (%s)."}}),window.Parsley.addValidator("before",{requirementType:"string",validateString:function(e,n,i){var a=t.getDateFormatsOption(i),s=r(n,a,!0);return s===!1?!1:r(e,a)<s},messages:{en:"The date you entered should be before %s."}}),window.Parsley.addValidator("beforeInput",{requirementType:"string",validateString:function(n,i,a){var s=t.getDateFormatsOption(a),o=e(i);if(0==o.length)return!0;var d=o.val();if(""==d)return!0;var l=r(d,s,!0);if(l.isValid()===!1)return console.warn(i+" input does not contain a valid date"),!1;var u=r(n,s,!0);return u.isValid()===!1?(console.warn("the input being checked does not contain a valid date"),!1):l>u},messages:{en:"The date you entered should be before %s."}}),window.Parsley.addValidator("after",{requirementType:"string",validateString:function(e,n,i){var a=t.getDateFormatsOption(i),s=r(n,a,!0);return s===!1?!1:r(e,a)>s},messages:{en:"The date you entered should be after %s."}}),window.Parsley.addValidator("afterInput",{requirementType:"string",validateString:function(n,i,a){var s=t.getDateFormatsOption(a),o=e(i);if(console.log(this,s),0==o.length)return!0;var d=o.val();if(""==d)return!0;var l=r(d,s,!0);if(l.isValid()===!1)return console.warn(i+" input does not contain a valid date"),!1;var u=r(n,s,!0);return u.isValid()===!1?(console.warn("the input being checked does not contain a valid date"),!1):u>l},messages:{en:"The date you entered should be after %s."}});var t=window.formatDatePhpToJs={mapChars:{d:"DD",D:"ddd",j:"D",l:"dddd",N:"E",S:function(){return"["+this.format("Do",!0).replace(/\d*/g,"")+"]"},w:"d",z:function(){return this.format("DDD",!0)-1},W:"W",F:"MMMM",m:"MM",M:"MMM",n:"M",t:function(){return this.daysInMonth()},L:function(){return this.isLeapYear()?1:0},o:"GGGG",Y:"YYYY",y:"YY",a:"a",A:"A",B:function(){var e=this.clone().utc(),r=(e.hours()+1)%24+e.minutes()/60+e.seconds()/3600;return Math.floor(1e3*r/24)},g:"h",G:"H",h:"hh",H:"HH",i:"mm",s:"ss",u:"[u]",e:"[e]",I:function(){return this.isDST()?1:0},O:"ZZ",P:"Z",T:"[T]",Z:function(){return 36*parseInt(this.format("ZZ",!0),10)},c:"YYYY-MM-DD[T]HH:mm:ssZ",r:"ddd, DD MMM YYYY HH:mm:ss ZZ",U:"X"},formatEx:/[dDjlNSwzWFmMntLoYyaABgGhHisueIOPTZcrU]/g,convert:function(e){return e.replace(this.formatEx,function(e){return console.log(t.mapChars[e]),"function"==typeof t.mapChars[e]?t.mapChars[e].call(r()):t.mapChars[e]})},getDateFormatsOption:function(e){return"undefined"==typeof e.options.dateFormats?this.getDateFormatsOption(e.parent):e.options.dateFormats}},n={parseArrayStringParameter:function(e){var r=e.match(/^\s*\[(.*)\]\s*$/);if(!r)throw'Requirement is not an array: "'+e+'"';return r[1].replace(/\'+/g,"").split(",")},bindChangeToOtherElement:function(r,t,n,i){var a=e(t),s=a.data("larapars-rules");if(void 0===s)s=[r],a.data("larapars-rules",s);else{if(-1!=s.indexOf(r))return;s.push(r),a.data("larapars-rules",s)}a.on("change",function(){i===!0&&""!=e(n.$element.get(0)).val()?n.validate():i!==!0&&n.validate()})}};window.Parsley.addValidator("different",{requirementType:"string",validateString:function(r,t,i){return 0==e(t).length?!0:(n.bindChangeToOtherElement("different",t,i,!0),e(t).val()!=r)},messages:{en:'The value should not be the same as "%s".'}}),window.Parsley.addValidator("between",{requirementType:["integer","integer"],validateNumber:function(e,r,t){return e>r&&t>e},messages:{en:'The value should be between "%s" and "%s".'}}),window.Parsley.addValidator("sizeNumber",{requirementType:"integer",validateNumber:function(e,r){return e==r},messages:{en:'The value should be "%s".'}}),window.Parsley.addValidator("sizeString",{requirementType:"integer",validateString:function(e,r){return e.length==r},messages:{en:'The value should be "%s" characters long.'}}),window.Parsley.addValidator("distinct",{requirementType:"boolean",validateMultiple:function(e){var r=[],t=!0;return e.forEach(function(e){return r.indexOf(e)>-1?(t=!1,!1):void r.push(e)}),t},messages:{en:"Not all values are distinct."}}),window.Parsley.addValidator("inArray",{requirementType:"string",validateString:function(r,t,i){var a=(e(i.$element.get(0)),[]);return"#"==t.substring(0,1)?(n.bindChangeToOtherElement("inArray",t,i,!0),e(t).val().split(",").indexOf(r)>-1):(e('input:checkbox[name="'+t+'"]').each(function(){n.bindChangeToOtherElement("inArray",this,i,!0)}),e('input:checkbox[name="'+t+'"]:checked').each(function(){a.push(e(this).val())}),a.indexOf(r)>-1)},messages:{en:"This value is incorrect."}}),window.Parsley.addValidator("requiredIf",{requirementType:"string",validateString:function(r,t,i){var a=n.parseArrayStringParameter(t),s=a[0];if(t=a.slice(1),n.bindChangeToOtherElement("requiredIf",s,i),0==r.length){var o=e(s).val();return-1==t.indexOf(o)}return!0},messages:{en:"This field is required."}}),window.Parsley.addValidator("requiredUnless",{requirementType:"string",validateString:function(r,t,i){var a=n.parseArrayStringParameter(t),s=a[0];if(t=a.slice(1),n.bindChangeToOtherElement("requiredUnless",s,i),0==r.length){var o=e(s).val();return t.indexOf(o)>-1}return!0},messages:{en:"This field is required."}}),window.Parsley.addValidator("requiredWith",{requirementType:"string",validateString:function(r,t,i){var a=n.parseArrayStringParameter(t);if(0==r.length){var s=!1;return a.forEach(function(r){var t=e(r);n.bindChangeToOtherElement("requiredWith",r,i),t.length>0&&""!=t.val()&&(s=!0)}),!s}return!0},messages:{en:"This field is required."}}),window.Parsley.addValidator("requiredWithAll",{requirementType:"string",validateString:function(r,t,i){var a=n.parseArrayStringParameter(t);if(0==r.length){var s=!0;return a.forEach(function(r){var t=e(r);n.bindChangeToOtherElement("requiredWithAll",r,i),0!=t.length&&""!=t.val()||(s=!1)}),!s}return!0},messages:{en:"This field is required."}}),window.Parsley.addValidator("requiredWithout",{requirementType:"string",validateString:function(r,t,i){var a=n.parseArrayStringParameter(t);if(0==r.length){var s=!1;return a.forEach(function(r){var t=e(r);n.bindChangeToOtherElement("requiredWithAll",r,i),0!=t.length&&""!=t.val()||(s=!0)}),s}return!0},messages:{en:"This field is required."}}),window.Parsley.addValidator("requiredWithoutAll",{requirementType:"string",validateString:function(r,t,i){var a=n.parseArrayStringParameter(t);if(0==r.length){var s=!0;return a.forEach(function(r){var t=e(r);n.bindChangeToOtherElement("requiredWithAll",r,i),1==t.length&&""!=t.val()&&(s=!1)}),s}return!0},messages:{en:"This field is required."}});var i={b:1,kb:1024,mb:1048576,gb:1073741824};window.Parsley.addValidator("fileSizeMax",{requirementType:["integer","string"],validateString:function(e,r,t,n){t=t.toLowerCase();var a=n.$element[0].files;if(r*=i[t.toLowerCase()],console.log(r),a.length>0)for(var s=0;s<a.length;s++)if(console.log(a[s].size),a[s].size>r)return!1;return!0},messages:{en:"Your file(s) are too big."}}),window.Parsley.addValidator("fileSizeMin",{requirementType:["integer","string"],validateString:function(e,r,t,n){var a=n.$element[0].files;if(r*=i[t.toLowerCase()],a.length>0)for(var s=0;s<a.length;s++)if(a[s].size<r)return!1;return!0},messages:{en:"Your file(s) should are too small."}}),window.Parsley.addValidator("fileSizeBetween",{requirementType:["integer","integer","string"],validateString:function(e,r,t,n,a){var s=a.$element[0].files;if(r*=i[n.toLowerCase()],t*=i[n.toLowerCase()],s.length>0)for(var o=0;o<s.length;o++)if(s[o].size<=r||s[o].size>=t)return!1;return!0},messages:{en:"Your file(s) should be between %s and %s %s."}}),window.Parsley.addValidator("image",{validateString:function(e,r,t){var n=t.$element[0].files;if(n.length>0)for(var i=0;i<n.length;i++)if(!n[i].type.match("image/*"))return!1;return!0},messages:{en:"This is not an image."}}),window.Parsley.addValidator("fileMimetype",{requirementType:"string",validateString:function(e,r,t){var i=n.parseArrayStringParameter(r),a=t.$element[0].files;if(a.length>0)for(var s=0;s<a.length;s++)if(-1==i.indexOf(a[s].type))return!1;return!0},messages:{en:'This file does not have the correct mimetype "%s".'}}),window.Parsley.addValidator("fileExt",{requirementType:"string",validateString:function(e,r,t){var i=n.parseArrayStringParameter(r),a=t.$element[0].files;if(a.length>0)for(var s=0;s<a.length;s++){var o=a[s].name.split(".");if(-1==i.indexOf(o[o.length-1]))return!1}return!0},messages:{en:"This file does not have the correct extensions."}}),window.Parsley.addValidator("dimensions",{requirementType:{"":"boolean",min_width:"number",max_width:"number",min_height:"number",max_height:"number",width:"number",height:"number",ratio:"string"},validateString:function(r,t,n){var i=n.$element[0].files,a=n.domOptions.dimensionsOptions;if(i.length>0){var s=e.Deferred(),o=window.URL||window.webkitURL,d=new Image;return d.onload=function(){var e=this.width,r=this.height;if("undefined"!=typeof a.min_width&&e<a.min_width)return s.reject(d),!0;if("undefined"!=typeof a.max_width&&e>a.max_width)return s.reject(d),!0;if("undefined"!=typeof a.min_height&&r<a.min_height)return s.reject(d),!0;if("undefined"!=typeof a.max_height&&r>a.max_height)return s.reject(d),!0;if("undefined"!=typeof a.width&&e!=a.width)return s.reject(d),!0;if("undefined"!=typeof a.height&&r!=a.height)return s.reject(d),!0;if("undefined"!=typeof a.ratio){var t=a.ratio.split(":");if(t[0]/t[1]!=e/r)return s.reject(d),!0}s.resolve(d)},d.onerror=function(){console.warn("image load error"),s.reject()},d.src=o.createObjectURL(i[0]),s.promise().then(function(e){return e=null,!0},function(e){return e=null,!1})}return!0}}),window.ParsleyExtend=e.extend({},window.ParsleyExtend,{_isRequired:function(){var e=["required","requiredIf","requiredUnless","requiredWith","requiredWithAll","requiredWithout","requiredWithoutAll"],r=[];return e.forEach(function(e){"undefined"!=typeof this.constraintsByName[e]&&r.push(e)},this),0==r.length?!1:r.indexOf("required")>=0?!1!==this.constraintsByName.required.requirements:!0}});var a=n;return a});
//# sourceMappingURL=laravel-parsley.min.js.map