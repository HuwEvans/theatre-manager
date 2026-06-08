# Theatre Manager - Changelog

## [3.7.11] - 2026-06-08

### Added
- **URL Button Display**: Added `urlbutton` parameter to tm_landingpage shortcode to display ticket URLs as styled buttons.
- **Button Format Options**: Added `buttonformat` parameter with 9 comprehensive styling options (default, modern, minimal, outline, gradient, prominent, success, ghost, glass).
- **Button Styling**: Comprehensive CSS button styles with hover effects, transitions, and responsive sizing.
- **Fixed castcols Parameter**: Fixed issue where castcols parameter wasn't being passed to field rendering function for castwithbio field.

### Changed
- **Function Signature**: Updated `tm_render_landingpage_field()` to accept $atts parameter for proper parameter passing.
- **Ticket URL Rendering**: Enhanced to support both link and button display modes based on urlbutton parameter.
- **Page Alignment Support**: Landing page wrapper and field divs now inherit text-align from parent containers (WordPress center alignment, custom CSS classes, etc.), ensuring buttons conform to page alignment.
- **Version Update**: Plugin version moved from 3.7.10 to 3.7.11.

### Fixed
- **Button Alignment**: Buttons now properly respect page/container text-align properties for center, left, and right alignment.

## [3.7.10] - 2026-06-08

### Added
- **Show Dates Rich Editor**: Show dates field in Season Builder now supports HTML formatting with TinyMCE editor for bold, italic, lists, links, etc.
- **Castwithbio Labels**: Actor names in castwithbio field now display with "Played by" prefix for better readability.

### Changed
- **Show Dates Sanitization**: Updated to use `wp_kses_post()` to allow HTML formatting in show dates.
- **Version Update**: Plugin version moved from 3.7.9 to 3.7.10.

## [3.7.9] - 2026-06-08

### Added
- **Cast Bio in Season Builder**: Added bio textarea field to cast rows in Season Builder for editing actor biographies.
- **Show Producer & Stage Manager**: Added producer and stage_manager fields to Show form in Season Builder.
- **JavaScript Templates**: Updated dynamic row creation for cast and show rows to include new fields.

### Changed
- **Season Builder Cast Form**: Updated table column widths (28%/28%/28%/16%) to accommodate bio field.
- **Data Sanitization**: Applied appropriate sanitization (textarea vs text) for new fields.
- **Version Update**: Plugin version moved from 3.7.8 to 3.7.9.

## [3.7.8] - 2026-06-08

### Added
- **Column Control Parameter**: Added `castcols` parameter to tm_landingpage shortcode for castwithbio field (range 1-6, default 3).
- **Responsive Grid Updates**: Enhanced CSS media queries with grid-column min() function for tablet breakpoint adaptation.

### Changed
- **Image Sizing**: Reduced cast image heights from 250px to 180px (desktop), 160px (tablet), 140px (mobile) for better column fit.
- **Object-fit**: Applied `object-fit: cover` for consistent aspect ratios in fixed-size containers.
- **CSS Grid Implementation**: Updated `.tm-landingpage-castwithbio` to use CSS custom property `--cast-cols` for dynamic column control.
- **Version Update**: Plugin version moved from 3.7.7 to 3.7.8.

### Documentation
- **shortcode_usage.txt**: Added castcols parameter documentation and usage examples for 2-column and 4-column layouts.

## [3.7.7] - 2026-06-08

### Added
- **Cast Biography Integration**: Added bio field to cast posts and integrated into castwithbio shortcode field.
- **Bio Display**: Bio now displays below actor name in castwithbio responsive grid with HTML preservation via `wp_kses_post()`.

### Changed
- **Cast CPT**: Added _tm_cast_bio meta field to cast post type.
- **Shortcode Output**: Enhanced castwithbio to include bio text below actor information.
- **Version Update**: Plugin version moved from 3.7.6 to 3.7.7.

## [3.7.6] - 2026-06-08

### Added
- **Castwithbio Field**: New responsive grid field for tm_landingpage shortcode displaying cast members with picture, character name, actor name, and biography.
- **CSS Grid Layout**: Implemented responsive grid with auto-fit columns, gap spacing, and proper image sizing.
- **Hard Breaks Documentation**: Added section to shortcode_usage.txt explaining how hard_breaks true/false impacts block-level vs inline display of shortcode output.

### Changed
- **Image Display Logic**: Smart conditional wrapping based on content type (image-only uses inline-block, mixed content uses centered div, text-only uses raw output).
- **CSS Custom Properties**: Implemented --cast-cols for dynamic column control in responsive layouts.
- **Responsive Breakpoints**: Added media queries at 1024px, 768px, and 480px for mobile-first design.
- **Version Update**: Plugin version moved from 3.7.5 to 3.7.6.

### Documentation
- **shortcode_usage.txt**: Added comprehensive castwithbio field documentation with parameter explanations and responsive behavior notes.

## [3.4] - 2026-05-25

### Added
- **Auditions Shortcode**: Added `TM_Auditions` / `tm_auditions` to display upcoming auditions ordered by audition date.

### Changed
- **Version Update**: Plugin version moved from 3.3 to 3.4.

## [3.3] - 2026-05-23

### Added
- **Show Auditions**: Added `Audition Date` and rich-text `Audition Details` fields to Shows.
- **Season Builder (v1.4)**: Added audition fields to the unified Show editor in the builder.

### Changed
- **Builder UI**: Renamed the `Actions` column in Cast and Awards to `Remove` so it is clearly a row-control column, not saved content.
- **Version Update**: Plugin version moved from 3.2 to 3.3.

## [3.2] - 2026-05-23

### Added
- **Season Builder (v1.2)**: Added a dedicated `Media` tab for season images, show image/program media, and cast headshots.
- **Season Builder (v1.3)**: Added the remaining Show detail fields to the builder, including sub-authors, genre, director, associate director, show dates, and synopsis.
- **Season Builder Coverage**: Added season `Current` and `Upcoming` flags to the builder so all Season updates can be completed from the same screen.

### Changed
- **Tab Save Flow**: Switching between `Details` and `Media` now saves the current builder state before changing tabs and reopens on the requested tab after save.
- **Version Update**: Plugin version moved from 3.1 to 3.2.

## [3.1] - 2026-05-23

### Added
- **Season Builder (v1.1)**: Added safe delete behavior. Shows, Cast, and Awards rows removed from the builder are moved to Trash on save.

### Changed
- **Version Update**: Plugin version moved from 3.0 to 3.1.
- **Versioning Technique (standard)**: Going forward, every release updates all of the following together:
  - Plugin header `Version` in `theatre-manager-plugin.php`
  - `THEATRE_MANAGER_VERSION` constant in `theatre-manager-plugin.php`
  - `CHANGELOG.md`
  - `RELEASE-NOTES.md`
  - `Theatre-Manager/ChangeLog.txt`
  - Packaged zip artifact (`Theatre-Manager-x.y.zip`)

## [3.0] - 2026-05-23

### Added
- **Season Builder (v1)**: New single-screen admin page to edit Season + linked Shows + Cast + Awards in one workflow.
- **Season Builder Save Flow**: Supports creating/updating related posts and preserving links (`season -> show -> cast/award`) in one save action.
- **Awards Integration**: Awards are now included in the unified builder UI with category and status constraints.

### Changed
- **Version Update**: Plugin version moved from 2.8 to 3.0.

### Notes
- **Rollback Path**: The repository keeps a 2.8 package (`Theatre-Manager-2.8.zip`) and includes rollback instructions in `ROLLBACK-TO-2.8.md`.

## [2.8] - 2026-05-23

### Fixed
- **Image Rendering**: Fixed cast images in `tm_show_cast` and `tm_season_cast` grid mode to support attachment IDs via `tm_get_image_url()`.
- **Output Escaping**: Escaped show and season title output in `tm_shows` to prevent unsafe/unexpected HTML rendering.
- **Display Options Styling**: Repaired malformed inline style generation in season shortcodes (`tm_season_cast`, `tm_season_shows`) and restored box-shadow option behavior.
- **Admin Robustness**: Sanitized and validated display-options tab query parameter before using it to build settings page/group IDs.
- **Version Consistency**: Updated plugin header and `THEATRE_MANAGER_VERSION` constant to `2.8`.

### Added
- **Awards CPT**: Added a new `award` post type linked to Shows (and indirectly Seasons) with fields for Award ID, Show ID, Award Category, Award Name, Award Recipient, and Status.
- **Awards Constraints**: Enforced allowed status values (`Nominated`, `THEA Winner`) and category values (`Musical`, `Drama`, `Comedy`) in admin save handling.

### Security
- **CSS Value Hardening**: Added `tm_sanitize_css_color()` helper and applied it to shortcode-generated CSS color values to prevent unsafe style injection from option values.

## [2.6] - 2025-11-04

### Fixed
- **Sponsors Sync Support**: Plugin now properly supports all sponsor fields synced by tm-sync 2.6
- **Shows/Seasons/Cast Display**: Updated to work with new complete field mapping from tm-sync

### Changed
- **Compatibility**: Now requires tm-sync 2.6+ for full Seasons/Shows/Cast functionality

## [2.5] - 2025-11-04

### Added
- **Attachment ID Support**: All shortcodes now support attachment IDs from tm-sync plugin for better image management
- **Image URL Helper Function**: New `tm_get_image_url()` function for backwards compatibility with both attachment IDs and direct URLs
- **Enhanced Testimonials Display**: Proper star rating display with configurable symbols (Stars, Thumbs Up, Rockets, Hearts, Theatre Masks)

### Fixed
- **Critical**: All 10 shortcodes now properly display images from SharePoint sync
- **Board Members Display**: 
  - Fixed photos not showing (was looking for wrong meta field `_tm_media_urls` instead of `_tm_photo`)
  - Fixed board member names not showing (changed `get_the_title($post)` to `get_the_title($post->ID)`)
- **Testimonials Rating Display**: Now properly shows star ratings from SharePoint
- **Image Display Chain**: Fixed image rendering in:
  - cast-shortcode.php
  - sponsors-shortcode.php
  - sponsor-slider-shortcode.php
  - advertisers-shortcode.php
  - shows-shortcode.php (2 locations)
  - tm_season_images.php
  - tm_season_banner.php
  - tm_season_shows.php
  - tm_season_cast.php
  - board-members-shortcode.php

### Changed
- **Image Handling**: All shortcodes now use `tm_get_image_url()` helper function
- **Meta Field References**: Board members shortcode updated to use correct field names
- **Display Options**: Verified all shortcodes continue to respect WordPress display options

### Technical Details
- **Files Modified**:
  - `includes/helpers.php` - Added `tm_get_image_url()` function
  - `includes/shortcodes/cast-shortcode.php` - Use helper for images
  - `includes/shortcodes/sponsors-shortcode.php` - Use helper for images
  - `includes/shortcodes/sponsor-slider-shortcode.php` - Use helper for images
  - `includes/shortcodes/advertisers-shortcode.php` - Use helper for images
  - `includes/shortcodes/shows-shortcode.php` - Use helper for images (2 places)
  - `includes/shortcodes/tm_season_images.php` - Use helper for images
  - `includes/shortcodes/tm_season_banner.php` - Use helper for images
  - `includes/shortcodes/tm_season_shows.php` - Use helper for images
  - `includes/shortcodes/tm_season_cast.php` - Use helper for images
  - `includes/shortcodes/board-members-shortcode.php` - Fixed field names and ID parameter
  - `cpt/testimonials.php` - Rating display in admin columns

- **Commits**:
  - `121430a` - Fix board-members shortcode names and photos
  - `b619df1` - Update shortcodes to support attachment IDs from tm-sync
  - And related commits from session

### Backwards Compatibility
✅ All changes maintain backwards compatibility with existing sites:
- `tm_get_image_url()` handles both attachment IDs and direct URLs
- Display options continue to control shortcode styling
- Existing posts with URL-based images continue to work

### Known Issues
- None

### Dependencies
- WordPress 5.0+
- PHP 7.4+
- Theatre Manager Sync plugin 2.5+ (for proper image syncing)

---

## [2.4] - Previous Release
See git history for details on earlier versions.
