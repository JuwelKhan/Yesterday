=== Yesterday ===
Contributors: juwelkhan
Requires at least: 6.0
Tested up to: 7.0
Requires PHP: 7.4
Stable tag: 1.1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Tags: blog, three-columns, two-columns, left-sidebar, right-sidebar, custom-colors, custom-logo, custom-menu, featured-images, footer-widgets, post-formats, threaded-comments, translation-ready, sticky-post

Yesterday is a calm, content-first classic blog theme for writers and personal bloggers.

== Description ==

Yesterday is a calm, content-first classic blog theme for writers and personal
bloggers. A theme for people who actually write — minimal, readable,
three-column on desktop, with post formats, an automatic table of contents,
and a flexible author card. Designed to look clean on a fresh install, with no
external requests.

Features:

* Three-column desktop layout (left rail, content, right sidebar) with clean
  mobile stacking.
* Automatic table of contents with scroll-spy on long posts and pages.
* Post format support (standard, gallery, image, video, audio, quote, link,
  status, chat) with dedicated card styling.
* Custom Author Card widget with photo, bio, social links, and layout options.
* Deep, multi-level dropdown navigation with edge-aware flyout flipping.
* Customizer options for color palette / accent color, typography presets
  (self-hosted webfonts), and layout toggles (header search, author box,
  sticky sidebars).
* Custom "About / Author" page template with a portrait hero.
* Estimated reading time, related posts, and a back-to-top button, each
  optional from the Customizer.
* No external HTTP requests — icons and webfonts are bundled and self-hosted.

== Installation ==

1. In your admin panel, go to Appearance -> Themes and click the "Add New" button.
2. Click "Upload Theme" and use the file upload field to upload the theme zip file.
3. Click "Install Now".
4. Click "Activate" to use your new theme right away.

== Frequently Asked Questions ==

= Does this theme support any plugins? =

Yesterday supports core WordPress features (custom logo, nav menus, widgets,
post thumbnails, custom colors via the Customizer) and works with the block
editor out of the box. No additional plugins are required.

== Changelog ==

= 1.1.0 =
* New: estimated reading time on every post, in listings and on the single
  post itself. Counts words with a Unicode-aware split, so non-Latin scripts
  (Bengali and others) are counted correctly, not just Latin text.
* New: a "You might also like" related-posts section after each single
  post, matched by category (falling back to tags). Only appears when a
  genuine match is found — never fills the space with unrelated posts.
* New: a back-to-top button that fades in after scrolling.
* New: excerpt length is now adjustable from the Customizer (Layout
  section). Defaults to WordPress's own default (55 words), so existing
  sites keep their current excerpt length after updating unless changed.
* New Customizer toggles (Layout section, both on by default): "Show
  estimated reading time" and "Show related posts after each article" —
  every new addition in this release can be turned off individually.

= 1.0.3 =
* Replaced all three screenshot demo photos (previously from a stock-photo
  source not on WordPress.org's approved list for theme images) with CC0
  photos from the WordPress.org Photo Directory and StockSnap.io, and
  removed the disallowed source reference from readme.txt.

= 1.0.2 =
* Accessibility: the mobile menu now traps keyboard focus while open — Tab
  from the last item and Shift+Tab from the first both wrap to the menu
  toggle button, Shift+Tab from the toggle wraps to the last item, and Escape
  closes the menu and returns focus to the toggle.
* Included the unminified source (bootstrap-icons.css) alongside the bundled,
  enqueued bootstrap-icons.min.css.
* Documented the license and source URL for every photo shown in the
  screenshot preview, and replaced one demo photo whose source license
  couldn't be confirmed with a clearly-licensed replacement.

= 1.0.1 =
* Accessibility: fixed the skip-link to use the standard clip technique instead
  of an off-screen position, and fixed dropdown menus to close reliably with
  Escape, a repeat click, or the pointer leaving.
* Fixed the header search button losing its intended styling, and the search
  results page banner search alongside it.
* Fixed the single-post left rail to correctly show the current post's author
  when no headings are present for a table of contents.
* Theme URI updated to a page specific to this theme.

= 1.0.0 =
* Initial release.

== Credits ==

Yesterday WordPress Theme, (C) 2026 Jewel Khan.
Yesterday is distributed under the terms of the GNU GPL v2 or later.

* Bootstrap Icons (https://icons.getbootstrap.com/), (C) 2019-2024 The
  Bootstrap Authors, licensed under the MIT License
  (https://github.com/twbs/icons/blob/main/LICENSE.md).
* Inter font (https://github.com/rsms/inter), Copyright The Inter Project
  Authors, licensed under the SIL Open Font License, 1.1
  (https://openfontlicense.org/).
* Lora font (https://github.com/cyrealtype/Lora-Cyrillic), Copyright The Lora
  Project Authors, licensed under the SIL Open Font License, 1.1
  (https://openfontlicense.org/).
* Playfair Display font (https://github.com/clauseggers/Playfair-Display),
  Copyright The Playfair Display Project Authors, licensed under the SIL Open
  Font License, 1.1 (https://openfontlicense.org/).

Screenshot images (demo content shown on the theme preview only, not
included with the theme download):

* Photo from StockSnap.io, released under CC0 (public domain).
  https://stocksnap.io/photo/man-interior-BXO1M5UBLM
* Photo from the WordPress.org Photo Directory, released under CC0 (public
  domain). https://wordpress.org/photos/photo/8216a5c8ab/
* Photo from the WordPress.org Photo Directory, released under CC0 (public
  domain). https://wordpress.org/photos/photo/1156a73ff5/

All screenshots and demo content shown on the theme preview are for
demonstration purposes only and are not included with the theme download.
