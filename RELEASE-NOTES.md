# Theatre Manager v2.6 - Release Package

**Release Date:** November 4, 2025

## Installation

1. Extract `Theatre-Manager-2.6.zip` into your WordPress plugins directory
2. The folder should be named `Theatre-Manager`
3. Activate the plugin from WordPress Admin
4. Optionally: Install Theatre Manager Sync v2.6 for SharePoint integration

## What's Included

- Complete Theatre Manager plugin v2.6
- 8 Custom Post Type definitions (Advertisers, Board Members, Cast, Contributors, Seasons, Shows, Sponsors, Testimonials)
- 15+ shortcodes for frontend display
- Admin settings and display customization options
- Full compatibility with Theatre Manager Sync v2.6

## Key Features in v2.6

✅ **Complete Seasons Display** - All season fields and images working properly
✅ **Complete Shows Display** - Full show details with season relationships
✅ **Complete Cast Display** - Cast members linked to shows with images
✅ **Relationship Display** - Seasons→Shows→Cast hierarchy working correctly
✅ **Image Display Fixes** - All shortcodes now properly display synced images
✅ **Board Member Display** - Fixed names and photos showing correctly
✅ **Testimonials Ratings** - Star ratings display from SharePoint data
✅ **Attachment ID Support** - Compatible with Theatre Manager Sync plugin's image management
✅ **Display Options** - All shortcodes respect WordPress styling settings

## Shortcodes Available

- `[tm_cast]` - Display cast members
- `[tm_sponsors]` - Display sponsors by level
- `[tm_sponsor_slider]` - Sponsor carousel
- `[tm_advertisers]` - Display advertisers
- `[tm_board_members]` - Display board members
- `[tm_shows]` - Display shows
- `[tm_seasons]` - Display seasons
- `[tm_contributors]` - Display contributors
- `[tm_testimonials]` - Display testimonials with ratings

Season-specific shortcodes:
- `[tm_season_cast]` - Cast for current season
- `[tm_season_shows]` - Shows for current season
- `[tm_season_banner]` - Season banner image
- `[tm_season_images]` - Season images
- `[tm_season_cast]` - Season cast table

## Requirements

- WordPress 5.0+
- PHP 7.4+
- Theatre Manager Sync v2.6+ (for complete field syncing)
- Optional: Theatre Manager Sync plugin 2.5+ for SharePoint integration

## Documentation

See `CHANGELOG.md` in the plugin folder for complete list of changes and fixes.

## Display Options

All shortcodes support WordPress customization through the admin settings:
- Background colors
- Text colors
- Border styles
- Border radius
- Shadow effects
- Grid layouts (where applicable)

## Version History

- **v2.5** (2025-11-04): Image display fixes, attachment ID support, testimonials rating fix
- **v2.1** (Previous): Initial feature-complete release
