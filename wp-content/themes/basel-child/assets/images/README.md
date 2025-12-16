# Banner Images Directory

## Required Images

Place your homepage banner images in this directory:

### 1. Desktop Banner
- **Filename**: `banner-desktop.jpg`
- **Recommended Size**: 1600 x 400 pixels
- **Format**: JPG (optimized for web)
- **Max File Size**: 200KB (use compression tools like TinyPNG)

### 2. Mobile Banner
- **Filename**: `banner-mobile.jpg`
- **Recommended Size**: 768 x 400 pixels
- **Format**: JPG (optimized for web)
- **Max File Size**: 100KB

## Image Optimization Tips

1. **Use WebP format** for better compression (rename to `.webp`)
2. **Compress images** before uploading using:
   - TinyPNG (https://tinypng.com/)
   - Squoosh (https://squoosh.app/)
   - ImageOptim (Mac)
3. **Lazy loading** is automatically handled by the browser
4. **Responsive images** are served via `<picture>` element

## Current Banner Link

The banner currently links to:
```
https://realleathergarments.co.uk/mens-brown-leather-military-vest/
```

To change this, edit `wp-content/themes/basel-child/page-static-home.php` line 23.

## Example Banner Dimensions

### Desktop (1600x400)
```
┌─────────────────────────────────────────────────┐
│                                                 │
│         Your Banner Content Here                │
│                                                 │
└─────────────────────────────────────────────────┘
```

### Mobile (768x400)
```
┌──────────────────────┐
│                      │
│   Mobile Banner      │
│                      │
└──────────────────────┘
```

## Fallback

If images are not found, the homepage will still load but the banner section will be empty.

