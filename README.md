# Hexa Grid – Product Showcase and Category Layouts for WooCommerce

A powerful and flexible product showcase plugin for WooCommerce. Display your products in Grid, List, or Slider layouts with ease. Use the built-in preset builder to configure unlimited showcases and display them anywhere using shortcodes.

## Features

-   **3 Modern Layouts**: Grid, List, and Slider (Carousel).
-   **Showcase Builder**: Create unlimited presets with custom settings (Layout, Limit, Columns, Category).
-   **Shortcode Support**: Use `[hexagrid_product_showcase]` with attributes or `preset_id`.
-   **Responsive Design**: Looks great on Desktop, Tablet, and Mobile.
-   **WooCommerce Integration**: Automatically fetches products, prices, ratings, and add-to-cart buttons.
-   **Swiper.js Integration**: Smooth and touch-friendly product sliders.

## Installation

1.  Upload the `hexa-grid-product-showcase` folder to the `/wp-content/plugins/` directory.
2.  Activate the plugin through the 'Plugins' menu in WordPress.
3.  Ensure WooCommerce is installed and active.

## Usage

### Method 1: using the Builder (Recommended)
1.  Go to **Product Showcase > Showcase Presets** in your admin dashboard.
2.  Click **Add New**.
3.  Enter a title (e.g., "Homepage Best Sellers").
4.  Configure the settings in the **Showcase Settings** meta box:
    -   **Layout Type**: Choose between Grid, List, or Slider.
    -   **Columns**: (Grid only) Number of columns (1-6).
    -   **Product Limit**: Number of products to display.
    -   **Category Variable**: (Optional) Enter a product category slug to filter products.
5.  Publish the preset.
6.  Copy the shortcode shown in the settings box (e.g., `[hexagrid_product_showcase preset_id="123"]`) and paste it into any page or post.

### Method 2: Manual Shortcode
You can use the shortcode directly with attributes:

```
[hexagrid_product_showcase layout="grid" limit="8" columns="4"]
```

**Attributes:**
-   `layout`: `grid`, `list`, `slider` (default: `grid`)
-   `limit`: Number of products to show (default: `12`)
-   `columns`: Number of columns for grid layout (default: `3`)
-   `category`: Product category slug (comma separated)
-   `ids`: Specific product IDs (comma separated)
-   `orderby`: `date`, `price`, `rand`, `title` (default: `date`)
-   `order`: `DESC`, `ASC` (default: `DESC`)

## Requirements

-   WordPress 5.0+
-   WooCommerce 5.0+
-   PHP 7.4+

## License

GPLv2 or later
