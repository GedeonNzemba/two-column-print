# Two Column Print WordPress Plugin

A professional WordPress plugin that enables printing webpage content in a customizable two-column layout. Perfect for creating professional documents from web content without any third-party dependencies.

## Features

- ✨ Pure JavaScript/CSS implementation - no external dependencies
- 🔒 Secure, client-side execution
- 📏 Customizable column widths
- 🖼️ Proper image scaling and positioning
- 📄 Clean, header/footer-free output
- 🎨 Responsive design
- 🛡️ XSS prevention and content sanitization

## Installation

1. Download the latest release
2. Upload to your WordPress site
3. Activate the plugin through the WordPress admin panel
4. Configure through Settings > Two Column Print

## Usage

### Basic Implementation

Add the print button using the shortcode:

```php
[print_button]
```

### Custom Implementation

```php
[print_button text="Generate PDF" class="custom-button"]
```

### Selecting Content

Add CSS classes or IDs to your content:

```html
<!-- Left Column Content -->
<div class="left-content">
    <h2>Table of Contents</h2>
    <ul>
        <li>Section 1</li>
        <li>Section 2</li>
    </ul>
</div>

<!-- Right Column Content -->
<div class="main-content">
    <h1>Main Article</h1>
    <p>Article content goes here...</p>
</div>
```

## Configuration

### Column Widths
- Left Column: 20% (default)
- Middle Space: 10% (default)
- Right Column: 70% (default)

All values are customizable through the WordPress admin panel.

### Content Selection
Use CSS selectors to specify which elements appear in each column:

```
Left Column: .sidebar, #table-of-contents, .left-content
Right Column: .main-content, #article-body, .right-content
```

## Security

- No external dependencies
- Content stays within the client's browser
- Proper WordPress security implementations
- Input sanitization and output escaping
- XSS prevention measures

## Requirements

- WordPress 5.0 or higher
- Modern web browser (Chrome, Firefox, Safari, Edge)
- PHP 7.2 or higher

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is licensed under the GPL v2 or later - see the [LICENSE](LICENSE) file for details.

## Author

Gedeon Nzemba

## Support

For support, please open an issue in the GitHub repository.
