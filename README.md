# Hexa Grid – Product Showcase for WooCommerce

**Version:** 1.1.0  
**Contributors:** nazmunsakib  
**Requires at least:** WordPress 5.0  
**Tested up to:** WordPress 6.9  
**Requires PHP:** 7.4  
**License:** GPLv2 or later  
**License URI:** https://www.gnu.org/licenses/gpl-2.0.html

Build a Beautiful WooCommerce Product Showcase using responsive grid, slider, list and table layouts.

## Description

**Hexa Grid – Product Showcase** is a lightweight WooCommerce Product Showcase plugin that lets you build responsive product grids, carousel sliders, list views, and table layouts.

A professional WooCommerce store needs more than the default shop layout. Customers expect visually engaging product sections. This plugin makes it simple to create a high-converting WooCommerce Product Showcase anywhere on your website.

With an easy shortcode system, you can add a WooCommerce Product Showcase to homepages, landing pages, blog posts, or custom store sections without using a page builder. The plugin is built for performance, usability, and SEO-friendly structure.

## Key Features

- **Multiple Layout Types**: Grid, List, Carousel (Slider), and Table layouts
- **Layout Variations**: Product-specific variations (Product Grid Modern/Classic, etc.)
- **Intelligent Conditional Logic**: Settings automatically adapt based on layout type
- **Slider Configuration**: Full control over navigation, pagination dots, and autoplay
- **Modern Admin UI**: Card-based interface with visual selection and branding colors
- **Responsive Design**: All layouts are mobile-friendly and adapt to any screen size
- **Shortcode System**: Simple shortcodes for flexible placement anywhere
- **Performance Optimized**: Lightweight code that doesn't slow down your store
- **Auto-Selection**: Automatically selects appropriate variations when changing settings

## Installation

### From WordPress Dashboard

1. Go to **Plugins → Add New**
2. Search for "Hexa Grid – Product Showcase for WooCommerce"
3. Click **Install Now**
4. Activate the plugin
5. Add shortcodes to display your WooCommerce Product Showcase

### Manual Installation

1. Download the plugin ZIP file
2. Upload the plugin folder to the `/wp-content/plugins/` directory
3. Activate the plugin through the WordPress Plugins menu
4. Use shortcodes to create a WooCommerce Product Showcase

## Usage

### Method 1: Using the Preset Builder (Recommended)

1. Go to **Product Showcase → Showcase Presets** in your admin dashboard
2. Click **Add New**
3. Enter a title (e.g., "Homepage Featured Products")
4. Configure the settings in the **Showcase Settings** meta box:
   - **Layout Type**: Choose between Grid, List, Carousel, or Table
   - **Layout Style**: Select from product-specific variations (e.g., Product Grid Modern, Product List Minimal)
   - **Columns**: Number of columns (for Grid/Slider layouts)
   - **Product Limit**: Number of products to display
   - **Order By**: Sort products by date, price, title, or popularity
   - **Theme Color**: Customize the primary color
   - **Slider Configuration**: (For Carousel) Enable navigation, dots, and autoplay
5. Publish the preset
6. Copy the shortcode (e.g., `[hexagrid_product_showcase preset_id="123"]`) and paste it into any page or post

### Method 2: Manual Shortcode

You can use the shortcode directly with attributes:

```
[hexagrid_product_showcase layout="grid" style="product-grid-1" limit="8" columns="4"]
```

**Available Attributes:**

- `preset_id`: ID of a saved preset
- `layout`: `grid`, `list`, `slider`, `table` (default: `grid`)
- `style`: Layout variation (e.g., `product-grid-1`, `product-list-1`)
- `limit`: Number of products to show (default: `12`)
- `columns`: Number of columns for grid/slider layout (default: `3`)
- `category`: Product category slug(s) (comma separated)
- `ids`: Specific product IDs (comma separated)
- `orderby`: `date`, `price`, `ID`, `title`, `popularity` (default: `date`)
- `order`: `DESC`, `ASC` (default: `DESC`)
- `exclude_ids`: Comma-separated product IDs to exclude

## Layout Variations

### Product Layouts
- **Grid**: Product Grid Modern, Product Grid Classic
- **List**: Product List Minimal
- **Slider**: Product Carousel Standard
- **Table**: Product Table Simple

## Requirements

- WordPress 5.0+
- WooCommerce 5.0+
- PHP 7.4+

## Changelog

### Version 1.1.0
- **New:** Completely redesigned Admin UI with modern card-based interface and branding colors
- **New:** Added Layout Variations system with product-specific variations (Product Grid Modern/Classic, Product List Minimal, Product Carousel Standard, Product Table Simple)
- **New:** Added "Slider Configuration" options (Navigation, Dots, Auto Play) with modern switcher controls
- **New:** Implemented intelligent conditional logic in Admin (hides irrelevant settings based on layout type)
- **New:** Added auto-selection feature - automatically selects first layout variation when changing layout type
- **New:** Created reusable Addons Kit Settings Builder library with support for grouped card selectors and dependencies
- **New:** Added width control for card selectors (grid_columns and grid_min_width parameters)
- **Improved:** Refactored layout variation logic into library for better code organization and reusability
- **Improved:** Enhanced JavaScript dependency system
- **Improved:** Standardized CSS class naming from 'hexagrid-grid-1' to 'hexagrid-product-grid-1' for better clarity
- **Improved:** Refactored internal code structure (Meta Box data saving) for better scalability and performance
- **Improved:** Frontend Slider now dynamically adapts to configuration settings (autoplay, columns, navigation)
- **Fixed:** Resolved HTML markup issues in Layout Settings section that caused broken display with conditional fields
- **Fixed:** Corrected meta box wrapper margins to prevent content overlap with WordPress footer
- **Fixed:** Standardized template naming conventions and fallback logic

### Version 1.0.0
- Initial release
- Added WooCommerce Product Showcase grid, list, carousel, and table layouts
- Responsive design implementation
- Performance optimization

## Support

For support, feature requests, or bug reports, please visit [nazmunsakib.com](https://nazmunsakib.com)

## License

This plugin is licensed under GPLv2 or later. See [LICENSE](https://www.gnu.org/licenses/gpl-2.0.html) for more details.
