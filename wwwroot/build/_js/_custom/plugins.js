/* Eventual goal is to migrate all plugins to browserify/webpack 
   require statements. For now, copy contents of previous plugins.js
   file but gradually replace code with require statements. */

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

(function(e){var a=[],d=/^url\(["']?([^"'\)]*)["']?\);?$/i,c=/\.png$/i,b=e.browser.msie&&e.browser.version==6;function f(){e.each(a,function(){this.refresh(true)})}e(window).resize(f);e.Poshytip=function(h,g){this.$elm=e(h);this.opts=e.extend({},e.fn.poshytip.defaults,g);this.$tip=e(['<div class="',this.opts.className,'">','<div class="tip-inner tip-bg-image"></div>','<div class="tip-arrow tip-arrow-top tip-arrow-right tip-arrow-bottom tip-arrow-left"></div>',"</div>"].join("")).appendTo(document.body);this.$arrow=this.$tip.find("div.tip-arrow");this.$inner=this.$tip.find("div.tip-inner");this.disabled=false;this.content=null;this.init()};e.Poshytip.prototype={init:function(){a.push(this);var g=this.$elm.attr("title");this.$elm.data("title.poshytip",g!==undefined?g:null).data("poshytip",this);if(this.opts.showOn!="none"){this.$elm.bind({"mouseenter.poshytip":e.proxy(this.mouseenter,this),"mouseleave.poshytip":e.proxy(this.mouseleave,this)});switch(this.opts.showOn){case"hover":if(this.opts.alignTo=="cursor"){this.$elm.bind("mousemove.poshytip",e.proxy(this.mousemove,this))}if(this.opts.allowTipHover){this.$tip.hover(e.proxy(this.clearTimeouts,this),e.proxy(this.mouseleave,this))}break;case"focus":this.$elm.bind({"focus.poshytip":e.proxy(this.show,this),"blur.poshytip":e.proxy(this.hide,this)});break}}},mouseenter:function(g){if(this.disabled){return true}this.$elm.attr("title","");if(this.opts.showOn=="focus"){return true}this.clearTimeouts();this.showTimeout=setTimeout(e.proxy(this.show,this),this.opts.showTimeout)},mouseleave:function(g){if(this.disabled||this.asyncAnimating&&(this.$tip[0]===g.relatedTarget||jQuery.contains(this.$tip[0],g.relatedTarget))){return true}var h=this.$elm.data("title.poshytip");if(h!==null){this.$elm.attr("title",h)}if(this.opts.showOn=="focus"){return true}this.clearTimeouts();this.hideTimeout=setTimeout(e.proxy(this.hide,this),this.opts.hideTimeout)},mousemove:function(g){if(this.disabled){return true}this.eventX=g.pageX;this.eventY=g.pageY;if(this.opts.followCursor&&this.$tip.data("active")){this.calcPos();this.$tip.css({left:this.pos.l,top:this.pos.t});if(this.pos.arrow){this.$arrow[0].className="tip-arrow tip-arrow-"+this.pos.arrow}}},show:function(){if(this.disabled||this.$tip.data("active")){return}this.reset();this.update();this.display();if(this.opts.timeOnScreen){setTimeout(e.proxy(this.hide,this),this.opts.timeOnScreen)}},hide:function(){if(this.disabled||!this.$tip.data("active")){return}this.display(true)},reset:function(){this.$tip.queue([]).detach().css("visibility","hidden").data("active",false);this.$inner.find("*").poshytip("hide");if(this.opts.fade){this.$tip.css("opacity",this.opacity)}this.$arrow[0].className="tip-arrow tip-arrow-top tip-arrow-right tip-arrow-bottom tip-arrow-left";this.asyncAnimating=false},update:function(j,k){if(this.disabled){return}var i=j!==undefined;if(i){if(!k){this.opts.content=j}if(!this.$tip.data("active")){return}}else{j=this.opts.content}var h=this,g=typeof j=="function"?j.call(this.$elm[0],function(l){h.update(l)}):j=="[title]"?this.$elm.data("title.poshytip"):j;if(this.content!==g){this.$inner.empty().append(g);this.content=g}this.refresh(i)},refresh:function(h){if(this.disabled){return}if(h){if(!this.$tip.data("active")){return}var k={left:this.$tip.css("left"),top:this.$tip.css("top")}}this.$tip.css({left:0,top:0}).appendTo(document.body);if(this.opacity===undefined){this.opacity=this.$tip.css("opacity")}var l=this.$tip.css("background-image").match(d),m=this.$arrow.css("background-image").match(d);if(l){var i=c.test(l[1]);if(b&&i){this.$tip.css("background-image","none");this.$inner.css({margin:0,border:0,padding:0});l=i=false}else{this.$tip.prepend('<table border="0" cellpadding="0" cellspacing="0"><tr><td class="tip-top tip-bg-image" colspan="2"><span></span></td><td class="tip-right tip-bg-image" rowspan="2"><span></span></td></tr><tr><td class="tip-left tip-bg-image" rowspan="2"><span></span></td><td></td></tr><tr><td class="tip-bottom tip-bg-image" colspan="2"><span></span></td></tr></table>').css({border:0,padding:0,"background-image":"none","background-color":"transparent"}).find(".tip-bg-image").css("background-image",'url("'+l[1]+'")').end().find("td").eq(3).append(this.$inner)}if(i&&!e.support.opacity){this.opts.fade=false}}if(m&&!e.support.opacity){if(b&&c.test(m[1])){m=false;this.$arrow.css("background-image","none")}this.opts.fade=false}var o=this.$tip.find("table");if(b){this.$tip[0].style.width="";o.width("auto").find("td").eq(3).width("auto");var n=this.$tip.width(),j=parseInt(this.$tip.css("min-width")),g=parseInt(this.$tip.css("max-width"));if(!isNaN(j)&&n<j){n=j}else{if(!isNaN(g)&&n>g){n=g}}this.$tip.add(o).width(n).eq(0).find("td").eq(3).width("100%")}else{if(o[0]){o.width("auto").find("td").eq(3).width("auto").end().end().width(document.defaultView&&document.defaultView.getComputedStyle&&parseFloat(document.defaultView.getComputedStyle(this.$tip[0],null).width)||this.$tip.width()).find("td").eq(3).width("100%")}}this.tipOuterW=this.$tip.outerWidth();this.tipOuterH=this.$tip.outerHeight();this.calcPos();if(m&&this.pos.arrow){this.$arrow[0].className="tip-arrow tip-arrow-"+this.pos.arrow;this.$arrow.css("visibility","inherit")}if(h){this.asyncAnimating=true;var p=this;this.$tip.css(k).animate({left:this.pos.l,top:this.pos.t},200,function(){p.asyncAnimating=false})}else{this.$tip.css({left:this.pos.l,top:this.pos.t})}},display:function(h){var i=this.$tip.data("active");if(i&&!h||!i&&h){return}this.$tip.stop();if((this.opts.slide&&this.pos.arrow||this.opts.fade)&&(h&&this.opts.hideAniDuration||!h&&this.opts.showAniDuration)){var m={},l={};if(this.opts.slide&&this.pos.arrow){var k,g;if(this.pos.arrow=="bottom"||this.pos.arrow=="top"){k="top";g="bottom"}else{k="left";g="right"}var j=parseInt(this.$tip.css(k));m[k]=j+(h?0:(this.pos.arrow==g?-this.opts.slideOffset:this.opts.slideOffset));l[k]=j+(h?(this.pos.arrow==g?this.opts.slideOffset:-this.opts.slideOffset):0)+"px"}if(this.opts.fade){m.opacity=h?this.$tip.css("opacity"):0;l.opacity=h?0:this.opacity}this.$tip.css(m).animate(l,this.opts[h?"hideAniDuration":"showAniDuration"])}h?this.$tip.queue(e.proxy(this.reset,this)):this.$tip.css("visibility","inherit");this.$tip.data("active",!i)},disable:function(){this.reset();this.disabled=true},enable:function(){this.disabled=false},destroy:function(){this.reset();this.$tip.remove();delete this.$tip;this.content=null;this.$elm.unbind(".poshytip").removeData("title.poshytip").removeData("poshytip");a.splice(e.inArray(this,a),1)},clearTimeouts:function(){if(this.showTimeout){clearTimeout(this.showTimeout);this.showTimeout=0}if(this.hideTimeout){clearTimeout(this.hideTimeout);this.hideTimeout=0}},calcPos:function(){var n={l:0,t:0,arrow:""},h=e(window),k={l:h.scrollLeft(),t:h.scrollTop(),w:h.width(),h:h.height()},p,j,m,i,q,g;if(this.opts.alignTo=="cursor"){p=j=m=this.eventX;i=q=g=this.eventY}else{var o=this.$elm.offset(),l={l:o.left,t:o.top,w:this.$elm.outerWidth(),h:this.$elm.outerHeight()};p=l.l+(this.opts.alignX!="inner-right"?0:l.w);j=p+Math.floor(l.w/2);m=p+(this.opts.alignX!="inner-left"?l.w:0);i=l.t+(this.opts.alignY!="inner-bottom"?0:l.h);q=i+Math.floor(l.h/2);g=i+(this.opts.alignY!="inner-top"?l.h:0)}switch(this.opts.alignX){case"right":case"inner-left":n.l=m+this.opts.offsetX;if(n.l+this.tipOuterW>k.l+k.w){n.l=k.l+k.w-this.tipOuterW}if(this.opts.alignX=="right"||this.opts.alignY=="center"){n.arrow="left"}break;case"center":n.l=j-Math.floor(this.tipOuterW/2);if(n.l+this.tipOuterW>k.l+k.w){n.l=k.l+k.w-this.tipOuterW}else{if(n.l<k.l){n.l=k.l}}break;default:n.l=p-this.tipOuterW-this.opts.offsetX;if(n.l<k.l){n.l=k.l}if(this.opts.alignX=="left"||this.opts.alignY=="center"){n.arrow="right"}}switch(this.opts.alignY){case"bottom":case"inner-top":n.t=g+this.opts.offsetY;if(!n.arrow||this.opts.alignTo=="cursor"){n.arrow="top"}if(n.t+this.tipOuterH>k.t+k.h){n.t=i-this.tipOuterH-this.opts.offsetY;if(n.arrow=="top"){n.arrow="bottom"}}break;case"center":n.t=q-Math.floor(this.tipOuterH/2);if(n.t+this.tipOuterH>k.t+k.h){n.t=k.t+k.h-this.tipOuterH}else{if(n.t<k.t){n.t=k.t}}break;default:n.t=i-this.tipOuterH-this.opts.offsetY;if(!n.arrow||this.opts.alignTo=="cursor"){n.arrow="bottom"}if(n.t<k.t){n.t=g+this.opts.offsetY;if(n.arrow=="bottom"){n.arrow="top"}}}this.pos=n}};e.fn.poshytip=function(h){if(typeof h=="string"){var g=arguments,k=h;Array.prototype.shift.call(g);if(k=="destroy"){this.die("mouseenter.poshytip").die("focus.poshytip")}return this.each(function(){var l=e(this).data("poshytip");if(l&&l[k]){l[k].apply(l,g)}})}var i=e.extend({},e.fn.poshytip.defaults,h);if(!e("#poshytip-css-"+i.className)[0]){e(['<style id="poshytip-css-',i.className,'" type="text/css">',"div.",i.className,"{visibility:hidden;position:absolute;top:0;left:0;}","div.",i.className," table, div.",i.className," td{margin:0;font-family:inherit;font-size:inherit;font-weight:inherit;font-style:inherit;font-variant:inherit;}","div.",i.className," td.tip-bg-image span{display:block;font:1px/1px sans-serif;height:",i.bgImageFrameSize,"px;width:",i.bgImageFrameSize,"px;overflow:hidden;}","div.",i.className," td.tip-right{background-position:100% 0;}","div.",i.className," td.tip-bottom{background-position:100% 100%;}","div.",i.className," td.tip-left{background-position:0 100%;}","div.",i.className," div.tip-inner{background-position:-",i.bgImageFrameSize,"px -",i.bgImageFrameSize,"px;}","div.",i.className," div.tip-arrow{visibility:hidden;position:absolute;overflow:hidden;font:1px/1px sans-serif;}","</style>"].join("")).appendTo("head")}if(i.liveEvents&&i.showOn!="none"){var j=e.extend({},i,{liveEvents:false});switch(i.showOn){case"hover":this.live("mouseenter.poshytip",function(){var l=e(this);if(!l.data("poshytip")){l.poshytip(j).poshytip("mouseenter")}});break;case"focus":this.live("focus.poshytip",function(){var l=e(this);if(!l.data("poshytip")){l.poshytip(j).poshytip("show")}});break}return this}return this.each(function(){new e.Poshytip(this,i)})};e.fn.poshytip.defaults={content:"[title]",className:"tip-yellow",bgImageFrameSize:10,showTimeout:500,hideTimeout:100,timeOnScreen:0,showOn:"hover",liveEvents:false,alignTo:"cursor",alignX:"right",alignY:"top",offsetX:-22,offsetY:18,allowTipHover:true,followCursor:false,fade:true,slide:true,slideOffset:8,showAniDuration:300,hideAniDuration:300}})(global.jQuery);


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
