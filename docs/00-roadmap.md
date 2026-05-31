# Roadmap

This document outlines the planned features and improvements for the Akira Laravel QR Code package. All features are based on current architecture and existing extension points.

## Planned Features

### Additional Data Types

**vCard Support** (implemented for v1.3.0)
- Implement vCard QR codes for contact information
- Based on existing DataType architecture
- Use ValueObject pattern for contact data validation
- Use DataTypeMapper for method resolution

**Calendar Events (iCal)** (implemented for v1.3.0)
- Generate QR codes for calendar events
- Support VEVENT format
- Follow existing SMS/Email data type patterns
- Action-based string building

**Payment URIs**
- Support additional cryptocurrency formats (Ethereum, Litecoin)
- Extend existing Bitcoin implementation
- Reuse BitcoinDataType architecture
- Unified payment ValueObject structure

### Rendering Enhancements

**Custom Module Shapes**
- Add hexagon module style
- Implement triangle module pattern
- Extend existing ModuleInterface from BaconQrCode
- Follow current style() method pattern

**Eye Customization**
- Per-eye style control
- Rounded rectangle eye variant
- Build on existing eyeColor() and eye() methods
- Leverage EyeFill architecture

**Background Images**
- Support background image patterns
- Blend QR code over custom backgrounds
- Extend ImageMerge class capabilities
- Maintain alpha channel support

### Output Formats

**PDF Generation**
- Direct PDF output format
- Integrate with TCPDF or similar
- Follow existing format() method pattern
- Add PdfImageBackEnd implementation

**WebP Support**
- Modern image format support
- Leverage GD or Imagick extensions
- Extend ImagickImageBackEnd class
- Format detection in generate method

### Performance

**Caching Layer**
- Cache generated QR codes
- Laravel cache integration
- Configuration-based cache keys
- TTL from config file

**Batch Generation**
- Generate multiple QR codes efficiently
- Queue-based processing option
- Bulk Action implementation
- Collection return types

### Configuration

**Presets System**
- Named configuration presets
- Load preset configurations from config file
- Fluent API for preset application
- Override individual preset values

**Theme Support**
- Predefined color schemes
- Light/dark mode variants
- Brand-specific themes
- Extend color() and backgroundColor() methods

### Developer Experience

**Validation Helpers**
- Validate QR code data before generation
- Pre-generation error checking
- Leverage existing ValueObject validation
- Enhanced error messages

**CLI Commands**
- Artisan command for QR generation
- File output from terminal
- Batch processing from CSV
- Preview in terminal (ASCII art)

**Macros**
- Support for custom QrCode macros
- Laravel macro pattern integration
- Extend functionality without forking
- Community contribution path

### Testing Utilities

**Visual Regression Testing**
- Snapshot testing for QR codes
- Compare generated output
- Detect rendering changes
- Pest plugin integration

**QR Code Validation**
- Test if QR codes are scannable
- Decode and verify content
- Integration with ZXing or similar
- PHPUnit assertion helpers

### Documentation

**Interactive Examples**
- Web-based QR code generator demo
- Live configuration preview
- Copy-paste code snippets
- Hosted documentation site

**Video Tutorials**
- Step-by-step guides
- Common use case walkthroughs
- Integration examples
- YouTube channel or similar

## Extension Points

The package architecture supports extension through:

- **Custom DataTypes**: Implement `QrCodeDataTypeContract`
- **Custom Actions**: Follow single-responsibility Action pattern
- **Custom ValueObjects**: Readonly classes with validation
- **Service Provider Binding**: Register custom implementations
- **Facade Extension**: Add methods via __call magic
- **Configuration Override**: Environment-based defaults

## Contribution Welcome

All features are open for community contribution. The current codebase provides clear patterns and abstractions for extending functionality without breaking changes.

**Next:** [Installation](01-installation.md)
