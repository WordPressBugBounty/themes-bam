var bam = bam || {};

// polyfill forEach
// https://developer.mozilla.org/en-US/docs/Web/API/NodeList/forEach#Polyfill
if ( window.NodeList && ! NodeList.prototype.forEach ) {
	NodeList.prototype.forEach = function( callback, thisArg ) {
		var i;
		var len = this.length;

		thisArg = thisArg || window;

		for ( i = 0; i < len; i++ ) {
			callback.call( thisArg, this[ i ], i, this );
		}
	};
}

/**
 * Keyboard navigation support for menus
 */
 bam.keyboardNavigation = {

	init: function() {
		this.initMenus();
	},

	// The focusMenuItems() function implements Keyboard Navigation in the Bam Menus
	// by adding the '.focus' class to all 'li' items when the focus is on the 'a' element.
	focusMenuItems: function(navigationId) {
		var navigation = document.getElementById(navigationId); 

		// Return early if the navigation does not exist.
		if ( ! navigation ) {
			return;
		}

		const menu = navigation.getElementsByTagName( 'ul' )[ 0 ];

		// If menu is empty return early.
		if ( 'undefined' === typeof menu ) {
			return;
		}

		if ( ! menu.classList.contains( 'nav-menu' ) ) {
			menu.classList.add( 'nav-menu' );
		}

		// Get all the link elements within the menu.
		var links = menu.getElementsByTagName( 'a' );

		// Toggle focus each time a menu link is focused or blurred.
		for ( var link of links ) {
			link.addEventListener( 'focus', toggleFocus, true );
			link.addEventListener( 'blur', toggleFocus, true );
		}

		/**
		 * Sets or removes .foucs class on an element.
		 */
		function toggleFocus() {
			var self = this;
			// Move up through the ancestors of the current link until we hit .nav-menu
			while ( ! self.classList.contains( 'nav-menu' ) ) {
				// On li elements toggle the class .focus
				if ( 'li' === self.tagName.toLowerCase() ) {
					self.classList.toggle( 'focus' );
				}
				self = self.parentNode;
			}
		}
	},

	initMenus: function() {
		var navigationIds = ['site-navigation', 'top-navigation'];
		navigationIds.forEach(function(navigationId) {
            this.focusMenuItems(navigationId);
        }, this);
	}
};

/**
 * Mobile Menu.
 */
bam.mobileMenu = {

    init: function() {
        // Do the toggle.
        this.toggle();
    },

    performToggle: function( element ) {

        var target = element.dataset.toggleTarget,
            activeClass = 'toggled-on';

        // Get the target element.
        targetElement = document.querySelector(target);

        // Change the icon of the menu toggle button.
        if ( element.classList.contains('menu-toggle') ) {
            this.toggleIcon(element);
        }

        element.classList.toggle(activeClass);

        bamToggleAttribute(element, 'aria-expanded', 'true', 'false');

        targetElement.classList.toggle(activeClass);

    },

    toggle: function() {
        var self = this;

        document.querySelectorAll('*[data-toggle-target]').forEach( function( element ) {
            element.addEventListener( 'click', function( event ) {
                event.preventDefault();
                self.performToggle( element );
            } );
        } );
    },

    toggleIcon: function(element) {
        var icon = element.querySelector('i.fas');

		if ( ! icon ) {
			return false;
		}

        if( icon.classList.contains('fa-bars') ) {
            icon.classList.remove('fa-bars');
            icon.classList.add('fa-times');
        } else if ( icon.classList.contains('fa-times') ) {
            icon.classList.remove('fa-times');
            icon.classList.add('fa-bars');
        }
    }
};

/**
 * Search Toggle.
 */
 bam.searchToggle = {
    init: function() {
        this.addListener();
    },

    toggle: function( element ) {
        
		var navigationContainer = element.parentElement,
            icon                = navigationContainer.querySelector('i.fas'),
            searchBoxContainer  = navigationContainer.querySelector('.bam-search-box-container'),
            inputField          = navigationContainer.querySelector('input.search-field');

		// Toggle active class.
		searchBoxContainer.classList.toggle('active');

		// Toggle search icon.
		this.toggleIcon(icon);

		// Focus the input field if the search box is displayed.
		if( searchBoxContainer.classList.contains('active') ) {
			inputField.focus();
		}

    },

	addListener: function() {
		
		var searchButton = document.querySelector('.bam-search-button-icon');

		if ( ! searchButton ) {
			return false;
		}

		var self = this;
		
		searchButton.addEventListener( 'click', function() {
			self.toggle( this );
		} );

	},

    toggleIcon: function( icon ) {
        if ( icon.classList.contains('fa-search') ) {
            icon.classList.remove('fa-search');
            icon.classList.add('fa-times');
        } else if ( icon.classList.contains('fa-times') ) {
            icon.classList.remove('fa-times');
            icon.classList.add('fa-search');
        }
    }
};

/**
 * Intrinsic Ratio Embeds
 * From TwentyTwenty WordPress theme. - Copyright (C) 2020 WordPress.org, GPLv2 or later
 */
bam.intrinsicRatioVideos = {

	init: function() {
		this.makeFit();

		window.addEventListener( 'resize', function() {
			this.makeFit();
		}.bind( this ) );
	},

	makeFit: function() {
		document.querySelectorAll( 'iframe, object, video' ).forEach( function( video ) {
			var ratio, iTargetWidth,
				container = video.parentNode;

			// Skip videos we want to ignore.
			if ( video.classList.contains( 'intrinsic-ignore' ) || video.parentNode.classList.contains( 'intrinsic-ignore' ) ) {
				return true;
			}

			if ( ! video.dataset.origwidth ) {
				// Get the video element proportions.
				video.setAttribute( 'data-origwidth', video.width );
				video.setAttribute( 'data-origheight', video.height );
			}

			iTargetWidth = container.offsetWidth;

			// Get ratio from proportions.
			ratio = iTargetWidth / video.dataset.origwidth;

			// Scale based on ratio, thus retaining proportions.
			video.style.width = iTargetWidth + 'px';
			video.style.height = ( video.dataset.origheight * ratio ) + 'px';
		} );
	}

};

/* Toggle an attribute */
function bamToggleAttribute( element, attribute, trueVal, falseVal ) {
	if ( trueVal === undefined ) {
		trueVal = true;
	}
	if ( falseVal === undefined ) {
		falseVal = false;
	}
	if ( element.getAttribute( attribute ) !== trueVal ) {
		element.setAttribute( attribute, trueVal );
	} else {
		element.setAttribute( attribute, falseVal );
	}
}

/**
 * Is the DOM ready?
 *
 * This implementation is coming from https://gomakethings.com/a-native-javascript-equivalent-of-jquerys-ready-method/
 *
 * @param {Function} fn Callback function to run.
 */
function bamDomReady( fn ) {
	if ( typeof fn !== 'function' ) {
		return;
	}

	if ( document.readyState === 'interactive' || document.readyState === 'complete' ) {
		return fn();
	}

	document.addEventListener( 'DOMContentLoaded', fn, false );
}

bamDomReady( function() {
	bam.mobileMenu.init();
    bam.searchToggle.init();
    bam.keyboardNavigation.init();
    bam.intrinsicRatioVideos.init();
} );