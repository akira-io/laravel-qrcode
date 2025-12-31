# Security Policy

## Supported Versions

| Version | Supported          |
| ------- | ------------------ |
| 1.x     | :white_check_mark: |

## Reporting a Vulnerability

We take the security of Akira Laravel QR Code seriously. If you discover a security vulnerability, please follow these steps:

### Private Disclosure

**DO NOT** open a public issue for security vulnerabilities.

Instead, please email security reports to:

**kidiatoliny@gmail.com**

### What to Include

Please include the following information in your report:

- **Description** of the vulnerability
- **Steps to reproduce** the issue
- **Potential impact** of the vulnerability
- **Suggested fix** (if you have one)
- **Your contact information** for follow-up

### Example Security Report

```
Subject: [SECURITY] QR Code XSS Vulnerability

Description:
User-supplied data in QR codes is not properly sanitized when 
outputting as SVG, potentially allowing XSS attacks.

Steps to Reproduce:
1. Generate QR code with payload: <script>alert('XSS')</script>
2. Output as SVG format
3. Render in browser

Impact:
High - Could lead to XSS attacks when QR codes are displayed in web pages

Environment:
- Package version: 1.0.0
- PHP: 8.4
- Laravel: 12.0

Suggested Fix:
Sanitize all user input before generating SVG output
```

## Response Timeline

- **Initial Response**: Within 48 hours
- **Status Update**: Within 7 days
- **Fix Timeline**: Based on severity
  - Critical: Within 7 days
  - High: Within 30 days
  - Medium: Within 90 days
  - Low: Next planned release

## Security Update Process

1. **Verification**: We verify the vulnerability
2. **Fix Development**: Develop and test a fix
3. **Private Review**: Share fix with reporter for verification
4. **Release**: Publish security update
5. **Disclosure**: Publicly disclose after fix is available

## Disclosure Policy

- Vulnerabilities are kept private until a fix is released
- Reporter is credited (if desired) in release notes
- Public disclosure after fix is available
- CVE assigned for critical vulnerabilities

## Security Best Practices

When using this package:

### Input Validation

Always validate user input before generating QR codes:

```php
// Good - Validated input
$url = filter_var($input, FILTER_VALIDATE_URL);
if ($url) {
    $qrCode = QrCode::generate($url);
}

// Bad - Unvalidated input
$qrCode = QrCode::generate($_GET['url']);
```

### Output Encoding

Be cautious when displaying QR codes:

```php
// Good - Using Laravel's HtmlString (auto-escaped in Blade)
return QrCode::generate($data);

// Be careful with raw SVG output
echo $qrCode; // Ensure $qrCode is from trusted source
```

### File Operations

When saving QR codes to files:

```php
// Good - Controlled path
$filename = storage_path('qrcodes/' . hash('sha256', $data) . '.png');
QrCode::generate($data, $filename);

// Bad - User-controlled path
QrCode::generate($data, $_POST['filename']);
```

### Logo/Image Merging

Validate images before merging:

```php
// Good - Validate uploaded file
$logo = $request->file('logo');
if ($logo->isValid() && in_array($logo->extension(), ['png', 'jpg'])) {
    QrCode::merge($logo->path())->generate($data);
}

// Bad - Unvalidated file
QrCode::merge($_FILES['logo']['tmp_name'])->generate($data);
```

## Known Security Considerations

### SVG Output

SVG format can potentially contain JavaScript. While this package does not inject JavaScript, be cautious when:

- Accepting user-provided data for QR codes
- Displaying SVG QR codes in untrusted contexts
- Using Content Security Policy (CSP) with SVG output

### Image Processing

PNG format uses GD extension which may have vulnerabilities:

- Keep PHP and GD extension updated
- Validate image inputs when using merge functionality
- Limit file sizes to prevent DoS attacks

### Data Encoding

QR codes encode data as-is:

- Do not encode sensitive data without encryption
- Be aware data is readable by anyone with scanner
- Use HTTPS URLs for sensitive links

## Security Updates

Subscribe to security updates:

- Watch the GitHub repository
- Follow releases on Packagist
- Check CHANGELOG.md for security fixes

## Acknowledgments

We appreciate security researchers who responsibly disclose vulnerabilities. Contributors will be acknowledged in:

- Security advisories
- Release notes
- CHANGELOG.md

## Questions?

For security-related questions, contact: kidiatoliny@gmail.com

For general questions, use GitHub Discussions or Issues.

---

Last updated: 2025-12-31
