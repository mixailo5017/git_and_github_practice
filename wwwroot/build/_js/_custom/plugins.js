/* Eventual goal is to migrate all plugins to browserify/webpack 
   require statements. For now, copy contents of previous plugins.js
   file but gradually replace code with require statements. */

global.Wkt = require('wicket/wicket.js');

require('wicket/wicket-leaflet.js');

global._ = require('underscore');

/* Legacy plugins.js code follows */

/*

 CUSTOM FORM ELEMENTS

 Created by Ryan Fait
 www.ryanfait.com

 The only things you may need to change in this file are the following
 variables: checkboxHeight, radioHeight and selectWidth (lines 24, 25, 26)

 The numbers you set for checkboxHeight and radioHeight should be one quarter
 of the total height of the image want to use for checkboxes and radio
 buttons. Both images should contain the four stages of both inputs stacked
 on top of each other in this order: unchecked, unchecked-clicked, checked,
 checked-clicked.

 You may need to adjust your images a bit if there is a slight vertical
 movement during the different stages of the button activation.

 The value of selectWidth should be the width of your select list image.

 Visit http://ryanfait.com/ for more information.


 */

var checkboxHeight = "24";
var radioHeight = "24";
var selectWidth = "190";


/* No need to change anything after this */


document.write('<style type="text/css">input.styled { display: none; } select.styled { position: relative; width: ' + selectWidth + 'px; opacity: 0; filter: alpha(opacity=0); z-index: 5; } .disabled { opacity: 0.5; filter: alpha(opacity=50); }</style>');

var Custom = {
    init: function() {
        var inputs = document.getElementsByTagName("input"), span = Array(), textnode, option, active;
        for(a = 0; a < inputs.length; a++) {
            if((inputs[a].type == "checkbox" || inputs[a].type == "radio") && inputs[a].className == "styled") {
                span[a] = document.createElement("span");
                span[a].className = inputs[a].type;

                if(inputs[a].checked == true) {
                    if(inputs[a].type == "checkbox") {
                        position = "0 -" + (checkboxHeight*2) + "px";
                        span[a].style.backgroundPosition = position;
                    } else {
                        position = "0 -" + (radioHeight*2) + "px";
                        span[a].style.backgroundPosition = position;
                    }
                }
                inputs[a].parentNode.insertBefore(span[a], inputs[a]);
                inputs[a].onchange = Custom.clear;
                if(!inputs[a].getAttribute("disabled")) {
                    span[a].onmousedown = Custom.pushed;
                    span[a].onmouseup = Custom.check;
                } else {
                    span[a].className = span[a].className += " disabled";
                }
            }
        }
        inputs = document.getElementsByTagName("select");
        for(a = 0; a < inputs.length; a++) {
            if(inputs[a].className == "styled") {
                option = inputs[a].getElementsByTagName("option");
                active = option[0].childNodes[0].nodeValue;
                textnode = document.createTextNode(active);
                for(b = 0; b < option.length; b++) {
                    if(option[b].selected == true) {
                        textnode = document.createTextNode(option[b].childNodes[0].nodeValue);
                    }
                }
                span[a] = document.createElement("span");
                span[a].className = "select";
                span[a].id = "select" + inputs[a].name;
                span[a].appendChild(textnode);
                inputs[a].parentNode.insertBefore(span[a], inputs[a]);
                if(!inputs[a].getAttribute("disabled")) {
                    inputs[a].onchange = Custom.choose;
                } else {
                    inputs[a].previousSibling.className = inputs[a].previousSibling.className += " disabled";
                }
            }
        }
        document.onmouseup = Custom.clear;
    },
    pushed: function() {
        element = this.nextSibling;
        if(element.checked == true && element.type == "checkbox") {
            this.style.backgroundPosition = "0 -" + checkboxHeight*3 + "px";
        } else if(element.checked == true && element.type == "radio") {
            this.style.backgroundPosition = "0 -" + radioHeight*3 + "px";
        } else if(element.checked != true && element.type == "checkbox") {
            this.style.backgroundPosition = "0 -" + checkboxHeight + "px";
        } else {
            this.style.backgroundPosition = "0 -" + radioHeight + "px";
        }
    },
    check: function() {
        element = this.nextSibling;
        if(element.checked == true && element.type == "checkbox") {
            this.style.backgroundPosition = "0 0";
            element.checked = false;
        } else {
            if(element.type == "checkbox") {
                this.style.backgroundPosition = "0 -" + checkboxHeight*2 + "px";
            } else {
                this.style.backgroundPosition = "0 -" + radioHeight*2 + "px";
                group = this.nextSibling.name;
                inputs = document.getElementsByTagName("input");
                for(a = 0; a < inputs.length; a++) {
                    if(inputs[a].name == group && inputs[a] != this.nextSibling) {
                        inputs[a].previousSibling.style.backgroundPosition = "0 0";
                    }
                }
            }
            element.checked = true;
        }
    },
    clear: function() {
        inputs = document.getElementsByTagName("input");
        for(var b = 0; b < inputs.length; b++) {
            if(inputs[b].type == "checkbox" && inputs[b].checked == true && inputs[b].className == "styled") {
                inputs[b].previousSibling.style.backgroundPosition = "0 -" + checkboxHeight*2 + "px";
            } else if(inputs[b].type == "checkbox" && inputs[b].className == "styled") {
                inputs[b].previousSibling.style.backgroundPosition = "0 0";
            } else if(inputs[b].type == "radio" && inputs[b].checked == true && inputs[b].className == "styled") {
                inputs[b].previousSibling.style.backgroundPosition = "0 -" + radioHeight*2 + "px";
            } else if(inputs[b].type == "radio" && inputs[b].className == "styled") {
                inputs[b].previousSibling.style.backgroundPosition = "0 0";
            }
        }
    },
    choose: function() {
        option = this.getElementsByTagName("option");
        for(d = 0; d < option.length; d++) {
            if(option[d].selected == true) {
                document.getElementById("select" + this.name).childNodes[0].nodeValue = option[d].childNodes[0].nodeValue;
            }
        }
    }
}
window.onload = Custom.init;

/**
 * --------------------------------------------------------------------
 * jQuery customfileinput plugin
 * Author: Scott Jehl, scott@filamentgroup.com
 * Copyright (c) 2009 Filament Group
 * licensed under MIT (filamentgroup.com/examples/mit-license.txt)
 * --------------------------------------------------------------------
 */
$.fn.customFileInput = function(){
    //apply events and styles for file input element
    var fileInput = $(this)
        .addClass('customfile-input') //add class for CSS
        .mouseover(function(){ upload.addClass('customfile-hover'); })
        .mouseout(function(){ upload.removeClass('customfile-hover'); })
        .focus(function(){
            upload.addClass('customfile-focus');
            fileInput.data('val', fileInput.val());
        })
        .blur(function(){
            upload.removeClass('customfile-focus');
            $(this).trigger('checkChange');
        })
        .bind('disable',function(){
            fileInput.attr('disabled',true);
            upload.addClass('customfile-disabled');
        })
        .bind('enable',function(){
            fileInput.removeAttr('disabled');
            upload.removeClass('customfile-disabled');
        })
        .bind('checkChange', function(){
            if(fileInput.val() && fileInput.val() != fileInput.data('val')){
                fileInput.trigger('change');
            }
        })
        .bind('change',function(){
            //get file name
            var fileName = $(this).val().split(/\\/).pop();
            //get file extension
            var fileExt = 'customfile-ext-' + fileName.split('.').pop().toLowerCase();
            //update the feedback
            uploadFeedback
                .text(fileName) //set feedback text to filename
                .removeClass(uploadFeedback.data('fileExt') || '') //remove any existing file extension class
                .addClass(fileExt) //add file extension class
                .data('fileExt', fileExt) //store file extension for class removal on next change
                .addClass('customfile-feedback-populated'); //add class to show populated state
            //change text of button
            uploadButton.text('Change');
        })
        .click(function(){ //for IE and Opera, make sure change fires after choosing a file, using an async callback
            fileInput.data('val', fileInput.val());
            setTimeout(function(){
                fileInput.trigger('checkChange');
            },100);
        });

    //create custom control container
    var upload = $('<div class="customfile"></div>');
    //create custom control button
    var uploadButton = $('<span class="customfile-button" aria-hidden="true">Browse</span>').appendTo(upload);
    //create custom control feedback
    var uploadFeedback = $('<span class="customfile-feedback" aria-hidden="true">No file selected...</span>').appendTo(upload);

    //match disabled state
    if(fileInput.is('[disabled]')){
        fileInput.trigger('disable');
    }


    //on mousemove, keep file input under the cursor to steal click
    upload
        .mousemove(function(e){
            fileInput.css({
                'left': e.pageX - upload.offset().left - fileInput.outerWidth() + 20, //position right side 20px right of cursor X)
                'top': e.pageY - upload.offset().top - $(window).scrollTop() - 3
            });
        })
        .insertAfter(fileInput); //insert after the input

    fileInput.appendTo(upload);

    //return jQuery
    return $(this);
};


window.log = function f(){ log.history = log.history || []; log.history.push(arguments); if(this.console) { var args = arguments, newarr; args.callee = args.callee.caller; newarr = [].slice.call(args); if (typeof console.log === 'object') log.apply.call(console.log, console, newarr); else console.log.apply(console, newarr);}};
(function(a){function b(){}for(var c="assert,count,debug,dir,dirxml,error,exception,group,groupCollapsed,groupEnd,info,log,markTimeline,profile,profileEnd,time,timeEnd,trace,warn".split(","),d;!!(d=c.pop());){a[d]=a[d]||b;}})
(function(){try{console.log();return window.console;}catch(a){return (window.console={});}}());

/*!
 * jQuery Form Plugin
 * version: 3.14 (30-JUL-2012)
 * @requires jQuery v1.3.2 or later
 *
 * Examples and documentation at: http://malsup.com/jquery/form/
 * Project repository: https://github.com/malsup/form
 * Dual licensed under the MIT and GPL licenses:
 *    http://malsup.github.com/mit-license.txt
 *    http://malsup.github.com/gpl-license-v2.txt
 */
/*global ActiveXObject alert */
;(function($) {
    "use strict";

    /*
     Usage Note:
     -----------
     Do not use both ajaxSubmit and ajaxForm on the same form.  These
     functions are mutually exclusive.  Use ajaxSubmit if you want
     to bind your own submit handler to the form.  For example,

     $(document).ready(function() {
     $('#myForm').on('submit', function(e) {
     e.preventDefault(); // <-- important
     $(this).ajaxSubmit({
     target: '#output'
     });
     });
     });

     Use ajaxForm when you want the plugin to manage all the event binding
     for you.  For example,

     $(document).ready(function() {
     $('#myForm').ajaxForm({
     target: '#output'
     });
     });

     You can also use ajaxForm with delegation (requires jQuery v1.7+), so the
     form does not have to exist when you invoke ajaxForm:

     $('#myForm').ajaxForm({
     delegation: true,
     target: '#output'
     });

     When using ajaxForm, the ajaxSubmit function will be invoked for you
     at the appropriate time.
     */

    /**
     * Feature detection
     */
    var feature = {};
    feature.fileapi = $("<input type='file'/>").get(0).files !== undefined;
    feature.formdata = window.FormData !== undefined;

    /**
     * ajaxSubmit() provides a mechanism for immediately submitting
     * an HTML form using AJAX.
     */
    $.fn.ajaxSubmit = function(options) {
        /*jshint scripturl:true */

        // fast fail if nothing selected (http://dev.jquery.com/ticket/2752)
        if (!this.length) {
            log('ajaxSubmit: skipping submit process - no element selected');
            return this;
        }

        var method, action, url, $form = this;

        if (typeof options == 'function') {
            options = { success: options };
        }

        method = this.attr('method');
        action = this.attr('action');
        url = (typeof action === 'string') ? $.trim(action) : '';
        url = url || window.location.href || '';
        if (url) {
            // clean url (don't include hash vaue)
            url = (url.match(/^([^#]+)/)||[])[1];
        }

        options = $.extend(true, {
            url:  url,
            success: $.ajaxSettings.success,
            type: method || 'GET',
            iframeSrc: /^https/i.test(window.location.href || '') ? 'javascript:false' : 'about:blank'
        }, options);

        // hook for manipulating the form data before it is extracted;
        // convenient for use with rich editors like tinyMCE or FCKEditor
        var veto = {};
        this.trigger('form-pre-serialize', [this, options, veto]);
        if (veto.veto) {
            log('ajaxSubmit: submit vetoed via form-pre-serialize trigger');
            return this;
        }

        // provide opportunity to alter form data before it is serialized
        if (options.beforeSerialize && options.beforeSerialize(this, options) === false) {
            log('ajaxSubmit: submit aborted via beforeSerialize callback');
            return this;
        }

        var traditional = options.traditional;
        if ( traditional === undefined ) {
            traditional = $.ajaxSettings.traditional;
        }

        var elements = [];
        var qx, a = this.formToArray(options.semantic, elements);
        if (options.data) {
            options.extraData = options.data;
            qx = $.param(options.data, traditional);
        }

        // give pre-submit callback an opportunity to abort the submit
        if (options.beforeSubmit && options.beforeSubmit(a, this, options) === false) {
            log('ajaxSubmit: submit aborted via beforeSubmit callback');
            return this;
        }

        // fire vetoable 'validate' event
        this.trigger('form-submit-validate', [a, this, options, veto]);
        if (veto.veto) {
            log('ajaxSubmit: submit vetoed via form-submit-validate trigger');
            return this;
        }

        var q = $.param(a, traditional);
        if (qx) {
            q = ( q ? (q + '&' + qx) : qx );
        }
        if (options.type.toUpperCase() == 'GET') {
            options.url += (options.url.indexOf('?') >= 0 ? '&' : '?') + q;
            options.data = null;  // data is null for 'get'
        }
        else {
            options.data = q; // data is the query string for 'post'
        }

        var callbacks = [];
        if (options.resetForm) {
            callbacks.push(function() {$form.resetForm();});
        }
        if (options.clearForm) {
            callbacks.push(function() {$form.clearForm(options.includeHidden);});
        }

        // perform a load on the target only if dataType is not provided
        if (!options.dataType && options.target) {
            var oldSuccess = options.success || function(){};
            callbacks.push(function(data) {
                var fn = options.replaceTarget ? 'replaceWith' : 'html';
                $(options.target)[fn](data).each(oldSuccess, arguments);
            });
        }
        else if (options.success) {callbacks.push(options.success);}

        options.success = function(data, status, xhr) { // jQuery 1.4+ passes xhr as 3rd arg
            var context = options.context || this ;    // jQuery 1.4+ supports scope context
            for (var i=0, max=callbacks.length; i < max; i++) {
                callbacks[i].apply(context, [data, status, xhr || $form, $form]);
            }
        };

        // are there files to upload?
        var fileInputs = $('input:file:enabled[value]', this); // [value] (issue #113)
        var hasFileInputs = fileInputs.length > 0;
        var mp = 'multipart/form-data';
        var multipart = ($form.attr('enctype') == mp || $form.attr('encoding') == mp);

        var fileAPI = feature.fileapi && feature.formdata;
        log("fileAPI :" + fileAPI);
        var shouldUseFrame = (hasFileInputs || multipart) && !fileAPI;

        // options.iframe allows user to force iframe mode
        // 06-NOV-09: now defaulting to iframe mode if file input is detected
        if (options.iframe !== false && (options.iframe || shouldUseFrame)) {
            // hack to fix Safari hang (thanks to Tim Molendijk for this)
            // see:  http://groups.google.com/group/jquery-dev/browse_thread/thread/36395b7ab510dd5d
            if (options.closeKeepAlive) {
                $.get(options.closeKeepAlive, function() {fileUploadIframe(a);});
            }
            else {fileUploadIframe(a);}
        }
        else if ((hasFileInputs || multipart) && fileAPI) {fileUploadXhr(a);}
        else {$.ajax(options);}

        // clear element array
        for (var k=0; k < elements.length; k++)
            elements[k] = null;

        // fire 'notify' event
        this.trigger('form-submit-notify', [this, options]);
        return this;

        // XMLHttpRequest Level 2 file uploads (big hat tip to francois2metz)
        function fileUploadXhr(a) {
            var formdata = new FormData();

            for (var i=0; i < a.length; i++) {
                formdata.append(a[i].name, a[i].value);
            }

            if (options.extraData) {
                for (var p in options.extraData)
                    if (options.extraData.hasOwnProperty(p))
                        formdata.append(p, options.extraData[p]);
            }

            options.data = null;

            var s = $.extend(true, {}, $.ajaxSettings, options, {
                contentType: false,
                processData: false,
                cache: false,
                type: 'POST'
            });

            if (options.uploadProgress) {
                // workaround because jqXHR does not expose upload property
                s.xhr = function() {
                    var xhr = jQuery.ajaxSettings.xhr();
                    if (xhr.upload) {
                        xhr.upload.onprogress = function(event) {
                            var percent = 0;
                            var position = event.loaded || event.position; /*event.position is deprecated*/
                            var total = event.total;
                            if (event.lengthComputable) {
                                percent = Math.ceil(position / total * 100);
                            }
                            options.uploadProgress(event, position, total, percent);
                        };
                    }
                    return xhr;
                };
            }

            s.data = null;
            var beforeSend = s.beforeSend;
            s.beforeSend = function(xhr, o) {
                o.data = formdata;
                if(beforeSend)
                    beforeSend.call(this, xhr, o);
            };
            $.ajax(s);
        }

        // private function for handling file uploads (hat tip to YAHOO!)
        function fileUploadIframe(a) {
            var form = $form[0], el, i, s, g, id, $io, io, xhr, sub, n, timedOut, timeoutHandle;
            var useProp = !!$.fn.prop;

            if ($(':input[name=submit],:input[id=submit]', form).length) {
                // if there is an input with a name or id of 'submit' then we won't be
                // able to invoke the submit fn on the form (at least not x-browser)
                alert('Error: Form elements must not have name or id of "submit".');
                return;
            }

            if (a) {
                // ensure that every serialized input is still enabled
                for (i=0; i < elements.length; i++) {
                    el = $(elements[i]);
                    if ( useProp )
                        el.prop('disabled', false);
                    else
                        el.removeAttr('disabled');
                }
            }

            s = $.extend(true, {}, $.ajaxSettings, options);
            s.context = s.context || s;
            id = 'jqFormIO' + (new Date().getTime());
            if (s.iframeTarget) {
                $io = $(s.iframeTarget);
                n = $io.attr('name');
                if (!n)
                    $io.attr('name', id);
                else
                    id = n;
            }
            else {
                $io = $('<iframe name="' + id + '" src="'+ s.iframeSrc +'" />');
                $io.css({ position: 'absolute', top: '-1000px', left: '-1000px' });
            }
            io = $io[0];


            xhr = { // mock object
                aborted: 0,
                responseText: null,
                responseXML: null,
                status: 0,
                statusText: 'n/a',
                getAllResponseHeaders: function() {},
                getResponseHeader: function() {},
                setRequestHeader: function() {},
                abort: function(status) {
                    var e = (status === 'timeout' ? 'timeout' : 'aborted');
                    log('aborting upload... ' + e);
                    this.aborted = 1;
                    // #214
                    if (io.contentWindow.document.execCommand) {
                        try { // #214
                            io.contentWindow.document.execCommand('Stop');
                        } catch(ignore) {}}
                    $io.attr('src', s.iframeSrc); // abort op in progress
                    xhr.error = e;
                    if (s.error)
                        s.error.call(s.context, xhr, e, status);
                    if (g)
                        $.event.trigger("ajaxError", [xhr, s, e]);
                    if (s.complete)
                        s.complete.call(s.context, xhr, e);
                }
            };

            g = s.global;
            // trigger ajax global events so that activity/block indicators work like normal
            if (g && 0 === $.active++) {$.event.trigger("ajaxStart");}
            if (g) {
                $.event.trigger("ajaxSend", [xhr, s]);
            }

            if (s.beforeSend && s.beforeSend.call(s.context, xhr, s) === false) {
                if (s.global) {$.active--;}
                return;
            }
            if (xhr.aborted) {return;}

            // add submitting element to data if we know it
            sub = form.clk;
            if (sub) {
                n = sub.name;
                if (n && !sub.disabled) {
                    s.extraData = s.extraData || {};
                    s.extraData[n] = sub.value;
                    if (sub.type == "image") {
                        s.extraData[n+'.x'] = form.clk_x;
                        s.extraData[n+'.y'] = form.clk_y;
                    }
                }
            }

            var CLIENT_TIMEOUT_ABORT = 1;
            var SERVER_ABORT = 2;

            function getDoc(frame) {
                var doc = frame.contentWindow ? frame.contentWindow.document : frame.contentDocument ? frame.contentDocument : frame.document;
                return doc;
            }

            // Rails CSRF hack (thanks to Yvan Barthelemy)
            var csrf_token = $('meta[name=csrf-token]').attr('content');
            var csrf_param = $('meta[name=csrf-param]').attr('content');
            if (csrf_param && csrf_token) {
                s.extraData = s.extraData || {};
                s.extraData[csrf_param] = csrf_token;
            }

            // take a breath so that pending repaints get some cpu time before the upload starts
            function doSubmit() {
                // make sure form attrs are set
                var t = $form.attr('target'), a = $form.attr('action');

                // update form attrs in IE friendly way
                form.setAttribute('target',id);
                if (!method) {
                    form.setAttribute('method', 'POST');
                }
                if (a != s.url) {
                    form.setAttribute('action', s.url);
                }

                // ie borks in some cases when setting encoding
                if (! s.skipEncodingOverride && (!method || /post/i.test(method))) {
                    $form.attr({
                        encoding: 'multipart/form-data',
                        enctype:  'multipart/form-data'
                    });
                }

                // support timout
                if (s.timeout) {
                    timeoutHandle = setTimeout(function() { timedOut = true; cb(CLIENT_TIMEOUT_ABORT); }, s.timeout);
                }

                // look for server aborts
                function checkState() {
                    try {
                        var state = getDoc(io).readyState;
                        log('state = ' + state);
                        if (state && state.toLowerCase() == 'uninitialized')
                            setTimeout(checkState,50);
                    }
                    catch(e) {
                        log('Server abort: ' , e, ' (', e.name, ')');
                        cb(SERVER_ABORT);
                        if (timeoutHandle)
                            clearTimeout(timeoutHandle);
                        timeoutHandle = undefined;
                    }
                }

                // add "extra" data to form if provided in options
                var extraInputs = [];
                try {
                    if (s.extraData) {
                        for (var n in s.extraData) {
                            if (s.extraData.hasOwnProperty(n)) {
                                // if using the $.param format that allows for multiple values with the same name
                                if($.isPlainObject(s.extraData[n]) && s.extraData[n].hasOwnProperty('name') && s.extraData[n].hasOwnProperty('value')) {
                                    extraInputs.push(
                                        $('<input type="hidden" name="'+s.extraData[n].name+'">').attr('value',s.extraData[n].value)
                                            .appendTo(form)[0]);
                                } else {
                                    extraInputs.push(
                                        $('<input type="hidden" name="'+n+'">').attr('value',s.extraData[n])
                                            .appendTo(form)[0]);
                                }
                            }
                        }
                    }

                    if (!s.iframeTarget) {
                        // add iframe to doc and submit the form
                        $io.appendTo('body');
                        if (io.attachEvent)
                            io.attachEvent('onload', cb);
                        else
                            io.addEventListener('load', cb, false);
                    }
                    setTimeout(checkState,15);
                    form.submit();
                }
                finally {
                    // reset attrs and remove "extra" input elements
                    form.setAttribute('action',a);
                    if(t) {
                        form.setAttribute('target', t);
                    } else {$form.removeAttr('target');}
                    $(extraInputs).remove();
                }
            }

            if (s.forceSync) {doSubmit();}
            else {
                setTimeout(doSubmit, 10); // this lets dom updates render
            }

            var data, doc, domCheckCount = 50, callbackProcessed;

            function cb(e) {
                if (xhr.aborted || callbackProcessed) {return;}
                try {
                    doc = getDoc(io);
                }
                catch(ex) {
                    log('cannot access response document: ', ex);
                    e = SERVER_ABORT;
                }
                if (e === CLIENT_TIMEOUT_ABORT && xhr) {
                    xhr.abort('timeout');
                    return;
                }
                else if (e == SERVER_ABORT && xhr) {
                    xhr.abort('server abort');
                    return;
                }

                if (!doc || doc.location.href == s.iframeSrc) {
                    // response not received yet
                    if (!timedOut)
                        return;
                }
                if (io.detachEvent)
                    io.detachEvent('onload', cb);
                else
                    io.removeEventListener('load', cb, false);

                var status = 'success', errMsg;
                try {
                    if (timedOut) {
                        throw 'timeout';
                    }

                    var isXml = s.dataType == 'xml' || doc.XMLDocument || $.isXMLDoc(doc);
                    log('isXml='+isXml);
                    if (!isXml && window.opera && (doc.body === null || !doc.body.innerHTML)) {
                        if (--domCheckCount) {
                            // in some browsers (Opera) the iframe DOM is not always traversable when
                            // the onload callback fires, so we loop a bit to accommodate
                            log('requeing onLoad callback, DOM not available');
                            setTimeout(cb, 250);
                            return;
                        }
                        // let this fall through because server response could be an empty document
                        //log('Could not access iframe DOM after mutiple tries.');
                        //throw 'DOMException: not available';
                    }

                    //log('response detected');
                    var docRoot = doc.body ? doc.body : doc.documentElement;
                    xhr.responseText = docRoot ? docRoot.innerHTML : null;
                    xhr.responseXML = doc.XMLDocument ? doc.XMLDocument : doc;
                    if (isXml)
                        s.dataType = 'xml';
                    xhr.getResponseHeader = function(header){
                        var headers = {'content-type': s.dataType};
                        return headers[header];
                    };
                    // support for XHR 'status' & 'statusText' emulation :
                    if (docRoot) {
                        xhr.status = Number( docRoot.getAttribute('status') ) || xhr.status;
                        xhr.statusText = docRoot.getAttribute('statusText') || xhr.statusText;
                    }

                    var dt = (s.dataType || '').toLowerCase();
                    var scr = /(json|script|text)/.test(dt);
                    if (scr || s.textarea) {
                        // see if user embedded response in textarea
                        var ta = doc.getElementsByTagName('textarea')[0];
                        if (ta) {
                            xhr.responseText = ta.value;
                            // support for XHR 'status' & 'statusText' emulation :
                            xhr.status = Number( ta.getAttribute('status') ) || xhr.status;
                            xhr.statusText = ta.getAttribute('statusText') || xhr.statusText;
                        }
                        else if (scr) {
                            // account for browsers injecting pre around json response
                            var pre = doc.getElementsByTagName('pre')[0];
                            var b = doc.getElementsByTagName('body')[0];
                            if (pre) {
                                xhr.responseText = pre.textContent ? pre.textContent : pre.innerText;
                            }
                            else if (b) {
                                xhr.responseText = b.textContent ? b.textContent : b.innerText;
                            }
                        }
                    }
                    else if (dt == 'xml' && !xhr.responseXML && xhr.responseText) {
                        xhr.responseXML = toXml(xhr.responseText);
                    }

                    try {
                        data = httpData(xhr, dt, s);
                    }
                    catch (e) {
                        status = 'parsererror';
                        xhr.error = errMsg = (e || status);
                    }
                }
                catch (e) {
                    log('error caught: ',e);
                    status = 'error';
                    xhr.error = errMsg = (e || status);
                }

                if (xhr.aborted) {
                    log('upload aborted');
                    status = null;
                }

                if (xhr.status) { // we've set xhr.status
                    status = (xhr.status >= 200 && xhr.status < 300 || xhr.status === 304) ? 'success' : 'error';
                }

                // ordering of these callbacks/triggers is odd, but that's how $.ajax does it
                if (status === 'success') {
                    if (s.success)
                        s.success.call(s.context, data, 'success', xhr);
                    if (g)
                        $.event.trigger("ajaxSuccess", [xhr, s]);
                }
                else if (status) {
                    if (errMsg === undefined)
                        errMsg = xhr.statusText;
                    if (s.error)
                        s.error.call(s.context, xhr, status, errMsg);
                    if (g)
                        $.event.trigger("ajaxError", [xhr, s, errMsg]);
                }

                if (g)
                    $.event.trigger("ajaxComplete", [xhr, s]);

                if (g && ! --$.active) {$.event.trigger("ajaxStop");}

                if (s.complete)
                    s.complete.call(s.context, xhr, status);

                callbackProcessed = true;
                if (s.timeout)
                    clearTimeout(timeoutHandle);

                // clean up
                setTimeout(function() {
                    if (!s.iframeTarget)
                        $io.remove();
                    xhr.responseXML = null;
                }, 100);
            }

            var toXml = $.parseXML || function(s, doc) { // use parseXML if available (jQuery 1.5+)
                    if (window.ActiveXObject) {
                        doc = new ActiveXObject('Microsoft.XMLDOM');
                        doc.async = 'false';
                        doc.loadXML(s);
                    }
                    else {
                        doc = (new DOMParser()).parseFromString(s, 'text/xml');
                    }
                    return (doc && doc.documentElement && doc.documentElement.nodeName != 'parsererror') ? doc : null;
                };
            var parseJSON = $.parseJSON || function(s) {
                    /*jslint evil:true */
                    return window['eval']('(' + s + ')');
                };

            var httpData = function( xhr, type, s ) { // mostly lifted from jq1.4.4

                var ct = xhr.getResponseHeader('content-type') || '',
                    xml = type === 'xml' || !type && ct.indexOf('xml') >= 0,
                    data = xml ? xhr.responseXML : xhr.responseText;

                if (xml && data.documentElement.nodeName === 'parsererror') {
                    if ($.error)
                        $.error('parsererror');
                }
                if (s && s.dataFilter) {
                    data = s.dataFilter(data, type);
                }
                if (typeof data === 'string') {
                    if (type === 'json' || !type && ct.indexOf('json') >= 0) {
                        data = parseJSON(data);
                    } else if (type === "script" || !type && ct.indexOf("javascript") >= 0) {$.globalEval(data);}
                }
                return data;
            };
        }
    };

    /**
     * ajaxForm() provides a mechanism for fully automating form submission.
     *
     * The advantages of using this method instead of ajaxSubmit() are:
     *
     * 1: This method will include coordinates for <input type="image" /> elements (if the element
     *    is used to submit the form).
     * 2. This method will include the submit element's name/value data (for the element that was
     *    used to submit the form).
     * 3. This method binds the submit() method to the form for you.
     *
     * The options argument for ajaxForm works exactly as it does for ajaxSubmit.  ajaxForm merely
     * passes the options argument along after properly binding events for submit elements and
     * the form itself.
     */
    $.fn.ajaxForm = function(options) {
        options = options || {};
        options.delegation = options.delegation && $.isFunction($.fn.on);

        // in jQuery 1.3+ we can fix mistakes with the ready state
        if (!options.delegation && this.length === 0) {
            var o = { s: this.selector, c: this.context };
            if (!$.isReady && o.s) {
                log('DOM not ready, queuing ajaxForm');
                $(function() {$(o.s,o.c).ajaxForm(options);});
                return this;
            }
            // is your DOM ready?  http://docs.jquery.com/Tutorials:Introducing_$(document).ready()
            log('terminating; zero elements found by selector' + ($.isReady ? '' : ' (DOM not ready)'));
            return this;
        }

        if ( options.delegation ) {
            $(document)
                .off('submit.form-plugin', this.selector, doAjaxSubmit)
                .off('click.form-plugin', this.selector, captureSubmittingElement)
                .on('submit.form-plugin', this.selector, options, doAjaxSubmit)
                .on('click.form-plugin', this.selector, options, captureSubmittingElement);
            return this;
        }

        return this.ajaxFormUnbind()
            .bind('submit.form-plugin', options, doAjaxSubmit)
            .bind('click.form-plugin', options, captureSubmittingElement);
    };

// private event handlers    
    function doAjaxSubmit(e) {
        /*jshint validthis:true */
        var options = e.data;
        if (!e.isDefaultPrevented()) { // if event has been canceled, don't proceed
            e.preventDefault();
            $(this).ajaxSubmit(options);
        }
    }

    function captureSubmittingElement(e) {
        /*jshint validthis:true */
        var target = e.target;
        var $el = $(target);
        if (!($el.is(":submit,input:image"))) {
            // is this a child element of the submit el?  (ex: a span within a button)
            var t = $el.closest(':submit');
            if (t.length === 0) {return;}
            target = t[0];
        }
        var form = this;
        form.clk = target;
        if (target.type == 'image') {
            if (e.offsetX !== undefined) {
                form.clk_x = e.offsetX;
                form.clk_y = e.offsetY;
            } else if (typeof $.fn.offset == 'function') {
                var offset = $el.offset();
                form.clk_x = e.pageX - offset.left;
                form.clk_y = e.pageY - offset.top;
            } else {
                form.clk_x = e.pageX - target.offsetLeft;
                form.clk_y = e.pageY - target.offsetTop;
            }
        }
        // clear form vars
        setTimeout(function() { form.clk = form.clk_x = form.clk_y = null; }, 100);
    }


// ajaxFormUnbind unbinds the event handlers that were bound by ajaxForm
    $.fn.ajaxFormUnbind = function() {
        return this.unbind('submit.form-plugin click.form-plugin');
    };

    /**
     * formToArray() gathers form element data into an array of objects that can
     * be passed to any of the following ajax functions: $.get, $.post, or load.
     * Each object in the array has both a 'name' and 'value' property.  An example of
     * an array for a simple login form might be:
     *
     * [ { name: 'username', value: 'jresig' }, { name: 'password', value: 'secret' } ]
     *
     * It is this array that is passed to pre-submit callback functions provided to the
     * ajaxSubmit() and ajaxForm() methods.
     */
    $.fn.formToArray = function(semantic, elements) {
        var a = [];
        if (this.length === 0) {
            return a;
        }

        var form = this[0];
        var els = semantic ? form.getElementsByTagName('*') : form.elements;
        if (!els) {
            return a;
        }

        var i,j,n,v,el,max,jmax;
        for(i=0, max=els.length; i < max; i++) {
            el = els[i];
            n = el.name;
            if (!n) {continue;}

            if (semantic && form.clk && el.type == "image") {
                // handle image inputs on the fly when semantic == true
                if(!el.disabled && form.clk == el) {
                    a.push({name: n, value: $(el).val(), type: el.type });
                    a.push({name: n+'.x', value: form.clk_x}, {name: n+'.y', value: form.clk_y});
                }
                continue;
            }

            v = $.fieldValue(el, true);
            if (v && v.constructor == Array) {
                if (elements)
                    elements.push(el);
                for(j=0, jmax=v.length; j < jmax; j++) {
                    a.push({name: n, value: v[j]});
                }
            }
            else if (feature.fileapi && el.type == 'file' && !el.disabled) {
                if (elements)
                    elements.push(el);
                var files = el.files;
                if (files.length) {
                    for (j=0; j < files.length; j++) {
                        a.push({name: n, value: files[j], type: el.type});
                    }
                }
                else {
                    // #180
                    a.push({ name: n, value: '', type: el.type });
                }
            }
            else if (v !== null && typeof v != 'undefined') {
                if (elements)
                    elements.push(el);
                a.push({name: n, value: v, type: el.type, required: el.required});
            }
        }

        if (!semantic && form.clk) {
            // input type=='image' are not found in elements array! handle it here
            var $input = $(form.clk), input = $input[0];
            n = input.name;
            if (n && !input.disabled && input.type == 'image') {
                a.push({name: n, value: $input.val()});
                a.push({name: n+'.x', value: form.clk_x}, {name: n+'.y', value: form.clk_y});
            }
        }
        return a;
    };

    /**
     * Serializes form data into a 'submittable' string. This method will return a string
     * in the format: name1=value1&amp;name2=value2
     */
    $.fn.formSerialize = function(semantic) {
        //hand off to jQuery.param for proper encoding
        return $.param(this.formToArray(semantic));
    };

    /**
     * Serializes all field elements in the jQuery object into a query string.
     * This method will return a string in the format: name1=value1&amp;name2=value2
     */
    $.fn.fieldSerialize = function(successful) {
        var a = [];
        this.each(function() {
            var n = this.name;
            if (!n) {return;}
            var v = $.fieldValue(this, successful);
            if (v && v.constructor == Array) {
                for (var i=0,max=v.length; i < max; i++) {
                    a.push({name: n, value: v[i]});
                }
            }
            else if (v !== null && typeof v != 'undefined') {
                a.push({name: this.name, value: v});
            }
        });
        //hand off to jQuery.param for proper encoding
        return $.param(a);
    };

    /**
     * Returns the value(s) of the element in the matched set.  For example, consider the following form:
     *
     *  <form><fieldset>
     *      <input name="A" type="text" />
     *      <input name="A" type="text" />
     *      <input name="B" type="checkbox" value="B1" />
     *      <input name="B" type="checkbox" value="B2"/>
     *      <input name="C" type="radio" value="C1" />
     *      <input name="C" type="radio" value="C2" />
     *  </fieldset></form>
     *
     *  var v = $(':text').fieldValue();
     *  // if no values are entered into the text inputs
     *  v == ['','']
     *  // if values entered into the text inputs are 'foo' and 'bar'
     *  v == ['foo','bar']
     *
     *  var v = $(':checkbox').fieldValue();
     *  // if neither checkbox is checked
     *  v === undefined
     *  // if both checkboxes are checked
     *  v == ['B1', 'B2']
     *
     *  var v = $(':radio').fieldValue();
     *  // if neither radio is checked
     *  v === undefined
     *  // if first radio is checked
     *  v == ['C1']
     *
     * The successful argument controls whether or not the field element must be 'successful'
     * (per http://www.w3.org/TR/html4/interact/forms.html#successful-controls).
     * The default value of the successful argument is true.  If this value is false the value(s)
     * for each element is returned.
     *
     * Note: This method *always* returns an array.  If no valid value can be determined the
     *    array will be empty, otherwise it will contain one or more values.
     */
    $.fn.fieldValue = function(successful) {
        for (var val=[], i=0, max=this.length; i < max; i++) {
            var el = this[i];
            var v = $.fieldValue(el, successful);
            if (v === null || typeof v == 'undefined' || (v.constructor == Array && !v.length)) {continue;}
            if (v.constructor == Array)
                $.merge(val, v);
            else
                val.push(v);
        }
        return val;
    };

    /**
     * Returns the value of the field element.
     */
    $.fieldValue = function(el, successful) {
        var n = el.name, t = el.type, tag = el.tagName.toLowerCase();
        if (successful === undefined) {
            successful = true;
        }

        if (successful && (!n || el.disabled || t == 'reset' || t == 'button' ||
            (t == 'checkbox' || t == 'radio') && !el.checked ||
            (t == 'submit' || t == 'image') && el.form && el.form.clk != el ||
            tag == 'select' && el.selectedIndex == -1)) {
            return null;
        }

        if (tag == 'select') {
            var index = el.selectedIndex;
            if (index < 0) {
                return null;
            }
            var a = [], ops = el.options;
            var one = (t == 'select-one');
            var max = (one ? index+1 : ops.length);
            for(var i=(one ? index : 0); i < max; i++) {
                var op = ops[i];
                if (op.selected) {
                    var v = op.value;
                    if (!v) { // extra pain for IE...
                        v = (op.attributes && op.attributes['value'] && !(op.attributes['value'].specified)) ? op.text : op.value;
                    }
                    if (one) {
                        return v;
                    }
                    a.push(v);
                }
            }
            return a;
        }
        return $(el).val();
    };

    /**
     * Clears the form data.  Takes the following actions on the form's input fields:
     *  - input text fields will have their 'value' property set to the empty string
     *  - select elements will have their 'selectedIndex' property set to -1
     *  - checkbox and radio inputs will have their 'checked' property set to false
     *  - inputs of type submit, button, reset, and hidden will *not* be effected
     *  - button elements will *not* be effected
     */
    $.fn.clearForm = function(includeHidden) {
        return this.each(function() {
            $('input,select,textarea', this).clearFields(includeHidden);
        });
    };

    /**
     * Clears the selected form elements.
     */
    $.fn.clearFields = $.fn.clearInputs = function(includeHidden) {
        var re = /^(?:color|date|datetime|email|month|number|password|range|search|tel|text|time|url|week)$/i; // 'hidden' is not in this list
        return this.each(function() {
            var t = this.type, tag = this.tagName.toLowerCase();
            if (re.test(t) || tag == 'textarea') {
                this.value = '';
            }
            else if (t == 'checkbox' || t == 'radio') {
                this.checked = false;
            }
            else if (tag == 'select') {
                this.selectedIndex = -1;
            }
            else if (includeHidden) {
                // includeHidden can be the value true, or it can be a selector string
                // indicating a special test; for example:
                //  $('#myForm').clearForm('.special:hidden')
                // the above would clean hidden inputs that have the class of 'special'
                if ( (includeHidden === true && /hidden/.test(t)) ||
                    (typeof includeHidden == 'string' && $(this).is(includeHidden)) )
                    this.value = '';
            }
        });
    };

    /**
     * Resets the form data.  Causes all form elements to be reset to their original value.
     */
    $.fn.resetForm = function() {
        return this.each(function() {
            // guard against an input with the name of 'reset'
            // note that IE reports the reset function as an 'object'
            if (typeof this.reset == 'function' || (typeof this.reset == 'object' && !this.reset.nodeType)) {this.reset();}
        });
    };

    /**
     * Enables or disables any matching elements.
     */
    $.fn.enable = function(b) {
        if (b === undefined) {
            b = true;
        }
        return this.each(function() {
            this.disabled = !b;
        });
    };

    /**
     * Checks/unchecks any matching checkboxes or radio buttons and
     * selects/deselects and matching option elements.
     */
    $.fn.selected = function(select) {
        if (select === undefined) {
            select = true;
        }
        return this.each(function() {
            var t = this.type;
            if (t == 'checkbox' || t == 'radio') {
                this.checked = select;
            }
            else if (this.tagName.toLowerCase() == 'option') {
                var $sel = $(this).parent('select');
                if (select && $sel[0] && $sel[0].type == 'select-one') {
                    // deselect all other options
                    $sel.find('option').selected(false);
                }
                this.selected = select;
            }
        });
    };

// expose debug var
    $.fn.ajaxSubmit.debug = false;

// helper fn for console logging
    function log() {
        if (!$.fn.ajaxSubmit.debug)
            return;
        var msg = '[jquery.form] ' + Array.prototype.join.call(arguments,'');
        if (window.console && window.console.log) {window.console.log(msg);}
        else if (window.opera && window.opera.postError) {window.opera.postError(msg);}
    }

})(jQuery);

/*
 * Poshy Tip jQuery plugin v1.1
 * http://vadikom.com/tools/poshy-tip-jquery-plugin-for-stylish-tooltips/
 * Copyright 2010-2011, Vasil Dinkov, http://vadikom.com/
 */

(function(e){var a=[],d=/^url\(["']?([^"'\)]*)["']?\);?$/i,c=/\.png$/i,b=e.browser.msie&&e.browser.version==6;function f(){e.each(a,function(){this.refresh(true)})}e(window).resize(f);e.Poshytip=function(h,g){this.$elm=e(h);this.opts=e.extend({},e.fn.poshytip.defaults,g);this.$tip=e(['<div class="',this.opts.className,'">','<div class="tip-inner tip-bg-image"></div>','<div class="tip-arrow tip-arrow-top tip-arrow-right tip-arrow-bottom tip-arrow-left"></div>',"</div>"].join("")).appendTo(document.body);this.$arrow=this.$tip.find("div.tip-arrow");this.$inner=this.$tip.find("div.tip-inner");this.disabled=false;this.content=null;this.init()};e.Poshytip.prototype={init:function(){a.push(this);var g=this.$elm.attr("title");this.$elm.data("title.poshytip",g!==undefined?g:null).data("poshytip",this);if(this.opts.showOn!="none"){this.$elm.bind({"mouseenter.poshytip":e.proxy(this.mouseenter,this),"mouseleave.poshytip":e.proxy(this.mouseleave,this)});switch(this.opts.showOn){case"hover":if(this.opts.alignTo=="cursor"){this.$elm.bind("mousemove.poshytip",e.proxy(this.mousemove,this))}if(this.opts.allowTipHover){this.$tip.hover(e.proxy(this.clearTimeouts,this),e.proxy(this.mouseleave,this))}break;case"focus":this.$elm.bind({"focus.poshytip":e.proxy(this.show,this),"blur.poshytip":e.proxy(this.hide,this)});break}}},mouseenter:function(g){if(this.disabled){return true}this.$elm.attr("title","");if(this.opts.showOn=="focus"){return true}this.clearTimeouts();this.showTimeout=setTimeout(e.proxy(this.show,this),this.opts.showTimeout)},mouseleave:function(g){if(this.disabled||this.asyncAnimating&&(this.$tip[0]===g.relatedTarget||jQuery.contains(this.$tip[0],g.relatedTarget))){return true}var h=this.$elm.data("title.poshytip");if(h!==null){this.$elm.attr("title",h)}if(this.opts.showOn=="focus"){return true}this.clearTimeouts();this.hideTimeout=setTimeout(e.proxy(this.hide,this),this.opts.hideTimeout)},mousemove:function(g){if(this.disabled){return true}this.eventX=g.pageX;this.eventY=g.pageY;if(this.opts.followCursor&&this.$tip.data("active")){this.calcPos();this.$tip.css({left:this.pos.l,top:this.pos.t});if(this.pos.arrow){this.$arrow[0].className="tip-arrow tip-arrow-"+this.pos.arrow}}},show:function(){if(this.disabled||this.$tip.data("active")){return}this.reset();this.update();this.display();if(this.opts.timeOnScreen){setTimeout(e.proxy(this.hide,this),this.opts.timeOnScreen)}},hide:function(){if(this.disabled||!this.$tip.data("active")){return}this.display(true)},reset:function(){this.$tip.queue([]).detach().css("visibility","hidden").data("active",false);this.$inner.find("*").poshytip("hide");if(this.opts.fade){this.$tip.css("opacity",this.opacity)}this.$arrow[0].className="tip-arrow tip-arrow-top tip-arrow-right tip-arrow-bottom tip-arrow-left";this.asyncAnimating=false},update:function(j,k){if(this.disabled){return}var i=j!==undefined;if(i){if(!k){this.opts.content=j}if(!this.$tip.data("active")){return}}else{j=this.opts.content}var h=this,g=typeof j=="function"?j.call(this.$elm[0],function(l){h.update(l)}):j=="[title]"?this.$elm.data("title.poshytip"):j;if(this.content!==g){this.$inner.empty().append(g);this.content=g}this.refresh(i)},refresh:function(h){if(this.disabled){return}if(h){if(!this.$tip.data("active")){return}var k={left:this.$tip.css("left"),top:this.$tip.css("top")}}this.$tip.css({left:0,top:0}).appendTo(document.body);if(this.opacity===undefined){this.opacity=this.$tip.css("opacity")}var l=this.$tip.css("background-image").match(d),m=this.$arrow.css("background-image").match(d);if(l){var i=c.test(l[1]);if(b&&i){this.$tip.css("background-image","none");this.$inner.css({margin:0,border:0,padding:0});l=i=false}else{this.$tip.prepend('<table border="0" cellpadding="0" cellspacing="0"><tr><td class="tip-top tip-bg-image" colspan="2"><span></span></td><td class="tip-right tip-bg-image" rowspan="2"><span></span></td></tr><tr><td class="tip-left tip-bg-image" rowspan="2"><span></span></td><td></td></tr><tr><td class="tip-bottom tip-bg-image" colspan="2"><span></span></td></tr></table>').css({border:0,padding:0,"background-image":"none","background-color":"transparent"}).find(".tip-bg-image").css("background-image",'url("'+l[1]+'")').end().find("td").eq(3).append(this.$inner)}if(i&&!e.support.opacity){this.opts.fade=false}}if(m&&!e.support.opacity){if(b&&c.test(m[1])){m=false;this.$arrow.css("background-image","none")}this.opts.fade=false}var o=this.$tip.find("table");if(b){this.$tip[0].style.width="";o.width("auto").find("td").eq(3).width("auto");var n=this.$tip.width(),j=parseInt(this.$tip.css("min-width")),g=parseInt(this.$tip.css("max-width"));if(!isNaN(j)&&n<j){n=j}else{if(!isNaN(g)&&n>g){n=g}}this.$tip.add(o).width(n).eq(0).find("td").eq(3).width("100%")}else{if(o[0]){o.width("auto").find("td").eq(3).width("auto").end().end().width(document.defaultView&&document.defaultView.getComputedStyle&&parseFloat(document.defaultView.getComputedStyle(this.$tip[0],null).width)||this.$tip.width()).find("td").eq(3).width("100%")}}this.tipOuterW=this.$tip.outerWidth();this.tipOuterH=this.$tip.outerHeight();this.calcPos();if(m&&this.pos.arrow){this.$arrow[0].className="tip-arrow tip-arrow-"+this.pos.arrow;this.$arrow.css("visibility","inherit")}if(h){this.asyncAnimating=true;var p=this;this.$tip.css(k).animate({left:this.pos.l,top:this.pos.t},200,function(){p.asyncAnimating=false})}else{this.$tip.css({left:this.pos.l,top:this.pos.t})}},display:function(h){var i=this.$tip.data("active");if(i&&!h||!i&&h){return}this.$tip.stop();if((this.opts.slide&&this.pos.arrow||this.opts.fade)&&(h&&this.opts.hideAniDuration||!h&&this.opts.showAniDuration)){var m={},l={};if(this.opts.slide&&this.pos.arrow){var k,g;if(this.pos.arrow=="bottom"||this.pos.arrow=="top"){k="top";g="bottom"}else{k="left";g="right"}var j=parseInt(this.$tip.css(k));m[k]=j+(h?0:(this.pos.arrow==g?-this.opts.slideOffset:this.opts.slideOffset));l[k]=j+(h?(this.pos.arrow==g?this.opts.slideOffset:-this.opts.slideOffset):0)+"px"}if(this.opts.fade){m.opacity=h?this.$tip.css("opacity"):0;l.opacity=h?0:this.opacity}this.$tip.css(m).animate(l,this.opts[h?"hideAniDuration":"showAniDuration"])}h?this.$tip.queue(e.proxy(this.reset,this)):this.$tip.css("visibility","inherit");this.$tip.data("active",!i)},disable:function(){this.reset();this.disabled=true},enable:function(){this.disabled=false},destroy:function(){this.reset();this.$tip.remove();delete this.$tip;this.content=null;this.$elm.unbind(".poshytip").removeData("title.poshytip").removeData("poshytip");a.splice(e.inArray(this,a),1)},clearTimeouts:function(){if(this.showTimeout){clearTimeout(this.showTimeout);this.showTimeout=0}if(this.hideTimeout){clearTimeout(this.hideTimeout);this.hideTimeout=0}},calcPos:function(){var n={l:0,t:0,arrow:""},h=e(window),k={l:h.scrollLeft(),t:h.scrollTop(),w:h.width(),h:h.height()},p,j,m,i,q,g;if(this.opts.alignTo=="cursor"){p=j=m=this.eventX;i=q=g=this.eventY}else{var o=this.$elm.offset(),l={l:o.left,t:o.top,w:this.$elm.outerWidth(),h:this.$elm.outerHeight()};p=l.l+(this.opts.alignX!="inner-right"?0:l.w);j=p+Math.floor(l.w/2);m=p+(this.opts.alignX!="inner-left"?l.w:0);i=l.t+(this.opts.alignY!="inner-bottom"?0:l.h);q=i+Math.floor(l.h/2);g=i+(this.opts.alignY!="inner-top"?l.h:0)}switch(this.opts.alignX){case"right":case"inner-left":n.l=m+this.opts.offsetX;if(n.l+this.tipOuterW>k.l+k.w){n.l=k.l+k.w-this.tipOuterW}if(this.opts.alignX=="right"||this.opts.alignY=="center"){n.arrow="left"}break;case"center":n.l=j-Math.floor(this.tipOuterW/2);if(n.l+this.tipOuterW>k.l+k.w){n.l=k.l+k.w-this.tipOuterW}else{if(n.l<k.l){n.l=k.l}}break;default:n.l=p-this.tipOuterW-this.opts.offsetX;if(n.l<k.l){n.l=k.l}if(this.opts.alignX=="left"||this.opts.alignY=="center"){n.arrow="right"}}switch(this.opts.alignY){case"bottom":case"inner-top":n.t=g+this.opts.offsetY;if(!n.arrow||this.opts.alignTo=="cursor"){n.arrow="top"}if(n.t+this.tipOuterH>k.t+k.h){n.t=i-this.tipOuterH-this.opts.offsetY;if(n.arrow=="top"){n.arrow="bottom"}}break;case"center":n.t=q-Math.floor(this.tipOuterH/2);if(n.t+this.tipOuterH>k.t+k.h){n.t=k.t+k.h-this.tipOuterH}else{if(n.t<k.t){n.t=k.t}}break;default:n.t=i-this.tipOuterH-this.opts.offsetY;if(!n.arrow||this.opts.alignTo=="cursor"){n.arrow="bottom"}if(n.t<k.t){n.t=g+this.opts.offsetY;if(n.arrow=="bottom"){n.arrow="top"}}}this.pos=n}};e.fn.poshytip=function(h){if(typeof h=="string"){var g=arguments,k=h;Array.prototype.shift.call(g);if(k=="destroy"){this.die("mouseenter.poshytip").die("focus.poshytip")}return this.each(function(){var l=e(this).data("poshytip");if(l&&l[k]){l[k].apply(l,g)}})}var i=e.extend({},e.fn.poshytip.defaults,h);if(!e("#poshytip-css-"+i.className)[0]){e(['<style id="poshytip-css-',i.className,'" type="text/css">',"div.",i.className,"{visibility:hidden;position:absolute;top:0;left:0;}","div.",i.className," table, div.",i.className," td{margin:0;font-family:inherit;font-size:inherit;font-weight:inherit;font-style:inherit;font-variant:inherit;}","div.",i.className," td.tip-bg-image span{display:block;font:1px/1px sans-serif;height:",i.bgImageFrameSize,"px;width:",i.bgImageFrameSize,"px;overflow:hidden;}","div.",i.className," td.tip-right{background-position:100% 0;}","div.",i.className," td.tip-bottom{background-position:100% 100%;}","div.",i.className," td.tip-left{background-position:0 100%;}","div.",i.className," div.tip-inner{background-position:-",i.bgImageFrameSize,"px -",i.bgImageFrameSize,"px;}","div.",i.className," div.tip-arrow{visibility:hidden;position:absolute;overflow:hidden;font:1px/1px sans-serif;}","</style>"].join("")).appendTo("head")}if(i.liveEvents&&i.showOn!="none"){var j=e.extend({},i,{liveEvents:false});switch(i.showOn){case"hover":this.live("mouseenter.poshytip",function(){var l=e(this);if(!l.data("poshytip")){l.poshytip(j).poshytip("mouseenter")}});break;case"focus":this.live("focus.poshytip",function(){var l=e(this);if(!l.data("poshytip")){l.poshytip(j).poshytip("show")}});break}return this}return this.each(function(){new e.Poshytip(this,i)})};e.fn.poshytip.defaults={content:"[title]",className:"tip-yellow",bgImageFrameSize:10,showTimeout:500,hideTimeout:100,timeOnScreen:0,showOn:"hover",liveEvents:false,alignTo:"cursor",alignX:"right",alignY:"top",offsetX:-22,offsetY:18,allowTipHover:true,followCursor:false,fade:true,slide:true,slideOffset:8,showAniDuration:300,hideAniDuration:300}})(jQuery);


/**
 * jQuery Validation Plugin 1.9.0
 *
 * http://bassistance.de/jquery-plugins/jquery-plugin-validation/
 * http://docs.jquery.com/Plugins/Validation
 *
 * Copyright (c) 2006 - 2011 Jörn Zaefferer
 *
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 */
(function(c){c.extend(c.fn,{validate:function(a){if(this.length){var b=c.data(this[0],"validator");if(b)return b;this.attr("novalidate","novalidate");b=new c.validator(a,this[0]);c.data(this[0],"validator",b);if(b.settings.onsubmit){a=this.find("input, button");a.filter(".cancel").click(function(){b.cancelSubmit=true});b.settings.submitHandler&&a.filter(":submit").click(function(){b.submitButton=this});this.submit(function(d){function e(){if(b.settings.submitHandler){if(b.submitButton)var f=c("<input type='hidden'/>").attr("name",
    b.submitButton.name).val(b.submitButton.value).appendTo(b.currentForm);b.settings.submitHandler.call(b,b.currentForm);b.submitButton&&f.remove();return false}return true}b.settings.debug&&d.preventDefault();if(b.cancelSubmit){b.cancelSubmit=false;return e()}if(b.form()){if(b.pendingRequest){b.formSubmitted=true;return false}return e()}else{b.focusInvalid();return false}})}return b}else a&&a.debug&&window.console&&console.warn("nothing selected, can't validate, returning nothing")},valid:function(){if(c(this[0]).is("form"))return this.validate().form();
else{var a=true,b=c(this[0].form).validate();this.each(function(){a&=b.element(this)});return a}},removeAttrs:function(a){var b={},d=this;c.each(a.split(/\s/),function(e,f){b[f]=d.attr(f);d.removeAttr(f)});return b},rules:function(a,b){var d=this[0];if(a){var e=c.data(d.form,"validator").settings,f=e.rules,g=c.validator.staticRules(d);switch(a){case "add":c.extend(g,c.validator.normalizeRule(b));f[d.name]=g;if(b.messages)e.messages[d.name]=c.extend(e.messages[d.name],b.messages);break;case "remove":if(!b){delete f[d.name];
    return g}var h={};c.each(b.split(/\s/),function(j,i){h[i]=g[i];delete g[i]});return h}}d=c.validator.normalizeRules(c.extend({},c.validator.metadataRules(d),c.validator.classRules(d),c.validator.attributeRules(d),c.validator.staticRules(d)),d);if(d.required){e=d.required;delete d.required;d=c.extend({required:e},d)}return d}});c.extend(c.expr[":"],{blank:function(a){return!c.trim(""+a.value)},filled:function(a){return!!c.trim(""+a.value)},unchecked:function(a){return!a.checked}});c.validator=function(a,
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              b){this.settings=c.extend(true,{},c.validator.defaults,a);this.currentForm=b;this.init()};c.validator.format=function(a,b){if(arguments.length==1)return function(){var d=c.makeArray(arguments);d.unshift(a);return c.validator.format.apply(this,d)};if(arguments.length>2&&b.constructor!=Array)b=c.makeArray(arguments).slice(1);if(b.constructor!=Array)b=[b];c.each(b,function(d,e){a=a.replace(RegExp("\\{"+d+"\\}","g"),e)});return a};c.extend(c.validator,{defaults:{messages:{},groups:{},rules:{},errorClass:"error",
    validClass:"valid",errorElement:"label",focusInvalid:true,errorContainer:c([]),errorLabelContainer:c([]),onsubmit:true,ignore:":hidden",ignoreTitle:false,onfocusin:function(a){this.lastActive=a;if(this.settings.focusCleanup&&!this.blockFocusCleanup){this.settings.unhighlight&&this.settings.unhighlight.call(this,a,this.settings.errorClass,this.settings.validClass);this.addWrapper(this.errorsFor(a)).hide()}},onfocusout:function(a){if(!this.checkable(a)&&(a.name in this.submitted||!this.optional(a)))this.element(a)},
    onkeyup:function(a){if(a.name in this.submitted||a==this.lastElement)this.element(a)},onclick:function(a){if(a.name in this.submitted)this.element(a);else a.parentNode.name in this.submitted&&this.element(a.parentNode)},highlight:function(a,b,d){a.type==="radio"?this.findByName(a.name).addClass(b).removeClass(d):c(a).addClass(b).removeClass(d)},unhighlight:function(a,b,d){a.type==="radio"?this.findByName(a.name).removeClass(b).addClass(d):c(a).removeClass(b).addClass(d)}},setDefaults:function(a){c.extend(c.validator.defaults,
    a)},messages:{required:"This field is required.",remote:"Please fix this field.",email:"Please enter a valid email address.",url:"Please enter a valid URL.",date:"Please enter a valid date.",dateISO:"Please enter a valid date (ISO).",number:"Please enter a valid number.",digits:"Please enter only digits.",creditcard:"Please enter a valid credit card number.",equalTo:"Please enter the same value again.",accept:"Please enter a value with a valid extension.",maxlength:c.validator.format("Please enter no more than {0} characters."),
    minlength:c.validator.format("Please enter at least {0} characters."),rangelength:c.validator.format("Please enter a value between {0} and {1} characters long."),range:c.validator.format("Please enter a value between {0} and {1}."),max:c.validator.format("Please enter a value less than or equal to {0}."),min:c.validator.format("Please enter a value greater than or equal to {0}.")},autoCreateRanges:false,prototype:{init:function(){function a(e){var f=c.data(this[0].form,"validator"),g="on"+e.type.replace(/^validate/,
        "");f.settings[g]&&f.settings[g].call(f,this[0],e)}this.labelContainer=c(this.settings.errorLabelContainer);this.errorContext=this.labelContainer.length&&this.labelContainer||c(this.currentForm);this.containers=c(this.settings.errorContainer).add(this.settings.errorLabelContainer);this.submitted={};this.valueCache={};this.pendingRequest=0;this.pending={};this.invalid={};this.reset();var b=this.groups={};c.each(this.settings.groups,function(e,f){c.each(f.split(/\s/),function(g,h){b[h]=e})});var d=
    this.settings.rules;c.each(d,function(e,f){d[e]=c.validator.normalizeRule(f)});c(this.currentForm).validateDelegate("[type='text'], [type='password'], [type='file'], select, textarea, [type='number'], [type='search'] ,[type='tel'], [type='url'], [type='email'], [type='datetime'], [type='date'], [type='month'], [type='week'], [type='time'], [type='datetime-local'], [type='range'], [type='color'] ","focusin focusout keyup",a).validateDelegate("[type='radio'], [type='checkbox'], select, option","click",
    a);this.settings.invalidHandler&&c(this.currentForm).bind("invalid-form.validate",this.settings.invalidHandler)},form:function(){this.checkForm();c.extend(this.submitted,this.errorMap);this.invalid=c.extend({},this.errorMap);this.valid()||c(this.currentForm).triggerHandler("invalid-form",[this]);this.showErrors();return this.valid()},checkForm:function(){this.prepareForm();for(var a=0,b=this.currentElements=this.elements();b[a];a++)this.check(b[a]);return this.valid()},element:function(a){this.lastElement=
    a=this.validationTargetFor(this.clean(a));this.prepareElement(a);this.currentElements=c(a);var b=this.check(a);if(b)delete this.invalid[a.name];else this.invalid[a.name]=true;if(!this.numberOfInvalids())this.toHide=this.toHide.add(this.containers);this.showErrors();return b},showErrors:function(a){if(a){c.extend(this.errorMap,a);this.errorList=[];for(var b in a)this.errorList.push({message:a[b],element:this.findByName(b)[0]});this.successList=c.grep(this.successList,function(d){return!(d.name in a)})}this.settings.showErrors?
    this.settings.showErrors.call(this,this.errorMap,this.errorList):this.defaultShowErrors()},resetForm:function(){c.fn.resetForm&&c(this.currentForm).resetForm();this.submitted={};this.lastElement=null;this.prepareForm();this.hideErrors();this.elements().removeClass(this.settings.errorClass)},numberOfInvalids:function(){return this.objectLength(this.invalid)},objectLength:function(a){var b=0,d;for(d in a)b++;return b},hideErrors:function(){this.addWrapper(this.toHide).hide()},valid:function(){return this.size()==
    0},size:function(){return this.errorList.length},focusInvalid:function(){if(this.settings.focusInvalid)try{c(this.findLastActive()||this.errorList.length&&this.errorList[0].element||[]).filter(":visible").focus().trigger("focusin")}catch(a){}},findLastActive:function(){var a=this.lastActive;return a&&c.grep(this.errorList,function(b){return b.element.name==a.name}).length==1&&a},elements:function(){var a=this,b={};return c(this.currentForm).find("input, select, textarea").not(":submit, :reset, :image, [disabled]").not(this.settings.ignore).filter(function(){!this.name&&
a.settings.debug&&window.console&&console.error("%o has no name assigned",this);if(this.name in b||!a.objectLength(c(this).rules()))return false;return b[this.name]=true})},clean:function(a){return c(a)[0]},errors:function(){return c(this.settings.errorElement+"."+this.settings.errorClass,this.errorContext)},reset:function(){this.successList=[];this.errorList=[];this.errorMap={};this.toShow=c([]);this.toHide=c([]);this.currentElements=c([])},prepareForm:function(){this.reset();this.toHide=this.errors().add(this.containers)},
    prepareElement:function(a){this.reset();this.toHide=this.errorsFor(a)},check:function(a){a=this.validationTargetFor(this.clean(a));var b=c(a).rules(),d=false,e;for(e in b){var f={method:e,parameters:b[e]};try{var g=c.validator.methods[e].call(this,a.value.replace(/\r/g,""),a,f.parameters);if(g=="dependency-mismatch")d=true;else{d=false;if(g=="pending"){this.toHide=this.toHide.not(this.errorsFor(a));return}if(!g){this.formatAndAdd(a,f);return false}}}catch(h){this.settings.debug&&window.console&&console.log("exception occured when checking element "+
    a.id+", check the '"+f.method+"' method",h);throw h;}}if(!d){this.objectLength(b)&&this.successList.push(a);return true}},customMetaMessage:function(a,b){if(c.metadata){var d=this.settings.meta?c(a).metadata()[this.settings.meta]:c(a).metadata();return d&&d.messages&&d.messages[b]}},customMessage:function(a,b){var d=this.settings.messages[a];return d&&(d.constructor==String?d:d[b])},findDefined:function(){for(var a=0;a<arguments.length;a++)if(arguments[a]!==undefined)return arguments[a]},defaultMessage:function(a,
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         b){return this.findDefined(this.customMessage(a.name,b),this.customMetaMessage(a,b),!this.settings.ignoreTitle&&a.title||undefined,c.validator.messages[b],"<strong>Warning: No message defined for "+a.name+"</strong>")},formatAndAdd:function(a,b){var d=this.defaultMessage(a,b.method),e=/\$?\{(\d+)\}/g;if(typeof d=="function")d=d.call(this,b.parameters,a);else if(e.test(d))d=jQuery.format(d.replace(e,"{$1}"),b.parameters);this.errorList.push({message:d,element:a});this.errorMap[a.name]=d;this.submitted[a.name]=
        d},addWrapper:function(a){if(this.settings.wrapper)a=a.add(a.parent(this.settings.wrapper));return a},defaultShowErrors:function(){for(var a=0;this.errorList[a];a++){var b=this.errorList[a];this.settings.highlight&&this.settings.highlight.call(this,b.element,this.settings.errorClass,this.settings.validClass);this.showLabel(b.element,b.message)}if(this.errorList.length)this.toShow=this.toShow.add(this.containers);if(this.settings.success)for(a=0;this.successList[a];a++)this.showLabel(this.successList[a]);
        if(this.settings.unhighlight){a=0;for(b=this.validElements();b[a];a++)this.settings.unhighlight.call(this,b[a],this.settings.errorClass,this.settings.validClass)}this.toHide=this.toHide.not(this.toShow);this.hideErrors();this.addWrapper(this.toShow).show()},validElements:function(){return this.currentElements.not(this.invalidElements())},invalidElements:function(){return c(this.errorList).map(function(){return this.element})},showLabel:function(a,b){var d=this.errorsFor(a);if(d.length){d.removeClass(this.settings.validClass).addClass(this.settings.errorClass);
        d.attr("generated")&&d.html(b)}else{d=c("<"+this.settings.errorElement+"/>").attr({"for":this.idOrName(a),generated:true}).addClass(this.settings.errorClass).html(b||"");if(this.settings.wrapper)d=d.hide().show().wrap("<"+this.settings.wrapper+"/>").parent();this.labelContainer.append(d).length||(this.settings.errorPlacement?this.settings.errorPlacement(d,c(a)):d.insertAfter(a))}if(!b&&this.settings.success){d.text("");typeof this.settings.success=="string"?d.addClass(this.settings.success):this.settings.success(d)}this.toShow=
        this.toShow.add(d)},errorsFor:function(a){var b=this.idOrName(a);return this.errors().filter(function(){return c(this).attr("for")==b})},idOrName:function(a){return this.groups[a.name]||(this.checkable(a)?a.name:a.id||a.name)},validationTargetFor:function(a){if(this.checkable(a))a=this.findByName(a.name).not(this.settings.ignore)[0];return a},checkable:function(a){return/radio|checkbox/i.test(a.type)},findByName:function(a){var b=this.currentForm;return c(document.getElementsByName(a)).map(function(d,
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                e){return e.form==b&&e.name==a&&e||null})},getLength:function(a,b){switch(b.nodeName.toLowerCase()){case "select":return c("option:selected",b).length;case "input":if(this.checkable(b))return this.findByName(b.name).filter(":checked").length}return a.length},depend:function(a,b){return this.dependTypes[typeof a]?this.dependTypes[typeof a](a,b):true},dependTypes:{"boolean":function(a){return a},string:function(a,b){return!!c(a,b.form).length},"function":function(a,b){return a(b)}},optional:function(a){return!c.validator.methods.required.call(this,
            c.trim(a.value),a)&&"dependency-mismatch"},startRequest:function(a){if(!this.pending[a.name]){this.pendingRequest++;this.pending[a.name]=true}},stopRequest:function(a,b){this.pendingRequest--;if(this.pendingRequest<0)this.pendingRequest=0;delete this.pending[a.name];if(b&&this.pendingRequest==0&&this.formSubmitted&&this.form()){c(this.currentForm).submit();this.formSubmitted=false}else if(!b&&this.pendingRequest==0&&this.formSubmitted){c(this.currentForm).triggerHandler("invalid-form",[this]);this.formSubmitted=
        false}},previousValue:function(a){return c.data(a,"previousValue")||c.data(a,"previousValue",{old:null,valid:true,message:this.defaultMessage(a,"remote")})}},classRuleSettings:{required:{required:true},email:{email:true},url:{url:true},date:{date:true},dateISO:{dateISO:true},dateDE:{dateDE:true},number:{number:true},numberDE:{numberDE:true},digits:{digits:true},creditcard:{creditcard:true}},addClassRules:function(a,b){a.constructor==String?this.classRuleSettings[a]=b:c.extend(this.classRuleSettings,
    a)},classRules:function(a){var b={};(a=c(a).attr("class"))&&c.each(a.split(" "),function(){this in c.validator.classRuleSettings&&c.extend(b,c.validator.classRuleSettings[this])});return b},attributeRules:function(a){var b={};a=c(a);for(var d in c.validator.methods){var e;if(e=d==="required"&&typeof c.fn.prop==="function"?a.prop(d):a.attr(d))b[d]=e;else if(a[0].getAttribute("type")===d)b[d]=true}b.maxlength&&/-1|2147483647|524288/.test(b.maxlength)&&delete b.maxlength;return b},metadataRules:function(a){if(!c.metadata)return{};
    var b=c.data(a.form,"validator").settings.meta;return b?c(a).metadata()[b]:c(a).metadata()},staticRules:function(a){var b={},d=c.data(a.form,"validator");if(d.settings.rules)b=c.validator.normalizeRule(d.settings.rules[a.name])||{};return b},normalizeRules:function(a,b){c.each(a,function(d,e){if(e===false)delete a[d];else if(e.param||e.depends){var f=true;switch(typeof e.depends){case "string":f=!!c(e.depends,b.form).length;break;case "function":f=e.depends.call(b,b)}if(f)a[d]=e.param!==undefined?
    e.param:true;else delete a[d]}});c.each(a,function(d,e){a[d]=c.isFunction(e)?e(b):e});c.each(["minlength","maxlength","min","max"],function(){if(a[this])a[this]=Number(a[this])});c.each(["rangelength","range"],function(){if(a[this])a[this]=[Number(a[this][0]),Number(a[this][1])]});if(c.validator.autoCreateRanges){if(a.min&&a.max){a.range=[a.min,a.max];delete a.min;delete a.max}if(a.minlength&&a.maxlength){a.rangelength=[a.minlength,a.maxlength];delete a.minlength;delete a.maxlength}}a.messages&&delete a.messages;
    return a},normalizeRule:function(a){if(typeof a=="string"){var b={};c.each(a.split(/\s/),function(){b[this]=true});a=b}return a},addMethod:function(a,b,d){c.validator.methods[a]=b;c.validator.messages[a]=d!=undefined?d:c.validator.messages[a];b.length<3&&c.validator.addClassRules(a,c.validator.normalizeRule(a))},methods:{required:function(a,b,d){if(!this.depend(d,b))return"dependency-mismatch";switch(b.nodeName.toLowerCase()){case "select":return(a=c(b).val())&&a.length>0;case "input":if(this.checkable(b))return this.getLength(a,
        b)>0;default:return c.trim(a).length>0}},remote:function(a,b,d){if(this.optional(b))return"dependency-mismatch";var e=this.previousValue(b);this.settings.messages[b.name]||(this.settings.messages[b.name]={});e.originalMessage=this.settings.messages[b.name].remote;this.settings.messages[b.name].remote=e.message;d=typeof d=="string"&&{url:d}||d;if(this.pending[b.name])return"pending";if(e.old===a)return e.valid;e.old=a;var f=this;this.startRequest(b);var g={};g[b.name]=a;c.ajax(c.extend(true,{url:d,
    mode:"abort",port:"validate"+b.name,dataType:"json",data:g,success:function(h){f.settings.messages[b.name].remote=e.originalMessage;var j=h===true;if(j){var i=f.formSubmitted;f.prepareElement(b);f.formSubmitted=i;f.successList.push(b);f.showErrors()}else{i={};h=h||f.defaultMessage(b,"remote");i[b.name]=e.message=c.isFunction(h)?h(a):h;f.showErrors(i)}e.valid=j;f.stopRequest(b,j)}},d));return"pending"},minlength:function(a,b,d){return this.optional(b)||this.getLength(c.trim(a),b)>=d},maxlength:function(a,
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               b,d){return this.optional(b)||this.getLength(c.trim(a),b)<=d},rangelength:function(a,b,d){a=this.getLength(c.trim(a),b);return this.optional(b)||a>=d[0]&&a<=d[1]},min:function(a,b,d){return this.optional(b)||a>=d},max:function(a,b,d){return this.optional(b)||a<=d},range:function(a,b,d){return this.optional(b)||a>=d[0]&&a<=d[1]},email:function(a,b){return this.optional(b)||/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))$/i.test(a)},
    url:function(a,b){return this.optional(b)||/^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(a)},
    date:function(a,b){return this.optional(b)||!/Invalid|NaN/.test(new Date(a))},dateISO:function(a,b){return this.optional(b)||/^\d{4}[\/-]\d{1,2}[\/-]\d{1,2}$/.test(a)},number:function(a,b){return this.optional(b)||/^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:\.\d+)?$/.test(a)},digits:function(a,b){return this.optional(b)||/^\d+$/.test(a)},creditcard:function(a,b){if(this.optional(b))return"dependency-mismatch";if(/[^0-9 -]+/.test(a))return false;var d=0,e=0,f=false;a=a.replace(/\D/g,"");for(var g=a.length-1;g>=
    0;g--){e=a.charAt(g);e=parseInt(e,10);if(f)if((e*=2)>9)e-=9;d+=e;f=!f}return d%10==0},accept:function(a,b,d){d=typeof d=="string"?d.replace(/,/g,"|"):"png|jpe?g|gif";return this.optional(b)||a.match(RegExp(".("+d+")$","i"))},equalTo:function(a,b,d){d=c(d).unbind(".validate-equalTo").bind("blur.validate-equalTo",function(){c(b).valid()});return a==d.val()}}});c.format=c.validator.format})(jQuery);
(function(c){var a={};if(c.ajaxPrefilter)c.ajaxPrefilter(function(d,e,f){e=d.port;if(d.mode=="abort"){a[e]&&a[e].abort();a[e]=f}});else{var b=c.ajax;c.ajax=function(d){var e=("port"in d?d:c.ajaxSettings).port;if(("mode"in d?d:c.ajaxSettings).mode=="abort"){a[e]&&a[e].abort();return a[e]=b.apply(this,arguments)}return b.apply(this,arguments)}}})(jQuery);
(function(c){!jQuery.event.special.focusin&&!jQuery.event.special.focusout&&document.addEventListener&&c.each({focus:"focusin",blur:"focusout"},function(a,b){function d(e){e=c.event.fix(e);e.type=b;return c.event.handle.call(this,e)}c.event.special[b]={setup:function(){this.addEventListener(a,d,true)},teardown:function(){this.removeEventListener(a,d,true)},handler:function(e){arguments[0]=c.event.fix(e);arguments[0].type=b;return c.event.handle.apply(this,arguments)}}});c.extend(c.fn,{validateDelegate:function(a,
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    b,d){return this.bind(b,function(e){var f=c(e.target);if(f.is(a))return d.apply(f,arguments)})}})})(jQuery);


;
$.fn.digits = function(){
    return this.each(function(){
        $(this).text( $(this).text().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") );
    });
};


/*
 Comment Editor
 */
$.fn.CommentEditor=function(a){function g(a){$("#comment_"+a).find(d).hide().end().find(e).show().end()}function h(a){$("#comment_"+a).find(d).show().end().find(e).hide()}function i(a){var c={status:"close",comment_id:a,csrf_vip:f};$.post(b.url,c,function(b){return b.error?$.error("Could not moderate comment."):($("#comment_"+a).hide(),void 0)})}function j(a){var c=$("#comment_"+a).find(".editCommentBox textarea").val(),d={status:"update",comment:c,comment_id:a,csrf_vip:f};$.post(b.url,d,function(b){return b.error?$.error("Could not save comment."):($("#comment_"+a).find(".comment_body p").html(b.comment),h(a),void 0)})}var b,c=location.protocol+"//"+location.hostname;b=$.extend({url:c+"/forum/comment",comment_body:".comment_body",showEditor:".edit_link",hideEditor:".cancel_edit",saveComment:".submit_edit",closeComment:".mod_link"},a);var d=[b.comment_body,b.showEditor,b.closeComment].join(","),e=".editCommentBox",f=$("#comment_form input[type=hidden]").val();return this.each(function(){var a=this.id.replace("comment_",""),c=$(this);c.find(b.showEditor).click(function(){return g(a),!1}),c.find(b.hideEditor).click(function(){return h(a),!1}),c.find(b.saveComment).click(function(){return j(a),!1}),c.find(b.closeComment).click(function(){return i(a),!1})})},$(function(){$(".comment").CommentEditor()});
//

/* for private meeting form */
var SOE=function(){this.bookingPos=1;this.emailPos=3;this.phonePos=2;this.link;this.btLink;this.testLink;this.code="";this.bookingCode;this.emailCode;this.phoneCode;this.onclickProperty='onclick="soe.toggleDiv(';this.onclickProperty+="'SOWidgetContent','SOWidgetToggle')"+'"';this.phoneTitle;this.phoneText;this.emailTitle;this.emailBText;this.emailBTextColor="";this.emailBBGColor="";this.emailSendTo;this.emailAfter;this.emailAck="ScheduleOnce Mailer";this.bookingTitle;this.bookingBText;this.bookingBBGColor;this.bookingBTextColor;this.widgetTitle;this.widgetColor;this.widgetBGColor;this.widgetLeft="left";this.pageName="";this.WidgetBoxStyle="z-index:10000;font-family: Tahoma, HelveticaNeue, Arial;bottom:0px; margin:0px;padding: 0px; width: 260px; font-size:14px; border: solid 1px #FFF; background-color: #FFF; -webkit-border-top-left-radius: 4px; -webkit-border-top-right-radius: 4px; border-top-left-radius: 4px; border-top-right-radius: 4px;-webkit-box-shadow: 0px 3px 5px 1px rgba(0,0,0,0.4);box-shadow: 0px 3px 5px 1px rgba(0,0,0,0.4)";this.mWidgetBoxStyle="font-family: Tahoma, HelveticaNeue, Arial;margin: 0px auto;z-index: 10000;padding: 0px;width: 260px;border: 1px solid #FFF;background-color: #FFF;border-top-left-radius: 4px;border-top-right-radius: 4px;border-bottom-right-radius: 4px;border-bottom-left-radius: 4px;-webkit-box-shadow: 0 0px 9px -1px #000;box-shadow: 0 0px 9px -1px #000;opacity: 1;font-size:14px;top:2em;";this.widgetMeinHeadStyle="padding:0px; margin:0.8em auto;font-size:1em; color:#161616;  font-weight:bold;   display:block;  width:100%;";this.displayCount=3;this.postCode;this.isMobileView=false;this.isLive=true;this.divMobileTitle="";this.widgetHeadh5Style;this.noteValue="'Your note'";this.emailValue="'Your email'";this.emptyValue="''";this.bookingLightBoxCode="";this.isLightBox=false;this.calcWindowLeft=(this.getWindowWidth()-750)/2;this.winHeight="innerHeight"in window?window.innerHeight:document.documentElement.clientHeight;this.minWidthValue=774;this.URL="http://www.scheduleonce.com";this.isWidget=false;this.contactUsDiv="";this.prevTop=0;this.prevLeft=0;this.prevHtml="";this.oldScreenCssPixelRatio=-1;this.runCode=false};SOE.prototype.getBookingCode=function(){if(this.bookingPos!=-1){var bookingButtonStyle="background-color:"+(this.bookingBBGColor==""?"#FE9E0C":this.bookingBBGColor)+";-webkit-border-radius: 4px; border-radius: 4px; color:"+(this.bookingBTextColor==""?"#000000":this.bookingBTextColor)+"; cursor: pointer;font-size: 1em;font-weight: bold; margin: 0 auto; padding: 0em;width: 100%;text-align:center;-webkit-box-shadow: 0px 1px 2px #000 ;box-shadow: 0px 1px 2px #000;line-height:3em;height:3em;";this.bookingCode='<div id="SOBookingDiv" style="display:'+(this.bookingPos==0?"none":"block")+';padding:0 0.7em 0 0.7em; margin:0px 0px 1em 0px;"><div style="width:100%;;margin-bottom:0.4em;"><div style="float:left;margin-top:0px;width:80%;"><p style="'+this.widgetMeinHeadStyle.replace("margin:0.8em auto;","margin:"+(this.isMobileView?"1em auto 0 auto;":"0.8em auto 0.567em auto;"))+'"> ';this.bookingCode+=this.decodeToHTML(this.bookingTitle)+"</p></div>";this.bookingCode+='<div align="right" style="float:right;width:15%"><img src="'+this.URL+"/images/"+(this.isMobileView?"SOCalIconX2.png":"SOCalIcon.png")+'" style="max-height:2em;max-width:2.5em;margin-top:0.8em;"/></div><div style="clear:both;"></div></div>';this.bookingCode+='<div style="padding:0px; margin:0px auto; width:100%;">';this.bookingCode+='<a href="#" onclick="soe.toggleLightBox('+this.pageName+')" style="text-decoration:none;"><div id="SOBookingButton" style="'+bookingButtonStyle+'">'+this.decodeToHTML(this.bookingBText)+"</div></a></div></div>";this.bookingLightBoxCode='<div id="SOLightBox'+this.pageName.replace(/'/g,"")+'" name="SOLightBox" style="top:-10000px; position:fixed; padding:0px; margin:0px;"><div id="SOInnerLightBox">'+this.getIframeHTMLCode(this.link)+"</div></div>"}};SOE.prototype.getEmailCode=function(){var emailButtonStyle="background-color:"+(this.emailBBGColor==""?"#fe9e0c":this.emailBBGColor)+";color:"+(this.emailBTextColor==""?"#000":this.emailBTextColor)+";margin:"+(this.isLive&&this.isMobileView?"0px 0px 0px 5%;":"0px 0px 0px 0px;")+"font-size: 1em; width: 33%;height:2em;line-height:2em;-webkit-border-radius: 4px; border-radius: 4px; text-align:center;-webkit-box-shadow: 0px 1px 2px #000;box-shadow: 0px 1px 2px #000; float:right;cursor:pointer;";this.emailCode='<div id="soemailDiv" style="display:'+(this.emailPos==0?"none":"block")+';padding:0 0.7em 0 0.7em;margin:0px 0px 1em 0px;"><p style="'+this.widgetMeinHeadStyle.replace("margin:0.8em auto","margin:0.7em auto 0.5em auto")+'">';this.emailCode+=this.decodeToHTML(this.emailTitle)+'</p><div style="padding:0px; margin:0px auto 0px auto; width:100%;"><textarea id="SONote" onfocus="if(this.value=='+this.noteValue+"){this.value="+this.emptyValue+'}" onblur="if(this.value=='+this.emptyValue+"){this.value="+this.noteValue+'} soe.onBlurText(this)" maxlength="200" name="" cols="" rows="" style="margin: 0px; height: 2.2em; border: 1px solid #bcbcbc; padding: 5px; width: 95%;color:#666666;font-size:1em;-webkit-border-radius: 0px; border-radius: 0px;" ></textarea>';this.emailCode+='<div id="SONoteErr" style="display:none;color:Red;font-size:0.857em;">Note is a mandatory field</div><div style="margin-top:0.7em;width:100%;">';this.emailCode+='<input id="SOSenderEmail" onfocus="if(this.value=='+this.emailValue+"){this.value="+this.emptyValue+'}" onblur="if(this.value=='+this.emptyValue+"){this.value="+this.emailValue+'} soe.onBlurText(this)" name="" type="email" value="'+this.emailValue.replace(/'/g,"")+'" style="margin: 0px; border: 1px solid #bcbcbc; padding: 0px 0px 0px 5px; height: 2em; width: 58%; color:#666666;font-size:1em;-webkit-border-radius: 0px; border-radius: 0px;" />';this.emailCode+='<div id="SOEmailButton" onclick="soe.sendMail()" style="'+emailButtonStyle+'">'+this.decodeToHTML(this.emailBText)+"</div>";this.emailCode+='<div id="SOSenderEmailErr" style="display:none;color:Red; font-size:0.857em;">Email is a mandatory field</div></div></div></div>'};SOE.prototype.getPhoneCode=function(){this.phoneCode='<div id="SOPhoneDiv" style="display:'+(this.phonePos==0?"none":"block")+";padding:0 0.7em 0 0.7em; margin: -0.47em  0px  1em  0px;/* background-image: url("+this.URL+'/images/Phone_icon.png); background-repeat: no-repeat; background-position: 200px center;background-size:31px 41px;*/"><div style="width:90%;"><div style="float:left;margin-top:0px;width:90%;"><p style="line-height:1.3em;'+this.widgetMeinHeadStyle.replace("margin:0.8em auto;","margin:"+(this.isMobileView?"1em auto 0 auto;":"0.8em auto 0.567em auto;"))+'">'+this.decodeToHTML(this.phoneTitle)+'</p><p style="padding:0px; margin:0px auto; width:100%;font-size:1.286em;line-height:1.3em;font-weight:normal;color:#000;">'+this.decodeToHTML(this.phoneText)+"</p></div>";if(this.bookingPos<1)this.phoneCode+='<div align="right" style="float:right;width:10%;"><img src="'+this.URL+'/images/Phone_icon.png" style="max-height:3.5em;max-width:3em;margin-top:0.8em;"/></div>';this.phoneCode+='<div style="clear:both;height:0px;"></div></div></div>'};SOE.prototype.getWidgetCode=function(){var mCalcLeft=this.calcWindowLeft+250;var fontsizeOpenClose="1.19em";var mMarginTop="";var counterLeft="left";var topBottom="bottom";var mLeftValue="";var counterTopBottom="top";var mWidgetHeadStyle=(this.isLive?"position:fixed;width: 100%;bottom:0;left:0em; ":"position:absolute;width: 291px;bottom:33%;")+" padding: 0px;margin: 0px; height: 2.8em; -webkit-border-top-left-radius: 4px; -webkit-border-top-right-radius: 4px;border-top-left-radius: 4px; border-top-right-radius: 4px;font-family: Tahoma, HelveticaNeue, Arial;font-size:14px;-webkit-box-shadow: 0 -3px 9px -3px #000;box-shadow: 0 -3px 9px -3px #000;z-index: 10000;background-color:"+(this.widgetBGColor==""?"#333":this.widgetBGColor)+";";var openCloseHolderClass="padding: 0px; margin: 0px;width: 1.3em; height: 1.3em;line-height:1.3em;position: absolute;right: 0.8em;top: 0.5em; -webkit-border-radius: 50%; border-radius: 50%;  background-color: #FFF;  cursor: pointer; text-align: center;  font-family: Tahoma, HelveticaNeue, Arial;color: #333;font-size: "+fontsizeOpenClose+";font-weight: bold;";if(this.isMobileView){this.WidgetBoxStyle=this.WidgetBoxStyle.replace("-webkit-border-top-left-radius: 4px; -webkit-border-top-right-radius: 4px;border-top-left-radius: 4px; border-top-right-radius: 4px;","-webkit-border-radius:4px; border-radius: 4px;");if(!this.isLive)mMarginTop=this.widgetLeft=="left"?"margin-top:1.8em;":"margin-top:-0.6em;";else{mMarginTop=this.widgetLeft=="left"?"margin-top:1.3em;":"";openCloseHolderClass=openCloseHolderClass.replace("line-height:1.3em;","line-height:1.14em;")}this.mWidgetBoxStyle+=this.isLive?"left:"+mCalcLeft+"px;":"left:26%;"}else this.WidgetBoxStyle=this.WidgetBoxStyle.replace("-webkit-border-radius:4px; border-radius: 4px;","-webkit-border-top-left-radius: 4px; -webkit-border-top-right-radius: 4px;border-top-left-radius: 4px; border-top-right-radius: 4px;");var widgetHeadStyle="bottom:0;padding: 0px;margin: 0px; width: 100%; height: 2.8em; position: relative; -webkit-border-top-left-radius: 4px; -webkit-border-top-right-radius: 4px;border-top-left-radius: 4px; border-top-right-radius: 4px;";this.widgetHeadh5Style="padding-left:0.9em; margin:0px;font-size:1em;  font-weight:bold;line-height:2.8em;text-transform: none;letter-spacing: 0px;";var orHolderStyle="padding: 1em 0px 0px 0px; margin: 0px 0px -0.489em 0px; width: 98%;height: 1.1em;";var orLineStyle="padding: 0; margin: 0px -0.125em 0px 0.25em;/*height: 0.09em;background-color: #424242;*/border-bottom:0.09em solid #424242; position: relative;";var orDivStyle="padding: 0;margin: 0;width: 1.8em;  height: 1.8em; background-color: #424242;text-align: center;  -webkit-border-radius: 50%;border-radius: 50%;  position: absolute; left: 46%;  top: -0.9em;";var orPStyle="margin: 0px;color: #FFF; font-size: 0.9em; font-weight: bold;  line-height: 1.8em; width:100%;";var powerDivDesktop="padding:0px; margin:0px;  text-align:center; font-size:0.786em; color:#666666;line-height:1.9em;";var powerDivMobile="padding:0px;margin:0px; height:2.7em;";var closeWidgetStyle="padding:0px;margin:0.5em 5% 0 0;width:25%; height:1.8em; border:solid 1px #747474; text-align:center;float:right; font-size:0.857em;color:#000;text-decoration:none; line-height:1.8em;background: #eaeaea; /* Old browsers */ background: -moz-linear-gradient(top,  #eaeaea 1%, #919191 100%); /* FF3.6+ */ background: -webkit-gradient(linear, left top, left bottom, color-stop(1%,#eaeaea), color-stop(100%,#919191)); /* Chrome,Safari4+ */ background: -webkit-linear-gradient(top,  #eaeaea 1%,#919191 100%); /* Chrome10+,Safari5.1+ */ background: -o-linear-gradient(top,  #eaeaea 1%,#919191 100%); /* Opera 11.10+ */ background: -ms-linear-gradient(top,  #eaeaea 1%,#919191 100%); /* IE10+ */ background: linear-gradient(to bottom,  #eaeaea 1%,#919191 100%); /* W3C */ filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#eaeaea', endColorstr='#919191',GradientType=0 ); /* IE6-9 */-webkit-border-radius: 4px; border-radius: 4px;";var orEnd='<div style="'+orLineStyle+'"><div style="'+orDivStyle+'"><p style="'+orPStyle+'">or</p></div></div></div>';var or1='<div style="display:'+(this.displayCount==1?"none":"block")+";"+orHolderStyle+'" >'+orEnd;var or2='<div style="display:'+(this.displayCount==2||this.displayCount==1?"none":"block")+";"+orHolderStyle+'">'+orEnd;this.postCode='<div id="SODesktopPower"style="display:block;'+powerDivDesktop+'" > Powered by ScheduleOnce </div><div id="SOMobilePower" style="display:none;'+powerDivMobile+'"><p style="padding:1em 0 0 1.2em; margin:0px; font-size:0.786em; color:#666666;float:left;width:60%;">Powered by ScheduleOnce</p><a href="javascript: void(1);" '+this.onclickProperty+' style="'+closeWidgetStyle+'">Close</a></div>';this.code='<a href="javascript:void(1);"  '+this.onclickProperty+' style="text-decoration:none;"><div id="SOWidgetTitle" style="'+widgetHeadStyle+"background-color:"+(this.widgetBGColor==""?"#333":this.widgetBGColor)+';"><h5 style="color:'+(this.widgetColor==""?"#fff":this.widgetColor)+";"+this.widgetHeadh5Style+'">'+this.decodeToHTML(this.widgetTitle)+'</h5><div id="SOWidgetToggle" style="'+openCloseHolderClass+'" >+</div></div></a>';this.code+='<div id="SOWidgetContent" style="display:none;margin-top:0.7em;">';this.contactUsDiv='<h5 style="border-top:1px solid #fff;color:'+(this.widgetColor==""?"#fff":this.widgetColor)+";"+this.widgetHeadh5Style+'">'+this.decodeToHTML(this.widgetTitle)+'</h5><div style="'+openCloseHolderClass+'" >+</div>';if(!this.isLive)this.divMobileTitle='<div id="mSOWidgetTitle" style="/*'+this.widgetLeft+":-14%;*/"+mWidgetHeadStyle+'display:none;letter-spacing:1px;" '+this.onclickProperty+">"+this.contactUsDiv+"</div>";else this.divMobileTitle=mLeftValue+mWidgetHeadStyle+'display:none;"';if(this.bookingPos==1)this.code+=this.bookingCode+or1;if(this.emailPos==1)this.code+=this.emailCode+or1;if(this.phonePos==1)this.code+=this.phoneCode+or1;if(this.bookingPos==2)this.code+=this.bookingCode+or2;if(this.emailPos==2)this.code+=this.emailCode+or2;if(this.phonePos==2)this.code+=this.phoneCode+or2;if(this.bookingPos==3||this.bookingPos==0)this.code+=this.bookingCode;if(this.emailPos==3||this.emailPos==0)this.code+=this.emailCode;if(this.phonePos==3||this.phonePos==0)this.code+=this.phoneCode;this.code+='<div style="'+orLineStyle.replace("margin: 0px -0.125em 0px 0.25em;","margin-top: 0.489em;")+'"></div>'+this.postCode+"</div>";this.code+=this.bookingLightBoxCode};SOE.prototype.setWidgetHTML=function(){this.getBookingCode();this.getEmailCode();this.getPhoneCode();this.getWidgetCode();var ele=document.getElementById("SOWidget");if(ele==null||ele==="undefined"){ele=document.createElement("div");ele.id="SOWidget"}var body=document.getElementsByTagName("body")[0];body.appendChild(ele);ele.setAttribute("style",(this.isMobileView?"position:absolute;":"position:fixed;"+this.widgetLeft+":90px;")+(this.isMobileView?this.mWidgetBoxStyle:this.WidgetBoxStyle));if(this.isMobileView){ele.innerHTML=this.code;var bgEle=document.createElement("div");bgEle.id="SoWidgetBackground";bgEle.setAttribute("style","position:fixed;top:0px;right:0px;bottom:0px;left:0px;background-color:black;opacity:0.4;display:none;z-index:10000");body.insertBefore(bgEle,ele);bgEle=document.createElement("div");if(this.isLive){bgEle.id="mSOWidgetTitle";bgEle.setAttribute("style",this.divMobileTitle);bgEle.setAttribute("onclick",this.onclickProperty.replace("onclick=","").replace(/"/g,""));bgEle.innerHTML=this.contactUsDiv;body.insertBefore(bgEle,ele);bgEle=document.createElement("div");bgEle.id="SOmarginDivForMobile";bgEle.innerHTML="&nbsp;";bgEle.setAttribute("style","height:4em;");body.appendChild(bgEle)}document.getElementById("SOWidgetTitle").style.display="none";document.getElementById("mSOWidgetTitle").style.display="block";document.getElementById("SODesktopPower").style.display="none";document.getElementById("SOMobilePower").style.display="block";document.getElementById("SOWidget").style.display="none";document.getElementById("SOWidget").style.width="80%";document.getElementById("SOWidget").style.left="10%";this.orientationChange()}else ele.innerHTML=this.code;document.getElementById("SONote").value=soe.noteValue.replace(/'/g,"");this.TransitionCode(ele)};SOE.prototype.toggleDiv=function(id,obj,hideOnly){var elem=document.getElementById(id);if(elem){var widgetDiv=id=="SOWidgetContent"?true:false;if((elem.style.display=="none"||(elem.style.display==""||elem.style.display=="undefined"))&&hideOnly==null){elem.style.display="block";if(widgetDiv){if(this.isMobileView){document.getElementById("mSOWidgetTitle").style.display="none";document.getElementById("SOWidgetTitle").style.display="block";document.getElementById("SOWidget").style.display="block";if(this.isLive){document.getElementById("SoWidgetBackground").style.display="block";window.scrollTo(0,0)}}if(obj!=null)document.getElementById(obj).innerHTML="&#8211;"}}else{elem.style.display="none";if(widgetDiv){if(this.isMobileView){document.getElementById("SOWidgetTitle").style.display="none";document.getElementById("mSOWidgetTitle").style.display="block";document.getElementById("SOWidget").style.display="none";if(this.isLive)document.getElementById("SoWidgetBackground").style.display="none"}if(!this.isLightBox&&!this.isMobileView)this.TransitionCode(document.getElementById("SOWidget"));if(this.prevHtml!="")document.getElementById("SOWidgetContent").innerHTML=this.prevHtml}if(obj!=null)document.getElementById(obj).innerHTML="+"}}};SOE.prototype.toggleLightBox=function(page){if(this.isMobileView){var ele=document.getElementById("SOI_"+page);if(ele)window.open(ele.src.replace("em=1","bt=1"))}else{var divStyle;var id="SOLightBox"+page;var div=document.getElementById(id);var node=document.getElementsByTagName("body")[0];var ifrDiv=document.getElementById("SOI_"+page);var blanketTop=0;if(div.style.top.indexOf("-10000px")>-1){var blanket=document.getElementById("SOBlanket");if(blanket==null||blanket=="undefined"){blanket=document.createElement("div");blanket.id="SOBlanket";node.appendChild(blanket)}node.style.overflow="hidden";this.isLightBox=true;this.toggleDiv("SOWidgetContent","SOWidgetToggle",true);var blanketStyle="height:100%; width:100%; left:0px;z-index:9999;position:fixed;background-color:black;opacity:0.6;filter:alpha(opacity=60);top:0px;";blanket.setAttribute("style",blanketStyle);this.toggleDiv("SOBlanket",null);blanketTop=blanketTop+5;var _width=this.getWindowWidth()-this.calcWindowLeft;_width=(_width<this.minWidthValue?this.minWidthValue:_width)+"px";divStyle="height:100%;display:block;left:"+this.calcWindowLeft+"px;top:"+blanketTop+"px;width:"+_width+";z-index:10000000;position:fixed;overflow-y:auto";div.setAttribute("style",divStyle);var childEle=null;var childArr=div.children;for(var i=0;i<childArr.length;i++)if(childArr[i].id=="SOInnerLightBox"){childEle=childArr[i];break}var height="height:"+(childEle!=null&&(childEle.style&&childEle.style.height)?childEle.style.height:"360px");var topVal="top:15px;";divStyle=divStyle.replace("width:"+_width+";z-index:10000000;position:fixed;overflow-y:auto","width:750px;"+topVal).replace("height:100%",height)+"position:absolute;";divStyle=divStyle.replace("display:block;left:"+this.calcWindowLeft+"px;top:"+blanketTop+"px;","");childEle.setAttribute("style",divStyle);document.getElementById("SOIC_"+page).style.right="-9px"}else{this.toggleDiv("SOBlanket",null);node.style.overflow="auto";this.isLightBox=false;div.style.top="-10000px"}}};SOE.prototype.getIframeHTMLCode=function(_pageLink){var h="100%";var w="90%";var ver="";var style="border:solid 3px #FFF;background-color:#FFF;";var pre='<div id="SOIC_'+this.pageName.replace(/'/g,"")+'"  style="padding: 0px; margin: 0px;  width: 24px;height: 24px; background-color: #000; border: solid 2px #FFF;color:#FFF;font-size: 18px;font-weight: bold;font-family: tahoma; -webkit-border-radius: 14px;   border-radius: 14px; position: absolute; top: -15px; text-align:center; line-height:23px;cursor:pointer;'+(this.isMobileView?"right:-1.5em;":"right:-17px")+'" onclick="soe.toggleLightBox('+this.pageName+')">X</div>';pre='<div align="center" style="height:100%;">'+pre;var post="";if(!this.isMobileView){h="620px";w="735px";ver="";style="border-radius: 7px;-webkit-border-radius:7px;-webkit-box-sizing:content-box;box-sizing:content-box;-moz-box-sizing:content-box;"+style;post="</div>"}var _code=pre+'<iframe src="'+_pageLink+"&dt="+ver+'&em=1" id="SOI_'+this.pageName.replace(/'/g,"")+'" name="ScheduleOnceIframe"  scrolling="auto" frameborder="0" hspace="0" marginheight="0" marginwidth="0" height="'+h+'" width="'+w+'" vspace="0" style="'+style+'"></iframe>'+post;return _code};SOE.prototype.encodeToHTML=function(str,removeLines){if(str){if(removeLines)str=str.replace(/\r/g,"").replace(/\n/g,"line_break_placeHolder");str=str.replace(/\\/g,"&#92;").replace(/&/g,"&amp;").replace(/"/g,"&quot;").replace(/'/g,"&apos;").replace(/</g,"&lt;").replace(/>/g,"&gt;")}return str};SOE.prototype.fixLines=function(str){return str.replace(/line_break_placeHolder/g,"<br />")};SOE.prototype.decodeToHTML=function(str){str=str.replace(/&amp;/g,"&").replace(/&#92;/g,"\\").replace(/&quot;/g,'"').replace(/&apos;/g,"'").replace(/&gt;/g,">");return str};SOE.prototype.sendMail=function(){var uri=this.URL+"/webservice/MailSendEmbed.asmx/SendMailRequest";var validate=true;var from=document.getElementById("SOSenderEmail").value;var message=document.getElementById("SONote").value;if(!from>0){document.getElementById("SOSenderEmailErr").style.display="block";document.getElementById("SOSenderEmailErr").innerHTML="Email is a mandatory field.";validate=false}else if(!this.ValidateEmail(from)){document.getElementById("SOSenderEmailErr").style.display="block";document.getElementById("SOSenderEmailErr").innerHTML="Please enter a valid email.";validate=false}if(!this.ValidateQuery(message)){document.getElementById("SONoteErr").style.display="block";validate=false}if(validate){var data="ST|TO="+this.emailSendTo+"|FROM="+from+"|MSG="+message+"|EMAILLABEL="+this.emailAck+"|ED";var xmlHttp=this.createRequestObject();uri+="?data="+encodeURIComponent(this.encodeToHTML(data,true));xmlHttp.open("GET",uri,true);xmlHttp.send();this.prevHtml=document.getElementById("SOWidgetContent").innerHTML;var height=document.getElementById("SOWidgetContent").clientHeight;var html='<div style="height:'+(height-20)+'px; width:205px; word-wrap:break-word;font-size:14px;color:#333333;padding-left:14px;"><p style="width:100%;line-height:100%;">'+this.decodeToHTML(this.emailAfter)+"</p></div>"+this.postCode;if(this.isMobileView){document.body.scrollTop=0;html='<div style="height:'+(height-20)+'px; width:75%; word-wrap:break-word;font-size:1.2em;color:#333333;padding-left:14px;"><p style="width:100%;line-height:100%;">'+this.decodeToHTML(this.emailAfter)+"</p></div>"+this.postCode.replace('<div id="SODesktopPower"style="display:block;','<div id="SODesktopPower"style="display:none;"').replace('<div id="SOMobilePower"style="display:none;','<div id="SOMobilePower"style="display:block;')}document.getElementById("SOWidgetContent").innerHTML=html;if(this.isMobileView)document.body.scrollTop=0}};SOE.prototype.createRequestObject=function(){if(window.XMLHttpRequest)return xmlhttprequest=new XMLHttpRequest;else if(window.ActiveXObject)return xmlhttprequest=new ActiveXObject("Microsoft.XMLHTTP")};SOE.prototype.onBlurText=function(obj){if(obj.id=="SONote")if(this.ValidateQuery(obj.value)){document.getElementById("SONoteErr").style.display="none";obj.style.color="#161616"}else{obj.style.color="#666666";document.getElementById("SONoteErr").style.display="block"}else if(obj.id=="SOSenderEmail")if(!obj.value>0||obj.value==this.emailValue.replace(/'/g,"")){obj.style.color="#666666";document.getElementById("SOSenderEmailErr").style.display="block";document.getElementById("SOSenderEmailErr").innerHTML="Email is a mandatory field."}else if(!this.ValidateEmail(obj.value)){obj.style.color="#666666";document.getElementById("SOSenderEmailErr").style.display="block";document.getElementById("SOSenderEmailErr").innerHTML="Please enter a valid email."}else{document.getElementById("SOSenderEmailErr").style.display="none";obj.style.color="#161616"}};SOE.prototype.ValidateEmail=function(email){if(email&&email.length>0){if(!this.ValidateChars(email))return false;if(email.indexOf("@")<1)return false;else if(email.split("@").length>2)return false;else if(email.lastIndexOf(".")<=email.indexOf("@"))return false;else if(Math.max(email.indexOf("+"),email.indexOf("~"))>=email.indexOf("@"))return false;else if(email.indexOf("@")==email.length)return false;else if(email.indexOf("..")>=0)return false;else if(email.charAt(email.length-1).indexOf(".")>=0||email.indexOf(".")==0)return false;return true}else return false};SOE.prototype.ValidateChars=function(email){var parsed=true;var validMailchars="abcdefghijklmnopqrstuvwxyz0123456789@.-_+~";for(var i=0;i<email.length;i++){var letter=email.charAt(i).toLowerCase();if(validMailchars.indexOf(letter)!=-1)continue;parsed=false;break}return parsed};SOE.prototype.ValidateQuery=function(query){if(query.length<1||query=="Your note"){document.getElementById("SONoteErr").innerHTML="Note is a mandatory field.";return false}else if(query.length>200){document.getElementById("SONoteErr").innerHTML="Maximum 200 characters allowed";return false}else return true};SOE.prototype.getWindowWidth=function(){var n_win=window.innerWidth?window.innerWidth:0;var n_docel=document.documentElement?document.documentElement.clientWidth:0;var n_result=n_win?n_win:0;return n_docel&&(!n_result||n_result>n_docel)?n_docel:n_result};var soe=new SOE;SOE.prototype.TransitionCode=function(element){if(element==null&&this.isMobileView)return;var original=parseInt(element.style.bottom.replace("%",""));var number=original-30;element.style.bottom=number+"%";setTimeout(function(){soe.animateToTransition(number+1,element,original)},33)};SOE.prototype.animateToTransition=function(newValue,element,original){if(newValue<original){element.style.bottom=newValue+"%";setTimeout(function(){soe.animateToTransition(newValue+1,element,original)},33)}else element.style.bottom=original+"%"};SOE.prototype.orientationChange=function(){var _width=soe.getWindowWidth();soe.calcWindowLeft=(_width-750)/2;_width=_width-soe.calcWindowLeft;if(!soe.isMobileView){soe.calcWindowLeft=soe.calcWindowLeft<0?0:soe.calcWindowLeft;_width=(_width<soe.minWidthValue?soe.minWidthValue:_width)+"px";var f=document.querySelectorAll("*[id*='SOLightBox']");for(var i=0;i<f.length;i++){f[i].style.left=soe.calcWindowLeft+"px";f[i].style.width=_width}}else if(soe.isLive){var _screenWidth=screen.width;var isLandScape=window.orientation==90||window.orientation==-90;var _windowInnerWidth=window.innerWidth;var screenCssPixelRatio=1;var setMultiplier=false;var setDivider=false;if(window.outerWidth>0&&_windowInnerWidth>0)screenCssPixelRatio=(window.outerWidth-8)/_windowInnerWidth;if(_windowInnerWidth>_screenWidth)setMultiplier=true;else if(_windowInnerWidth<_screenWidth)setDivider=true;var _width=0.8*_windowInnerWidth;document.getElementById("SOWidget").style.width=_width+"px";var mCalcFont=14;var ceil=_windowInnerWidth==_screenWidth?null:Math.ceil(screenCssPixelRatio);if(_screenWidth<=320){if(ceil!=null){mCalcFont=14;if(!isLandScape)if(ceil<2)mCalcFont=35;else if(ceil<=4&&ceil>=2)mCalcFont=18;else if(ceil>8&&ceil<=13)mCalcFont=9;else if(ceil>13)mCalcFont=3;else if(setMultiplier){mCalcFont=mCalcFont*screenCssPixelRatio*2.5;mCalcFont=mCalcFont<14?14:mCalcFont>18?18:mCalcFont}else{if(setDivider){mCalcFont=Math.round(mCalcFont*(screenCssPixelRatio>=4.5?screenCssPixelRatio-Math.floor(screenCssPixelRatio):screenCssPixelRatio));mCalcFont=mCalcFont>14?14:mCalcFont<12?12:mCalcFont}}else if(ceil<2)mCalcFont=20;else if(ceil<4&&ceil>1)mCalcFont=16;else if(ceil>8&&ceil<=13)mCalcFont=6;else if(ceil>13)mCalcFont=3;else if(setMultiplier){mCalcFont=mCalcFont*screenCssPixelRatio*1.5;mCalcFont=mCalcFont<14?14:mCalcFont>16?16:mCalcFont}else if(setDivider){mCalcFont=Math.round(mCalcFont*(screenCssPixelRatio>=4.5?screenCssPixelRatio-Math.floor(screenCssPixelRatio):screenCssPixelRatio));mCalcFont=mCalcFont>11?11:mCalcFont<7?7:mCalcFont}}}else if(_screenWidth>320&&_screenWidth<=480){mCalcFont=15;if(ceil!=null)if(!isLandScape)if(ceil<2)mCalcFont=30;else if(ceil<4&&ceil>1)mCalcFont=17;else if(ceil>8&&ceil<=13)mCalcFont=6;else if(ceil>13)mCalcFont=3;else if(setMultiplier){mCalcFont=mCalcFont*screenCssPixelRatio*1.5;mCalcFont=mCalcFont<15?15:mCalcFont>17?17:mCalcFont}else{if(setDivider){mCalcFont=Math.round(mCalcFont*(screenCssPixelRatio>=4.5?screenCssPixelRatio-Math.floor(screenCssPixelRatio):screenCssPixelRatio));mCalcFont=mCalcFont>11?11:mCalcFont<7?7:mCalcFont}}else{if(ceil<3&&ceil>1)mCalcFont=17;if(ceil>5&&ceil<=9)mCalcFont=5;else if(ceil>9)mCalcFont=4;else if(setMultiplier){mCalcFont=mCalcFont*screenCssPixelRatio;mCalcFont=mCalcFont<14?14:mCalcFont>17?17:mCalcFont}else if(setDivider){mCalcFont=Math.round(mCalcFont*(screenCssPixelRatio>=4.5?screenCssPixelRatio-Math.floor(screenCssPixelRatio):screenCssPixelRatio));mCalcFont=mCalcFont>10?10:mCalcFont<4?4:mCalcFont}}}else if(_screenWidth>480&&_screenWidth<=800){mCalcFont=19;if(ceil!=null)if(!isLandScape){if(ceil<3&&ceil>1)mCalcFont=24;if(ceil>5&&ceil<=9)mCalcFont=6;else if(ceil>9)mCalcFont=4;else if(setMultiplier){mCalcFont=mCalcFont*screenCssPixelRatio;mCalcFont=mCalcFont<19?19:mCalcFont>21?21:mCalcFont}else if(setDivider){mCalcFont=Math.round(mCalcFont*(screenCssPixelRatio>=4.5?screenCssPixelRatio-Math.floor(screenCssPixelRatio):screenCssPixelRatio));mCalcFont=mCalcFont>11?11:mCalcFont<5?5:mCalcFont}}else{if(ceil<3&&ceil>1)mCalcFont=14;if(ceil>5&&ceil<=9)mCalcFont=4;else if(ceil>9)mCalcFont=3;else if(setMultiplier){mCalcFont=mCalcFont*screenCssPixelRatio;mCalcFont=mCalcFont<17?17:mCalcFont>20?20:mCalcFont}else if(setDivider){mCalcFont=Math.round(mCalcFont*(screenCssPixelRatio>=4.5?screenCssPixelRatio-Math.floor(screenCssPixelRatio):screenCssPixelRatio));mCalcFont=mCalcFont>10?1:mCalcFont<5?5:mCalcFont}}}document.getElementById("SOWidget").style.fontSize=mCalcFont+"px";document.getElementById("mSOWidgetTitle").style.fontSize=mCalcFont+"px"}};SOE.prototype.zoomChange=function(){var _screenWidth=screen.width;var isLandScape=window.orientation==90||window.orientation==-90;var _windowInnerWidth=window.innerWidth;var screenCssPixelRatio=1;var setMultiplier=false;var setDivider=false;if(window.outerWidth>0&&_windowInnerWidth>0)screenCssPixelRatio=(window.outerWidth-8)/_windowInnerWidth;if(_windowInnerWidth>_screenWidth)setMultiplier=true;else if(_windowInnerWidth<_screenWidth)setDivider=true;if(soe.runCode&&soe.oldScreenCssPixelRatio!=screenCssPixelRatio){soe.oldScreenCssPixelRatio=screenCssPixelRatio;var mCalcFont=14;var ceil=_windowInnerWidth==_screenWidth?null:Math.ceil(screenCssPixelRatio);var _width=0.8*_windowInnerWidth;document.getElementById("SOWidget").style.width=_width+"px";if(_screenWidth<=320){if(ceil!=null){mCalcFont=14;if(!isLandScape)if(ceil<2)mCalcFont=35;else if(ceil<=4&&ceil>=2)mCalcFont=18;else if(ceil>8&&ceil<=13)mCalcFont=9;else if(ceil>13)mCalcFont=3;else if(setMultiplier){mCalcFont=mCalcFont*screenCssPixelRatio*2.5;mCalcFont=mCalcFont<14?14:mCalcFont>18?18:mCalcFont}else{if(setDivider){mCalcFont=Math.round(mCalcFont*(screenCssPixelRatio>=4.5?screenCssPixelRatio-Math.floor(screenCssPixelRatio):screenCssPixelRatio));mCalcFont=mCalcFont>14?14:mCalcFont<12?12:mCalcFont}}else if(ceil<2)mCalcFont=20;else if(ceil<4&&ceil>1)mCalcFont=16;else if(ceil>8&&ceil<=13)mCalcFont=6;else if(ceil>13)mCalcFont=3;else if(setMultiplier){mCalcFont=mCalcFont*screenCssPixelRatio*1.5;mCalcFont=mCalcFont<14?14:mCalcFont>16?16:mCalcFont}else if(setDivider){mCalcFont=Math.round(mCalcFont*(screenCssPixelRatio>=4.5?screenCssPixelRatio-Math.floor(screenCssPixelRatio):screenCssPixelRatio));mCalcFont=mCalcFont>11?11:mCalcFont<7?7:mCalcFont}}}else if(_screenWidth>320&&_screenWidth<=480){mCalcFont=15;if(ceil!=null)if(!isLandScape)if(ceil<2)mCalcFont=30;else if(ceil<4&&ceil>1)mCalcFont=17;else if(ceil>8&&ceil<=13)mCalcFont=6;else if(ceil>13)mCalcFont=3;else if(setMultiplier){mCalcFont=mCalcFont*screenCssPixelRatio*1.5;mCalcFont=mCalcFont<15?15:mCalcFont>17?17:mCalcFont}else{if(setDivider){mCalcFont=Math.round(mCalcFont*(screenCssPixelRatio>=4.5?screenCssPixelRatio-Math.floor(screenCssPixelRatio):screenCssPixelRatio));mCalcFont=mCalcFont>11?11:mCalcFont<7?7:mCalcFont}}else{if(ceil<3&&ceil>1)mCalcFont=17;if(ceil>5&&ceil<=9)mCalcFont=5;else if(ceil>9)mCalcFont=4;else if(setMultiplier){mCalcFont=mCalcFont*screenCssPixelRatio;mCalcFont=mCalcFont<14?14:mCalcFont>17?17:mCalcFont}else if(setDivider){mCalcFont=Math.round(mCalcFont*(screenCssPixelRatio>=4.5?screenCssPixelRatio-Math.floor(screenCssPixelRatio):screenCssPixelRatio));mCalcFont=mCalcFont>10?10:mCalcFont<4?4:mCalcFont}}}else if(_screenWidth>480&&_screenWidth<=800){mCalcFont=19;if(ceil!=null)if(!isLandScape){if(ceil<3&&ceil>1)mCalcFont=24;if(ceil>5&&ceil<=9)mCalcFont=6;else if(ceil>9)mCalcFont=4;else if(setMultiplier){mCalcFont=mCalcFont*screenCssPixelRatio;mCalcFont=mCalcFont<19?19:mCalcFont>21?21:mCalcFont}else if(setDivider){mCalcFont=Math.round(mCalcFont*(screenCssPixelRatio>=4.5?screenCssPixelRatio-Math.floor(screenCssPixelRatio):screenCssPixelRatio));mCalcFont=mCalcFont>11?11:mCalcFont<5?5:mCalcFont}}else{if(ceil<3&&ceil>1)mCalcFont=14;if(ceil>5&&ceil<=9)mCalcFont=4;else if(ceil>9)mCalcFont=3;else if(setMultiplier){mCalcFont=mCalcFont*screenCssPixelRatio;mCalcFont=mCalcFont<17?17:mCalcFont>20?20:mCalcFont}else if(setDivider){mCalcFont=Math.round(mCalcFont*(screenCssPixelRatio>=4.5?screenCssPixelRatio-Math.floor(screenCssPixelRatio):screenCssPixelRatio));mCalcFont=mCalcFont>10?1:mCalcFont<5?5:mCalcFont}}}document.getElementById("SOWidget").style.fontSize=mCalcFont+"px";document.getElementById("mSOWidgetTitle").style.fontSize=mCalcFont+"px"}};SOE.prototype.setButtonFrameToBottom=function(){var f=document.querySelectorAll("*[id*='SOLightBox']");for(var i=0;i<f.length;i++){f[i].style.top="-10000px";var sibling=f[i].parentElement.children;var onclickProperty="soe.toggleLightBox('"+f[i].id.replace("SOLightBox","")+"')";for(var j=0;j<sibling.length;j++)if(sibling[j].getAttribute("onclick")&&sibling[j].getAttribute("onclick")==onclickProperty){var child=sibling[j].querySelector("*[type='button']");if(child)child.style.backgroundImage="none";document.getElementsByTagName("body")[0].appendChild(f[i]);break}}};if(window.addEventListener)window.addEventListener("message",receiveMessage,false);else if(window.attachEvent)window.attachEvent("onmessage",receiveMessage);else window.onmessage=receiveMessage;SOE.prototype.AddEventListners=function(){var supportOrientation="onorientationchange"in window,orientationEvent=supportOrientation?"orientationchange":"resize";if(window.addEventListener)window.addEventListener(orientationEvent,soe.orientationChange,false);else if(window.attachEvent)window.attachEvent("on"+orientationEvent,soe.orientationChange);else;if(soe.isMobileView&&soe.isLive){var body=document.getElementsByTagName("body")[0];body.addEventListener("touchmove",function(evt){if(evt.touches.length>1)soe.runCode=true;else soe.runCode=false});body.addEventListener("touchend",soe.zoomChange)}var f=document.querySelectorAll("*[name='ScheduleOnceIframe']");while(f==null||f=="undefined")setTimeout(function(){f=document.querySelectorAll("*[name='ScheduleOnceIframe']")},33);for(var i=0;i<f.length;f++){f[i].height=soe.isMobileView?"100%":"618px";f[i].scrolling="auto";if(document.getElementById&&!document.all)f[i].width=soe.isMobileView?"90%":"735px"}};if(screen.width<801)soe.isMobileView=true;soe.setButtonFrameToBottom();try{soe.bookingPos=ScheduleOnceEmbedPosition.split(",")[0];soe.emailPos=ScheduleOnceEmbedPosition.split(",")[1];soe.phonePos=ScheduleOnceEmbedPosition.split(",")[2];soe.displayCount=soe.bookingPos<1?soe.displayCount-1:soe.displayCount;soe.displayCount=soe.phonePos<1?soe.displayCount-1:soe.displayCount;soe.displayCount=soe.emailPos<1?soe.displayCount-1:soe.displayCount;soe.widgetTitle=ScheduleOnceEmbedWidgetTitle;soe.widgetColor=ScheduleOnceEmbedWidgetColor;soe.widgetBGColor=ScheduleOnceEmbedWidgetBGColor;soe.widgetLeft=ScheduleOnceEmbedWidgetLeft;soe.phoneTitle=ScheduleOnceEmbedPhoneTitle;soe.phoneText=ScheduleOnceEmbedPhoneText;soe.emailTitle=ScheduleOnceEmbedEmailTitle;soe.emailBText=ScheduleOnceEmbedEmailBText;soe.emailBTextColor=ScheduleOnceEmbedEmailBTextColor;soe.emailBBGColor=ScheduleOnceEmbedEmailBBGColor;soe.emailSendTo=ScheduleOnceEmbedEmailSendTo;soe.emailAck=ScheduleOnceEmbedEmailAck;soe.emailAfter=ScheduleOnceEmbedEmailAfter;soe.bookingTitle=ScheduleOnceEmbedBookingTitle;soe.bookingBText=ScheduleOnceEmbedBookingBText;soe.bookingBBGColor=ScheduleOnceEmbedBookingBBGColor;soe.bookingBTextColor=ScheduleOnceEmbedBookingBTextColor;soe.link=ScheduleOnceEmbedLink;soe.pageName=ScheduleOnceEmbedPageName;soe.isWidget=true;soe.setWidgetHTML()}catch(err){}
function receiveMessage(event){var tmp=event.data.toString();if(tmp.indexOf("ScheduleOnceifrId")>-1){var ScheduleOnceifrId=null;var height=400;eval(event.data);if(ScheduleOnceifrId!=null){var innerLightBoxHeight=height+40;var SOI_ele=document.getElementById("SOI_"+ScheduleOnceifrId);if(SOI_ele){SOI_ele.height=height+10+"px";if(!soe.isMobileView){var SOI_lbEle=SOI_ele.parentElement.parentElement;if(SOI_lbEle.id=="SOInnerLightBox")SOI_lbEle.style.height=innerLightBoxHeight+"px"}else if(!soe.isLive){SOI_ele.height=innerLightBoxHeight+"px";var SOI_lbEle=SOI_ele.parentElement;SOI_lbEle.style.height=innerLightBoxHeight+"px"}}}}return};
//

function overlayIt(image, x, y){

    if( window.location.host.indexOf('dev') === -1){ return false; }

    $('body').prepend('<div class="overlay" style="display:none; position:absolute; top:0; bottom:0; left:0; right:0; z-index:9998; background:url('+image+') no-repeat '+x+' '+y+'; opacity:.5; height:3000px"></div><a href="#" class="trigger" style="position:fixed; z-index:9999; display:block; text-indent:-9999px; width:50px; height:20px; background:#000;">Trigger</a>');

    $('a.trigger').toggle(function(){
        $('.overlay').css('display', 'block');
    },function(){
        $('.overlay').hide();
    });

}



// JRATE Star Ratings Library
(function($, undefined) {

    $.fn.jRate = function(options) {

        "use strict";
        var $jRate = $(this);
        var defaults = {
            rating: 0,
            shape: "STAR",
            count: 5,
            width: "20",
            height: "20",
            widthGrowth: 0.0,
            heightGrowth: 0.0,
            backgroundColor: "white",
            startColor: "yellow",
            endColor: "green",
            strokeColor: "black",
            shapeGap: "0px",
            opacity: 1,
            min: 0,
            max: 5,
            precision: 1,
            horizontal: true,
            reverse: false,
            readOnly: false,
            touch: true,
            onChange: null,
            onSet: null
        };
        var settings = $.extend({}, defaults, options);
        var startColorCoords, endColorCoords, shapes;

        function isDefined(name) {
            return typeof name !== "undefined";
        }

        function getRating() {
            if (isDefined(settings))
                return settings.rating;
        }

        function setRating(rating) {
            if (!isDefined(rating) || rating < settings.min || rating > settings.max)
                throw rating + " is not in range(" + min + "," + max + ")";
            showRating(rating);
        }

        function getShape(currValue) {
            var header = '<svg width="' + settings.width + '" height=' + settings.height + ' xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"';
            var hz = settings.horizontal;
            var id = $jRate.attr('id');
            var linearGrad = '<defs><linearGradient id="'+id+'_grad'+currValue+'" x1="0%" y1="0%" x2="' + (hz ? 100 : 0) + '%" y2="' + (hz ? 0 : 100) + '%">' +
                '<stop offset="0%"  stop-color=' + settings.backgroundColor + '/>' +
                '<stop offset="0%" stop-color=' + settings.backgroundColor + '/>' +
                '</linearGradient></defs>';
            var shapeRate;
            switch (settings['shape']) {
                case 'STAR':
                    shapeRate = header + 'viewBox="0 12.705 512 486.59"' + '>' + linearGrad + '<polygon style="fill: url(#'+id+'_grad'+currValue+');stroke:' + settings.strokeColor + ';stroke-width:2px;" ' + 'points="256.814,12.705 317.205,198.566' + ' 512.631,198.566 354.529,313.435 ' + '414.918,499.295 256.814,384.427 ' + '98.713,499.295 159.102,313.435 ' + '1,198.566 196.426,198.566 "/>' + '</svg>';
                    break;
                case 'CIRCLE':
                    shapeRate = header + '>' + linearGrad + '<circle  cx="' + settings.width / 2 + '" cy="' + settings.height / 2 + '" r="' + settings.width / 2 + '" fill="url(#'+id+'_grad'+currValue+')" style="stroke:' + settings.strokeColor + ';stroke-width:2px;"/>' + '</svg>';
                    break;
                case 'RECTANGLE':
                    shapeRate = header + '>' + linearGrad + '<rect width="' + settings.width + '" height="' + settings.height + '" fill="url(#'+id+'_grad'+currValue+')" style="stroke:' + settings.strokeColor + ';stroke-width:2px;"/>' +
                    '</svg>';
                    break;
                case 'TRIANGLE':
                    shapeRate = header + '>' + linearGrad +
                    '<polygon points="' + settings.width / 2 + ',0 0,' + settings.height + ' ' + settings.width + ',' + settings.height + '" fill="url(#'+id+'_grad'+currValue+')" style="stroke:' + settings.strokeColor + ';stroke-width:2px;"/>' +
                    '</svg>';
                    break;
                case 'RHOMBUS':
                    shapeRate = header + '>' + linearGrad + '<polygon points="' + settings.width / 2 + ',0 ' + settings.width + ',' + settings.height / 2 + ' ' + settings.width / 2 + ',' + settings.height + ' 0,' + settings.height / 2 + '" fill="url(#'+id+'_grad'+currValue+')"  style="stroke:' + settings.strokeColor + ';stroke-width:2px;"/>' + '</svg>';
                    break;
                case 'FOOD':
                    shapeRate = header + 'viewBox="0 0 50 50"' + '>' + linearGrad +
                    '<path fill="url(#'+id+'_grad'+currValue+')" style="stroke:' + settings.strokeColor + ';"' +
                    'd="M45.694,21.194C45.694,9.764,36.43,0.5,25,0.5S4.306,9.764,4.306,21.194c0,8.621,5.272,16.005,12.764,19.115'+
                    'c-1.882,2.244-3.762,4.486-5.645,6.73c-0.429,0.5-0.458,1.602,0.243,2.145c0.7,0.551,1.757,0.252,2.139-0.289'+
                    'c1.878-2.592,3.753-5.189,5.63-7.783c1.774,0.494,3.633,0.777,5.561,0.777c1.85,0,3.64-0.266,5.349-0.723'+
                    'c1.617,2.563,3.238,5.121,4.862,7.684c0.34,0.555,1.365,0.91,2.088,0.414c0.728-0.492,0.759-1.58,0.368-2.096'+
                    'c-1.663-2.252-3.332-4.508-4.995-6.76C40.3,37.354,45.694,29.91,45.694,21.194z M25,37.824c-1.018,0-2.01-0.105-2.977-0.281'+
                    'c1.07-1.48,2.146-2.965,3.215-4.447c0.939,1.48,1.874,2.959,2.81,4.436C27.058,37.717,26.041,37.824,25,37.824z M30.155,37'+
                    'c-1.305-1.764-2.609-3.527-3.91-5.295c0.724-1,1.446-1.998,2.17-3c1.644,0.746,3.646,0,4.827-1.787c1.239-1.872,0.005,0,0.005,0.002'+
                    'c0.01-0.014,5.822-8.824,5.63-8.97c-0.186-0.15-3.804,4.771-6.387,8.081l-0.548-0.43c2.362-3.481,5.941-8.426,5.757-8.575'+
                    'c-0.189-0.146-3.959,4.655-6.652,7.878l-0.545-0.428c2.463-3.398,6.202-8.228,6.014-8.374c-0.188-0.15-4.115,4.528-6.917,7.67'+
                    'l-0.547-0.43c2.575-3.314,6.463-8.02,6.278-8.169c-0.191-0.15-5.808,6.021-7.319,7.651c-1.325,1.424-1.664,3.68-0.562,5.12'+
                    'c-0.703,0.84-1.41,1.678-2.113,2.518c-0.781-1.057-1.563-2.111-2.343-3.17c1.975-1.888,1.984-5.234-0.054-7.626'+
                    'c-2.14-2.565-6.331-5.22-8.51-3.818c-2.093,1.526-1.14,6.396,0.479,9.316c1.498,2.764,4.617,3.965,7.094,2.805'+
                    'c0.778,1.227,1.554,2.455,2.333,3.684c-1.492,1.783-2.984,3.561-4.478,5.342C13.197,34.826,8.38,28.574,8.38,21.191'+
                    'c0-9.183,7.444-16.631,16.632-16.631c9.188,0,16.625,7.447,16.625,16.631C41.63,28.576,36.816,34.828,30.155,37z"/>'+'</svg>';
                    break;
                case 'TWITTER':
                    shapeRate = header + 'viewBox="0 0 512 512"' + '>' + linearGrad +
                    '<path fill="url(#'+id+'_grad'+currValue+')" style="stroke:' + settings.strokeColor + ';stroke-width:0.7px;"' +
                    'd="M512,97.209c-18.838,8.354-39.082,14.001-60.33,16.54c21.687-13,38.343-33.585,46.187-58.115'+								 'c-20.299,12.039-42.778,20.78-66.705,25.49c-19.16-20.415-46.461-33.17-76.674-33.17c-58.011,0-105.043,47.029-105.043,105.039'+
                    'c0,8.233,0.929,16.25,2.72,23.939c-87.3-4.382-164.701-46.2-216.509-109.753c-9.042,15.514-14.223,33.558-14.223,52.809'+
                    'c0,36.444,18.544,68.596,46.73,87.433c-17.219-0.546-33.416-5.271-47.577-13.139c-0.01,0.438-0.01,0.878-0.01,1.321'+
                    'c0,50.894,36.209,93.348,84.261,103c-8.813,2.399-18.094,3.686-27.674,3.686c-6.769,0-13.349-0.66-19.764-1.887'+
                    'c13.368,41.73,52.16,72.104,98.126,72.949c-35.95,28.175-81.243,44.967-130.458,44.967c-8.479,0-16.84-0.497-25.058-1.471'+
                    'c46.486,29.806,101.701,47.197,161.021,47.197c193.211,0,298.868-160.062,298.868-298.872c0-4.554-0.103-9.084-0.305-13.59'+
                    'C480.11,136.773,497.918,118.273,512,97.209z"/>'+'</svg>';
                    break;
                case 'BULB':
                    shapeRate = header + 'viewBox="0 0 512 512"' + '>' + linearGrad +
                    '<path fill="url(#'+id+'_grad'+currValue+')" style="stroke:' + settings.strokeColor + ';stroke-width:0.7px;"' + 'd="M384,192c0,64-64,127-64,192H192c0-63-64-128-64-192c0-70.688,57.313-128,128-128S384,121.313,384,192z M304,448h-96'+
                    'c-8.844,0-16,7.156-16,16s7.156,16,16,16h2.938c6.594,18.625,24.188,32,45.063,32s38.469-13.375,45.063-32H304'+
                    'c8.844,0,16-7.156,16-16S312.844,448,304,448z M304,400h-96c-8.844,0-16,7.156-16,16s7.156,16,16,16h96c8.844,0,16-7.156,16-16'+
                    'S312.844,400,304,400z M81.719,109.875l28.719,16.563c4.438-9.813,9.844-19,16.094-27.656L97.719,82.125L81.719,109.875z'+
                    ' M272,33.625V0h-32v33.625C245.344,33.063,250.5,32,256,32S266.656,33.063,272,33.625z M190.438,46.438l-16.563-28.719l-27.75,16'+
                    'l16.656,28.813C171.438,56.281,180.625,50.875,190.438,46.438z M430.281,109.875l-16-27.75l-28.813,16.656'+
                    'c6.25,8.656,11.688,17.844,16.125,27.656L430.281,109.875z M365.844,33.719l-27.688-16l-16.563,28.719'+
                    'c9.781,4.438,19,9.844,27.625,16.063L365.844,33.719z M96,192c0-5.5,1.063-10.656,1.625-16H64v32h33.688'+
                    'C97.063,202.688,96,197.438,96,192z M414.375,176c0.563,5.344,1.625,10.5,1.625,16c0,5.438-1.063,10.688-1.688,16H448v-32H414.375z'+
                    ' M388.094,286.719l26.188,15.125l16-27.719l-29.063-16.75C397.188,267.313,392.813,277.063,388.094,286.719z M81.719,274.125'+
                    'l16,27.719l25.969-14.969c-4.688-9.688-9.063-19.5-13.031-29.438L81.719,274.125z"/>'+'</svg>';
                    break;
                default:
                    throw Error("No such shape as " + settings['shape']);
            }
            return shapeRate;
        }

        function setCSS() {
            // setup css properies
            $jRate.css("white-space", "nowrap");
            $jRate.css("cursor", "pointer");

            $jRate.css('fill', settings['shape']);
        }

        function bindEvents($svg, i) {
            $svg.on("mousemove", onMouseEnter(i))
                .on("mouseenter", onMouseEnter(i))
                .on("click", onMouseClick(i))
                .on("mouseover", onMouseEnter(i))
                .on("hover", onMouseEnter(i))
                .on("mouseleave", onMouseLeave)
                .on("mouseout", onMouseLeave)
                .on("JRate.change", onChange)
                .on("JRate.set", onSet);
            if (settings.touch) {
                $svg.on("touchstart", onTouchEnter(i))
                    .on("touchmove", onTouchEnter(i))
                    .on("touchend", onTouchClick(i))
                    .on("tap", onTouchClick(i))
                    .on("JRate.change", onChange)
                    .on("JRate.set", onSet);
            }
        }

        function showNormalRating() {
            var id = $jRate.attr('id');
            for (var i = 0; i < settings.count; i++) {
                shapes.eq(i).find('#'+id+'_grad'+(i+1)).find("stop").eq(0).attr({
                    'offset': '0%'
                });
                shapes.eq(i).find('#'+id+'_grad'+(i+1)).find("stop").eq(0).attr({
                    'stop-color': settings.backgroundColor
                });
                shapes.eq(i).find('#'+id+'_grad'+(i+1)).find("stop").eq(1).attr({
                    'offset': '0%'
                });
                shapes.eq(i).find('#'+id+'_grad'+(i+1)).find("stop").eq(1).attr({
                    'stop-color': settings.backgroundColor
                });
            }
        }

        function showRating(rating) {

            showNormalRating();
            var singleValue = (settings.max - settings.min) / settings.count;
            rating = (rating - settings.min) / singleValue;
            var fillColor = settings.startColor;
            var id = $jRate.attr('id');

            if (settings.reverse) {
                for (var i = 0; i < rating; i++) {
                    var j = settings.count - 1 - i;
                    shapes.eq(j).find('#'+id+'_grad'+(j+1)).find("stop").eq(0).attr({
                        'offset': '100%'
                    });
                    shapes.eq(j).find('#'+id+'_grad'+(j+1)).find("stop").eq(0).attr({
                        'stop-color': fillColor
                    });
                    if (parseInt(rating) !== rating) {
                        var k = Math.ceil(settings.count - rating) - 1;
                        shapes.eq(k).find('#'+id+'_grad'+(k+1)).find("stop").eq(0).attr({
                            'offset': 100 - (rating * 10 % 10) * 10 + '%'
                        });
                        shapes.eq(k).find('#'+id+'_grad'+(k+1)).find("stop").eq(0).attr({
                            'stop-color': settings.backgroundColor
                        });
                        shapes.eq(k).find('#'+id+'_grad'+(k+1)).find("stop").eq(1).attr({
                            'offset': 100 - (rating * 10 % 10) * 10 + '%'
                        });
                        shapes.eq(k).find('#'+id+'_grad'+(k+1)).find("stop").eq(1).attr({
                            'stop-color': fillColor
                        });
                    }
                    if (isDefined(endColorCoords)) {
                        fillColor = formulateNewColor(settings.count - 1, i);
                    }
                }
            } else {
                for (var i = 0; i < rating; i++) {
                    shapes.eq(i).find('#'+id+'_grad'+(i+1)).find("stop").eq(0).attr({
                        'offset': '100%'
                    });
                    shapes.eq(i).find('#'+id+'_grad'+(i+1)).find("stop").eq(0).attr({
                        'stop-color': fillColor
                    });
                    if (rating * 10 % 10 > 0) {
                        shapes.eq(Math.ceil(rating) - 1).find('#'+id+'_grad'+(i+1)).find("stop").eq(0).attr({
                            'offset': (rating * 10 % 10) * 10 + '%'
                        });
                        shapes.eq(Math.ceil(rating) - 1).find('#'+id+'_grad'+(i+1)).find("stop").eq(0).attr({
                            'stop-color': fillColor
                        });
                    }
                    if (isDefined(endColorCoords)) {
                        fillColor = formulateNewColor(settings.count, i);
                    }
                }
            }
        }

        var formulateNewColor = function(totalCount, currentVal) {
            var avgFill = [];
            for (var i = 0; i < 3; i++) {
                var diff = Math.round((startColorCoords[i] - endColorCoords[i]) / totalCount);
                var newValue = startColorCoords[i] + (diff * (currentVal + 1));
                if (newValue / 256)
                    avgFill[i] = (startColorCoords[i] - (diff * (currentVal + 1))) % 256;
                else
                    avgFill[i] = (startColorCoords[i] + (diff * (currentVal + 1))) % 256;
            }
            return "rgba(" + avgFill[0] + "," + avgFill[1] + "," + avgFill[2] + "," + settings.opacity + ")";
        };



        function colorToRGBA(color) {
            var cvs, ctx;
            cvs = document.createElement('canvas');
            cvs.height = 1;
            cvs.width = 1;
            ctx = cvs.getContext('2d');
            ctx.fillStyle = color;
            ctx.fillRect(0, 0, 1, 1);
            return ctx.getImageData(0, 0, 1, 1).data;
        }

        function onMouseLeave() {
            if (!settings.readOnly) {
                showRating(settings.rating);
                onChange(null, {rating : settings.rating});
            }
        }

        function onEnterOrClickEvent(e, ith, label, update) {
            if (settings.readOnly) return;

            var svg = shapes.eq(ith - 1);
            var partial;

            if (settings.horizontal) {
                partial = (e.pageX - svg.offset().left) / svg.width();
            } else {
                partial = (e.pageY - svg.offset().top) / svg.height();
            }

            var count = (settings.max - settings.min) / settings.count;
            partial = (settings.reverse) ? partial : 1 - partial;
            var rating = ((settings.reverse ? (settings.max - settings.min - ith + 1) : ith) - partial) * count;
            if(settings.precision === 0) {
                rating = (settings.reverse ? (settings.max - settings.min - ith + 1) : ith);
            } else {
                rating = settings.min + Number(rating.toFixed(settings.precision));
            }

            if (rating <= settings.max && rating >= settings.min) {
                showRating(rating);
                if (update) settings.rating = rating;
                svg.trigger(label, {
                    rating: rating
                });
            }

        }

        function onTouchOrTapEvent(e, ith, label, update) {
            if (settings.readOnly) return;

            var touches = e.originalEvent.changedTouches;
            // Ignore multi-touch
            if (touches.length > 1) return;
            var touch = touches[0];

            var svg = shapes.eq(ith - 1);
            var partial;
            if (settings.horizontal) {
                partial = (touch.pageX - svg.offset().left) / svg.width();
            } else {
                partial = (touch.pageY - svg.offset().top) / svg.height();
            }

            var count = (settings.max - settings.min) / settings.count;
            partial = (settings.reverse) ? partial : 1 - partial;
            var rating = ((settings.reverse ? (settings.max - settings.min - ith + 1) : ith) - partial) * count;
            rating = settings.min + Number(rating.toFixed(settings.precision));

            if (rating <= settings.max && rating >= settings.min) {
                showRating(rating);
                if (update) settings.rating = rating;
                svg.trigger(label, {
                    rating: rating
                });
            }
        }

        function onMouseEnter(i) {
            return function(e) {
                onEnterOrClickEvent(e, i, "JRate.change");
            };
        }

        function onMouseClick(i) {
            return function(e) {
                onEnterOrClickEvent(e, i, "JRate.set", true);
            };
        }

        function onTouchEnter(i) {
            return function(e) {
                onTouchOrTapEvent(e, i, "JRate.touch");
            };
        }

        function onTouchClick(i) {
            return function(e) {
                onTouchOrTapEvent(e, i, "JRate.tap", true);
            };
        }

        function onChange(e, data) {
            if (settings.onChange && typeof settings.onChange === "function") {
                settings.onChange.apply(this, [data.rating]);
            }
        }

        function onSet(e, data) {
            if (settings.onSet && typeof settings.onSet === "function") {
                settings.onSet.apply(this, [data.rating]);
            }
        }

        function drawShape() {
            var svg, i, sw, sh;
            for (i = 0; i < settings.count; i++) {
                $jRate.append(getShape(i+1));
            }
            shapes = $jRate.find('svg');
            for (i = 0; i < settings.count; i++) {
                svg = shapes.eq(i);
                bindEvents(svg, i + 1);
                if (!settings.horizontal) {
                    svg.css({
                        'display': 'block',
                        'margin-bottom': settings.shapeGap || '0px'
                    });
                } else {
                    svg.css('margin-right', (settings.shapeGap || '0px'));
                }
                if (settings.widthGrowth) {
                    sw = 'scaleX(' + (1 + settings.widthGrowth * i) + ')';
                    svg.css({
                        'transform': sw,
                        '-webkit-transform': sw,
                        '-moz-transform': sw,
                        '-ms-transform': sw,
                        '-o-transform': sw,
                    });
                }

                if (settings.heightGrowth) {
                    sh = 'scaleY(' + (1 + settings.heightGrowth * i) + ')';
                    svg.css({
                        'transform': sh,
                        '-webkit-transform': sh,
                        '-moz-transform': sh,
                        '-ms-transform': sh,
                        '-o-transform': sh,
                    });
                }
            }
            showNormalRating();
            showRating(settings.rating);
            shapes.attr({
                width: settings.width,
                height: settings.height
            });
        }

        //TODO
        //Validation implementation
        //Mini to max size

        //TODO Add this as a part of validation
        if (settings.startColor) startColorCoords = colorToRGBA(settings.startColor);
        if (settings.endColor) endColorCoords = colorToRGBA(settings.endColor);

        setCSS();
        drawShape();;

        return $.extend({}, this, {
            "getRating": getRating,
            "setRating": setRating,
            "setReadOnly": function(flag) {
                settings.readOnly = flag;
            },
            "isReadOnly": function() {
                return settings.readOnly;
            },
        });
    };
}(jQuery));
