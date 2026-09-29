/* Yesterday theme — front-end scripts */
( function () {
	'use strict';

	document.addEventListener( 'DOMContentLoaded', function () {

		// Mobile navigation toggle. While open, keyboard focus is trapped inside
		// the toggle button + nav panel: Tab past the last focusable item wraps
		// to the toggle button, Shift+Tab on the first item (or on the toggle
		// button itself) wraps to the other end, and Escape closes the menu and
		// returns focus to the toggle. Without this, Tab/Shift+Tab can walk
		// straight past the open menu into the page content hidden behind it.
		var toggle = document.querySelector( '.nav-toggle' );
		var nav = document.getElementById( 'primary-nav' );
		var mobileNav = window.matchMedia( '(max-width: 991px)' );

		function getTrapFocusables() {
			if ( ! nav ) {
				return [];
			}
			var all = nav.querySelectorAll( 'a[href], button:not([disabled]), input:not([disabled])' );
			// Nested submenus are `display:none` until their own toggle opens
			// them, so filter to what's actually focusable right now rather
			// than caching this list once when the menu first opens.
			return Array.prototype.filter.call( all, function ( el ) {
				return null !== el.offsetParent;
			} );
		}

		function closeMobileNav() {
			nav.classList.remove( 'open' );
			toggle.setAttribute( 'aria-expanded', 'false' );
		}

		if ( toggle && nav ) {
			toggle.addEventListener( 'click', function () {
				var isOpen = nav.classList.toggle( 'open' );
				toggle.setAttribute( 'aria-expanded', isOpen ? 'true' : 'false' );
			} );

			document.addEventListener( 'keydown', function ( e ) {
				if ( ! mobileNav.matches || ! nav.classList.contains( 'open' ) ) {
					return;
				}

				if ( 'Escape' === e.key ) {
					closeMobileNav();
					toggle.focus();
					return;
				}

				if ( 'Tab' !== e.key ) {
					return;
				}

				var focusables = getTrapFocusables();
				var first = focusables[ 0 ];
				var last = focusables[ focusables.length - 1 ];
				var active = document.activeElement;

				if ( e.shiftKey ) {
					if ( active === toggle ) {
						e.preventDefault();
						if ( last ) { last.focus(); }
					} else if ( active === first ) {
						e.preventDefault();
						toggle.focus();
					}
				} else if ( active === last ) {
					e.preventDefault();
					toggle.focus();
				}
				// Forward Tab from the toggle button needs no handling — DOM
				// order already leads straight into the first nav item.
			} );
		}

		// Header "Pages" dropdown. `.open` is the single source of truth for
		// visibility (see _dropdown.scss) — hover, click and keyboard all just
		// add/remove that one class, so they can never fight each other (a
		// plain CSS `:hover` rule alongside a JS-toggled class used to mean a
		// click-to-close while the pointer was still hovering had no visible
		// effect, and Escape/outside-click had nothing dropdown-specific to
		// hook into either).
		var dropdowns = document.querySelectorAll( '.has-dropdown' );
		var desktopNav = window.matchMedia( '(min-width: 992px)' );

		function openDropdown( item, btn ) {
			item.classList.add( 'open' );
			if ( btn ) {
				btn.setAttribute( 'aria-expanded', 'true' );
			}
		}

		function closeDropdown( item, btn ) {
			item.classList.remove( 'open' );
			if ( btn ) {
				btn.setAttribute( 'aria-expanded', 'false' );
			}
		}

		dropdowns.forEach( function ( item ) {
			var btn = item.querySelector( ':scope > .dropdown-toggle' );
			if ( ! btn ) {
				return;
			}

			btn.addEventListener( 'click', function ( e ) {
				e.stopPropagation();
				if ( item.classList.contains( 'open' ) ) {
					closeDropdown( item, btn );
				} else {
					openDropdown( item, btn );
				}
			} );

			// Desktop pointer users: hover opens/closes it exactly like click
			// does (same class, same effect) — mobile has no hover concept and
			// uses the stacked-accordion `.open` toggle only.
			item.addEventListener( 'mouseenter', function () {
				if ( desktopNav.matches ) {
					openDropdown( item, btn );
				}
			} );
			item.addEventListener( 'mouseleave', function () {
				if ( desktopNav.matches ) {
					closeDropdown( item, btn );
				}
			} );
		} );

		// Close any open dropdown when clicking outside it.
		document.addEventListener( 'click', function ( e ) {
			dropdowns.forEach( function ( item ) {
				if ( ! item.contains( e.target ) ) {
					closeDropdown( item, item.querySelector( ':scope > .dropdown-toggle' ) );
				}
			} );
		} );

		// Escape closes the innermost open dropdown and returns focus to its
		// toggle button, matching standard disclosure-menu keyboard behaviour.
		document.addEventListener( 'keydown', function ( e ) {
			if ( 'Escape' !== e.key ) {
				return;
			}
			var open = Array.prototype.filter.call( dropdowns, function ( item ) {
				return item.classList.contains( 'open' );
			} );
			if ( ! open.length ) {
				return;
			}
			var deepest = open[ open.length - 1 ];
			var btn = deepest.querySelector( ':scope > .dropdown-toggle' );
			closeDropdown( deepest, btn );
			if ( btn ) {
				btn.focus();
			}
		} );

		// Keep deep fly-out submenus inside the viewport (desktop).
		setupSubmenuFlip();

		// Collapse very long widget lists behind a "Show more" toggle.
		setupWidgetListCollapse();

		// Table of contents (single post + page)
		buildTableOfContents();

		// Image lightbox (galleries + content images)
		setupLightbox();

		// "Copy link" share button
		setupCopyLink();

		// Back-to-top button
		setupBackToTop();

		// Drop genuinely-empty caption boxes left behind by some imported
		// content (a .wp-caption with no media and no text). Captions only —
		// paragraphs and author-intended blank-line spacing are never touched.
		tidyEmptyCaptions();
	} );

	/**
	 * Collision handling for the multi-level menu (desktop only).
	 *
	 * Nested fly-outs open to the right by default. If opening right would push a
	 * fly-out past the viewport's right edge, we add `.flip-down` to its parent so
	 * the CSS opens that submenu downward (from under the parent item, right-
	 * aligned) instead — keeping every level reachable without a horizontal page
	 * scroll. Top-level dropdowns near the right edge get right-aligned the same
	 * way. Measured fresh on each open, so it always matches the current width.
	 */
	function setupSubmenuFlip() {
		var desktop = window.matchMedia( '(min-width: 992px)' );
		var parents = document.querySelectorAll( '.has-dropdown' );
		var EDGE_GAP = 8; // px breathing room from the viewport edge.

		function place( item ) {
			var submenu = item.querySelector( ':scope > .submenu' );
			if ( ! submenu ) {
				return;
			}

			// On mobile the menu is a stacked accordion — no flipping needed.
			if ( ! desktop.matches ) {
				item.classList.remove( 'flip-down' );
				return;
			}

			// Measure from the default (open-right / left-aligned) position.
			item.classList.remove( 'flip-down' );
			var rect = submenu.getBoundingClientRect();
			if ( rect.right > window.innerWidth - EDGE_GAP ) {
				item.classList.add( 'flip-down' );
			}
		}

		parents.forEach( function ( item ) {
			// Pointer users: decide when the fly-out is about to appear.
			item.addEventListener( 'mouseenter', function () {
				place( item );
			} );
			// Keyboard / click users: decide when focus or the toggle opens it.
			item.addEventListener( 'focusin', function () {
				place( item );
			} );
			var toggle = item.querySelector( ':scope > .dropdown-toggle' );
			if ( toggle ) {
				toggle.addEventListener( 'click', function () {
					place( item );
				} );
			}
		} );
	}

	/**
	 * Collapse very long lists inside widget areas behind a "Show more" toggle.
	 *
	 * Only genuinely long top-level lists are touched: a list with more than
	 * COLLAPSE_OVER items is trimmed to the first VISIBLE, and a button reveals
	 * the rest (and hides them again). Nested sub-lists are left alone.
	 */
	function setupWidgetListCollapse() {
		var VISIBLE = 6;        // items shown while collapsed.
		var COLLAPSE_OVER = 8;  // only collapse lists longer than this.

		var l10n = window.yesterdayData || {};
		var moreLabel = '+ ' + ( l10n.showMore || 'Show more' );
		var lessLabel = '− ' + ( l10n.showLess || 'Show less' ); // − minus sign

		var widgets = document.querySelectorAll( '.widget, .footer-widget' );

		widgets.forEach( function ( widget ) {
			var lists = widget.querySelectorAll( 'ul, ol' );

			lists.forEach( function ( list ) {
				// Top-level lists only — skip nested sub-lists.
				if ( list.closest( 'li' ) ) {
					return;
				}

				var items = Array.prototype.filter.call( list.children, function ( el ) {
					return 'LI' === el.tagName;
				} );

				if ( items.length <= COLLAPSE_OVER ) {
					return;
				}

				// Hide everything past the visible count.
				items.forEach( function ( li, i ) {
					if ( i >= VISIBLE ) {
						li.hidden = true;
					}
				} );

				var btn = document.createElement( 'button' );
				btn.type = 'button';
				btn.className = 'widget-more';
				btn.textContent = moreLabel;
				btn.setAttribute( 'aria-expanded', 'false' );

				var expanded = false;
				btn.addEventListener( 'click', function () {
					expanded = ! expanded;
					items.forEach( function ( li, i ) {
						if ( i >= VISIBLE ) {
							li.hidden = ! expanded;
						}
					} );
					btn.textContent = expanded ? lessLabel : moreLabel;
					btn.setAttribute( 'aria-expanded', expanded ? 'true' : 'false' );
				} );

				list.parentNode.insertBefore( btn, list.nextSibling );
			} );
		} );
	}

	// Minimum headings before a TOC is worth showing.
	var TOC_MIN_HEADINGS = 3;

	function slugify( text, used ) {
		// Keep letters/numbers of ANY language (so Bengali and other non-Latin
		// headings get meaningful ids, not "section-2"); \p{M} keeps combining
		// marks such as Bengali vowel signs (কার); drop other punctuation.
		var base = text.toLowerCase()
			.replace( /[^\p{L}\p{N}\p{M}\s-]/gu, '' )
			.trim()
			.replace( /\s+/g, '-' );
		if ( ! base ) {
			base = 'section';
		}
		var slug = base;
		var i = 2;
		while ( used[ slug ] ) {
			slug = base + '-' + i;
			i++;
		}
		used[ slug ] = true;
		return slug;
	}

	function buildTableOfContents() {
		var rail = document.querySelector( '.left-rail' );
		if ( ! rail ) {
			return;
		}

		var toc = rail.querySelector( '.toc' );
		// The wrapper, not `.author-card` itself — it may hold the Author Card
		// widget (dynamic_sidebar) or the post-author fallback card, and either
		// can change without this needing to know which.
		var fallback = rail.querySelector( '.left-rail-fallback' );
		var content = document.querySelector( '.entry-content' );
		var layout = rail.closest( '.layout' );

		function showFallback() {
			if ( toc ) { toc.hidden = true; }
			if ( fallback ) {
				fallback.hidden = false;
			} else {
				// No fallback (e.g. a page): drop the empty rail so the
				// grid returns to its normal column count.
				rail.hidden = true;
				if ( layout ) { layout.classList.remove( 'toc-active' ); }
			}
		}

		if ( ! toc || ! content ) {
			showFallback();
			return;
		}

		var headings = content.querySelectorAll( 'h2, h3' );
		if ( headings.length < TOC_MIN_HEADINGS ) {
			showFallback();
			return;
		}

		var list = toc.querySelector( '.toc-list' );
		var used = {};
		var links = [];

		headings.forEach( function ( h ) {
			if ( ! h.id ) {
				h.id = slugify( h.textContent, used );
			} else {
				used[ h.id ] = true;
			}

			var li = document.createElement( 'li' );
			if ( h.tagName.toLowerCase() === 'h3' ) {
				li.className = 'toc-h3';
			}
			var a = document.createElement( 'a' );
			a.href = '#' + h.id;
			a.textContent = h.textContent;
			li.appendChild( a );
			list.appendChild( li );
			links.push( { link: a, target: h } );
		} );

		toc.hidden = false;
		if ( fallback ) { fallback.hidden = true; }
		if ( layout ) { layout.classList.add( 'toc-active' ); }

		setupScrollSpy( links );
	}

	function setupScrollSpy( links ) {
		if ( ! ( 'IntersectionObserver' in window ) || ! links.length ) {
			return;
		}

		var byId = {};
		links.forEach( function ( item ) {
			byId[ item.target.id ] = item.link;
		} );

		var current = null;
		function setActive( link ) {
			if ( current === link ) { return; }
			if ( current ) { current.classList.remove( 'is-active' ); }
			if ( link ) { link.classList.add( 'is-active' ); }
			current = link;
		}

		var visible = {};

		// Decide which link should be active right now.
		function updateActive() {
			// If the page is scrolled (near) to the bottom, the last section
			// can't reach the top of the viewport — force its link active.
			var scrollBottom = window.innerHeight + window.scrollY;
			var docHeight = document.documentElement.scrollHeight;
			if ( docHeight - scrollBottom < 40 ) {
				setActive( links[ links.length - 1 ].link );
				return;
			}

			// Otherwise: the first heading (in document order) currently in
			// the spy band wins.
			for ( var i = 0; i < links.length; i++ ) {
				if ( visible[ links[ i ].target.id ] ) {
					setActive( byId[ links[ i ].target.id ] );
					return;
				}
			}
		}

		var observer = new IntersectionObserver( function ( entries ) {
			entries.forEach( function ( entry ) {
				if ( entry.isIntersecting ) {
					visible[ entry.target.id ] = true;
				} else {
					delete visible[ entry.target.id ];
				}
			} );
			updateActive();
		}, {
			rootMargin: '-10% 0px -70% 0px',
			threshold: 0
		} );

		links.forEach( function ( item ) {
			observer.observe( item.target );
		} );

		// Catch the bottom-of-page case, which the observer alone can miss.
		window.addEventListener( 'scroll', updateActive, { passive: true } );
	}

	// ---------------------------------------------------------------
	//  Lightbox — galleries + content images, with prev/next (no deps)
	// ---------------------------------------------------------------
	var IMG_EXT = /\.(jpe?g|png|gif|webp|avif|bmp|tiff?)(\?.*)?$/i;
	var SCOPE_SEL = '.entry-content, .gallery-grid';
	var GALLERY_SEL = '.gallery, .gallery-grid, .wp-block-gallery, .blocks-gallery-grid, .tiled-gallery';

	function setupLightbox() {
		if ( ! document.querySelector( SCOPE_SEL ) ) {
			return;
		}

		var box, boxImg, boxCap, btnPrev, btnNext;
		var group = [];
		var index = 0;

		// Resolve the best full-size URL for a thumbnail image.
		function fullSrc( img ) {
			var a = img.closest( 'a' );
			if ( a && a.href && IMG_EXT.test( a.href ) ) {
				return a.href; // link points straight at the file.
			}
			var ss = img.getAttribute( 'srcset' );
			if ( ss ) {
				var best = null, bestW = 0;
				ss.split( ',' ).forEach( function ( part ) {
					var bits = part.trim().split( /\s+/ );
					var w = parseInt( bits[ 1 ] || '0', 10 );
					if ( w >= bestW ) {
						bestW = w;
						best = bits[ 0 ];
					}
				} );
				if ( best ) {
					return best;
				}
			}
			var src = img.currentSrc || img.src;
			// Drop a WordPress size suffix (name-1024x768.jpg → name.jpg).
			return src.replace( /-\d+x\d+(\.[a-z0-9]+)(\?.*)?$/i, '$1$2' );
		}

		function captionFor( img ) {
			var fig = img.closest( 'figure, .gallery-item, dl' );
			var c = fig && fig.querySelector( 'figcaption, .gallery-caption, .wp-caption-text' );
			return c ? c.textContent.trim() : '';
		}

		function build() {
			box = document.createElement( 'div' );
			box.className = 'yd-lightbox';
			box.setAttribute( 'role', 'dialog' );
			box.setAttribute( 'aria-modal', 'true' );
			box.innerHTML =
				'<button class="yd-lightbox-close" type="button" aria-label="Close"><i class="bi bi-x-lg" aria-hidden="true"></i></button>' +
				'<button class="yd-lightbox-nav yd-prev" type="button" aria-label="Previous image"><i class="bi bi-chevron-left" aria-hidden="true"></i></button>' +
				'<figure class="yd-lightbox-figure"><img alt=""><figcaption class="yd-lightbox-caption"></figcaption></figure>' +
				'<button class="yd-lightbox-nav yd-next" type="button" aria-label="Next image"><i class="bi bi-chevron-right" aria-hidden="true"></i></button>';
			boxImg = box.querySelector( 'img' );
			boxCap = box.querySelector( '.yd-lightbox-caption' );
			btnPrev = box.querySelector( '.yd-prev' );
			btnNext = box.querySelector( '.yd-next' );
			document.body.appendChild( box );

			box.addEventListener( 'click', function ( e ) {
				if ( e.target === box || e.target.closest( '.yd-lightbox-close' ) ) {
					close();
				}
			} );
			btnPrev.addEventListener( 'click', function ( e ) {
				e.stopPropagation();
				show( index - 1 );
			} );
			btnNext.addEventListener( 'click', function ( e ) {
				e.stopPropagation();
				show( index + 1 );
			} );
			document.addEventListener( 'keydown', function ( e ) {
				if ( ! box || ! box.classList.contains( 'is-open' ) ) {
					return;
				}
				if ( 'Escape' === e.key ) {
					close();
				} else if ( 'ArrowLeft' === e.key ) {
					show( index - 1 );
				} else if ( 'ArrowRight' === e.key ) {
					show( index + 1 );
				}
			} );
		}

		function show( i ) {
			if ( ! group.length ) {
				return;
			}
			index = ( i + group.length ) % group.length; // wrap around.
			var item = group[ index ];
			boxImg.src = item.src;
			boxImg.alt = item.alt || '';
			boxCap.textContent = item.caption || '';
			boxCap.style.display = item.caption ? '' : 'none';
			var multi = group.length > 1;
			btnPrev.style.display = multi ? '' : 'none';
			btnNext.style.display = multi ? '' : 'none';
		}

		function open( imgs, startImg ) {
			if ( ! box ) {
				build();
			}
			group = imgs.map( function ( img ) {
				return { src: fullSrc( img ), alt: img.alt, caption: captionFor( img ) };
			} );
			var start = imgs.indexOf( startImg );
			show( start < 0 ? 0 : start );
			box.classList.add( 'is-open' );
			document.body.style.overflow = 'hidden';
		}

		function close() {
			if ( box ) {
				box.classList.remove( 'is-open' );
				document.body.style.overflow = '';
			}
		}

		// Should a click on this image open the lightbox (vs. follow a link)?
		function isLightboxable( img ) {
			if ( img.closest( GALLERY_SEL ) ) {
				return true; // gallery images always zoom, even if linked to an attachment page.
			}
			var a = img.closest( 'a' );
			if ( ! a ) {
				return true; // bare content image.
			}
			return !! ( a.href && IMG_EXT.test( a.href ) ); // a link straight to a file.
		}

		document.addEventListener( 'click', function ( e ) {
			var img = e.target.closest( 'img' );
			if ( ! img || ! img.closest( SCOPE_SEL ) || ! isLightboxable( img ) ) {
				return;
			}
			e.preventDefault();
			var gallery = img.closest( GALLERY_SEL );
			var imgs = gallery
				? Array.prototype.slice.call( gallery.querySelectorAll( 'img' ) )
				: [ img ];
			open( imgs, img );
		} );
	}

	// ---------------------------------------------------------------
	//  "Copy link" share button
	// ---------------------------------------------------------------
	function setupCopyLink() {
		var buttons = document.querySelectorAll( '.share-btn[data-copy-url]' );
		buttons.forEach( function ( btn ) {
			btn.addEventListener( 'click', function () {
				var url = btn.getAttribute( 'data-copy-url' );
				if ( navigator.clipboard && navigator.clipboard.writeText ) {
					navigator.clipboard.writeText( url );
				}
				var original = btn.getAttribute( 'data-tooltip' );
				btn.setAttribute( 'data-tooltip', 'Copied!' );
				setTimeout( function () {
					if ( original ) {
						btn.setAttribute( 'data-tooltip', original );
					}
				}, 1500 );
			} );
		} );
	}

	// ---------------------------------------------------------------
	//  Back to top
	// ---------------------------------------------------------------
	function setupBackToTop() {
		var btn = document.querySelector( '.back-to-top' );
		if ( ! btn ) {
			return;
		}

		function toggle() {
			btn.classList.toggle( 'is-visible', window.scrollY > window.innerHeight );
		}

		window.addEventListener( 'scroll', toggle, { passive: true } );
		toggle();

		btn.addEventListener( 'click', function () {
			window.scrollTo( { top: 0, behavior: 'smooth' } );
		} );
	}

	// ---------------------------------------------------------------
	//  Empty caption cleanup
	//  Some imported posts ship a stray <dl class="wp-caption"> whose <dt>
	//  is empty — a caption box wrapping nothing. It renders as dead space.
	//  We remove ONLY captions that contain no media (img/picture/iframe/
	//  video/svg) AND no non-whitespace text, so a real caption (or one
	//  around a video) is never affected, and no paragraph/spacing is touched.
	// ---------------------------------------------------------------
	function tidyEmptyCaptions() {
		var captions = document.querySelectorAll( '.entry-content .wp-caption, .c-body .wp-caption' );
		captions.forEach( function ( cap ) {
			if ( cap.querySelector( 'img, picture, iframe, video, svg' ) ) {
				return;
			}
			// Strip non-breaking spaces too, so an &nbsp;-only caption counts
			// as blank.
			var text = cap.textContent.replace( / /g, "" ).trim();
			if ( text === '' ) {
				cap.remove();
			}
		} );
	}
} )();
