(function($){	

	jQuery.fn.extend({

		_getStateId: function() {
			var id = $(this).attr('data-state');
			if (!id.length) {
				console.log('element can not maintain state without id or data-state attributes ::');
				console.log(this);
			}
			//console.log(id);
			return 'gui.session.' + id;
		},

		_encodeState: function(state_object) {
			sessionStorage.setItem($(this)._getStateId(), JSON.stringify(state_object));
			return this;
		},

		_decodeState: function() {
			// if element is w/o state record one is created
			var state = sessionStorage.getItem($(this)._getStateId());
			if (!state) state = '{"forceClasses":[], "banClasses":[], "style":""}';
			// formValues: {},
			//console.log(state);
			return JSON.parse(state);
		},

		// add a class to an element and it's session
		addStateClass: function(string) {
			$(this).each(function() {
				var state = $(this)._decodeState();
				// if class is missing, add it
				if (state.forceClasses.indexOf(string) == -1) {
					$(this).addClass(string);
					state.forceClasses.push(string);
				}
				// if class is banned, unban it
				if (state.banClasses.indexOf(string) != -1) {
					state.banClasses.splice(state.banClasses.indexOf(string),1);
				}
				$(this)._encodeState(state);
			});
			// save changes
			return this;
		},

		// remove a class from an element and it's session
		removeStateClass: function(string) {
			$(this).each(function() {
				var state = $(this)._decodeState();
				// if the class is unbanned, ban it
				if (state.banClasses.indexOf(string) == -1) {
					state.banClasses.push(string);
				}
				// if class is present, remove it
				if (state.forceClasses.indexOf(string) != -1) {
					$(this).removeClass(string);
					state.forceClasses.splice(state.forceClasses.indexOf(string),1);
				}
				$(this)._encodeState(state);
			});
			// save changes
			return this;
		},

		hasStateClass: function(string) {
			var state = $(this)._decodeState();
			if (state.forceClasses.indexOf(string) != -1) return true;
			else return false;
		},

		toggleStateClass: function(string) {
			if ($(this).hasStateClass(string)) {
				return $(this).removeStateClass(string);
			}
			else {
				return $(this).addStateClass(string);
			}
		},

		updateStateStyle: function() {
			$(this).each(function() {
				var state = $(this)._decodeState();
				state.style = $(this).attr('style');
				$(this)._encodeState(state);
			});
			return this;
		},

		enforceState: function() {
			$(this).each(function() {
				var state = $(this)._decodeState();
				var $this = $(this);
				// add all enforces classes
				$.each(state.forceClasses, function(index, value) {
					$this.addClass(value);
				});
				// remove all banned classes
				$.each(state.banClasses, function(index, value) {
					$this.removeClass(value);
				});
				$this.attr('style',state.style);
			});
			return this;
		},

	});

})(jQuery);
