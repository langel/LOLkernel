var lol = lol || {};


lol.fn = {

	lib_cycle: function() {
		$.each(lol,function(key, val) {
			if (lol.fn.settings.initialized[key]!==true) {
				if ($.isFunction(this.initialize))	{
					this.boot();
					//console.log(key+' initialized');
				}
				lol.fn.settings.initialized[key] = true;
			}
			else {
				if ($.isFunction(this.recall)) {
					this.cycle();
				}
			}
		});
	},

	boot: function() {
		// XXX load lists of js and css attachments in head
	},

	cycle: function() {
		lol.fn.processAjax();
	},

	loljax_defaults: {
		// setting dataType is a hack for undefined content type
		data: {},
		dataType: 'json',
		error: function(jqXHR,textStatus) {
			console.log(textStatus+' :: '+this.url);
		},
		global: false,
		success: function(data,textStatus,jqXHR) {
		type: 'POST',
		},
	},

	ajax: function(settings,context) {
		if (settings.substring) settings = { url: settings };
		var settings = $.extend({},lol.fn.loljax_defaults,settings);
		if (settings.url.indexOf('.nimjax') < 0) {
			settings.url = settings.url + '.nimjax';
		}
		settings.data.ajax_page_state = lol.settings.ajaxPageState;
		settings.data.page_session = lol.settings.page_session;
		//console.log(settings.url);
		context = context || 'document';
		$.ajax(settings).done(function(data) {
			//console.log(lol.settings);
			//console.log(data.length);
			var c = 0;
			$.each(data,function() {
				c++;
				//console.log(this.command+'  '+settings.url);
				if (lol.fn.ajax_commands[this.command]==undefined) {
					//console.log("nimjax command '"+this.command+"' undefined");
					//console.log(this);
				}
				else {
					$.extend(true,this,{ajax_send_settings:settings});
					lol.fn.ajax_commands[this.command].call({},this);
				}
			});
			//console.log(c);
			lol.fn.lib_cycle();
		});
	},



	ajax_commands: {


		history: function(response) {
			var state = { source : window.location.href };
			history.pushState(state,'',response.url);
		},


		jquery: function(response) {
			element = $(response.selector);
			if (!response.arguments instanceof Array) response.arguments = [response.arguments];
			element[response.method].call(element, response.arguments);
			/*
			XXX left this code in incase the above breaks
			if (response.arguments instanceof Array) {
				element[response.method].apply(element, response.arguments);
			} else {
				element[response.method].call(element, response.arguments);
			}
			*/
		},


		log: function (response) {
			console.log(response.message);
		},


		update_slot: function(response) {
			// discover target
			var target = response.ajax_send_settings.target;
			var newhtml = $('<div/>').html(response.data).children();
			if (!target.length) {
				target = $('#'+newhtml.attr('id'));
			}
			else {
				target = $(target);
			}
			// replace statehood of new html
			$('[data-state]',newhtml).enforceState();
			//insert updated html
			target.replaceWith(newhtml);
		},


		redirect: function(response) {
			window.location = response.url;
		},

	},


	processAjax: function() {
		
		// XXX add forms to process
		$('form:not(.nojs):not(.ajax-processed)').addClass('use-ajax');

		$('.use-ajax').each(function() {
			var $this = $(this);

			// anchor tags
			if ($this.is('a')) {
				$this.click(function(e) {
					e.preventDefault();
					nimjax({url: $this.attr('href')});
				});
			}

			// form submission
			if ($this.is('form')) {
				$this.submit(function(e) {
					e.preventDefault();
					var inputs = $('input,textarea,select,button',$this);
					inputs.prop('disabled',true);
					var cleanup = function() { 
						inputs.prop('disabled',true); 
						lol.progress.remove($this);
					}

					// uses a different procedure if file field is present
					if ($('input:file',$this).size()>0) {
						var data = new FormData();
						$('input:not(:file),textarea,select,button',$this).each(function() {
							data.append($(this).attr('name'), $(this).val());
						});
						// XXX untested on multiple file uploads
						$('input:file',$this).each(function() {
							data.append($(this).attr('name'), this.files[0]); 
							//console.log(this.files[0]);
							lol.progress.filebar($this,'append',base_url + 
								'medialibrary/progress/?id=' + 
								$('input[name=UPLOAD_IDENTIFIER]',$this).val());
						});
						nimjax({
							url: $this.attr('action'),
							contentType: false,
							processData: false,
							data: data,
							complete: cleanup,
						});
					}
					// standard procedure without file transfer
					else {
						var data = {};
						$('input,textarea,select,button',$this).each(function() {
							data[$(this).attr('name')] = $(this).val();
						});
						lol.progress.spinner($this,'append','PROCESSING . . .');
						nimjax({
							url: $this.attr('action'),
							data: data,
							complete: cleanup,
						});
					}
				});
			}

			// process once
			$this.removeClass('use-ajax').addClass('ajax-processed');
		});
	},

	addTransitionListener: function(callback,context) {
		context = context || document;
		var t, transition,
			el = document.createElement('tranChecker'),
			transitions = {
				'transition':'transitionend',
				'OTransition':'oTransitionEnd',
				'MozTransition':'transitionend',
				'WebkitTransition':'webkitTransitionEnd'
			};
		for (t in transitions) {
			if (el.style[t] !== undefined) transition = transitions[t];
		}
		if (typeof transition === 'string') {
			context.addEventListener(transition,callback,false);
		}
	},

};


// intialize lolkernel.js system
$(function() { lol.fn.run_cycle(); });


// shorthand!! :D/
var loljax = lol.fn.ajax;
