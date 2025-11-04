# Theatre Manager - Changelog

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
